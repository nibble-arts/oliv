<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="system_name">
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="system_autor">
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="system_version">
		<xsl:value-of select="."/>
	</xsl:template>
</xsl:stylesheet>

