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

// status parameter found
		$status = (string)$header->param->status;
		
		if ($articleParam = status::$status())
		{
//TODO retranslate article name
			$header->param->content = $articleParam;			
		}


// load content
		$article = OLIVModule::load_content($header);

		
// check if content exist
// set article and content to "error_no_article" article
		if (!$article)
		{
			$article = OLIVModule::load_content($header,"error_no_article");
			$article->content->articlename = (string)$header->param->content;
//			$article->argv->source = "sourcePath";
		}


// create edit frame
		$editStruct = new simpleXmlElement("<structure><article id='article_edit'/></structure>");
		olivxml_insert($editStruct->article,$article->structure);


// set article
		$this->content = $article;


//TODO insert edit frame if toolbox = article_edit
// set article for editing
		if (argv::toolbox() == "article_edit")
		{
			unset($this->content->structure);
			olivxml_insert($this->content,$editStruct,"ALL");
		}


// combine article
		$this->create();


// check activities
		$this->activities($header,$this->content);


// add source and textname attribute recursive
		if ($article)
		{
// get article languages
			$langs = OLIVText::getLanguages($article);

			olivxml_insert($article,OLIVLang::selector($langs),"ALL");

// load central article template
//			$this->template = OLIVModule::load_template($header,"default");
			$this->template = OLIVModule::load_template($header);
		}
  }


//------------------------------------------------------------------------------
// create article
	private function create()
	{
		$structure = $this->content->structure;
		$content = $this->content->content;

		if ($content)
		{
			foreach ($content->children() as $entry)
			{
				$name = $entry->getName();


// insert translated text in attibutes
// text tag with leading $
				$tempName = "$" . $name;
				$nodes = $structure->XPath("//*[@* = '{$tempName}']");

				foreach ($nodes as $node)
				{
					$text = OLIVText::xml($entry);

					foreach ($node->attributes() as $key => $value)
					{
						if ((string)$value == $tempName)
							$node[$key] = $text;
					}
				}


// insert content at name attribute
				$nodes = $structure->XPath("//*[@name = '$name']");

				foreach ($nodes as $node)
				{
// insert text into node
					olivxml_insert($node,$entry);
				}
			}
		}

//echoall($this->content->structure);
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
