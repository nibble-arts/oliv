<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="content">

		<xsl:apply-templates select="//param"/>

		<xsl:copy-of select="."/>
		
	</xsl:template>

	<xsl:template match="param">
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="argv">
	</xsl:template>
</xsl:stylesheet>
