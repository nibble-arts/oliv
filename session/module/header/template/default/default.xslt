<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='oliv_header'>
		<a>
			Title <xsl:value-of select="./title"/>

<!--	  	<div id='header_img' background-image="oliv_logo.png" />-->
	  </a>
	</xsl:template>

</xsl:stylesheet>
