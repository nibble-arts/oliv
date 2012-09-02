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


if (!system::OLIVCORE()) die ("render.php - OLIVCore not present");
if (!system::OLIVTEXT()) die ("render.php - OLIVText not present");
if (!system::OLIVERROR()) die ("render.php - OLIVError not present");


class OLIVRender extends OLIVCore
{
  var $o = "";

//------------------------------------------------------------------------------
// constructor
  public function __construct()
  {
    $this->o = "";
  }



//------------------------------------------------------------------------------
// return render result
  public function display()
  {
    return ($this->o);
  }


//------------------------------------------------------------------------------
// main rendering routine
// render page to this->o
//------------------------------------------------------------------------------
  public function page($template,$page)
  {
// output rendered page
    if ($template)
      $this->o = OLIVRender::template($template->xml(),$page->structure());
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
    $url = "";
    $val = "";

    if ($template)
    {
// get template name
//      $templateName = (string)$template->attributes()->name;


//------------------------------------------------------------------------------
// loop over children of template
      foreach($template->children() as $entry)
      {
// render only if permission
				if(OLIVRight::r($entry))
				{
		    	$tempO = "";
		      $style = "";
		      $class = "";
		      $script = "";
		      $mod = "";
		      $background_image = "";

		      $areaName = (string)$entry->attributes()->id;
		      $areaTag = $entry->getName();


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// if content present and permissiongs
// get parameters and collect content parameters
// start content sequenz

		      if ($content and OLIVRight::r($content))
		      {
		        $areaContent = $content->$areaName;
		        $value = (string)$areaContent;
		        $contentName = $areaContent->getName();


// area present in content definition
		        if (($areaName == $contentName))
		        {


//TODO
// extract content
//		if tag_name == template_area_name => insert content in template and render
//		else
//			if content part has children => render content recursive in $areaName

							if (count($areaContent->children()))
			        {
			        	$tempO .= OLIVRender::_template($content,"");
		  	      }


//------------------------------------------------------------------------------
// loop over multiple content entries
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
// get module script call
		          if (isset($areaContent))
		          {
		            if (isset($areaContent->attributes()->mod))
		              $mod = (string)$areaContent->attributes()->mod; // add module
		          }
		        }
		      }

// end content sequenz
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------



//------------------------------------------------------------------------------
// call plugin
//echo "<hr>";
//echoall($areaTag);
	        $pluginArray = OLIVPlugin::call($areaTag,"render",array("template" => $entry,"content" => $content,"value" => $value));
//echoall($pluginArray);

// if no plugin found
// output default div clause
		      if (!$pluginArray)
		      {
		        $pluginArray['startTag'] = "<$areaTag>";
		        $pluginArray['value'] = $value;
		        $pluginArray['endTag'] = "</$areaTag>";
		        unset($pluginArray['link']);
		      }


//------------------------------------------------------------------------------
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


// output content text
		      else
		        $tempO .= OLIVText::_($pluginArray['value']);


//------------------------------------------------------------------------------
// display template edit layer
		      if (system::OLIV_TEMPLATE_EDIT())
		      {
		        if (system::OLIV_TEMPLATE_MARK())
		          $tempO .= "<div id='oliv_markbox'>";
		        else
		          $tempO .= "<div id='oliv_editbox'>";

		        $tempO .= "<div id='oliv_edittitle'><b>$areaName</b></div></div>";
		      }


// end tag sequenz
	        $tempO .= $pluginArray['endTag'];
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
// create link for tag
		      if (array_key_exists('link',$pluginArray))
		      {
		        $linkArray = $pluginArray['link'];
		        $paramArray['id'] = $areaName;


// insert link
		        $tempO = OLIVRoute::link($tempO,array("link" => $linkArray,"param" => $paramArray));
//------------------------------------------------------------------------------

		      }

				$o .= $tempO;
				}


      }

      return ($o);
    }
    else
    {
//TODO render content only ???
//      echoall($content);
      return OLIVERROR::renderError("render::_template - no template to render");
    }
  }



//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// call module and return module class object
  static private function callScript($module)
  {
    if ($module->attributes()->main)
    {
      $tempArray = explode(".",$module->attributes()->main);

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
