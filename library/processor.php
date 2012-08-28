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

if (!system::OLIVCORE()) die ("processor.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("processor.php - OLIVError not present");


class OLIVProcessor extends OLIVCore
{
  // constructor
  public function __construct()
  {
  }
  
  
//------------------------------------------------------------------------------
// parse page/template/module
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
          if ($mod = $entry->attributes()->mod)
          {
            $script = $module->getModuleByName($mod);

      // script found
            if (isset($script->script))
            {
      // link modules to page elements
              $page->setScript((string)$entry->getName(),$script->script);
    
  //------------------------------------------------------------------------------
  // load module script
              $this->loadScript($script->script->main,system::OLIV_MODULE_PATH() . $script->name . "/");

  
              // load module text
              $path = system::OLIV_MODULE_PATH() . $script->name . "/language/";
              $file = $script->name;
              $default_language = $script->script->default_language;
              
              OLIVText::load($path,$file,$default_language);
  
  //------------------------------------------------------------------------------
            }
            else
              OLIVError::warning("processor::process - required module '" . $mod . "' not found");
          }
        }
//echoall($page->structure());
      }
      else
        OLIVError::fire("processor::process - page is empty");
    }
  }
}


?>
