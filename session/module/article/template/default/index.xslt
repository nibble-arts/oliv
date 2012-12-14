<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="article_index">
		<xsl:apply-templates select="//article_index_001"/>
		<xsl:apply-templates select="//article_index_002"/>
		<xsl:apply-templates select="//daba"/>

		<span class='daba-field'>
		</span>
	</xsl:template>

	<xsl:template match="article_index_001">
		<h1>
			<xsl:value-of select="./text[@lang=$lang]"/>
		</h1>
	</xsl:template>

	<xsl:template match="article_index_002">
		<p>
			<xsl:value-of select="./text[@lang=$lang]"/>
		</p>
	</xsl:template>

	<xsl:template match="daba">
		<p>
			Database value
			<xsl:value-of select="."/>
		</p>
	</xsl:template>

</xsl:stylesheet>
