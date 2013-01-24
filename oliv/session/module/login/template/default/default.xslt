<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='login'>
		<div id="module_login_field">
			<form>
<!-- login field -->
				<input type="text" name="login">
					<xsl:attribute name="value">
						<xsl:value-of select="./user"/>
					</xsl:attribute>
				</input>

<!-- password field -->
				<input type="password" name="password"/>

<!-- submit -->
				<input type="submit">
					<xsl:attribute name="value">
						<xsl:value-of select="./login"/>
					</xsl:attribute>
				</input>

<!-- login action definition -->
				<input type="hidden" name="action" value="login"/>
			</form>
		</div>
	</xsl:template>

</xsl:stylesheet>
