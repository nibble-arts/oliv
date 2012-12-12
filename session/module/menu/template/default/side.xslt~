<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="menu">
		Seiten-Menü anzeigen
		<div id='menu_background_side' style='background-color:#dcdc78;padding:3px'>
			<xsl:apply-templates select="//menu_item_side"/>
		</div>
	</xsl:template>

	<xsl:template match="menu_item_side">
    <div id='menu_item_side'>
Seitenmenü
    	<xsl:value-of select="./url"/>
    </div>
	</xsl:template>

</xsl:stylesheet>

