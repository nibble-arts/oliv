<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="redirect">
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="masterpage">
		<xsl:value-of select="."/>
	</xsl:template>
</xsl:stylesheet>

