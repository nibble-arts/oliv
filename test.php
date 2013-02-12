<html>
	<head>
		<title>Tiny-MCE Test</title>
		<script type="text/javascript" src="javascript/jquery-1.7.1.js"></script>
		<script type="text/javascript" src="javascript/tiny_mce/tiny_mce.js"></script>

		<script type="text/javascript">
		tinyMCE.init({
				    // General options
				    mode : "exact",
				    elements : "editor",
				    theme : "advanced",
				    encoding : "xml",
				    plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

				    // Theme options
				    theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
				    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
				    theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
				    theme_advanced_toolbar_location : "top",
				    theme_advanced_toolbar_align : "left",
				    theme_advanced_statusbar_location : "bottom",
				    theme_advanced_resizing : true,
		});
		</script>

	</head>

	<body>
		<h1>Tiny-MCE Testseite</h1>
		<?PHP
// load text submitted
		if (array_key_exists("text",$_GET))
			$text = $_GET['text'];
		// convert to html
		// convert to html
		else
			$test = "";
			
		?>

		<form method="get" action="test.php">
		<textarea id="editor" name="text" rows="10" cols="50"><?PHP echo $text; ?></textarea>

		<input type="submit" value="abschicken">


		<?PHP
		echo "<hr>";

//		$textXml = new simpleXmlElement($text);
		
		// convert to html
		$text = str_replace(array("<",">","\n"),array("&lt;","&gt;","<br>"),$text);

		print_r($text);


		?>
	</body>
</html>
