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
  static public function call($page,$type)
  {
    global $_PLUGIN;

//echoall($_PLUGIN);
/*		if (is_object($page))
		{
			foreach($_PLUGIN as $entry)
			{
				$tempXsl = new XSLTProcessor();
				$tempXsl->importStylesheet($entry);

//echoall($page->structure());
//echoall($_PLUGIN);
				foreach ($_PLUGIN as $entry)
				{
					echo "<hr>";
//					echoall($page->structure());
					echoall($entry->children("http://www.w3.org/1999/XSL/Transform")->asXML());

					
				}
			}
		}*/
/*    if (isset($_PLUGIN->$type->func->$func))
    {
  // get render plugin if registered
      $plugin = $_PLUGIN->$type->func;

      $pluginScript = (string)$plugin->$func->attributes()->script;
      
      $pluginCall = explode("::",(string)$plugin->$func->attributes()->class);
      $pluginEditCall = explode("::",(string)$plugin->$func->attributes()->editClass);


//TODO check content edit flag

//$options['template']->attributes()->id


//------------------------------------------------------------------------------
// permission to display
			if (OLIVRight::r($options['template']))
			{

// permission to edit
// write permission && edit plugin fourn && edit mode && source founr
//				$source = (string)$options['template']->attributes()->source;
				
		    if (OLIVRight::w($options['template'],TRUE) and $pluginEditCall[0] and system::OLIV_CONTENT_EDIT()) // and $source)
		    {
		      $class = $pluginEditCall[0]; // set edit class
				}
		    else
				{
		      $class = $pluginCall[0]; // set display class
				}
		    $func = $pluginCall[1];

//------------------------------------------------------------------------------
// call render class for tag
// load plugin script
	      OLIVCore::loadScript($pluginScript . ".php",system::OLIV_PLUGIN_PATH() . $pluginScript . "/");


// call script and return output
		    if (class_exists($class))
		      return ($class::$func($options));


// error class not found
		    else
		      OLIVError::fire("OLIVPlugin::call - plugin class $class not found");
		      return (FALSE);
			}
    }
    else
      return (FALSE);*/
  }

  
//------------------------------------------------------------------------------
// scan plugin directory and load plugin metadata
  private function scan($path)
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
