<form action="test.php">
<input type="text" name="user"> user<br>
<input type="text" name="password"> password<br>

<input type="submit" value="abschicken">


<?PHP
print_r($_GET);

if (isset($_GET['user'])) $user = $_GET['user'];
if (isset($_GET['password'])) $password = $_GET['password'];

if ($user and $password)
{
	echo "<hr>";

	echo $user . " = user<br>";
	echo $password . " = password<br>";

	echo md5($password) . " md5<br>";
	echo crypt($password) . " crypt without salt<br>";
	echo crypt($password,$user) . " crypt with salt<br>";
}
?>
