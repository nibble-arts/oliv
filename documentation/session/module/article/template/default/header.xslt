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

			<span id="article_lang_selector">
				<xsl:apply-templates select="lang_selector/selector"/>
			</span>
		</div>
	</xsl:template>

	<xsl:template match="header_title">
		<div id="header_title">
			<xsl:call-template name="article_paragraph">
				<xsl:with-param name="node" select="."/>
			</xsl:call-template>
		</div>
	</xsl:template>

	<xsl:template match="lang_selector/selector">
		<a>
			<xsl:attribute name="href">
				<xsl:value-of select="./a/@href"/>
			</xsl:attribute>

			<xsl:attribute name="title">
				<xsl:value-of select="./a/@title"/>
			</xsl:attribute>

			<xsl:attribute name="lang">
				<xsl:value-of select="./a/@lang"/>
			</xsl:attribute>

			<img>
				<xsl:attribute name="src">
					<xsl:value-of select="./img"/>
				</xsl:attribute>

				<xsl:attribute name="lang">
					<xsl:value-of select="./img/@lang"/>
				</xsl:attribute>

				<xsl:attribute name="id">
					<xsl:value-of select="./img/@id"/>
				</xsl:attribute>
			</img>
		</a>
	</xsl:template>

</xsl:stylesheet>
