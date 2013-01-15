<?xml version="1.0" encoding="UTF-8"?>

<!-- spring calculator -->

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='spring'>
		<h1>
			<xsl:attribute name="textsource">
				<xsl:value-of select="title/@source"/>
			</xsl:attribute>

			<xsl:value-of select="title"/>
		</h1>

		<form>
<!-- spring type -->
			<fieldset>
				<legend>
					<xsl:call-template name="article_paragraph">
						<xsl:with-param name="node" select="type"/>
					</xsl:call-template>
				</legend>

				<div>
					<input type="radio" name="spring_type" value="0"/>
					<img src="spring01.png" width="250px"/><br/>
					<input type="radio" name="spring_type" value="1"/>
					<img src="spring02.png" width="250px"/>
				</div>
			</fieldset>

<!-- spring leaf values -->
			<fieldset>
				<legend>
					<xsl:call-template name="article_paragraph">
						<xsl:with-param name="node" select="leaf"/>
					</xsl:call-template>
				</legend>

				<table>
					<tr>
						<td>
							<xsl:call-template name="article_paragraph">
								<xsl:with-param name="node" select="leaf_length"/>
							</xsl:call-template>
						</td>
						<td> L </td>
						<td>
							<input type="text" name="spring_L" value="$spring_L">
								<xsl:attribute name="class"><xsl:value-of select="leaf_length_class"/></xsl:attribute>
							</input> mm
						</td>
					</tr>
					<tr>
						<td>
							<xsl:call-template name="article_paragraph">
								<xsl:with-param name="node" select="leaf_width"/>
							</xsl:call-template>
						</td>
						<td> b </td>
						<td>
							<input type="text" name="spring_b" value="$spring_b">
								<xsl:attribute name="class"><xsl:value-of select="leaf_width_class"/></xsl:attribute>
							</input> mm
						</td>
					</tr>
					<tr>
						<td>
							<xsl:call-template name="article_paragraph">
								<xsl:with-param name="node" select="leaf_thickness"/>
							</xsl:call-template>
						</td>
						<td> t </td>
						<td>
							<input type="text" name="spring_t" value="$spring_t">
								<xsl:attribute name="class"><xsl:value-of select="leaf_thickness_class"/></xsl:attribute>
							</input> mm
						</td>
						</tr>
				</table>
			</fieldset>
			
			<fieldset>
				<legend>
					<xsl:call-template name="article_paragraph">
						<xsl:with-param name="node" select="package"/>
					</xsl:call-template>
				</legend>

<!-- spring package values -->
				<table>
					<tr>
						<td>
							<xsl:call-template name="article_paragraph">
								<xsl:with-param name="node" select="leaf_count"/>
							</xsl:call-template>
						</td>
						<td> n </td>

						<td>
							<input type="text" name="spring_n" value="$spring_n">
								<xsl:attribute name="class"><xsl:value-of select="leaf_count_class"/></xsl:attribute>
							</input>
						</td>
					</tr>


					<tr><td>
						<xsl:call-template name="article_paragraph">
							<xsl:with-param name="node" select="leaf_add_count"/>
						</xsl:call-template>
					</td>
					<td> n' </td>
					<td><input type="text" name="spring_n1" value="$spring_n1"/></td></tr>
				</table>

				<img src="spring03.png" width="250px"/><br/>

<!-- list of leaf lengths if L and n defined -->
				<xsl:if test="count(leaf_length_list)">
					<div class="spring_result">
						<span>
							<xsl:call-template name="article_paragraph">
								<xsl:with-param name="node" select="leaf_lengths"/>
							</xsl:call-template>
							<xsl:text>: </xsl:text>
						</span>
						<xsl:apply-templates select="leaf_length_list"/> mm
						<br/>
						<span>
							<xsl:call-template name="article_paragraph">
								<xsl:with-param name="node" select="material_length"/>
							</xsl:call-template>
							<xsl:text>: </xsl:text>
						</span>
						<xsl:value-of select="leaf_length_overall"/> mm
					</div>
				</xsl:if>
			</fieldset>

<!-- operation values -->
			<fieldset>
				<legend>
					<xsl:attribute name="textsource">
						<xsl:value-of select="operation/@source"/>
					</xsl:attribute>

					<xsl:value-of select="operation"/>
				</legend>

				<i>
					<xsl:call-template name="article_paragraph">
						<xsl:with-param name="node" select="operation_summary"/>
					</xsl:call-template>
				</i>

				<table>
					<tr>
						<td>
							<xsl:call-template name="article_paragraph">
								<xsl:with-param name="node" select="force"/>
							</xsl:call-template>
						</td>
						<td> F </td>
						<td><input type="text" name="spring_F" value="$spring_F"/> N</td>
					</tr>
					<tr>
						<td>
							<xsl:call-template name="article_paragraph">
								<xsl:with-param name="node" select="bending"/>
							</xsl:call-template>
						</td>
						<td> s </td>
						<td><input type="text" name="spring_s" value="$spring_s"/> mm</td>
					</tr>
				</table>

				<xsl:choose>
<!-- display result -->
					<xsl:when test="result_name">
						<div>
							<xsl:attribute name="class">
								<xsl:value-of select="spring_result_class"/>
							</xsl:attribute>
							
							<xsl:value-of select="result_name"/>:

							<xsl:value-of select="result_value"/>
							<xsl:text> = </xsl:text>
							<xsl:value-of select="result"/>
							<xsl:text> </xsl:text>
							<xsl:value-of select="result_unit"/>

							<br/>
							
							<xsl:value-of select="sigma"/>:
							<xsl:text> &#963; = </xsl:text>
							<xsl:value-of select="spring_sigma"/> N/mm²
						</div>
					</xsl:when>

<!-- no value defined => error -->
					<xsl:otherwise>
<!-- display only if calculation was started -->
						<xsl:if test="action">
							<div>
								<xsl:attribute name="class">
									<xsl:value-of select="spring_result_class"/>
								</xsl:attribute>
							
								<xsl:value-of select="no_value"/>
							</div>
						</xsl:if>
					</xsl:otherwise>
				</xsl:choose>
			</fieldset>

			<input type="submit" name="spring_calc">
				<xsl:attribute name="value"><xsl:value-of select="calc"/></xsl:attribute>
			</input>

			<input type="submit" name="string_abort">
				<xsl:attribute name="value"><xsl:value-of select="abort"/></xsl:attribute>
			</input>
		</form>

	</xsl:template>


	<xsl:template match="leaf_length_list">
		<xsl:if test="position() > 1">
			<xsl:text>, </xsl:text>
		</xsl:if>

		<xsl:value-of select="."/>
	</xsl:template>

</xsl:stylesheet>
