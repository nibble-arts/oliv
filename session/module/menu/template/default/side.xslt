<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="menu">
		Seiten-Menü anzeigen

		<xsl:value-of select="count(.)"/>

		<xsl:for-each select="menu">
			<xsl:value-of select="position()"/>
		</xsl:for-each>

<!--		<xsl:apply-templates select="url"/>-->

		<div id='menu_background_side' style='background-color:#dcdc78;padding:3px'>
			<xsl:apply-templates select="//menu_item_side"/>
		</div>
	</xsl:template>

	<xsl:template match="url">
		<p>
			<xsl:value-of select="position()"/>
			<xsl:value-of select="."/>
		</p>
	</xsl:template>

</xsl:stylesheet>

