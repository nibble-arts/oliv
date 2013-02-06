<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='login'>
		<div id="module_login_incorrect">
			<form>
				<input type="text" name="login">
					<xsl:attribute name="value">
						<xsl:value-of select="'$login'"/>
					</xsl:attribute>
				</input>

				<input type="password" name="password"/>

				<input type="submit">
					<xsl:attribute name="value">
						<xsl:value-of select="./login"/>
					</xsl:attribute>
				</input>

				<br/>

				<xsl:value-of select="./incorrect"/>

				<input type="hidden" name="action" value="login"/>
			</form>
		</div>
	</xsl:template>

</xsl:stylesheet>
