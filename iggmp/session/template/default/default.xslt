<?xml version="1.0" encoding="UTF-8" standalone="yes"?>

<!-- IG-CMS
//
// This file is part of the IG Content Management System.
//
// Copyright(c) 2012 Thomas H Winkler
// thomas.winkler@iggmp.net
//
// This file may be licensed under the terms of of the
// GNU General Public License Version 3 (the ``GPL'').
//
// Software distributed under the License is distributed
// on an ``AS IS'' basis, WITHOUT WARRANTY OF ANY KIND, either
// express or implied. See the GPL for the specific language
// governing rights and limitations.
//
// You should have received a copy of the GPL along with this
// program. If not, go to http://www.gnu.org/licenses/gpl.html
// or write to the Free Software Foundation, Inc.,
// 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.


// IG-CMS template definition
//
// Version 0.1 -->

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

  <xsl:output method="xml" encoding="UTF-8" indent="yes"/>
	<xsl:strip-space elements="*" />

	<xsl:param name="lang"/>


	<xsl:template match='/'>

		<div id='oliv_site'>
			<div id="oliv_login">
			  <xsl:apply-templates select='//oliv_login'/>
			</div>	

			<div id="oliv_toolbox">
				<xsl:apply-templates select="//oliv_toolbox"/>
			</div>

		  <div id='oliv_page'>
		    <div id='oliv_main' background-image='locoback.png'>
				  <div id='oliv_header'>
					  <xsl:apply-templates select='//oliv_header'/>
					</div>

		    	<table><tr>
						<td id="oliv_table_left">
						  <div id='oliv_left'>
								<xsl:apply-templates select='//oliv_left'/>
						  </div>
						</td>
						
						<td id="oliv_table_right">
						  <div id='oliv_center'>
								<div id="oliv_content">
									<xsl:apply-templates select='//oliv_content'/>
								</div>
						  </div>
						</td>
					</tr></table>

		    </div>
		    <div id='oliv_footer'>
	        <xsl:apply-templates select='//oliv_footer'/>
			  </div>

				<span style="clear:both;display:block;"/>
		  </div>

		</div>
	</xsl:template>


<!-- paragraph template -->
	<xsl:template name="article_paragraph">
		<xsl:param name="node"/>

		<xsl:if test="$node/@source">
			<xsl:attribute name="textsource">
				<xsl:value-of select="$node/@source"/>
			</xsl:attribute>
		</xsl:if>

		<xsl:attribute name="articlename">
			<xsl:value-of select="name($node)"/>
		</xsl:attribute>
		<xsl:attribute name="owner">
			<xsl:value-of select="$node/@owner"/>
		</xsl:attribute>
		<xsl:attribute name="group">
			<xsl:value-of select="$node/@group"/>
		</xsl:attribute>
		<xsl:attribute name="access">
			<xsl:value-of select="$node/@access"/>
		</xsl:attribute>

		<xsl:value-of select="$node"/>
	</xsl:template>

</xsl:stylesheet>

