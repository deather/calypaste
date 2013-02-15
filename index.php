<!DOCTYPE html>
<?php
  require_once "./config.php";
  require_once INC."header.php";

	$paste_id = htmlentities($_GET['paste']);
?>
<html>
	<head>
		<title>CalyPaste</title>
		<meta charset="UTF-8">
		<link href="<? echo $config['base_url']."css/header.css"; ?>" type="text/css" rel="stylesheet"/>
		<link href="<? echo $config['base_url']."css/main.css"; ?>" type="text/css" rel="stylesheet"/>
		<link href="<? echo $config['base_url']."css/display.css"; ?>" type="text/css" rel="stylesheet"/>
		<link href="<? echo $config['base_url']."css/connection.css"; ?>" type="text/css" rel="stylesheet"/>
		<script src="<? echo $config['base_url']."javascript/jquery.js"; ?>" type="text/javascript"></script>
	</head>
	<body>
		<div class="header">
			<a href="<? echo $config['base_url'];?>"><div class="title">CalyPaste</div></a>
			<?php 
				include_once INC."connection.php";
			?>
		</div>
		<div class="content">
			<?php
				if(empty($paste_id)){
					include_once INC."paste.php";
				}
				else{
					include_once INC."display.php";
				}
			?>
		</div>
		<div class="footer">
			&copy; deather
		</div>
	</body>
</html>
