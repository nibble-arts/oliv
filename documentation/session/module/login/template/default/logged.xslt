<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='login'>
		<div id="module_login_field">

			<form method="current()">
				<span class="module_login">
					<xsl:value-of select="welcome"/>
					<xsl:text> </xsl:text>
				</span>

				<span>
					<xsl:choose>
<!-- logged in as superuser -->
						<xsl:when test="su">
							<xsl:attribute name="class">module_login_su</xsl:attribute>
							<a>
								<xsl:attribute name="title">
									<xsl:value-of select="su_note"/>
								</xsl:attribute>
								<xsl:value-of select="username"/>
							</a>
						</xsl:when>

<!-- logged in as normal user -->
						<xsl:when test="not(su) and user_groups != ''">
							<xsl:attribute name="class">module_login</xsl:attribute>
								<a>
									<xsl:attribute name="title">
										<xsl:value-of select="user_note"/>
										<xsl:text>: </xsl:text>
										<xsl:value-of select="user_groups"/>
									</xsl:attribute>
									<xsl:value-of select="username"/>
								</a>
						</xsl:when>

<!-- logged without rights -->
						<xsl:when test="not(su) and user_groups = ''">
							<span>
								<xsl:attribute name="class">module_login</xsl:attribute>
								<xsl:value-of select="username"/>
							</span>
						</xsl:when>
					</xsl:choose>
				</span>
			
				<xsl:text> </xsl:text>

				<input type="submit">
					<xsl:attribute name="value">
						<xsl:value-of select="logout"/>
					</xsl:attribute>
				</input>

				<input type="hidden" name="action" value="logout"/>
			</form>
		</div>
	</xsl:template>

</xsl:stylesheet>
