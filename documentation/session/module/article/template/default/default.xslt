<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="*">

		<xsl:for-each select="*">
			<xsl:variable name="tag" select="name(.)"/>

<!-- parse tags -->
<!-- image tag -->
			<xsl:choose>
				<xsl:when test="$tag = 'img'">
				</xsl:when>

<!-- a tag -->
				<xsl:when test="$tag = 'a'">
LINK
				</xsl:when>


<!-- ul tag -->
				<xsl:when test="$tag = 'ul'">
					<xsl:call-template name="article_ul">
						<xsl:with-param name="node" select="."/>
					</xsl:call-template>
				</xsl:when>
				
<!-- text tag -->
				<xsl:otherwise>
					<xsl:copy>
						<xsl:call-template name="article_paragraph">
							<xsl:with-param name="node" select="."/>
						</xsl:call-template>
					</xsl:copy>

				</xsl:otherwise>
			</xsl:choose>
		</xsl:for-each>

	</xsl:template>

</xsl:stylesheet>
