<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='page_construct'>
		<span id="article_lang_selector">
			<xsl:apply-templates select="lang_selector/selector"/>
		</span>

		<h1><xsl:apply-templates select="title"/></h1>
		<p><xsl:apply-templates select="summary"/></p>

		<form>
			<fieldset>
				<legend>
				</legend>
				
				<p><xsl:apply-templates select="pagename"/><br/>
				<input class="input" type="text" name="pageName" value="$pageName"/></p>

				<p><xsl:apply-templates select="friendlyname"/><br/>
				<input class="input" type="text" name="pageFriendlyName" value="$pageFriendlyName"/></p>

				<p><xsl:apply-templates select="pageid"/><br/>
				<input class="input" type="text" name="pageId" value="$pageId"/></p>

				<p><xsl:apply-templates select="pagetitle"/><br/>
				<input class="input" type="text" name="pageTitle" value="$pageTitle"/></p>

				<input type="submit" name="" value="anlegen"/>
			</fieldset>
		</form>
	</xsl:template>


<!-- paragraph section -->
	<xsl:template match="title">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

	<xsl:template match="summary">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

	<xsl:template match="pagename">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

	<xsl:template match="friendlyname">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

	<xsl:template match="pageid">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>

	<xsl:template match="pagetitle">
		<xsl:call-template name="article_paragraph">
			<xsl:with-param name="node" select="."/>
		</xsl:call-template>
	</xsl:template>



<!-- language selector -->
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
