<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='header'>
		<div id="header_background" background-image="">
			<a href="index">
		  	<div id='header_img' background-image="oliv_logo.png" />
			</a>


			<div id="header_title">
				<xsl:value-of select="./header_title/text[@lang=$lang]"/>
			</div>
		</div>
	</xsl:template>

</xsl:stylesheet>
