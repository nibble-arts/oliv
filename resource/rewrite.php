<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Test ModRewrite</title>	
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
</head>
<body>

<?PHP

@MYSQL_CONNECT("localhost","iggmp","grumblfurz34");
@MYSQL_SELECT_DB("iggmp");

$res = mysql_query("SELECT * FROM lang_railway_summary");

echo mysql_num_rows($res);

while ($entry = mysql_fetch_array($res))
{
	$text = utf8_encode($entry['content']);

	echo $entry['content'] . "<hr>" . $text . "<hr><hr>";
}

?>

</body>
</html>

