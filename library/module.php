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

if (!system::OLIVCORE()) die ("module.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("module.php - OLIVError not present");


$_MODULES;

//------------------------------------------------------------------------------
// class for managing modules

class OLIVModule
{
  
  public function __construct()
  {
		global $_MODULES;

		$_MODULES = array();

    // load module metadata
    $this->scan(system::OLIV_MODULE_PATH());
//    echoall($_MODULES);
  }
  

//------------------------------------------------------------------------------
// load template
// header ... module header information
// [name] ... name of special template
  public static function load_template($header,$name="")
  {
		// if no name parameter, look for template attribute
    if (!$name)
    {
      if (!($name = (string)$header->attributes()->template)) // special template defined
        $name = system::OLIV_TEMPLATE(); // use template name
    }

    $path = system::OLIV_MODULE_PATH() . (string)$header->attributes()->mod . "/template/" . system::OLIV_TEMPLATE() . "/";

    if (!olivfile_exists($path . $name . ".xml"))
      $path = system::OLIV_MODULE_PATH() . (string)$header->attributes()->mod . "/template/default/"; // if no template use default

    // load template and link css
    return (OLIVTemplate::load($path,$name));
  }


//------------------------------------------------------------------------------
// load file from module session
// path ... path inside the module session directory
// name ... name of the file with extension
	public static function load_xml($header,$path,$name)
	{
		$filePath = system::OLIV_MODULE_PATH() . (string)$header->attributes()->mod . "/";
		$filePath .= $path . $name;

// load file
    if (sessionfile_exists($filePath))
    {
			return sessionxml_load_file($filePath);
		}
		else
			OLIVError::fire("module.php::load_xml - $filePath not found");
	}

	
//------------------------------------------------------------------------------
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
  
  
//------------------------------------------------------------------------------
// get image path
	static public function getImagePath($name)
	{
		global $_MODULES;

    if ($_MODULES[$name])
    {
			$path = system::OLIV_MODULE_PATH() . $name . "/template/" . system::OLIV_TEMPLATE() . "/images/";
	
		if ($path) // image found in template
				return $path;
			else
			{
				$path = system::OLIV_MODULE_PATH() . $name . "/template/default/images/";
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
		$cnt = 0;
    
    if ($modDir = olivopendir ($path))
    {
  
      while ($file = readdir($modDir))
      {
        if (olivis_dir($path . $file) and $file != "." and $file != "..")
        {
          $filePath = "$file/";
// get modules define.xml
          if (olivfile_exists($path . $filePath))
          {
// load module information
            $xml = olivxml_load_file($path . $filePath . "define.xml");


// insert title and description in metadata
						if (!($modText = OLIVText::_load("",$path . $filePath . "language/","_define")))
							OLIVError::fire("module.php::scan - no language definition for module $file found");
						else
						{
							$summary = $modText['MOD_' . strtoupper($file)]['MOD_SUMMARY']['text'];
							$description = $modText['MOD_' . strtoupper($file)]['MOD_DESCRIPTION']['text'];

							$xml->addChild("summary",$summary);
							$xml->addChild("description",$description);
						}


// save module metadata
            $_MODULES[(string)$xml->name] = $xml;
						
            $cnt++;
          }
        }
      }
      closedir ($modDir);
      return ($cnt);
    }
    else
      OLIVError::fire("module::scan - directory $path not found");
      return (FALSE);
  }
}
?>
