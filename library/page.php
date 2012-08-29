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


class OLIVPage
{
  private $structure;

  // constructor
  public function __construct()
  {
    $this->structure = new simpleXmlElement("<page></page>"); // create empty page
  }
  
  
//------------------------------------------------------------------------------
// load xml page
  public function load($pageName = "")
  {

    if (!$pageName)
    {
      $url = status::url();

      if (!$url)
			{
				$url = system::OLIV_INDEX_PAGE(); // set index_page if no arguments
			}
      $pageName = strtolower($url); // set to lowercase
    }

// load content xml
		$path = system::OLIV_PAGE_PATH() . $pageName . "/" . $pageName . ".xml";

		if (sessionfile_exists($path))
		{
			$xml = sessionxml_load_file($path);
			$masterPage = $xml->masterpage;

	// combine page parts
			olivxml_insert($this->structure,$xml);
//echoall($this->structure);

	// load content text
			$langPath = system::OLIV_PAGE_PATH() . $pageName . "/language/";
			$langFile = $pageName;

			OLIVText::load($langPath,$langFile);


	// load masterpage recursive
			if ($masterPage and ($pageName != $masterPage)) // not link to itself
			{
				$this->load($masterPage);
			}
			return (TRUE);
		}
		else
			OLIVError::fire("page::load - page not found");
			return (FALSE);
  }


//------------------------------------------------------------------------------
// set module value in page
  public function setScript($id,$module)
  {
// insert attributes into page definition
		foreach($module->children() as $entry)
		{
			$this->structure->$id->addAttribute($entry->getName(),(string)$entry);
		}
  }


//------------------------------------------------------------------------------
// return page structure xml
  public function structure()
  {
    return ($this->structure);
  }
}
