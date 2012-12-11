<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="xml" encoding="UTF-8" indent="yes"/>

	<xsl:template match="/">
		Alles
		<xsl:apply-templates select="p"/>
		<xsl:apply-templates select="h1"/>
		<xsl:apply-templates select="h2"/>
		<xsl:apply-templates select="h3"/>
		<xsl:apply-templates select="li"/>
	</xsl:template>

	<xsl:template match="p">
		<p>P-Tag <xsl:value-of select="."/></p>
	</xsl:template>
</xsl:stylesheet>

