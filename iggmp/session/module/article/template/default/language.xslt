<?xml version='1.0' encoding='utf-8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="article_language">
		<span id="article_lang_selector">
			<xsl:apply-templates select="lang_selector/selector"/>
		</span>

		<xsl:copy-of select="structure/*"/>
	</xsl:template>
</xsl:stylesheet>
