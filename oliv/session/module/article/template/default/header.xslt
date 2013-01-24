<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='header'>
		<div id="header_background">
			<a href="index" id='header_img'>
	  		<xsl:attribute name="background-image">
	  			<xsl:value-of select="header_logo"/>
	  		</xsl:attribute>
			</a>

			<xsl:apply-templates select="header_title"/>
		</div>
	</xsl:template>

	<xsl:template match="header_title">
		<div id="header_title">
			<xsl:call-template name="article_paragraph">
				<xsl:with-param name="node" select="."/>
			</xsl:call-template>
		</div>
	</xsl:template>

</xsl:stylesheet>
