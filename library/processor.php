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
  public function process($page,$template)
  {
    $i = 0;

//------------------------------------------------------------------------------
// parse site
//TODO change processor functionality
//TODO parse and execute module
 
    if (count($page->structure()))
    {

      $areas = $page->structure()->children();

      if (count($areas))
      {
        foreach($areas as $entry)
        {
          if ($mod = $entry->attributes()->mod)
          {
            $script = OLIVModule::getModuleByName($mod);

      // script found
            if (isset($script->script))
            {
      // link modules to page elements
      				$script->param = (string)$entry;

//              $page->setScript((string)$entry->getName(),$script->script);

  //------------------------------------------------------------------------------
  // load module script
              $this->loadScript($script->script->main,system::OLIV_MODULE_PATH() . $script->name . "/");

      // load module text
/*              $path = system::OLIV_MODULE_PATH() . $script->name . "/language/";
              $file = $script->name;
              $default_language = $script->script->default_language;

              OLIVText::load($path,$file);*/
  
//TODO execute module
//TODO get template XSLTand add to template
//TODO get content XML and add to page

//------------------------------------------------------------------------------
// call module class and merge template and content
							$outputObj = FALSE;

							if ($script->script->main)
							{
								$tempArray = explode(".",$script->script->main);

								$class = $tempArray[0];

								if (class_exists($class))
								{
					        $outputObj = new $class($script);

									if (is_object($outputObj))
									{
										$tempTemplate = $outputObj->o['template'];


// insert module template in page template
										if (array_key_exists("template",$outputObj->o))
										{
											echoall($outputObj->o['template']);


//TODO insert the module stylesheet into the page stylesheet
											$template->stylesheet->importStylesheet($outputObj->o['template']);
										}


//echoall($template->template->asXML());

// insert module content in page content
										if (array_key_exists("content",$outputObj->o))
											$page->insert($outputObj->o['content']);

//echoall($page->structure());
									}
								} 
							}
							else
								OLIVError::fire("render::callModule - no main script declared");
            }
            else
              OLIVError::warning("processor::process - required module '" . $mod . "' not found");
          }
//------------------------------------------------------------------------------

        }
//echoall($page->structure());
      }
      else
        OLIVError::fire("processor::process - page is empty");
    }

//global $_TEXT;
//echoall($_TEXT);
  }
}


?>
