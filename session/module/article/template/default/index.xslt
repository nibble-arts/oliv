<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="article_index">
		<img id="article_index_img" src="CIMG1529.jpg"/>
		
		<xsl:apply-templates select="//article_index_001"/>
		<xsl:apply-templates select="//article_index_002"/>
		<xsl:apply-templates select="//daba_init"/>
		<xsl:apply-templates select="//daba"/>

		<span class='daba-field'>
		</span>
	</xsl:template>

	<xsl:template match="article_index_001">
		<h1>
			<xsl:attribute name="source">
				<xsl:value-of select="./text[@lang=$lang]/@source"/>
			</xsl:attribute>
			
			<xsl:value-of select="./text[@lang=$lang]"/>
		</h1>
	</xsl:template>

	<xsl:template match="article_index_002">
		<p>
			<xsl:attribute name="source">
				<xsl:value-of select="./text[@lang=$lang]/@source"/>
			</xsl:attribute>

			<xsl:value-of select="./text[@lang=$lang]"/>
		</p>
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
