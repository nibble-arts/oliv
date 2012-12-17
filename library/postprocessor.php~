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
// Postprocessor object
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("processor.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("processor.php - OLIVError not present");


class OLIVPostProcessor extends OLIVCore
{
  // constructor
  public function __construct()
  {
  }
  
  
//------------------------------------------------------------------------------
// parse page
//------------------------------------------------------------------------------
  public function process($page)
  {
  	global $_PLUGIN;


// get plugin methods
		$methods = $_PLUGIN->XPath("//method");

// loop methods
		foreach($methods as $entry)
		{
			$class = $entry->XPath("../class");
			$class = (string)$class[0];

			foreach($entry as $method)
			{
				$name = $method->getName();
				$classMethod = (string)$method;

echoall("$class -> $classMethod - $name");

				$class::$classMethod($page);

			}
		}


echoall($funcs);
//echoall($_PLUGIN);
		$func = "p";

		$pageXml = new simpleXmlElement($page);
		$test = $pageXml->XPath("//$func");

		for ($i = 0;$i < count($test);$i++)
		{
			$text = (string)$test[$i][0];
			$test[$i][0] = "";
			$test[$i]->addChild("i",$text);
//echoall($test[$i]->getName());
		}

//echoall($pageXml->XPath("//p"));

//------------------------------------------------------------------------------
// convert page xml to html
		if (sessionfile_exists(system::OLIV_TEMPLATE_PATH() . "post.xslt"))
			$postStylesheet = sessionxml_load_file(system::OLIV_TEMPLATE_PATH() . "post.xslt");
		else
		{
			OLIVError::fire("postprocessor.php::process - post.xslt file not found");
			die();
		}

		$htmlProcessor = new XSLTProcessor();
		$htmlProcessor->importStylesheet($postStylesheet);
		$pageString = $htmlProcessor->transformToXML($pageXml);

//echoall($pageXml->asXML());

		return ($pageString);
  }
}


?>
