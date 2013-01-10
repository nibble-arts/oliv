<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='toolbox'>
		<div id="toolbox">
			<div id="tools">
				<xsl:apply-templates select="icon"/>
			</div>

			<span id="toolbox_grip">
				<img id="toolgrip_img" src='toolbox_grip_out.png'/>
			</span>
		</div>
	</xsl:template>

	<xsl:template match='icon'>
		<div id="toolbox_icon">
			<a href="template_edit">
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
					<xsl:attribute name="alt">
						<xsl:value-of select="name"/>
					</xsl:attribute>
				</img>
			</a>
		</div>
	</xsl:template>
	
</xsl:stylesheet>
