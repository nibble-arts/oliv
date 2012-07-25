<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Beispiel</title>	
</head>
<body>

<?PHP
include("library/core.php");
$core = new OLIVCore();
$core->init("iggmp");

$index = new OLIVIndex();

$url = "index";
$val = "";
$text = "Willkommen beim OLIV Content Management System OLIV ist ein Content-Management-System, das eigentlich für den Ersatz einer bestehenden Internet-Präsentation begonnen wurde, die auf sehr unsauber programmierten und unflexieblen Scripten basierte. Um den gewünschten Anforderungen gerecht zu werden, entstanden einige Vorgaben: Multilingualität in allen Funktionen Multisessionfähigkeit Erweiterbarkeit durch Module Nur PHP > 5.3 aber keine Datenbank nötig Einfachste Bedienung Multilingualtiät Auch andere CM-Systeme beherrschen die Darstellung in mehreren Sprachen. Der Wunsch bei OLIV war jedoch die Integration der Mehrsprachigkeit tief im Grundsystem zu verankern und auf alle Funktionen anzuwenden. So werden zum Beispiel auch die klar lesbaren URLs in die jeweilige Sprache übersetzt. Eine Besonderheit ist auch der mehrsprachige Umgang mit Texten. So wird ein Text von dem ursprünglichen Autor in seiner Sprache verfasst. Der Übersetzer kann nun die einzelnen Teile, wie Überschriften, Absätze, Aufzählungen, etc. einzeln übersetzten. Die Grundstruktur bleibt dabei aber unverändert und ist nur einmal definiert. Wird vom Ursprungsautor eine Änderung vorgenommen, so wird der Übersetzer durch ein internes Messaging-System über diese Änderung benachrichtigt. Bei der Suche ist es so möglich bei der Anzeige der deutschen Version auch anderssprachige Begriffe zu finden, und den entsprechenden deutschen Text anzuzeigen (mit einem Hinweis auf den gesuchten Begriff). Multisession Obwohl OLIV relativ schlank ist, bestand der Wunsch mit einer Installation mehrere Seiten betreiben zu können. Daraus ist ein Session-Modell entstanden. Jede Website kann innerhalb seiner Session alle Inhalte, Templates und Bilder selbst verwalten, nutzt jedoch den nur einmal installierten Core von OLIV. Systemanforderungen OLIV benötigt zum Betrieb lediglich PHP in einer Version nach 5.3. Es ist so ausgelegt, dass jegliche Daten in XML oder INI Files abgelegt werden und somit keine Datenbank benötigt wird. Die Einbindung von Datenbanken ist für Module natürlich möglich und wird in Form eines relationalen Datenbankmoduls auch realisiert. Das OLIV-Grundsystem verzichtet primär auf den Einsatz von JavaScript für die reine Anzeige der Seite. Solche Erweiterungen können nachtrüglich eingeführt werden. Für den Administrator ist JavaScript für die komfortable Bearbeitung der Texte vorgesehen und nutzt den TinyMCE Editor. Bedienung Das Bedienungskonzept ist auf weitestgehende Einfachheit ausgelegt. Ein UNIX/LINUX-artiges Rechtesystem ermöglicht auf Modul- und Seiten- und Artikelebene ein direktes Editieren auf der Seite. Es wird nur für die Systemeinstellungen eine Art Backend genutzt, alle sonstigen Einstellungen erfolgen in der normalen Seitenansicht.";

echo $index->insertText($text,$url,$val);

print_r($index->find("unix%"));
//print_r($index->index);

?>

</body>
</html>

