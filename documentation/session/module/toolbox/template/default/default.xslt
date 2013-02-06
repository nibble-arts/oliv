<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='toolbox'>
		<div id="toolbox">

			<div id="tools">
				<xsl:apply-templates select="icon"/>
			</div>

			<span id="toolbox_grip">
				<img id="toolgrip_img" src='toolbox_grip_out.png'/>

				<v id="tooltitle"><xsl:value-of select="name"/></v>
			</span>

<!--			<span>
				<table width="100%" id="toolbox_config"><tr>
					<td align="left">
						<a href="current():toolfix=fix">
							<img src="toolbox_fix.png"/>
						</a>
					</td>
					<td align="right"><img src="toolbox_config.png"/></td>
				</tr></table>
			</span>-->

		</div>
	</xsl:template>

	<xsl:template match='icon'>
		<div id="toolbox_icon">
			<xsl:choose>
				<xsl:when test="href">

					<a>
						<xsl:attribute name="href">
							<xsl:value-of select="href"/>
						</xsl:attribute>
						<xsl:attribute name="title">
							<xsl:value-of select="title"/>
						</xsl:attribute>

						<img>
							<xsl:attribute name="src">
								<xsl:value-of select="image"/>
							</xsl:attribute>
							<xsl:attribute name="width">
								<xsl:value-of select="'36'"/>
							</xsl:attribute>
							<xsl:attribute name="alt">
								<xsl:value-of select="name"/>
							</xsl:attribute>
						</img>
					</a>
				</xsl:when>

				<xsl:otherwise>
					<img>
						<xsl:attribute name="src">
							<xsl:value-of select="image"/>
						</xsl:attribute>
						<xsl:attribute name="width">
							<xsl:value-of select="'36'"/>
						</xsl:attribute>
						<xsl:attribute name="alt">
							<xsl:value-of select="name"/>
						</xsl:attribute>
					</img>
				</xsl:otherwise>
			</xsl:choose>
		</div>
	</xsl:template>
	
</xsl:stylesheet>
