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

			<system id="footer_text">oliv_version</system>
		</div>
	</xsl:template>

</xsl:stylesheet>
