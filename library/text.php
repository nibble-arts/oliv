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


if (!system::OLIVCORE()) die ("text.php - OLIVCore not present");


system::set("OLIVTEXT","alive");


// global static text array
$_TEXT = array();

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
  static public function _($text,$option = "")
  {
    global $_TEXT;
    $retText = "";

    if (is_array($_TEXT))
    {
			// look in root nameSpace
      $retText = OLIVText::fetchText(strtoupper($text),"",$option);
      
			if ($retText) return ($retText);

			// look in namespaces
			foreach($_TEXT as $key => $value)
			{
				if (is_array($value))
				  if (($retText = OLIVText::fetchText(strtoupper($text),strtoupper($key),$option)))
            return ($retText);
			}
      return ($text); // return text value
		}
  }





//-------------------------------------------------------------------------------------------
// fetch namespace to array
  static public function fetchNameSpace($nameSpace)
  {
    global $_TEXT;

    if ($nameSpace) return($_TEXT[strtoupper($nameSpace)]);
    return false;
  }


//-------------------------------------------------------------------------------------------
// update text file
	static public function update($updateArray,$nameSpace,$path,$file,$lang)
	{
  	$textArray = array();
  	$tempTextArray = array();

		$textArray = OLIVText::_load($textArray,$path,$file,$lang);


// loop over update entries
		if (is_array($updateArray))
		{
			foreach ($updateArray as $key=>$value)
			{
				if ($nameSpace)
				{
					$textArray[$nameSpace][$key] = array('text' => $value,'lang' => $lang);
				}
				else
					$textArray[$key] = array('text' => $value,'lang' => $lang);
			}
		}
			
// write array to file
		OLIVText::write($textArray,$path,$file,$lang);

// reload altered text
		OLIVText::load($path,$file,$lang);
	}


//-------------------------------------------------------------------------------------------
// fetch text from array
  static private function fetchText($text,$nameSpace="",$option="")
  {
  	global $_TEXT;

		switch($option)
		{
			case 'lang':
				$part = "lang";
				break;
			default:
				$part = "text";
				break;
		}

    if ($nameSpace) // look in nameSpace
    {
      if (isset($_TEXT[$nameSpace][$text][$part]))
				return ($_TEXT[$nameSpace][$text][$part]); // return string
      else
        return false;
    }

    else // look in root
    {
      if (isset($_TEXT[$text][$part]) and !is_array($_TEXT[$text])) // text found and not namespace
				return($_TEXT[$text][$part]); // return string
      else return false;
    }
  }


//-------------------------------------------------------------------------------------------
// get text id
  static public function getId($text)
  {
    global $_TEXT;

    return (strtolower(OLIVText::_scanText($text,$_TEXT)));
  }


//-------------------------------------------------------------------------------------------
// scan text array for text
// return ID
  static private function _scanText($text,$textArray)
  {
    $retKey = "";
    
    if (is_array($textArray))
    {
      foreach($textArray as $key => $value)
      {
        if (!array_key_exists('text',$value)) // recursion
        {
          $retKey = OLIVText::_scanText($text,$value);
          if ($retKey)
            break;
        }
        else
        {
          if ($value['text'] == $text)
          {
            $retKey = $key;
            break;
          }
        }
      }
      return $retKey;
    }
  }


//-------------------------------------------------------------------------------------------
// load text file from path
// to global _TEXT
  static public function load($path,$file,$default_language = "")
  {
    global $_TEXT;

		if (!$default_language)
			$default_language = status::OLIV_DEFAULT_LANG();
			
    $_TEXT = OLIVText::_load($_TEXT,$path,$file,$default_language);
  }
  

// load text from file, add to $textArray and return array
  static public function _load($textArray,$path,$file,$default_language = "")
  {
		if (!$default_language)
			$default_language = system::OLIV_DEFAULT_LANG();

    if (!is_array($textArray)) $textArray = array();
    
    $defaultText = OLIVText::loadText($default_language,$path,$file);
    $langText = OLIVText::loadText(status::lang(),$path,$file);


// insert default text
    if (is_array($defaultText))
      $textArray = array_replace_recursive($textArray,$defaultText);

// insert language text
    if (is_array($langText))
      $textArray = array_replace_recursive($textArray,$langText);

    return ($textArray);
  }
  
  
//-------------------------------------------------------------------------------------------
// write text array to ini file
	static public function write($textArray,$path,$file,$lang)
	{
		if (is_array($textArray))
		{
			olivini_writeFile($textArray,$path,$file,$lang);
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
    
    $fileName = $lang . "." . $fileName . ".ini";; // add language prefix
    $filePath = $path . $fileName;

// load if file exists
    if (sessionfile_exists($filePath))
    {
      $tempArray = sessionparse_ini_file($filePath,true);

      $langArray = OLIVText::_insertLang($tempArray,$lang);

      return ($langArray);
    }
    return (false);
  }



//-------------------------------------------------------------------------------------------
  static private function _insertLang($textArray,$lang)
  {
    $retArray = array();
    
    foreach($textArray as $key => $value)
    {
      if (is_array($value)) // recursion
        $retArray[$key] = OLIVText::_insertLang($value,$lang);

      else
        $retArray[$key] = array('lang' => $lang,'text' => $value);
    }
    return $retArray;
  }


//-------------------------------------------------------------------------------------------
// add assoziative b to a
// overwrites existing keys
/*  static public function mergeArray($a,$b)
  {
    if (!is_array($a)) $a = array();
    
    if (is_array($b))
      return (array_merge_recursive($a,$b));
  }*/



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
      			$o .= "<td class='oliv_var_name'>$key</td><td class='oliv_var_value'>$value</td>";
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

?>
