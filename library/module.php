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
// Module management
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("module.php - OLIVCore not present");
defined('OLIVERROR') or die ("module.php - OLIVError not present");

$_MODULES;

//------------------------------------------------------------------------------
// class for managing modules

class OLIVModule
{
  
  public function __construct()
  {
		global $_MODULES;

		$_MODULES = array();
;
    // load module metadata
    $this->scan(OLIV_MODULE_PATH);
  }
  

  // load template
  // header ... module header information
  // [name] ... name of special template
  public static function load_template($header,$name="")
  {
//    print_r((string)$header->attributes()->template);
    // if no name parameter, look for template attribute
    if (!$name)
    {
      if (!($name = (string)$header->attributes()->template)) // special template defined
        $name = OLIV_TEMPLATE; // use template name
    }
    
    $path = OLIV_MODULE_PATH . (string)$header->attributes()->mod . "/" . $header->script->template . OLIV_TEMPLATE . "/";

    if (!olivfile_exists($path . $name . ".xml"))
      $path = OLIV_MODULE_PATH . (string)$header->attributes()->mod . "/" . $header->script->template . "default/"; // if no template use default

    // load template and link css
    return (OLIVTemplate::load($path,$name));
  }


  // get module by name
  public function getModuleByName($name)
  {
		global $_MODULES;

    if (is_array($_MODULES))
    {
      foreach ($_MODULES as $entry)
      {
        if ((string)$entry->name == $name) return ($entry);
      }
    }
    else
      OLIVError::fire("module '{$name}' not found");
  }
  
  
	// get image path
	public function getImagePath($name)
	{
		global $_MODULES;

    if ($_MODULES[$name])
    {
			$path = OLIV_MODULE_PATH . $name . "/" . $_MODULES[$name]->script->template . OLIV_TEMPLATE . "/images/";
	
		if ($path) // image found in template
				return $path;
			else
			{
				$path = OLIV_MODULE_PATH . $name . "/" . $_MODULES[$name]->script->template . "default/images/";
				if ($path) // image found in default template
					return $path;
			}
    }
    else
      OLIVError::fire("module '{$name}' not found");
  }


//------------------------------------------------------------------------------
// scan module directory and load module metadata
  private function scan($path)
  {
		global $_MODULES;

		$_MODULES = array();
    
    if ($modDir = olivopendir ($path))
    {
  
      while ($file = readdir($modDir))
      {
        if (olivis_dir($path . $file) and $file != "." and $file != "..")
        {
          $file .= "/";
          // get define.xml
          if (olivfile_exists($path . $file))
          {
            $xml = olivxml_load_file($path . $file . "define.xml");
            $_MODULES[(string)$xml->name] = $xml;
          }
        }
      }
      closedir ($modDir);
    }
    else
      OLIVError::fire("module::scan - directory $path not found");
  }
}
?>
