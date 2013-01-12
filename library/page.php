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
// Page content class
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("page.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("page.php - OLIVError not present");


class OLIVPage
{
  private $structure;
  private $template;

  // constructor
  public function __construct()
  {
  }
  
  
//------------------------------------------------------------------------------
// load xml page and store in structure
  public function load($pageName = "")
  {
  	$templateName = "";
  	
		$xml = OLIVPage::_load($pageName);
		$this->structure = $xml->content;


// look for template node
		$nodes = $this->structure->XPath("//template");
		if ($nodes)
			$templateName = (string)$nodes[0];

		if (!$templateName)
			$templateName = "default";

// store template name
		$this->template = $templateName;
  }


//------------------------------------------------------------------------------
// return template name
	public function template()
	{
		return $this->template;
	}
	

//------------------------------------------------------------------------------
// load and return page xml file
// call plugins
	public static function _load($pageName)
	{
  	$xml = "";
  	
    if (!$pageName)
    {
      $url = status::url();

// if no url defined, user OLIV_INDEX_PAGE
      if (!$url)
			{
				$url = system::OLIV_INDEX_PAGE();
			}
      $pageName = strtolower($url); // set to lowercase
    }


// create content path
		$path = system::OLIV_PAGE_PATH() . $pageName . "/" . $pageName . ".xml";

		if (sessionfile_exists($path))
		{
// load content xml
			$xml = sessionxml_load_file($path);

//------------------------------------------------------------------------------
// search for plugins and call methods

			if ($xml->content->include)
				OLIVPlugin::call($xml->content);

//------------------------------------------------------------------------------

			return ($xml);
		}
		else
			OLIVError::fire("page::load - page not found");
			return (FALSE);
	}
	

//------------------------------------------------------------------------------
// return page structure xml
  public function structure()
  {
    return ($this->structure);
  }


//------------------------------------------------------------------------------
// insert xml in page structure
	public function insert($xml)
	{
		olivxml_insert($this->structure,$xml,"ALL");
	}


//------------------------------------------------------------------------------
// clear content of node in page structure
	public function clear($nodeName)
	{
		$this->structure->$nodeName = "";
	}
}
