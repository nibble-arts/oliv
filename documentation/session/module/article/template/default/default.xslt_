<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- paragraph template -->
	<xsl:template name="article_paragraph">
		<xsl:param name="node"/>

		<xsl:if test="$node/@source">
			<xsl:attribute name="textsource">
				<xsl:value-of select="$node/@source"/>
			</xsl:attribute>
		</xsl:if>

		<xsl:attribute name="articlename">
			<xsl:value-of select="name($node)"/>
		</xsl:attribute>
		<xsl:attribute name="owner">
			<xsl:value-of select="$node/@owner"/>
		</xsl:attribute>
		<xsl:attribute name="group">
			<xsl:value-of select="$node/@group"/>
		</xsl:attribute>
		<xsl:attribute name="access">
			<xsl:value-of select="$node/@access"/>
		</xsl:attribute>

		<xsl:value-of select="$node"/>
	</xsl:template>

</xsl:stylesheet>
