<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="content">

<!-- text paragraph sequence -->
		<h1><xsl:apply-templates select="article_001"/></h1>
		<pre><status>pagestructure</status></pre>

	</xsl:template>


<!-- paragraph definitions -->
	<xsl:template match="article_001">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

</xsl:stylesheet>
