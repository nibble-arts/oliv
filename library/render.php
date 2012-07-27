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
// Page rendering engine
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("render.php - OLIVCore not present");
defined('OLIVTEXT') or die ("render.php - OLIVText not present");
defined('OLIVERROR') or die ("render.php - OLIVError not present");


class OLIVRender extends OLIVCore
{

//------------------------------------------------------------------------------
// constructor
  public function __construct()
  {
  }


//------------------------------------------------------------------------------
// main rendering routine
// display page
//------------------------------------------------------------------------------
  public function page($template,$page)
  {
// output rendered page
    if ($template)
      echo OLIVRender::template($template->xml(),$page->structure());
    else
      OLIVError::fire ("render::page - no template found");
  }



// call template rendering
  static public function template($template,$content="")
  {
    return (OLIVRender::_template($template,$content));
  }


//------------------------------------------------------------------------------
// render template recursive
// return rendered string
// template ... simplexml Object with template information
// content ... [optional] simplexml Object for script link information
//
// only template areas present in content get parameters
//   style ... style expression
//   link ... hyperlink on area
//   title ... hyperlink-title
//   script ... script in module to be executed
  static private function _template($template,$content)
  {
    global $_PLUGIN;

    $o = "";
    $templateName = "";
    $areaName = "";
    $contentName = "";
    $value = "";
    $areaContent = "";

    if ($template)
    {
// get template name
//      $templateName = (string)$template->attributes()->name;


// loop over children
      foreach($template->children() as $entry)
      {
      	$tempO = "";
        $style = "";
        $class = "";
        $script = "";
        $mod = "";
        $background_image = "";

        $areaName = (string)$entry->attributes()->id;
        $areaTag = $entry->getName();



//echo "<hr>";
//echoall($template);
//echoall($content);


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// collect content parameters
    // if content present, get parameters
        if ($content)
        {
          $areaContent = $content->$areaName;
          $value = (string)$areaContent;
          $contentName = $areaContent->getName();


// area present in content definition
          if (($areaName == $contentName))
          {


//------------------------------------------------------------------------------
// loop over multiple content
            if (count($areaContent) > 1)
            {
              foreach ($areaContent as $contentEntry)
              {
					// insert area name
              	$contentEntry->$areaName = (string)$contentEntry;
              	
              	$tempXml = new simpleXmlElement("<temp></temp>");
              	olivxml_insert($tempXml,$entry);

                $tempO .= OLIVRender::_template($tempXml,$contentEntry);
              }
              return($tempO); // end rendering -> supresses single output outside of loop
            }


//------------------------------------------------------------------------------
// script and module call only from content possible
            if (isset($areaContent))
            {
  // get module script call
              if (isset($areaContent->attributes()->mod))
                $mod = (string)$areaContent->attributes()->mod; // add module
  
  
//TODO insert content parameters in template
//TODO change to plugin
// insert content background image
/*						if ($background_image = OLIVImage::_($background_image))
							$style .= "background-image:url($background_image)";
*/
            }
          }
        }


//------------------------------------------------------------------------------
// start tag
// get plugin output
        $pluginArray = OLIVPlugin::call($areaTag,"render",array("template" => $entry,"content" => $areaContent,"value" => $value));


// if no plugin found
// output default div clause
        if (!$pluginArray)
        {
          $pluginArray['startTag'] = "<$areaTag>";
          $pluginArray['endTag'] = "</$areaTag>";
          $pluginArray['value'] = $value;
        }


//------------------------------------------------------------------------------
// start tag sequenz
        $tempO .= $pluginArray['startTag'];


//------------------------------------------------------------------------------
// call module
        if ($mod)
        {
          if ($areaContent)
          {
            $outputObj = OLIVRender::callScript($areaContent); // create output string from module

  // supress tag value which is module parameter
            $pluginArray['value'] = "";

  //TODO
  // make syntax check of module output
    				$tempO .= $outputObj->o;
          }
  			}
//------------------------------------------------------------------------------



// check for recursion
        if (count($entry->children()))
          $tempO .= OLIVRender::_template($entry,$content);


// output content directly as text
        else
          $tempO .= OLIVText::_($pluginArray['value']);


//------------------------------------------------------------------------------
// display template edit layer
        if (OLIV_TEMPLATE_EDIT)
        {
          if (OLIV_TEMPLATE_MARK)
            $tempO .= "<div id='oliv_markbox'>";
          else
            $tempO .= "<div id='oliv_editbox'>";

          $tempO .= "<div id='oliv_edittitle'><b>$areaName</b></div></div>";
        }


// end tag sequenz
        $tempO .= $pluginArray['endTag'];
//------------------------------------------------------------------------------

  

//------------------------------------------------------------------------------
// create link on div


        if (array_key_exists('url',$pluginArray))
        {
          $url = $pluginArray['url'];
          $paramArray['id'] = $areaName;

          if (array_key_exists('val',$pluginArray))
            $val = $pluginArray['val'];

          if (array_key_exists('title',$pluginArray))
            $paramArray['title'] = $pluginArray['title'];
          

//------------------------------------------------------------------------------
// insert link
          $tempO = OLIVRoute::intern($tempO,array("url" => $url,"val" => $val,"param" => $paramArray));
        }

				$o .= $tempO;
      }

      return ($o);
    }
    else
    {
//TODO render content only
//      echoall($content);
      return OLIVERROR::render ("render::_template - no template to render");
    }
  }



//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// call module and return module class object
  static private function callScript($module)
  {
    if ($module->script->main)
    {
//OLIVError::debug("render - run mod: " . $module->script->main);
      $tempArray = explode(".",$module->script->main);
      $class = $tempArray[0];

      if (class_exists($class))
      {


//------------------------------------------------------------------------------
// call module class and return output
        $outputObj = new $class($module);
//------------------------------------------------------------------------------


      } 
    }
    else
      OLIVError::fire("render::callModule - no main script declared");
		return ($outputObj);
  }
}
?>
