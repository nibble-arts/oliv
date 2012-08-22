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

defined('OLIVCORE') or die ("render.php - OLIVCore not present");
defined('OLIVERROR') or die ("render.php - OLIVError not present");

$_PLUGIN;

class OLIVPlugin
{
  public function __construct()
  {
    $this->scan(OLIV_PLUGIN_PATH);
  }


//------------------------------------------------------------------------------
// call plugin
// $func ... function name (in render plugin normally the tag name)
// $type ... type of plugin: render, search, etc.

// $options ... array of options for plugin call
  static public function call($func,$type,$options = array())
  {
    global $_PLUGIN;


    if (isset($_PLUGIN->$type->func->$func))
    {
  // get render plugin if registered
      $plugin = $_PLUGIN->$type->func;

      $pluginScript = (string)$plugin->$func->attributes()->script;
      
      $pluginCall = explode("::",(string)$plugin->$func->attributes()->class);
      $pluginEditCall = explode("::",(string)$plugin->$func->attributes()->editClass);


//TODO check content edit flag
//------------------------------------------------------------------------------
// check for rights to edit

      if (OLIVRight::w($options['template'],TRUE) and $pluginEditCall[0] and OLIV_CONTENT_EDIT)
        $class = $pluginEditCall[0];
      else
        $class = $pluginCall[0];

      $func = $pluginCall[1];


//------------------------------------------------------------------------------
// call render class for tag
// load plugin script
      OLIVCore::loadScript($pluginScript . ".php",OLIV_PLUGIN_PATH . $pluginScript . "/");


// call script and return output
      if (class_exists($class))
        return ($class::$func($options));


// error class not found
      else
        OLIVError::fire("OLIVPlugin::call - plugin class $class not found");
        return (FALSE);
    }
    else
      return (FALSE);
  }

  
//------------------------------------------------------------------------------
// scan plugin directory and load plugin metadata
  private function scan($path)
  {
    global $_PLUGIN;

    $_PLUGIN = new simpleXmlElement("<plugin></plugin>");

    if ($pluginDir = olivopendir ($path))
    {
    	$cnt = 0;
      while ($file = readdir($pluginDir))
      {
        if (olivis_dir($path . $file) and $file != "." and $file != "..")
        {
          $file .= "/";

      // get define.xml
          if (olivfile_exists($path . $file . "define.xml"))
          {
            $xml = olivxml_load_file($path . $file . "define.xml");

            // get type of plugin
            $type = $xml->children()->getName();

// type don't exist -> create
            if ($type != (string)$_PLUGIN->$type->getName())
            {
              olivxml_insert($_PLUGIN,$xml);
            }

// insert or replace functions
            else
            {
              $func = $xml->$type->func;

              foreach ($func->children() as $entry)
              {
                olivxml_insert($_PLUGIN->$type->func,$entry);
              }
            }
            $cnt++;
          }
        }
      }
//echoall($_PLUGIN);
      closedir ($pluginDir);
      return ($cnt);
    }
    else
      OLIVError::fire("plugin::scan - directory $path not found");
			return (FALSE);
  }
  


}

?>
