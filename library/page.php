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

defined('OLIVCORE') or die ("render.php - OLIVCore not present");

$_PAGES;

class OLIVPage extends OLIVCore
{
  private $structure;
 

  // constructor
  public function __construct()
  {
    $this->scan(OLIV_LANG);
    $this->structure = new simpleXmlElement("<page></page>"); // create empty page
  }
  
  
//------------------------------------------------------------------------------
// load xml page
  public function load($pageName = "")
  {
    if (!$pageName)
    {
      $arg = $this->argv();
      $url = $arg[url];

      if (!$url)
			{
				$url = OLIV_INDEX_PAGE; // set index_page if no arguments
			}
      $pageName = strtolower($url); // set to lowercase
    }

// load content xml
		$path = OLIV_PAGE_PATH . $pageName . "/" . $pageName . ".xml";

		if (sessionfile_exists($path))
		{
			$xml = sessionxml_load_file($path);
			$masterPage = $xml->masterpage;

	// combine page parts
			olivxml_insert($this->structure,$xml);


	// load content text
			$langPath = OLIV_PAGE_PATH . $pageName . "/language/";
			$langFile = $pageName;

			OLIVText::load($langPath,$langFile);


	// load masterpage recursive
			if ($masterPage and ($pageName != $masterPage)) // not link to itself
			{
				$this->load($masterPage);
			}
		}
		else
			OLIVError::fire("page::load - page not found");
  }


// set module value in page
  public function setScript($id,$module)
  {
    $this->structure->$id->addChild("script","");
    olivxml_insert($this->structure->$id->script,$module);
  }


// return page structure xml
  public function structure()
  {
    return ($this->structure);
  }


//------------------------------------------------------------------------------
// get list of existing pages
  public function scan($lang)
  {
		global $_PAGES;

		$_PAGES = array();

    $path = OLIV_PAGE_PATH;
    if ($pageDir = sessionopendir ($path))
    {

      while ($file = readdir($pageDir))
      {
        if (sessionis_dir($path . $file) and $file != "." and $file != "..")
        {
          // get define.xml
          if (sessionfile_exists($path . $file . "/$file.xml"))
          {
            $xml = sessionxml_load_file($path . $file . "/$file.xml");
            $_PAGES[$file] = $xml;
          }
        }
      }
      closedir ($pageDir);
    }
    else
      OLIVError::fire("page::scan - directory $path not found");
  }
}
