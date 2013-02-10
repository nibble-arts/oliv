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



//------------------------------------------------------------------------------
class article extends OLIVModule
{
  public function __construct($header)
  {
    global $_argv;



// load content
		$article = OLIVModule::load_content($header);


// check if content exist
// set article and content to "error_no_article" article
		if (!$article)
		{
			$article = OLIVModule::load_content($header,"error_no_article");
			$article->param->articlename = (string)$header->param->content;
		}
		
// set article
		$this->content = $article;


// check activities
		$this->activities($header,$this->content);


// add source and textname attribute recursive
		if ($article)
		{
// get article languages
			$langs = OLIVText::getLanguages($article);

			olivxml_insert($article,OLIVLang::selector($langs),"ALL");

// load central article template
			$this->template = OLIVModule::load_template($header,"default");
		}
  }


//------------------------------------------------------------------------------
  private function activities($header,$article)
  {
// create index file
		switch (argv::action())
		{
			case "create_index":

				$articleName = (string)$header->param->content;
				$index = new OLIVIndex();
				$textNodes = $article->XPath("//text");

				foreach ($textNodes as $text)
				{
					$index->insertText((string)$text,"article:$articleName",(string)$text['lang']);
				}
				break;

			case "delete_index":
				argv::remove("action");
				break;
		}

  }
}

?>
