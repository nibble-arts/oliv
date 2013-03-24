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


//------------------------------------------------------------------------------
// class for managing modules

class OLIVModule
{
	var $template;
	var $content;
  
  public function __construct()
  {
// load module metadata
    $this->scan(system::OLIV_MODULE_PATH());
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
// load module template path
// header ... module header information
// [name] ... name of special template
  public static function load_template($header,$name = "")
  {
  	if (!$name)
  	{
			// look for template attribute
		  if (!($name = $header->param->template)) // special template defined
				$name = "default";
		}

    $path = system::OLIV_MODULE_PATH() . $header->name . "/template/" . system::OLIV_TEMPLATE() . "/";

// if no template use default
    if (!olivfile_exists($path . $name . ".xslt"))
      $path = system::OLIV_MODULE_PATH() . $header->name . "/template/default/";

// load template and link css
    return ($path . $name);
  }


//------------------------------------------------------------------------------
// load module content file
	public static function load_content($header,$contentName="")
	{
		$contentPath = (string)$header->content;
		$moduleName = (string)$header->name;

		if (!$contentName)
			$contentName = (string)$header->param->content;

// if content path but no content defined
// use module_name.xml for content 
		if (!$contentName)
			$contentName = "default";

		$contentName .=  ".xml";
  	if ($contentPath and $contentName)
  	{
	  	return OLIVModule::load_xml($header,$contentPath,$contentName);
  	}
	}


//------------------------------------------------------------------------------
// load module system content file
// centrally stored in the module root directory
	public static function load_system_content($header,$contentName = "")
	{
		
	}	


//------------------------------------------------------------------------------
// load module javascript file
// if no scriptName is defined use the contentName od default.js
	public static function load_javascript($header,$scriptName = "")
	{

		$scriptPath = (string)$header->javascript;

		if ($scriptPath)
		{
			if (!$scriptName)
				$scriptName = (string)$header->param->javascript;

			$contentName = (string)$header->param->content;
			$moduleName = (string)$header->name;


	// use scriptName.js
			if ($scriptName)
				OLIVModule::load_js($header,$scriptPath,$scriptName . ".js");

	// use contentName.js
			elseif ($contentName)
				OLIVModule::load_js($header,$scriptPath,$contentName . ".js");
		
	// use moduleName.js or default.js
			else
			{
				if (!OLIVModule::load_js($header,$scriptPath,$moduleName . ".js"))
					OLIVModule::load_js($header,$scriptPath,"default.js");
			}
		}
	}


//------------------------------------------------------------------------------
// try to include javascript file
// returns true if linked
// returns false if not found 
	public static function load_js($header,$path,$name)
	{
		$filePath = system::OLIV_MODULE_PATH() . (string)$header->name . "/" . $path;


// load file
		if (OLIVCore::loadScript($name,$filePath,session_path("")))
			return TRUE;

		return FALSE;
	}


//------------------------------------------------------------------------------
// load file from module
// path ... path inside the module directory
// name ... name of the file with extension
// root ... = "" => use session dir as root
//					= "system" => use module system root
//
// inserts source attribute to all text nodes for editing
	public static function load_xml($header,$path,$name,$root = "")
	{
		$filePath = system::OLIV_MODULE_PATH();

			
// use module name from header
		if ($header)
			$filePath .= (string)$header->name . "/";

		$filePath .= $path;

//TODO
// load system file
		if (strtolower($root) == "system")
		{
			if (olivfile_exists($filePath . $name))
			{
		  	return olivxml_load_file($filePath . $name);
			}
		}
		
		
// load session file
		else
		{
		  if (sessionfile_exists($filePath . $name))
		  {
		  	$xml = sessionxml_load_file($filePath . $name);


// if access defined and write access -> enable editing
				if ($xml->access->getName())
				{

// read access -> display
					if (!OLIVRight::r($xml->access))
					{
						$xmlName = $xml->getName();
						return (new simpleXmlElement("<$xmlName/>"));
					}

// write access -> insert source path
					elseif (OLIVRight::w($xml->access))
					{
						OLIVText::writeSource($xml,$filePath . $name);
					}
				}
//TODO write imagesource
//			OLIVImage::writeSource($xml,$filePath);
				return $xml;
			}
			else
				OLIVError::warning("module.php::load_xml - $root$filePath$name not found");
		}
	}

	
//------------------------------------------------------------------------------
// get module by name
  public static function getModuleByName($name)
  {
		$modules = system::modules();

    if (is_array($modules))
    {
      foreach ($modules as $entry)
      {
        if ((string)$entry->name == $name) return (new simpleXmlElement($entry->asXML()));
      }
    }
    else
      OLIVError::fire("module '{$name}' not found");
  }
  

//------------------------------------------------------------------------------
// get content path
	static public function getContentPath($name)
	{
		$modules = system::modules();

    if ($modules[$name])
    {
			return system::OLIV_MODULE_PATH() . $name . "/content/";
	
    }
    else
      OLIVError::fire("module '{$name}' not found");
  }

  
//------------------------------------------------------------------------------
// get image path
	static public function getImagePath($name)
	{
		$modules = system::modules();

    if ($modules[$name])
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
// parse string of parameters and return array
// format [name]:[value];...
	public static function parse_param_string($string)
	{
		$retArray = array();
	
		$paramArray = explode(";",$string);
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
		
		if (count($retArray))
			return $retArray;
		else
			return FALSE;
	}


//------------------------------------------------------------------------------
// parse header parameter sting
// format [name]:[value];...
// return assoziative array or single value
	public static function parse_param($header)
	{
		return OLIVModule::parse_param_string($header->param);
	}


//------------------------------------------------------------------------------
// get the content xml definition
	public static function getContentDefine($mod,$name)
	{
		if ($mod and $name)
		{
			$modules = system::modules();
			$header = $modules[$mod];

// module header found
			if ($header)
			{
				$content = OLIVModule::load_content($header,$name);

				if ($content)
					return $content->define;
			}
		}
	}
	

//------------------------------------------------------------------------------
// get the name of the content xml
	public static function getContentName($mod,$name)
	{
		$modDefine = OLIVModule::getContentDefine($mod,$name);

		if ($modDefine)
			return $modDefine->name;
	}


//------------------------------------------------------------------------------
// get the friendly_name of the content xml
	public static function getContentFriendlyName($mod,$name)
	{
		$modDefine = OLIVModule::getContentDefine($mod,$name);

		if ($modDefine)
			return $modDefine->friendly_name;
	}


//------------------------------------------------------------------------------
// get the name of the content xml
	public static function getContentTitle($mod,$name)
	{
		$modDefine = OLIVModule::getContentDefine($mod,$name);

		if ($modDefine)
			return $modDefine->title;
	}


//------------------------------------------------------------------------------
// scan module directory and load module metadata
  private function scan($path)
  {
		$modules = array();
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

//------------------------------------------------------------------------------
// check for session directory
						$sessionDir = system::oliv_module_path() . $filePath;

						if (sessionfile_exists($sessionDir))
						{
							$contentPath = $xml->content;
							$templatePath = $xml->template;

// write directory permissions to module header
							$xml->content['permission'] = get_permission(session_path($sessionDir) . $contentPath);
							$xml->template['permission'] = get_permission(session_path($sessionDir) . $templatePath);
						}
						else
						{
// session directory don't exist
							$xml->status = "NO_SESSION_DIR";
							$xml->permission = 0;
						}


//------------------------------------------------------------------------------
// save module metadata
            $modules[(string)$xml->name] = $xml;
						
            $cnt++;
          }
        }
      }
      closedir ($modDir);

			system::set("modules",$modules);
      return ($cnt);
    }
    else
      OLIVError::fire("module::scan - directory $path not found");
      return (FALSE);
  }
}
?>
