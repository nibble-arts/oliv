<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="article_index">
		<a href="index">
			Edit Template
		</a>

<!-- image sequence -->
		<img id="article_index_img">
			<xsl:attribute name="src">
				<xsl:value-of select="//article_index_img_001"/>
			</xsl:attribute>
		</img>
		
<!-- text paragraph sequence -->
		<h1><xsl:apply-templates select="//article_index_001"/></h1>
		<p><xsl:apply-templates select="//article_index_002"/></p>
		<xsl:apply-templates select="//daba_init"/>
		<xsl:apply-templates select="//daba"/>

<!-- spezial fields -->
		<span class='daba-field'/>
	</xsl:template>


<!-- paragraph definitions -->
	<xsl:template match="article_index_001">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

	<xsl:template match="article_index_002">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>


	<xsl:template match="daba_init">
		<daba_init>Database plugin not installed: <xsl:value-of select="."/></daba_init>
	</xsl:template>


	<xsl:template match="daba">
		<p>
			<daba><xsl:value-of select="."/></daba>
		</p>
	</xsl:template>

</xsl:stylesheet>
