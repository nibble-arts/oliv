<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="div">
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="span">
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="background-image">
		<xsl:value-of select="."/>
	</xsl:template>
</xsl:stylesheet>

