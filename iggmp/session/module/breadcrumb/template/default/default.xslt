<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='path'>
<!-- display only if more than one menu level -->
		<xsl:if test="count(path_point) > 1">
			<div id="module_breadcrumb_field">
				<xsl:apply-templates select="path_point"/>
			</div>
		</xsl:if>
	</xsl:template>

<!-- create path point with link to page -->
	<xsl:template match="path_point">
<!--		<xsl:if test="position() > 1">
			<xsl:text> | </xsl:text>
		</xsl:if>-->

		<a>
			<xsl:attribute name="href">
				<xsl:value-of select="@href"/>
			</xsl:attribute>

			<span>
				<xsl:attribute name="class">
					<xsl:value-of select="@class"/>
				</xsl:attribute>

				<xsl:value-of select="."/>
			</span>
		</a>
	</xsl:template>
</xsl:stylesheet>
