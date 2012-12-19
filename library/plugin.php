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

if (!system::OLIVCORE()) die ("plugin.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("plugin.php - OLIVError not present");

$_PLUGIN;

class OLIVPlugin
{
  public function __construct()
  {
    $this->scan(system::OLIV_PLUGIN_PATH());
  }



//------------------------------------------------------------------------------
// call plugin
// $func ... function name (in render plugin normally the tag name)
// $type ... type of plugin: render, search, etc.

// $options ... array of options for plugin call
  static public function call($page)
  {
    global $_PLUGIN;

// get plugin methods
		$methods = $_PLUGIN->XPath("//method");


// loop over plugins
		foreach($methods as $plugin)
		{
// load plugin definition
			$class = $plugin->XPath("../class");
			if (count($class))
				$class = (string)array_pop($class);
			else
				$class = "";

			$editclass = $plugin->XPath("../editclass");
			if (count($editclass))
				$editclass = (string)array_pop($editclass);
			else
				$editclass = "";


// loop over plugin methods
			foreach($plugin as $method)
			{
				$name = $method->getName();
				$classMethod = (string)$method;

		// load plugin script
			  OLIVCore::loadScript($class . ".php",system::OLIV_PLUGIN_PATH() . $class . "/");

//------------------------------------------------------------------------------
// permission to display
//TODO use class or editclass, depending on rights


// call plugin
				if (class_exists($class))
				{
					$class::$classMethod($page,$name);
				}
				else
					OLIVError::fire("postprocessor.php::process - class $class not found");
			}
		}
//------------------------------------------------------------------------------
		return $page;
  }

  
//------------------------------------------------------------------------------
// scan plugin directory and load plugin metadata
  static private function scan($path)
  {
    global $_PLUGIN;

//    $_PLUGIN = new simpleXmlElement("<plugin></plugin>");
    $_PLUGIN = new simpleXmlElement("<plugins></plugins>");

    if ($pluginDir = olivopendir ($path))
    {
    	$cnt = 0;
      while ($file = readdir($pluginDir))
      {
        if (olivis_dir($path . $file) and $file != "." and $file != "..")
        {
          $file .= "/";

      // get define.xml
          if (olivfile_exists($path . $file . "define.xslt"))
          {
            $xml = olivxml_load_file($path . $file . "define.xslt");

//TODO include plugin script
            // get type of plugin
						olivxml_insert($_PLUGIN,$xml,"ALL");
          }
        }
      }

      closedir ($pluginDir);
      return ($cnt);
    }
    else
      OLIVError::fire("plugin::scan - directory $path not found");
			return (FALSE);
  }
  


}

?>
