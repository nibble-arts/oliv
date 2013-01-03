<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='login'>
		<div id="module_login_field">

			<form method="current()">
				<span class="module_login">
					<xsl:value-of select="./welcome"/>
					<xsl:text> </xsl:text>
					<xsl:value-of select="./username"/>
					<xsl:text> </xsl:text>
				</span>

				<input type="submit">
					<xsl:attribute name="value">
						<xsl:value-of select="./logout"/>
					</xsl:attribute>
				</input>

				<input type="hidden" name="action" value="logout"/>
			</form>
		</div>
	</xsl:template>

</xsl:stylesheet>
