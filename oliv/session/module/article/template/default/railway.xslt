<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="article_railway">
		<h3><daba_init><xsl:value-of select="daba_init"/></daba_init></h3>

		<div id="railway_main_image"><daba_image><xsl:value-of select="mainimage"/></daba_image></div>

		<h3><xsl:value-of select="address"/></h3>

		<p>
			<div><daba><xsl:value-of select="road"/></daba></div>
			<div><daba><xsl:value-of select="zip"/></daba>

			<xsl:text> - </xsl:text>

			<daba><xsl:value-of select="city"/></daba></div>
			<div><daba><xsl:value-of select="land"/></daba></div>
		</p>

		<h3>
			<text lang="de">Betreiber</text>
			<text lang="en">operator</text>
		</h3>
		<p>
			<daba><xsl:value-of select="society"/></daba>
		</p>
	</xsl:template>

</xsl:stylesheet>
