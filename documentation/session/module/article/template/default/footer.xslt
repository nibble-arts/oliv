<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='footer'>
		<div id="footer_background">
			<a href="http://github.com/nibble-arts/oliv" target="_blank">
				<span id="footer_text">
					<xsl:value-of select="./content/text_001"/>
				</span>

				<img id="footer_img" src="oliv_logo"/>

				<system id="footer_text">oliv_version</system>
			</a>
		</div>
		<pre><status>debug</status></pre>
	</xsl:template>

</xsl:stylesheet>
