<?xml version="1.0" encoding="UTF-8" standalone="yes"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="article_header">

		<div id='header_background'>
			<img src="mrdb_logo.png" id='header_img' width='200px'/>

			<a href='mailto:office@iggmp.net' id='header_logo'>
				<xsl:attribute name="title">
					<xsl:value-of select="get_contact"/>
				</xsl:attribute>

				<img src='iggmp_logo.png' id='header_logo_img'/>
			</a>

			<div id='header_title'><p class='header_title'>
				<xsl:apply-templates select="//header_title"/>
			</p></div>
		</div>

	</xsl:template>
</xsl:stylesheet>
