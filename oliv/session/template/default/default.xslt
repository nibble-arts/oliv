<?xml version="1.0" encoding="UTF-8"?>

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


// IG-CMS XSL template definition
//
// Version 0.1 -->

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

  <xsl:output method="xml" encoding="UTF-8" indent="yes"/>
	<xsl:strip-space elements="*" />

	<xsl:param name="lang"/>


	<xsl:template match='/'>
		<page>
			<div id="oliv_toolbox">
				<xsl:apply-templates select="//oliv_toolbox"/>
			</div>

			<div id='oliv_site' background-image='oliv_leaves'>
				<div id='oliv_page'>

					<div id='oliv_header'>
				    <div id='oliv_login'>
						  <xsl:apply-templates select='//oliv_login'/>
						</div>
					  <xsl:apply-templates select='//oliv_header'/>

						<span class="oliv_clear"/>
				  </div>
		
			    <div id='oliv_search'>
					  <xsl:apply-templates select='//oliv_search'/>
				  </div>
				  
				  <span id='oliv_main'>
				  	<table width="100%"><tr>
				  		<td class="oliv_left">
								<div id='oliv_left'>
								  <div id='oliv_left1'>
								  	<xsl:apply-templates select='//oliv_left1'/>
									</div>
								  <div id='oliv_left2'>
								  	<xsl:apply-templates select='//oliv_left2'/>
									</div>
								  <div id='oliv_left3'>
								  	<xsl:apply-templates select='//oliv_left3'/>
									</div>
								</div>
								<span class="oliv_clear"/>
							</td>
							<td class='oliv_content'>
								<div id="oliv_content">
								  <xsl:apply-templates select='//oliv_content'/>
								</div>

								<span class="oliv_clear"/>
							</td>
						</tr></table>
				  </span>
				  <div id='oliv_footer'>
			      <xsl:apply-templates select='//oliv_footer'/>
					</div>
				</div>
			</div>
		</page>
	</xsl:template>


<!-- paragraph template -->
	<xsl:template name="article_paragraph">
		<xsl:param name="node"/>

		<xsl:if test="$node/@source">
			<xsl:attribute name="textsource">
				<xsl:value-of select="$node/@source"/>
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
		</xsl:if>

		<xsl:value-of select="$node"/>
	</xsl:template>

</xsl:stylesheet>

