<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='search'>
		<div id="module_search_field">
			<form>
				<xsl:attribute name="action">
					<xsl:value-of select="target"/>
				</xsl:attribute>
				
<!-- search text field -->
				<input type="text" name="search">
					<xsl:attribute name="value">
						<xsl:value-of select="searchtext"/>
					</xsl:attribute>
				</input>

<!-- submit -->
				<input type="submit">
					<xsl:attribute name="value">
						<xsl:value-of select="search"/>
					</xsl:attribute>
				</input>

<!-- login action definition -->
				<input type="hidden" name="action" value="search"/>
			</form>
		</div>
	</xsl:template>

</xsl:stylesheet>
