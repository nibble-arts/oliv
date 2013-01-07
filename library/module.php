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
	var $template;
	var $content;
  
  public function __construct()
  {
		global $_MODULES;

		$_MODULES = array();

    // load module metadata
    $this->scan(system::OLIV_MODULE_PATH());
//    echoall($_MODULES);
  }
  

//------------------------------------------------------------------------------
// return template path
  public function template()
  {
  	return ($this->template);
  }

//------------------------------------------------------------------------------
// return content xml
  public function content()
  {
  	return ($this->content);
  }


//------------------------------------------------------------------------------
// use content node as text
// write to xml node with name
	public function text($node,$name)
	{
		olivxml_insert($this->content->$node,$this->content->$name);
	}


//------------------------------------------------------------------------------
// load template
// header ... module header information
// [name] ... name of special template
  public static function load_template($header,$name = "")
  {
  	if (!$name)
  	{
			// look for template attribute
		  if (!($name = $header->param->template)) // special template defined
		    $name = system::OLIV_TEMPLATE(); // use template name
		}

    $path = system::OLIV_MODULE_PATH() . $header->name . "/template/" . system::OLIV_TEMPLATE() . "/";

// if no template use default
    if (!olivfile_exists($path . $name . ".xslt"))
      $path = system::OLIV_MODULE_PATH() . $header->name . "/template/default/";

    // load template and link css
    return ($path . $name);
  }


//------------------------------------------------------------------------------
	public static function load_content($header)
	{
		$contentPath = (string)$header->content;
		$contentName = (string)$header->param->content;
		$moduleName = (string)$header->name;

// if content path but no content defined
// use module_name.xml for content 
		if (!$contentName)
			$contentName = $moduleName;

		$contentName .=  ".xml";

  	if ($contentPath and $contentName)
  	{
	  	return OLIVModule::load_xml($header,$contentPath,$contentName);
  	}
	}
	

//------------------------------------------------------------------------------
// load file from module session
// path ... path inside the module session directory
// name ... name of the file with extension
	public static function load_xml($header,$path,$name)
	{
		$filePath = system::OLIV_MODULE_PATH() . (string)$header->name . "/";
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
  public static function getModuleByName($name)
  {
		global $_MODULES;

    if (is_array($_MODULES))
    {
      foreach ($_MODULES as $entry)
      {
        if ((string)$entry->name == $name) return (new simpleXmlElement($entry->asXML()));
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
// parse parameter sting
// format [name]:[value];...
// return assoziative array or single value
	public static function parse_param($header)
	{
		$retArray = array();
	
		$paramArray = explode(";",$header->param);
		foreach($paramArray as $entry)
		{
			$valueArray = explode(":",$entry);

	// enter name:value pair
			if (count($valueArray) > 1)
			{
				if ($valueArray[1])
					$retArray[$valueArray[0]] = $valueArray[1];
			}
		}
		return $retArray;
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


// insert module title and description in metadata
/*						if (!($modText = OLIVText::_load($path . $filePath . "language/","_define.xml")))
							OLIVError::fire("module.php::scan - no language definition for module $file found");
						else
						{
							$summary = new simpleXmlElement("<MOD_" . strtoupper($file) . ">" . $modText->MOD_SUMMARY->asXML() . "</MOD_" . strtoupper($file) . ">");
							$description = new simpleXmlElement("<MOD_" . strtoupper($file) . ">" . $modText->MOD_DESCRIPTION->asXML() . "</MOD_" . strtoupper($file) . ">");

							olivxml_insert($xml->summary,$summary);
							olivxml_insert($xml->description,$description);
						}*/


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
