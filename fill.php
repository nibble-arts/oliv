<?PHP

@MYSQL_CONNECT("localhost","iggmp","grumblfurz34");
@MYSQL_SELECT_DB("iggmp");

$db = "lang_railway_summary";

$res = mysql_query("SELECT * FROM $db");

while($entry = mysql_fetch_assoc($res))
{
	$id = $entry['_id'];
//	mysql_query("UPDATE $db SET relation='$id' WHERE _id='$id'");
	echo $entry['_id'] . "<br>";
}

?>
