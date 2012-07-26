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
    $template = $options[0]['template'];
    $content = $options[0]['content'];
    $value = $options[0]['value'];

    return (array("startTag" => templatePlugin::startTag($tag,$template,$content),"value" => $value,"endTag" => "</$tag>"));
  }


//------------------------------------------------------------------------------
// create tag string
  static private function startTag($tag,$template,$content)
  {
    $templateArray = array();
    $retString = "";

    $templateArray = templatePlugin::getParamArray($template);
//TODO
// insert also content parameters to template
//echoall($content);

  // combine parameters from array to tag string
    foreach ($templateArray as $key => $value)
    {
      $retString .= "$key='$value' ";
    }

    return ("<$tag $retString>"); //name='$tag' 
 }


//------------------------------------------------------------------------------
// create parameter string for tag
  static private function getParamArray($param)
  {
    $paramArray = array();

    if ($param->attributes())
    {
      foreach ($param->attributes() as $entry)
      {
        $key = (string)$entry->getName();
        $value = (string)$entry;
        
        $newParam = templatePlugin::parse($key,$value);#
        $newKey = key($newParam);
        $newValue = $newParam[$newKey];

    // add parameter to existing key
        if (array_key_exists($newKey,$paramArray))
          $paramArray[$newKey] .= $newValue;
        else
    // add new parameter
          $paramArray = array_merge ($paramArray,$newParam);
      }
    }
    return ($paramArray);
  }


//------------------------------------------------------------------------------
// parse tag parameters
// insert content background image
  static private function parse($key,$value)
  {
    switch ($key)
    {
// use image class for background-image
      case 'background-image':
    		if ($background_image = OLIVImage::_($value))
    			$paramArray['style'] = "background-image:url($background_image);";
        break;

      default:
  			$paramArray[$key] = $value;;
    }

    return ($paramArray);
  }
}


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