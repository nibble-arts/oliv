<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="menu">
		<div id='menu_background_side' style='background-color:#dcdc78;padding:3px'>
			<xsl:for-each select="./*">
				<xsl:if test="url">
					<a>
						<xsl:attribute name="href">
							<xsl:value-of select="url"/>
						</xsl:attribute>

						<xsl:attribute name="target">
							<xsl:value-of select="title"/>
						</xsl:attribute>

						<xsl:attribute name="target">
							<xsl:value-of select="title/text[@lang=$lang]"/>
						</xsl:attribute>

						<div id="menu_item_side"><xsl:value-of select="name/text[@lang=$lang]"/></div>
					</a>
				</xsl:if>
			</xsl:for-each>

			<xsl:apply-templates select="//menu_item_side"/>
		</div>
	</xsl:template>

	<xsl:template match="url">
		<p>
			<xsl:value-of select="position()"/>
			<xsl:value-of select="."/>
		</p>
	</xsl:template>

</xsl:stylesheet>

