<?php
	global $config, $user;

	if(!empty($paste_id)){
		include_once CLASSES."Paste.php";
		
		$paste = Paste::getWithHash($paste_id);
		
		if($paste == NULL || (!$paste->isPublic() && !$paste->isOwner($user))){
			header("Location: ".$config['base_url']);
		}

		$action = htmlentities($_POST['action']);
		$new_text = htmlentities($_POST['paste']);

		if($action == "Modifier Paste" && !empty($new_text)){
			$paste->changeText($new_text);
			$paste->save();
		}
	}
?>
<h3>Paste <?echo $paste->getHash();?></h3>
<div class="displayer">
	<ul class="code">
	<? 
    $lines = explode("\n", $paste->getText());
		foreach($lines as $key => $value){
			echo "<li class='".((($key+1)%2 == 0)?"pair": "impair")."'><pre>".$value."</pre> </li>";
		}
	?>
	</ul>
</div>
<div style="clear:both"></div>
<h3>Modifier Paste</h3>
<form method="post" action="<?echo $config['base_url']?>?paste=<?echo $paste->getHash()?>">
	<textarea rows="20" name="paste"><?echo $paste->getText();?></textarea>
	<br/>
	<? if($paste->isOwner($user)){?>
	Public :<input type="checkbox" name="public" <? if($paste->isPublic()) echo 'checked="checked"';?>/>
	<input type="submit" name="action" value="Modifier Paste"/>
	<br/>
	<?}?>
</form>
