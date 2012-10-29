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


// global static text xml
$_TEXT;


//-------------------------------------------------------------------------------------------

class OLIVText extends OLIVCore
{

//-------------------------------------------------------------------------------------------
// return text string
  static public function _($text,$option = "")
  {
    global $_TEXT;
    $retText = "";
		$lang = "";


// parse option
		$paramArray = explode("=",$option);
		if (count($paramArray) > 1)
		{
			switch ($paramArray[0])
			{
				case 'lang':
					$lang = $paramArray[1];
					break;
			}
		}


// get text
    if (isset($_TEXT))
    {
// get text string
      $retText = OLIVText::fetchText($_TEXT,strtoupper($text),"",$option,$lang);

// return text
			if ($retText)
				return ($retText);

// nothing found -> return id
			else
	      return ($text);
		}
  }


//-------------------------------------------------------------------------------------------
// fetch namespace to array
  static public function fetchNameSpace($nameSpace)
  {
    global $_TEXT;

    if ($nameSpace) return($_TEXT->strtoupper($nameSpace));
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

  static public function fetchText($textXml,$text,$nameSpace="",$option="",$langParam="")
  {
  	if (!$langParam)
			$langParam = OLIVLang::family(status::lang());


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
					$result = $textXml->$text->xpath("text[@lang='" . $langParam . "']");
					$lang = $langParam;
					$languages = $textXml->$text;

	// if no lang found, return default lang
					if (!$result)
					{
						$result = $textXml->$text->xpath("text[@lang='" . OLIVLang::family(system::oliv_default_lang()) . "']");
						$lang = OLIVLang::family(system::oliv_default_lang());
						$languages = $textXml->$text;
					}


// return text string
// parse option string
					if (count($result))
					{

						switch (strtolower($option))
						{
// return lang code
							case 'lang':
								return $lang;
								break;

// return all languages
							case 'all':
								return $languages;
								break;
								
// return number of languages
							case 'count':
								return count($languages->children());
								break;
								
// return language list
							case 'languages':
								$langArray = array();
								
								foreach ($languages->children() as $entry)
								{
									array_push($langArray,(string)$entry->attributes()->lang);
								}
								return $langArray;
								break;
								
// return text string
							default:
								return (string)$result[0];
						}
					}
					else
						return false;
				}
// look for subtree
				else
				{
					foreach($textXml as $entry)
					{
						$recText = OLIVText::fetchText($entry,$text,$nameSpace,$option,$langParam);
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

// load xml text and insert into global xml
		OLIVText::insert(OLIVText::_load($path,$file));
  }
  

//-------------------------------------------------------------------------------------------
// insert text xml in global $_TEXT
	static public function insert($xmlText)
	{
		global $_TEXT;
		
		if ($xmlText)
		{
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
  
  
//-------------------------------------------------------------------------------------------
// get languages of text xml
	static public function getLanguages($textXml,&$langXml="")
	{
// init xml
		if (!$langXml)
			$langXml = new simpleXmlElement("<lang></lang>");
			

// loop for nodes
		if (count($textXml))
		{
			foreach($textXml as $entry)
			{
				if ($entry->text)
				{
// get languages of node
					foreach ($entry->text as $lang)
					{
						if ($lang = (string)$lang->attributes()->lang)
						{
// insert if not present
							if (!$langXml->$lang)
								$langXml->addChild($lang);
						}
					}
				}
// recursion
				else
				{
					OLIVText::getLanguages($entry,$langXml);
				}
			}
		}

		return ($langXml);
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
