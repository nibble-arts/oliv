<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Test ModRewrite</title>	
</head>
<body>

<?PHP
	date_default_timezone_set('Europe/Paris');
	if (!isset($lang)) $lang = "";
	if (!isset($url)) $url = "";
	
	echo "<pre>";
		print_r($_GET);

//	print_r($_SERVER);
	echo "</pre>";

	echo "$1: $lang<br>";
	echo "$2: $url<br>";
?>
	<a href='/oliv/editor/rewrite.php/ich/bins'>fire link</a>

</body>
</html>

