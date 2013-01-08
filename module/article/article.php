<?PHP
//
// OLIV
//
// This file is part of the OLIV Content Management System.
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
//

//------------------------------------------------------------------------------
// Article module
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("mod_article::article.php - OLIVCore not present");
if (!system::OLIVTEXT()) die ("mod_article::article.php - OLIVText not present");
if (!system::OLIVERROR()) die ("mod_article::article.php - OLIVError not present");


$_ARTICLES;


//------------------------------------------------------------------------------
class article extends OLIVModule
{
  public function __construct($header)
  {
    global $_argv;

		$articleName = (string)$header->param->content;

// load content
		$article = OLIVModule::load_content($header);


// add source and textname attribute recursive
		if ($article)
		{
// set source to all article paragraphs
// moved to module xml_load
/*			foreach($article as $entry)
			{
				$entry["source"] = $header->path . "content/$articleName";
			}*/

// set param in header to articleName and load template path
			$this->template = OLIVModule::load_template($header);
			$this->content = $article;
		}
	  else
	    $this->o .= OLIVError::renderError("article.php - content for <b>'$articleName'</b> not found");
  }
}

?>
