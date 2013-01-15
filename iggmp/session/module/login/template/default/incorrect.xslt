<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='login'>
		<div id="module_login_incorrect">
			<form>
				<xsl:if test="incorrect != ''">
					<xsl:value-of select="incorrect"/>
					<xsl:text> </xsl:text>
				</xsl:if>			

				<input type="text" name="login">
					<xsl:attribute name="value">
						<xsl:value-of select="'user'"/>
					</xsl:attribute>
				</input>

				<input type="password" name="password">
					<xsl:attribute name="value">
						<xsl:value-of select="password"/>
					</xsl:attribute>
				</input>

				<input type="submit">
					<xsl:attribute name="value">
						<xsl:value-of select="login"/>
					</xsl:attribute>
				</input>

				<input type="hidden" name="action" value="login"/>
			</form>
		</div>
	</xsl:template>

</xsl:stylesheet>
