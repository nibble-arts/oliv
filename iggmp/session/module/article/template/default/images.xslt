<?xml version="1.0" encoding="UTF-8" standalone="yes"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="article_images">
		<div>
			<img src='loco1' width='200px' />

			<p class='oliv_image_text'>
				<xsl:apply-templates select="//article_images_001"/>
			</p>

			<img src='loco2' width='200px' />

			<p class='oliv_image_text'>
				<xsl:apply-templates select="//article_images_002"/>
			</p>
		</div>

	</xsl:template>
	
	<xsl:template match="article_images_001">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_images_002">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

</xsl:stylesheet>
