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

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

	<xsl:param name="lang"/>


	<xsl:template match='/'>
		<div id='oliv_site' background-image='oliv_leaves'>
		  <div id='oliv_page'>
		    <div id='oliv_space' />

				<div id='oliv_header'>
<h1>Header</h1>
			    <xsl:apply-templates select='oliv_header'/>

		      <div id='oliv_login' />
		      <div id='oliv_title' />
		    </div>
		
		    <div id='oliv_space' />

		    <div id='oliv_top'>
		      <div id='oliv_search' />
		    </div>
		    
		    <div id='oliv_space' />
		  
		    <div id='oliv_main'>
		      <div id='oliv_left'>
<h1>Left</h1>
		        <div id='oliv_left1' />
		        <div id='oliv_left2' />
		        <div id='oliv_left3' />
		      </div>

		      <div id='oliv_center'>
		      	<div id="oliv_content">
<h1>Content</h1>
			        <xsl:apply-templates select='oliv_content'/>
			      </div>
		      </div>

		    </div>
		    <div id='oliv_footer' />
		  </div>

		  <div style="clear:both;"/>
		</div>
	</xsl:template>

</xsl:stylesheet>
