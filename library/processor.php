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
					if(OLIVRight::r($entry))
					{
		        if ($mod = $entry->attributes()->mod)
		        {
		          $script = OLIVModule::getModuleByName($mod);

      // script found
		          if (isset($script->script))
		          {
      // link modules to page elements
	      				$script->param = (string)$entry;

//------------------------------------------------------------------------------
// load module script
 	             $this->loadScript($script->script->main,system::OLIV_MODULE_PATH() . $script->name . "/");

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

// insert module template
											if (array_key_exists("template",$outputObj->o))
											{
												$templates[$entry->getName() . "::" . $outputObj->o['content']->getName()] = $outputObj->o['template'];
											}

// insert module content in page content
											if (array_key_exists("content",$outputObj->o))
												$page->insert($outputObj->o['content']);
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
        }
//echoall($page->structure());
	    }
	    else
	      OLIVError::fire("processor::process - page is empty");
	  }
// include module templates in page template

// create temporary xslt for include process
		$tempXsl = new XSLTProcessor();


// include page template
		if (sessionfile_exists($templatePath . ".xslt"))
		{
			$xmlString = "<xsl:include href='" . session_path($templatePath) . ".xslt'/>";
			OLIVTemplate::link_css($templatePath);
		}
		else
			OLIVError::fire("processor.php::process - no page template found");


// include module templates
		foreach ($templates as $key=>$entry)
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
  }
}


?>
