<?php
	global $config;
	include_once CLASSES."User.php";
	$user = NULL;

	if(session_start()){
		$user = User::getWithSession(session_id());
	}
	$action = htmlentities($_POST['action']);
	
	if($action == "Enregistrer"){
		$login = htmlentities($_POST['login']);
		$email = htmlentities($_POST['email']);
		$password = htmlentities($_POST['password']);
		$confirm_password = htmlentities($_POST['confirm_password']);

		if(empty($password) || $password != $confirm_password || User::loginExists($login)){
			echo "pas bon";
		}
		else{
			$user = new User($login, $email, $password);
			$user->setSession(session_id());
			$user->save();
		}
	}else if( $action == "Connexion" ){
		$login = htmlentities($_POST['login']);
		$password = htmlentities($_POST['password']);

		$user = User::getWithLogin($login);
		if($user== NULL || $user->isBlocked() || !$user->checkGoodPassword($password)){
			$user = NULL;
			echo "user existe pas";
		}
		else{
			$user->setSession(session_id());
			$user->save();
		}
	}else if($action == "disconnect"){
		$user->flushSession();
		session_destroy();
		$user = NULL;
	}
?>
<div class="connection">
	<? if(!isset($user) || $user == NULL){ ?>
		<div class="menu">
			<span class="item connect">se connecter</span>
			<span class="item register">s'enregistrer</span>
		</div>
		<div style="clear:both"></div>
		<div class="submenu menu-connect" style="display:none">
			<form method="post" action="<? echo $config['base_url']; ?>">
				Login : <input type="text" name="login"/>
				Mot de passe : <input type="password" name="password"/>
				<input type="submit" name="action" value="Connexion"/>
			</form>
		</div>
		<div class="submenu menu-register" style="display:none;">
			<form method="post" action="<? echo $config['base_url']; ?>">
				Login : <input type="text" name="login"/><br/>
				Email : <input type="email" name="email"/><br/>
				Mot de passe : <input type="password" name="password"/><br/>
				Confirmation mot de passe : <input type="password" name="confirm_password"/><br/>
				<input type="submit" name="action" value="Enregistrer"/><br/>
			</form>
		</div>
	<?}else{?>
		<div class="menu">
			<span class="info">Connect&eacute; en tant que : <? echo $user->getLogin();?></span>
			<form style="display:inline" method="post" action="<? echo $config['base_url']; ?>">
				<input type="hidden" name="action" value="disconnect"/>
				<span class="item disconnect">se d&eacute;connecter</span>
			</form>
		</div>
	<?}?>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".menu .item.connect").click(function(){
			$(".submenu.menu-register").hide();
			$(".submenu.menu-connect").slideToggle("fast");
		});
		$(".menu .item.register").click(function(){
			$(".submenu.menu-connect").hide();
			$(".submenu.menu-register").slideToggle("fast");
		});

		$(".item.disconnect").click(function(){
			$(this).parents("form").submit();
		});
	});
</script>
