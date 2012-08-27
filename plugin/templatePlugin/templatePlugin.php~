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

class templatePlugin
{
  var $editor;
  
//------------------------------------------------------------------------------
// render functions
  static public function __callStatic($tag,$options)
  {
  	$link = "";

    $template = $options[0]['template'];
    $content = $options[0]['content'];
    $value = $options[0]['value'];


// parse start tag parameters
    $startTagArray = templatePlugin::startTag($template,$content);


// create tag string
    $startTag = "<$tag " . templatePlugin::paramToString($startTagArray['param']) . ">";
    $endTag = "</$tag>";


//echoall($startTagArray);
// return plugin array
    $tagArray = (array(
      "startTag" => $startTag,
      "value" => $value,
      "endTag" => $endTag)
    );

// insert link if present
		if (count($startTagArray['link'])) $tagArray['link'] = $startTagArray['link'];

		return $tagArray;
  }


//------------------------------------------------------------------------------
// create tag string
  static private function startTag($template,$content)
  {
//    $templateArray = array();
//    $retString = "";

// get template parameters
    $paramArray = templatePlugin::getParamArray($template);

// insert content parameters
		$paramArray = templatePlugin::getParamArray($content,$paramArray);

    return ($paramArray);
 }



//------------------------------------------------------------------------------
// convert array to parameter string
  static private function paramToString($paramArray)
  {
    $retString = "";
    
    foreach($paramArray as $key => $value)
    {
      $retString .= "$key='$value' ";
    }
    
    return ($retString);
  }


//------------------------------------------------------------------------------
// create parameter string for tag
  static private function getParamArray($param,$paramArray = array("param" => array(),"link" => array()))
  {
// loop over parameters
    if ($param)
    {
      foreach ($param->attributes() as $key => $value)
      {
        $newParam = array();
        $newLink = array();
        $newParamValue = "";
        $newLinkValue = "";
        

// get parameters from array
        $newParamArray = templatePlugin::parse($key,$value);


// select param and link part
        if (array_key_exists("param",$newParamArray)) $newParam = $newParamArray['param'];
        if (array_key_exists("link",$newParamArray)) $newLink = $newParamArray['link'];

        $newParamKey = key($newParam);
        $newLinkKey = key($newLink);
        
        if ($newParamKey) $newParamValue = $newParam[$newParamKey];
        if ($newLinkKey) $newLinkValue = $newLink[$newLinkKey];


//------------------------------------------------------------------------------
// add parameter to existing parameter
        if (array_key_exists($newParamKey,$paramArray['param']))
          $paramArray['param'][$newParamKey] .= $newParamValue;
        else
// add new parameter
          $paramArray['param'] = array_merge ($paramArray['param'],$newParam);


//------------------------------------------------------------------------------
// add parameter to existing link


        if (array_key_exists($newLinkKey,$paramArray['link']))
          $paramArray['link'][$newLinkKey] = $newLinkValue;
        else
        {
          $paramArray['link'] = array_merge ($paramArray['link'],$newLink);
        }
      }
    }

    return ($paramArray);
  }





//------------------------------------------------------------------------------
// parse tag parameters
// insert content background image
  static private function parse($key,$value)
  {
    $paramArray = array();

    switch ($key)
    {
// use image class for background-image
      case 'background-image':
    		if ($background_image = OLIVImage::_($value))
    			$paramArray['param']['style'] = "background-image:url($background_image);";
        break;


// set url parameters for link
      case 'url':
        $paramArray['link']['url'] = (string)$value;
        break;
      
      case 'title':
        $paramArray['link']['title'] = (string)OLIVText::_($value);
        break;

      case 'target':
        $paramArray['link']['target'] = (string)$value;
        break;

// set normal value
      default:
  			$paramArray['param'][$key] = $value;
        break;
    }

    return ($paramArray);
  }
}








//------------------------------------------------------------------------------
// class for editor call
class templateEditPlugin
{
//------------------------------------------------------------------------------
// render functions
  static public function __callStatic($tag,$options)
  {
    $template = $options[0]['template'];
    $content = $options[0]['content'];
    $value = $options[0]['value'];

    return (array("startTag" => templatePlugin::startTag($tag,$template,$content),"value" => $value,"endTag" => "</$tag>"));
  }
}
?>
