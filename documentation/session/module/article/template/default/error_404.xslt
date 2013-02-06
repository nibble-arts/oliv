<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="error_404">

<!-- text paragraph sequence -->
		<h1><xsl:apply-templates select="article_001"/></h1>
		<p><xsl:apply-templates select="article_002"/></p>
	</xsl:template>


<!-- paragraph definitions -->
	<xsl:template match="article_001">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

	<xsl:template match="article_002">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

</xsl:stylesheet>
