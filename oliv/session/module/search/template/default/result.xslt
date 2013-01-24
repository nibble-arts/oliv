<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='search_result'>

		<p><span class="oliv_title">
			<xsl:call-template name="article_paragraph">
				<xsl:with-param name="node" select="searchresult"/>
			</xsl:call-template>
		</span><br/>
		<span>
			<xsl:value-of select="count(./search_result/result)"/>
			<xsl:text> </xsl:text>
			
			<xsl:call-template name="article_paragraph">
				<xsl:with-param name="node" select="hits"/>
			</xsl:call-template>
		</span></p>
		
		<div id="module_search_result">
			<xsl:apply-templates select="./search_result/result"/>
		</div>
	</xsl:template>

	<xsl:template match="result">
		<div id="search_result_box">
			<a>
				<xsl:attribute name="href">
					<xsl:value-of select="page"/>
				</xsl:attribute>
				<xsl:attribute name="lang">
					<xsl:value-of select="lang"/>				
				</xsl:attribute>

				<span class='oliv_small'><xsl:value-of select="pagename"/></span><br/>
				<xsl:value-of select="summary"/>
			</a>
		</div>
	</xsl:template>
</xsl:stylesheet>
