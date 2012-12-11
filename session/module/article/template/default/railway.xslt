<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:variable name="lang" select="'de'"/>

	<xsl:call-template name="daba_init">
		<xsl:with-param name="db" select="'iggmp'"/>
		<xsl:with-param name="table" select="'railway'"/>
	</xsl:call-template>
	
	<dabaimg src='images' float='right' width='300em'  margin_left='0.5em' margin_bottom='0.5em'>_id</dabaimg>

  <h3><daba>title</daba></h3>
  <p><daba>summary</daba></p>

	<h3>address</h3>
  <p>
  	<div><daba>address</daba></div>
  	<div><daba>zip</daba><daba>city</daba></div>
  	<div><daba>land</daba></div>
  </p>

  <h3>
  	<text lang="de">Betreiber</text>
  	<text lang="en">operator</text>
  </h3>
	<p>
  	<daba>operator</daba>
  </p>

</xsl:stylesheet>
