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
//$_TEXT = array();
$_TEXT;
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

// TODO change to xml
//-------------------------------------------------------------------------------------------
// return text string
  static public function _($text,$option = "")
  {
    global $_TEXT;
    $retText = "";
    $origin = FALSE;

		switch ($option)
		{
			case 'lang':
				$origin = TRUE;
				break;
		}

    if (isset($_TEXT))
    {
// get text string
      $retText = OLIVText::fetchText($_TEXT,strtoupper($text),"",$option,$origin);

// return text
			if ($retText)
				return ($retText);

// nothing found -> return id
			else
	      return ($text);
		}
  }




// TODO change to xml
//-------------------------------------------------------------------------------------------
// fetch namespace to array
  static public function fetchNameSpace($nameSpace)
  {
    global $_TEXT;

    if ($nameSpace) return($_TEXT[strtoupper($nameSpace)]);
    return false;
  }


// TODO change to xml
//-------------------------------------------------------------------------------------------
// update text file
	static public function update($updateArray,$nameSpace,$path,$file,$lang)
	{
/*  	$textArray = array();
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
		OLIVText::load($path,$file,$lang);*/
	}


//-------------------------------------------------------------------------------------------
// return language text string
// search recursive in $textXml
//
// if $nameSpace, return nameSpace xml

  static public function fetchText($textXml,$text,$nameSpace="",$origin="")
  {
		if ($text)
		{
		  if ($nameSpace) // look in nameSpace
		  {
		    if (isset($textXml->$nameSpace))
					return ($textXml->$nameSpace); // return string
		    else
		      return false;
		  }


// search for entry
		  else
		  {
// text found
		    if (isset($textXml->$text->text))
		    {
	// get lang text
					$result = $textXml->$text->xpath("text[@lang='" . OLIVLang::family(status::lang()) . "']");
					$lang = OLIVLang::family(status::lang());


	// if no lang found, return default lang
					if (!$result)
					{
						$result = $textXml->$text->xpath("text[@lang='" . OLIVLang::family(system::oliv_default_lang()) . "']");
						$lang = OLIVLang::family(system::oliv_default_lang());
					}


	// return text string
					if (count($result))
					{
						if ($origin) // return origin language
							return $lang;
						else // return text string
							return (string)$result[0];
					}
					else
						return false;
				}
// look for subtree
				else
				{
					foreach($textXml as $entry)
					{
						$recText = OLIVText::fetchText($entry,$text,$nameSpace,$origin);
						if ($recText)
							return ($recText);
					}
				}
		  }
		}
  }



// TODO change to xml
//-------------------------------------------------------------------------------------------
// get text id
//TODO extend also to default language
  static public function getId($text)
  {
    global $_TEXT;

    return (strtolower(OLIVText::_scanText($text,$_TEXT)));
  }


// TODO change to xml
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
          if (strtolower($value['text']) == strtolower($text)) // not case sensitive
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
  static public function load($path,$file)
  {
    global $_TEXT;

// initialize global xml if not done jet
		if (!isset($_TEXT)) $_TEXT = new simpleXmlElement("<TEXT></TEXT>");

// load xml text file
		$xmlText = OLIVText::_load($path,$file);

// insert text xml into global xml
		if ($xmlText)
		{
// TODO insert but overwrite existing entries
			olivxml_insert($_TEXT,$xmlText,'ALL_UNIQUE');
		}
  }
  

//-------------------------------------------------------------------------------------------
// load text from xml file
  static public function _load($path,$file)
  {
// check if xml extension and add if necessary
  	if (substr($file,strlen($file)-4) != ".xml")
  		$file .= ".xml";

		return sessionxml_load_file($path . $file);
  }
  
  
// TODO change to xml
//-------------------------------------------------------------------------------------------
// write text array to ini file
	static public function write($textArray,$path,$file,$lang)
	{
/*		if (is_array($textArray))
		{
			olivini_writeFile($textArray,$path,$file,$lang);
		}*/
	}


//-------------------------------------------------------------------------------------------
// load text array
/*  static private function loadText($lang,$path,$fileName="")
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
  }*/



//-------------------------------------------------------------------------------------------
/*  static private function _insertLang($textArray,$lang)
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
  }*/


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
