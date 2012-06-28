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


class OLIVProcessor extends OLIVCore
{
  // constructor
  public function __construct()
  {
  }
  
  
//------------------------------------------------------------------------------
// parse page/template/module with argv parameters
//------------------------------------------------------------------------------
  public function process($page,$template,$module)
  {
    $i = 0;

//------------------------------------------------------------------------------
// parse site
    if (count($page->structure()))
    {
      $areas = $page->structure()->children();

      if (count($areas))
      {
        foreach($areas as $entry)
        {
          $mod = $entry->attributes()->mod;
          $script = $module->getModuleByName($mod);

    // script found
          if ($script->script)
          {
    // link modules to page elements
            $page->setScript((string)$entry->getName(),$script->script);
  
//------------------------------------------------------------------------------
// load module script
            $this->loadScript($script->script->main,OLIV_MODULE_PATH . $script->name . "/");

            // load module text
            $path = OLIV_MODULE_PATH . $script->name . "/" . $script->script->language;
            $file = $script->name;
            $default_language = $script->script->default_language;
            
            OLIVText::load($path,$file,$default_language);

//------------------------------------------------------------------------------
          }
          else
            OLIVError::warning("processor::process - required module '" . $mod . "' not found");
        }
      }
      else
        OLIVError::fire("processor::process - page is empty");
    }
  }

}


?>
