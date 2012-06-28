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
// Multilangual static text rendering
//
// V0.1
//------------------------------------------------------------------------------


// todo:  insert printf, sprintf method



defined('OLIVCORE') or die ("language.php - OLIVCore not present");
define ("OLIVTEXT","alive");

// global static text array
$_TEXT = new SimpleXMLElement("<LANGUAGE></LANGUAGE>");

// method name = nameSpace
//  Text::text ... uses entries in [Text] namespace
//
// language.ini files must be in language directory in a subdirectory with the correct language code
//
// text definitions must be named with upper case and _ seperators
// text can be in namespaces:
//    [TEXT]                    namespace
//      TEXT_1 = "text 1"       text definitions
//      TEXT_2 = "text 2"
//
// language file for site must be of the type
//    lang-code.script-name.ini
//
//    default loading
//    lang-code.system.ini
//
//    loading lang-code.path_scriptName.ini
//      path with subpaths formatted with _ instead of /
//      if option[FILE] loading landId.FILE.ini
//
// options array1
//                [path] ... path to language files
//
//                [FILE] ... name of site
//                no FILE parameter -> use script path
//
// lang			      language ID

//-------------------------------------------------------------------------------------------

class OLIVText extends OLIVCore
{

//-------------------------------------------------------------------------------------------
// return text string
  static public function _($text)
  {
    global $_TEXT;

    if (count($_TEXT->children()))
    {
			// look in root nameSpace
      $retText = OLIVText::fetchText(strtoupper($text));
			if ($retText) return ($retText);

			// look in namespaces
			foreach($_TEXT->children() as $entry)
			{
				if (count($value->children()))
				  if (($retText = OLIVText::fetchText(strtoupper($text),strtoupper($entry->getName())))) return ($retText);
			}
      return ($text); // return text value
		}
  }


// return printf


//-------------------------------------------------------------------------------------------
// load text file from path
  static public function load($path,$file,$default_language = OLIV_DEFAULT_LANG)
  {
    global $_TEXT;

// default language
    OLIVText::merge($_TEXT,OLIVText::loadText($default_language,$path,$file));
// selected language
    OLIVText::merge($_TEXT,OLIVText::loadText(OLIV_LANG,$path,$file));
  }
  
  
//-------------------------------------------------------------------------------------------
// fetch namespace to array
  static public function fetchNameSpace($nameSpace)
  {
    global $_TEXT;

    if ($nameSpace) return($_TEXT->strtoupper($nameSpace));
    return false;
  }


// fetch text from xml
  static private function fetchText($text,$nameSpace="")
  {
  	global $_TEXT;

    if ($nameSpace) // look in nameSpace
    {
      if (isset($_TEXT->$nameSpace->$text))
				return((string)$_TEXT->$nameSpace->$text); // return string
      else
        return false;
    }
    else // look in root
    {
      if (isset($_TEXT->$text) and !count($_TEXT->$text->children())) // text found and not namespace
				return((string)$_TEXT->$text); // return string
      else return false;
    }
  }





//-------------------------------------------------------------------------------------------
// load text array
  static private function loadText($lang,$path,$fileName="")
  {
// create filename
    if (!$fileName)
    {
      $fileName = str_replace("/","_",$fileName); // change / to _ and add .ini
    }
    
    $fileName = $lang . "." . $fileName . ".xml"; // add language prefix
    $filePath = OLIV_DOCUMENT_ROOT . $path . $lang . "/" . $fileName;

// load if file exists
    if (file_exists($filePath))
    {
      $xml = simplexml_load_file($filePath);
      return ($xml);
    }
    return (false);
  }


//-------------------------------------------------------------------------------------------
// add assoziative b to a
// overwrites existing keys
  static private function merge(&$a,$b)
  {
    if (count($b->children()))
    {
      foreach ($b->children() as $entry)
      {
        simple_xml_insert($a,$entry);
      }
    }
  }




//-------------------------------------------------------------------------------------------
// return array table
  static public function assocArray($dispArray)
  {
    if (is_array($dispArray))
    {
      $o = "<table border='1' cellspacing='0' cellpadding='3' width='100%'>";

      foreach($dispArray as $key => $value)
      {
      	$o .= "<tr>";
      		if (!is_array($value))
      		{
      			$o .= "<td class='ig_var_name'>$key</td><td class='ig_var_value'>$value</td>";
      		}
      		else // recursion
      		{
      			$o .= "<td>$key</td>";
      			$o .= "<td>" . OLIVText::assocArray($value) . "</td>";
      		}
      	$o .= "</tr>";
      }
      $o .= "</table>";
    }
    return ($o);
  }
}

