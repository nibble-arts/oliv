<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="structure">

<!--<xsl:variable name="param" select="'articlename'"/>-->
<!--<xsl:value-of select="param/*[$param]"/>-->

		<xsl:for-each select="*">
			<xsl:value-of select="name(.)"/>
			<xsl:variable name="text" select="."/>
			'<xsl:value-of select="$text"/>'<br/>
		</xsl:for-each>

	</xsl:template>


	<xsl:template match="content"/>
</xsl:stylesheet>
