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
  static public function template($template,$content="")
  {
    $o = "";
    $templateName = "";
    $areaName = "";
    $contentName = "";
    $script = "";
    $mod = "";
    $value = "";
    
    if ($template)
    {
      // get template name
      $templateName = (string)$template->attributes()->name;

  // loop over children
      foreach($template->children() as $entry)
      {
        $areaName = (string)$entry->getName();


//------------------------------------------------------------------------------
// repeat function detected
        if ($repeat = (string)$entry->attributes()->repeat)
        {
          // if area information in content loop
          $part = $content->$areaName;

          if (count($part))
          {
            $entry->attributes()->repeat = ""; // deactivate repeat function for recursion
            
						$tempTempl = new simpleXmlElement("<template></template>");
						olivxml_insert($tempTempl,$entry); // insert template part

            // loop over content entries
            foreach ($part as $element)
            {
							$val = (string)$element;

							$tempCont = new simpleXmlElement("<content></content>");
							olivxml_insert($tempCont,$element); // insert template part

              $o .= OLIVRender::template($tempTempl,$tempCont);
              // print_r($entry);
            }
          }
					break; // end rendering
        }



//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//   collect parameters
        // if content get name and value of area
        if ($content)
        {
          $value = (string)$content->$areaName;
          $contentName = (string)$content->$areaName->getName();
        }

        // get information for link
        $link = (string)$entry->attributes()->link;
        $title = (string)$entry->attributes()->title;

// get template attributes
        $style = (string)$entry->attributes()->style;
        $class = (string)$entry->attributes()->class;
				$tag = (string)$entry->attributes()->tag;
				$image = (string)$entry->attributes()->image;
				$background_image = (string)$entry->attributes()->background_image;

// insert template background image
				if ($background_image = OLIVImage::_($background_image))
					$style .= "background-image:url($background_image)";


//------------------------------------------------------------------------------
// area present in content definition or no centent defined
        if (($areaName == $contentName) or !$content)
        {

// script and module call only from content possible
// get content attributes
          if ($content)
          {
            $style .= (string)$content->$areaName->attributes()->style; // add style
            $class .= (string)$content->$areaName->attributes()->class; // add class
            $script = (string)$content->$areaName->attributes()->script; // set module script
            $mod = (string)$content->$areaName->attributes()->mod; // add module
					  $background_image = (string)$content->$areaName->attributes()->background_image;

// insert content background image
						if ($background_image = OLIVImage::_($background_image))
							$style .= "background-image:url($background_image)";
          }

//------------------------------------------------------------------------------
        }
        else
        {
//          $style = "";
          $script = "";
          $mod = "";
        }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// start tag
				if (!$tag) $tag = "div";

        $divString = "<{$tag} id='" . $areaName . "'";
        if ($style) $divString .= " style='{$style}'"; // insert and overwrite css style
        if ($class) $divString .= " class='{$class}'"; // insert and overwrite css style

        $divString .= ">";
        $o .= $divString;
  

//------------------------------------------------------------------------------
// display template edit layer
        if (OLIV_TEMPLATE_EDIT)
        {
          if (OLIV_TEMPLATE_MARK)
            $o .= "<div id='oliv_markbox'>";
          else
            $o .= "<div id='oliv_editbox'>";

          $o .= "<div id='oliv_edittitle'><b>$areaName</b></div></div>";
        }

//------------------------------------------------------------------------------
// call script
        if ($script)
        {
//          $o .= $this->$script($areaName,$content->$areaName);
        }

// if no script call module
        elseif ($mod)
        {
          $element = $content->$areaName;
          if ($element)
          {
            $outputObj = OLIVRender::callScript($element); // create output string from module
//TODO
// make syntax check of script output
    				$o .= $outputObj->o;
          }
  			}
//------------------------------------------------------------------------------
// no module or script
//   direct render of multilingual text
        else
        {
          if ($value)
          {
            $o .= OLIVText::_($value);
          }
        }
//
//------------------------------------------------------------------------------

// call recursion
        if (count($entry->children()))
          $o .= OLIVRender::template($entry,$content);
  

// end of div tag
        $o .= "</{$tag}>";
  

//------------------------------------------------------------------------------
// create link on div
        if ($link)
        {
          $o .= OLIVRoute::intern("",array("url" => $link,"param" => array("id" => $areaName,"title" => $title)));
        }
//------------------------------------------------------------------------------
// end not table rendering
//        }
      }
      return ($o);
    }
    else
      return OLIVERROR::render ("render::template - no template to render");
  }



//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// calls module
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
