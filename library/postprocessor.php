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

//echoall($page);
// call plugins
//		$pageXml = olivxml_create($page,"page");
		if ($page)
		{
			$pageXml = OLIVPlugin::call(new simpleXmlElement($page),"render");


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


//------------------------------------------------------------------------------
// run markup parser
			$pageString = $this->markup($pageString);

			return ($pageString);
		}
  }



//------------------------------------------------------------------------------
// parse markup
	static private function markup($text)
	{
		$search = array("{","}");
		$replace = array("<",">");
		
		$text = str_replace($search,$replace,$text);

		return $text;
	}
}


?>
