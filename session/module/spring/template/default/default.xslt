<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='spring'>
		<h1><xsl:value-of select="title"/></h1>

		<form>
<!-- spring type -->
			<fieldset>
				<legend><xsl:value-of select="type"/></legend>

				<div>
					<input type="radio" name="spring_type" value="0"/>
					<img src="spring01.png"/><br/>
					<input type="radio" name="spring_type" value="1"/>
					<img src="spring02.png"/>
				</div>
			</fieldset>

<!-- spring leaf values -->
			<fieldset>
				<legend><xsl:value-of select="leaf"/></legend>

				<table>
					<tr>
						<td><xsl:value-of select="leaf_length"/></td>
						<td> L </td>
						<td>
							<input type="text" name="spring_L" value="$spring_L">
								<xsl:attribute name="class"><xsl:value-of select="leaf_length_class"/></xsl:attribute>
							</input> mm
						</td>
					</tr>
					<tr>
						<td><xsl:value-of select="leaf_width"/></td>
						<td> b </td>
						<td>
							<input type="text" name="spring_b" value="$spring_b">
								<xsl:attribute name="class"><xsl:value-of select="leaf_width_class"/></xsl:attribute>
							</input> mm
						</td>
					</tr>
					<tr>
						<td><xsl:value-of select="leaf_thickness"/></td>
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
				<legend><xsl:value-of select="package"/></legend>

<!-- spring package values -->
				<table>
					<tr>
						<td><xsl:value-of select="leaf_count"/></td>
						<td> n </td>
						<td>
							<input type="text" name="spring_n" value="$spring_n">
								<xsl:attribute name="class"><xsl:value-of select="leaf_count_class"/></xsl:attribute>
							</input>
						</td>
					</tr>
					<tr><td><xsl:value-of select="leaf_add_count"/></td><td> n' </td><td><input type="text" name="spring_n1" value="$spring_n1"/></td></tr>
				</table>
			</fieldset>

<!-- operation values -->
			<fieldset>
				<legend><xsl:value-of select="operation"/></legend>

				<i><xsl:value-of select="operation_summary"/></i>

				<table>
					<tr><td><xsl:value-of select="force"/></td><td> F </td><td><input type="text" name="spring_F" value="$spring_F"/> N</td></tr>
					<tr><td><xsl:value-of select="bending"/></td><td> s </td><td><input type="text" name="spring_s" value="$spring_s"/> mm</td></tr>
				</table>

				<xsl:choose>
<!-- display result -->
					<xsl:when test="result_name">
						<div>

							<xsl:attribute name="class">
								<xsl:value-of select="spring_result_class"/>
							</xsl:attribute>
							
							<xsl:value-of select="result_name"/>:
							<xsl:text> </xsl:text>
							<xsl:value-of select="result"/>
							<xsl:text> </xsl:text>
							<xsl:value-of select="result_unit"/>
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

</xsl:stylesheet>
