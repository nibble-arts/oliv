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


class OLIVPreProcessor extends OLIVCore
{
  // constructor
  public function __construct()
  {
  }
  
  
//------------------------------------------------------------------------------
// parse page/template/module
//------------------------------------------------------------------------------
  public function process($page,$stylesheet,$templatePath)
  {
    $i = 0;
    $templates = array();

//------------------------------------------------------------------------------
// parse site
 
    if (count($page->structure()))
    {
      $areas = $page->structure()->children();

      if (count($areas))
      {
        foreach($areas as $entry)
        {
// check for read access rights
// if not, clear node to hide from rendering
					if(OLIVRight::r($entry))
					{
		        if ($mod = $entry->attributes()->mod)
		        {
		          $script = OLIVModule::getModuleByName($mod);
		          $originScript = $script;


//------------------------------------------------------------------------------
// script found
		          if (isset($script->script))
		          {
// insert parameters
								olivxml_insert($script,OLIVPreprocessor::parse_param((string)$entry),"ALL");

// create paths for module, content, template, image
								$script->path = system::OLIV_MODULE_PATH() . (string)$script->name . "/";

// insert script access rights 
								$script->access->r = OLIVRight::r($entry);
								$script->access->w = OLIVRight::w($entry);
								$script->access->x = OLIVRight::x($entry);

//------------------------------------------------------------------------------
// load module script
 	             $this->loadScript($script->script->main,system::OLIV_MODULE_PATH() . $script->name . "/");

//------------------------------------------------------------------------------
// call module class and merge template and content

								if ($script->script->main)
								{
									$tempArray = explode(".",$script->script->main);

									$class = $tempArray[0];

									if (class_exists($class))
									{

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// create and execute module class
							      $module = new $class($script);
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

										if (is_object($module))
										{
											$tempTemplate = $module->template();

// set path to insert module-template-link stylesheet
// link only if template and content found
											if ($module->template() and $module->content())
											{
												if (is_object($module->content()))
												{
													$templates[$entry->getName() . "::" . $module->content()->getName()] = $module->template();
												}
											}

// insert module content in page content
											if ($module->content())
												$page->insert($module->content());
										}
										else
											$page->clear($entry->getName());

// destroy module object
//									unset($module);
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
//TODO remove node completely
// if no read permission -> clear content to not render
					else
						$page->clear($entry->getName());
        }
//echoall($page->structure());
	    }
	    else
	      OLIVError::fire("processor::process - page is empty");
	  }


// include module templates in page template
// create temporary xslt for include process
		$tempXsl = new XSLTProcessor();
		$tempXsl->registerPHPFunctions();


// include page template
		if (sessionfile_exists($templatePath . ".xslt"))
		{
			$xmlString = "<xsl:include href='" . session_path($templatePath) . ".xslt'/>";
			OLIVTemplate::link_css($templatePath);
		}
		else
			OLIVError::fire("processor.php::process - no page template found");



//------------------------------------------------------------------------------
// include module templates
		foreach ($templates as $key => $entry)
		{
			if (sessionfile_exists($entry . ".xslt"))
			{
				$linkArray = explode("::",$key);

// create stylesheet to link module template to page area
				$tempSting = "<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>";
				$tempSting .= "<xsl:template match='" . $linkArray[0] . "'>";
				$tempSting .= "<xsl:apply-templates select='../" . $linkArray[1] . "'/>";
				$tempSting .= "</xsl:template>";
				$tempSting .= "</xsl:stylesheet>";

				$tempXsl = new simpleXmlElement($tempSting);

// write template to disk
				$fileName = explode("/",$entry);
				array_pop($fileName);
				$fileName = implode("/",$fileName);

				$filePath = session_path($fileName) . "/link_" . $linkArray[0] . "_to_" . $linkArray[1] . ".xslt";
			
				$fileHandle = fopen($filePath,"w");
				if ($fileHandle)
				{
					fputs($fileHandle,$tempXsl->asXML());
					fclose($fileHandle);
				}

				$xmlString .= "<xsl:include href='" . $filePath . "'/>";
				$xmlString .= "<xsl:include href='" . session_path($entry) . ".xslt'/>";

// link css file
				OLIVTemplate::link_css($entry);
			}
		}


//TODO create temporary include template 
		$xmlString = "<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>" . $xmlString . "</xsl:stylesheet>";


		$tempXml = new simpleXmlElement($xmlString);
		$stylesheet->importStylesheet($tempXml);

//echoall($page->structure());
  }

//------------------------------------------------------------------------------
// parse parameter sting
// format [name]:[value];...
// return assoziative array or single value
	public static function parse_param($paramString)
	{
		$retXml = new simpleXmlElement("<param></param>");
	
		$paramArray = explode(";",$paramString);
		foreach($paramArray as $entry)
		{
			$valueArray = explode(":",$entry);

	// enter name:value pair
			if (count($valueArray) > 1)
			{
				if ($valueArray[1])
					$retXml->addChild($valueArray[0],$valueArray[1]);
			}
		}
		return $retXml;
	}
}


?>
