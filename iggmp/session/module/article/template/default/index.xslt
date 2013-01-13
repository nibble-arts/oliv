<?xml version='1.0' encoding='utf8'?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="article_index">
		<span id="selector">
			<xsl:apply-templates select="lang_selector/selector"/>
		</span>
		
		<h2><xsl:apply-templates select="article_index_002"/></h2>
		<p><xsl:apply-templates select="article_index_003"/></p>
		<p><xsl:apply-templates select="article_index_004"/></p>
		<ul>
			<li><xsl:apply-templates select="article_index_005"/></li>
			<li><xsl:apply-templates select="article_index_006"/></li>
			<li><xsl:apply-templates select="article_index_007"/></li>
			<li><xsl:apply-templates select="article_index_008"/></li>
			<li><xsl:apply-templates select="article_index_009"/></li>
		</ul>
		<p><xsl:apply-templates select="article_index_010"/></p>

	<!--  <p owner='tom' group='admin' access='774' lang='de' index='1336633758'>ARTICLE_INDEX_002</p>-->
	</xsl:template>


	<xsl:template match="lang_selector/selector">

		<a>
			<xsl:attribute name="href">
				<xsl:value-of select="./a/@href"/>
			</xsl:attribute>

			<xsl:attribute name="title">
				<xsl:value-of select="./a/@title"/>
			</xsl:attribute>

			<xsl:attribute name="lang">
				<xsl:value-of select="./a/@lang"/>
			</xsl:attribute>

			<img>
				<xsl:attribute name="src">
					<xsl:value-of select="./img"/>
				</xsl:attribute>

				<xsl:attribute name="lang">
					<xsl:value-of select="./img/@lang"/>
				</xsl:attribute>

				<xsl:attribute name="id">
					<xsl:value-of select="./img/@id"/>
				</xsl:attribute>
			</img>
		</a>
	</xsl:template>


<!-- paragraph definitions -->
	<xsl:template match="article_index_002">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_index_003">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_index_004">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_index_005">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_index_006">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_index_007">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_index_008">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_index_009">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="article_index_010">
		<xsl:attribute name="textsource">
			<xsl:value-of select="./@source"/>
		</xsl:attribute>
		<xsl:attribute name="articlename">
			<xsl:value-of select="name(.)"/>
		</xsl:attribute>

		<xsl:value-of select="."/>
	</xsl:template>

</xsl:stylesheet>
