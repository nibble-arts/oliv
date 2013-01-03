<style type="text/css">
	.xslt_testsystem {
		border:solid 1px;
		background-color:lightyellow;
		padding:5px;
	}

	@media print {
		.xslt_testsystem {
			display:none;
		}
	}

</style>

<div class="xslt_testsystem">

	<p><b>XSLT Testsystem</b></p>

	<?PHP
	date_default_timezone_set("Europe/Paris");

	$xmlfile = "";
	$xslfile = "";
	$lang = "";
	$xml = "";
	$xsl = "";

	if (isset($_POST['xmlfile'])) $xmlfile=$_POST['xmlfile'];
	if (isset($_POST['xslfile'])) $xslfile=$_POST['xslfile'];
	if (isset($_POST['lang'])) $lang=$_POST['lang'];
	else $lang = 3;
	?>

	<form method='post' action='stylesheet.php'>
<?PHP
		echo "XML-File <input type='text' name='xmlfile' value='$xmlfile'> ";
		echo "XSTL-File 1 <input type='text' name='xslfile' value='$xslfile'> ";

		echo "de <input type='radio' name='lang' value='3'";
		if ($lang == 3) echo "checked='checked'";
		echo "> ";
		echo "en <input type='radio' name='lang' value='0'";
		if ($lang == 0) echo "checked='checked'";
		echo "> ";
?>

		<input type='submit' value='laden'>
	</form>

	<?PHP

	$styleSheet = new XSLTProcessor();
echo "</div><br>";


if ($xmlfile and $xslfile)
{
	if (file_exists($xmlfile))
		$xml = simplexml_load_file($xmlfile);
	else
		echo "XML $xmlfile not found<br>";

	if (file_exists($xslfile))
		$xsl = simplexml_load_file($xslfile);
	else
		echo "XSLT $xmlfile not found<br>";

	if ($xsl and $xml)
	{
		$styleSheet->importStylesheet($xsl);

		$styleSheet->setParameter("","ui_language",$lang);
	
		$text = $styleSheet->transformToXML($xml);

		print_r($text);
	}
}
?>
