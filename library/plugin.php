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
// scan plugin directory and load plugin metadata
  private function scan($path)
  {
    global $_PLUGIN;
    $_PLUGIN = new simpleXmlElement("<plugin></plugin>");

    if ($pluginDir = olivopendir ($path))
    {
  
      while ($file = readdir($pluginDir))
      {
        if (olivis_dir($path . $file) and $file != "." and $file != "..")
        {
          $file .= "/";

          // get define.xml
          if (olivfile_exists($path . $file))
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
                $func = (string)$entry->getName();
                // function exists -> replace

                if ($func == (string)$_PLUGIN->$type->func->$func)
                {
                  $_PLUGIN->$type->func->$func = (string)$entry;
                  $_PLUGIN->$type->func->$func->attributes()->script = (string)$entry->attributes()->script;
                }
              }
            }
          }
        }
      }
      closedir ($pluginDir);
    }
    else
      OLIVError::fire("plugin::scan - directory $path not found");
//print_r($_PLUGIN->asXML());
  }
}

?>