<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="menu_main">
		<div id='menu_background_side'>
			<xsl:apply-templates select="menu_item_side"/>
		</div>
	</xsl:template>

	<xsl:template match="menu_admin">
		<div id='menu_background_side'>
			<xsl:apply-templates select="menu_item_side"/>
		</div>
	</xsl:template>


	<xsl:template match="menu_item_side">
		<div id="menu_item_side">
			<xsl:attribute name="class">
				<xsl:value-of select="class"/>
			</xsl:attribute>

				<a>
					<xsl:if test="./url != ''">
						<xsl:attribute name="href">
							<xsl:value-of select="url"/>
						</xsl:attribute>

						<xsl:attribute name="target">
							<xsl:value-of select="target"/>
						</xsl:attribute>

						<xsl:attribute name="title">
							<xsl:value-of select="title"/>
						</xsl:attribute>
					</xsl:if>

					<span>
						<xsl:attribute name="class">
							<xsl:value-of select="class"/>
						</xsl:attribute>

						<xsl:value-of select="name"/>
					</span>
				</a>
			</div>
	</xsl:template>
</xsl:stylesheet>

