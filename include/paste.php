<?php
	global $config, $user;
	
	$action = htmlentities($_POST['action']);
	$public = (htmlentities($_POST['public']) == "on")? 1: 0;
	$paste = htmlentities($_POST['paste']);
	
	if($action == "Paste" && !empty($paste)){
		require_once CLASSES."Paste.php";
		
		$new_paste = new Paste($paste, ($user == NULL)? NULL: $user->getLogin(), $public, NULL);
		$new_paste->save();
		header('Location: '.$config['base_url'].'?paste='.$new_paste->getHash());
	}
?>
<h3>Nouveau Paste</h3>

<form method="post" action="<? echo $config['base_url']?>">
	<textarea name="paste" rows="10"></textarea>
	<br/>
	Public : <input type="checkbox" name="public" checked="checked"/><br/>
	<input type="submit" name="action" value="Paste"/>
</form>
