<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="daba_init">
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="daba">
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="dabaimg">
		<xsl:value-of select="."/>
	</xsl:template>
</xsl:stylesheet>

