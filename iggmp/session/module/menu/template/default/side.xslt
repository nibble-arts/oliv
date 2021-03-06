<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="menu">
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
						<xsl:if test="val">
							<xsl:text>:</xsl:text>
							<xsl:value-of select="val"/>
						</xsl:if>
					</xsl:attribute>

					<xsl:attribute name="target">
						<xsl:value-of select="target"/>
					</xsl:attribute>

					<xsl:attribute name="title">
						<xsl:value-of select="title"/>
					</xsl:attribute>

					<xsl:attribute name="name">
						<xsl:value-of select="name"/>
					</xsl:attribute>

					<xsl:attribute name="val">
						<xsl:value-of select="friendly_name"/>
					</xsl:attribute>
				</xsl:if>

<!-- show arrows -->
				<xsl:if test="submenu">
					<xsl:choose>
		<!-- active -->
						<xsl:when test="status">
							<img class="menu_arrow" src="less_arrow_bright.png"/>
						</xsl:when>

		<!-- inactive -->
						<xsl:otherwise>
							<img class="menu_arrow" alt="[+]" src="more_arrow_dark.png"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:if>
			
<!-- show text -->
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

