<?PHP
//
// IG-CMS
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
// system environment definitions
//
// Version 0.1
//------------------------------------------------------------------------------

define('IGENV','alive');


//------------------------------------------------------------------------------
// set document root path
$pathArray = explode("/",$_SERVER['SCRIPT_FILENAME']);
define (IG_SCRIPT_NAME, array_pop($pathArray));
define (IG_DOCUMENT_ROOT, implode("/",$pathArray) . "/");



//------------------------------------------------------------------------------
// load system definitions => load from core.xml
$this->coreXml = new IGXml("","core.xml");
$constantArray = $this->coreXml->_("core.system");

// set constant definitions
if (is_array($constantArray))
{
  foreach($constantArray as $key => $value)
  {
    define ($key,"$value");
  }
}
else
  die ("define:: - core.xml not found");


//------------------------------------------------------------------------------
// set global argument array
global $_argv;
$_argv = array();

foreach($_GET as $key => $value)
{
  $_argv[$key] = $value;
}

foreach($_POST as $key => $value)
{
  $_argv[$key] = $value;
}



//------------------------------------------------------------------------------
// language definition => loaded from argv
// if not in parameters
//									look for HTTP_USER_AGENT
//									set to default language

//debug
//echoall($argv);

define (IG_LANG, "en-GB");







//------------------------------------------------------------------------------
// xml class
class IGXml
{
  private $xmlArray = array();

  // construct new xml object
  // utf-8 encoded
  function __construct($path,$name)
  {
    $this->xmlArray = $this->parse(IG_DOCUMENT_ROOT . $path . $name);
  }


//------------------------------------------------------------------------------
// get xml value
//
// parameter defines hyrarchic path to values -> . separated string
// IGXml::_     returns value array
// IGXml::attr  returns attribute array
  function __call($method,$arg)
  {
    $parameter = $arg[0];
    $paramArray = explode(".",$parameter);

    // get subentries
    $tempArray = $this->xmlArray;
    foreach($paramArray as $entry)
    {
      $tempArray = $this->getTag($entry,$tempArray);
    }

//debug
//echoall($tempArray);
    switch($method)
    {
      case _: // return values
        return ($tempArray);
        break;

      case attr: // return attribute array

        return ($this->getAttr($tempArray));
        break;
    }
  }

  // get attribute array
  public function getAttr($xmlArray)
  {
    if (is_array($xmlArray))
    {
      foreach($xmlArray as $key => $value)
      {
        $index = intval(substr($key,0,1));
        $arg = substr($key,2);

        if ($arg == "attr") // attribute
        {
          foreach($value as $valkey => $valvalue)
            $retArray[$index][$valkey] = $valvalue;
        }
        else // value
        {
          $retArray[$index][value] = $value;
        }
      }
      return ($retArray);
    }
  }



  // return content of tag
  private function getTag($tag,$xml)
  {
    return($xml[$tag]);
  }



//------------------------------------------------------------------------------
// parse xml-file to associative array
// unchanged code from 'lz_speedy at web dot de 30-Dec-2008 10:20' at 'http://www.php.net/manual/de/function.xml-parse.php'
  private function parse($url, $get_attributes = 1, $priority = 'tag')
  {
    $contents = "";
    if (!function_exists('xml_parser_create'))
    {
        return array ();
    }
    $parser = xml_parser_create('');
    if (!($fp = @ fopen($url, 'rb')))
    {
        return array ();
    }
    while (!feof($fp))
    {
        $contents .= fread($fp, 8192);
    }
    fclose($fp);

    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if (!$xml_values)
        return; //Hmm...

    $xml_array = array ();
    $parents = array ();
    $opened_tags = array ();
    $arr = array ();
    $current = & $xml_array;
    $repeated_tag_index = array ();
    foreach ($xml_values as $data)
    {
        unset ($attributes, $value);
        extract($data);
        $result = array ();
        $attributes_data = array ();
        if (isset ($value))
        {
            if ($priority == 'tag')
                $result = $value;
            else
                $result['value'] = $value;
        }
        if (isset ($attributes) and $get_attributes)
        {
            foreach ($attributes as $attr => $val)
            {
                if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                else
                    $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }
        if ($type == "open")
        {
            $parent[$level -1] = & $current;
            if (!is_array($current) or (!in_array($tag, array_keys($current))))
            {
                $current[$tag] = $result;
                if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = & $current[$tag];
            }
            else
            {
                if (isset ($current[$tag][0]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset ($current[$tag . '_attr']))
                    {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset ($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete")
        {
            if (!isset ($current[$tag]))
            {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            }
            else
            {
                if (isset ($current[$tag][0]) and is_array($current[$tag]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' and $get_attributes and $attributes_data)
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes)
                    {
                        if (isset ($current[$tag . '_attr']))
                        {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                        if ($attributes_data)
                        {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                }
            }
        }
        elseif ($type == 'close')
        {
            $current = & $parent[$level -1];
        }
    }
    return ($xml_array);
  }
}






//------------------------------------------------------------------------------
// intelligent display for debug
function echoall($string)
{

  if (is_array($string))
  {
    echo count($string) . " elements";
    echoarray($string);
  }

  switch ($string)
  {
    case NULL:
      echo "*NULL";
      break;

    case "":
      echo "*empty";
      break;

    case FALSE:
      echo "*FALSE";
      break;

    default:
      echo $string;
      break;
  }
  echo "<br>";
}


// displays array in code-style
function echoarray($entry)
{
  echo "<pre>";
    print_r($entry);
  echo "</pre>";
}


?>
