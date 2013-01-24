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
// Preprocessor object
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("plugin::textPlugin.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("plugin::textPlugin.php - OLIVError not present");


class textPlugin
{
  var $editor;
  
//------------------------------------------------------------------------------
// render class
// options: [0] ... page content
//					[1] ... method name
//					[2] ... plugin type
  static public function __callStatic($method,$options)
  {
  	$content = $options[0];
  	$tag = $options[1];
		$type = $options[2];

		switch ($type)
		{
// call text search
			case 'search':
// merge result with status::set()
				$searchString = argv::search();
				$result = textPlugin::search();

				if ($result)
				{
// get translation of pages
					$pages = $result->XPath("./result");


// insert text snippets with highlighted searchString
					foreach ($pages as $page)
					{
						$moduleName = (string)$page->type;
						$name = (string)$page->name;
						$value = (string)$page->value;

						$article = OLIVModule::load_xml("",$moduleName . "/" . "content/",$name . ".xml");

						$texts = $article->XPath("//text");

						foreach ($texts as $text)
						{
// highlight seachstring
							if ($highlighted = OLIVText::highlight((string)$text,$searchString,"highlight",25))
							{
								$page->summary = $highlighted;
	// add language
								$page->lang = (string)$text['lang'];
							}
						}

// insert page name
						olivxml_insert($page->pagename,OLIVRoute::getName((string)$page->page));
					}

					olivxml_insert($content,$result);
				}
				break;

// call renderer
			case 'render':
				textPlugin::render($content);
				break;
		}
  }


//------------------------------------------------------------------------------
// run search
	private static function search()
	{
		$result = OLIVIndex::search(argv::search());

		if ($result)
			return $result;
	}


//------------------------------------------------------------------------------
// run rendering
	private static function render($content)
	{
// edit mode if edit mode and permission
		if (argv::toolbox() == "article_edit")
		{
			$nodes = $content->XPath("//*[@textsource]");

//echoall($nodes);
			for ($i = 0;$i < count($nodes);$i++)
			{
// if source, make edit possible
				$tag = $nodes[$i]->getName();
				$text = (string)$nodes[$i];
				$source = (string)$nodes[$i]->attributes()->textsource;

//TODO include javaScript content menu
// include editor call <a>

				$nodes[$i][0] = "";
				$nodes[$i] = $nodes[$i][0]->addChild("a",$text);

				$nodes[$i]["title"] = "call editor for '$source'";
			}
		}

		return ($content);
	}
}
?>
