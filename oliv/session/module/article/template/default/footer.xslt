<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='footer'>
		<div id="footer_background">
			<a href="index">
				<img id="footer_img" src="oliv_logo"/>
			
				<span id="footer_text">
					<xsl:value-of select="./footer_text"/>
				</span>
			</a>

			<oliv_version id="footer_text"/>
		</div>
	</xsl:template>

</xsl:stylesheet>
