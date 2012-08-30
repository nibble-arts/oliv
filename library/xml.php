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
// extending simple_xml_functionality
//
// Version 0.1
//------------------------------------------------------------------------------



//------------------------------------------------------------------------------
// insert complex xml structure to xml object
function olivxml_insert(&$xml_to,$xml_from)
{
// insert complex structure
	if (count($xml_from->children()))
	{
    foreach ($xml_from->children() as $xml_child)
    {
      $xml_temp = $xml_to->addChild($xml_child->getName(), (string) $xml_child);
      foreach ($xml_child->attributes() as $attr_key => $attr_value)
      {
          $xml_temp->addAttribute($attr_key, $attr_value);
      }

// add recursive
			if (count($xml_child->children()))
	      olivxml_insert($xml_temp, $xml_child);
    }
	}
  else
// insert single entry
  {
    $xml_temp = $xml_to->addChild($xml_from->getName(), (string) $xml_from);
    foreach ($xml_from->attributes() as $attr_key => $attr_value)
    {
        $xml_temp->addAttribute($attr_key, $attr_value);
    }
  }
} 


//------------------------------------------------------------------------------
// change node name of simpleXmlElement
function olivxml_changeNode($node,$xml)
{
  $temp = new simpleXmlElement("<$node>" . (string)$xml . "</$node>");

  foreach ($xml->attributes() as $key => $value)
  {
      $temp->addAttribute($key, $value);
  }
  
  if (count($xml)) 
  {
    foreach($xml->children() as $entry)
    {
      olivxml_insert($temp,$entry);
    }
  }
  return $temp;
}


//------------------------------------------------------------------------------
// add attributes to all children
function olivxml_addAttribute_recursive(&$xml,$name,$value)
{
	if ($xml->children())
	{
		foreach ($xml as $entry)
		{
			$tag = $entry->getName();
			if (!$xml->$tag->attributes()->$name)
				$xml->$tag->addAttribute($name,$value);
		}
	}
}


//------------------------------------------------------------------------------
// write simpleXmlElement to file
function olivxml_writeFile($xml,$file)
{
	echoall("write " . (string)$xml . " to $file");
}


//------------------------------------------------------------------------------
// write ini array to file
function olivini_writeFile($iniArray,$path,$file,$lang)
{
	$headerString = "; creaded by oliv\n";
	$headerString .= "; timestamp " . time() . "\n";
	$headerString .= "; user " . status::OLIV_USER() . "\n";

	
// convert array to string
	$iniString = olivini_array2string($iniArray);


// create file name
	$file = "{$lang}.{$file}.ini";


// get complete path to session
	$path = (session_path($path . $file));


// write string to file
	if ($iniString)
	{
		if (!$handle = fopen($path, 'w')) { 
			OLIVError::fire("olivini_writeFile -> can't open $path to write");
      return FALSE;
    } 

// insert header
		fwrite($handle,$headerString);

    if (!fwrite($handle, $iniString)) { 
			OLIVError::fire("olivini_writeFile -> can't write to $path");
      return FALSE;
    }
    else

    fclose($handle); 

    return TRUE; 
	}

	return FALSE;
}


//------------------------------------------------------------------------------
// convert ini array to ini string
function olivini_array2string($iniArray)
{
	if (!isset($iniString))
		$iniString = "";
		
	
	foreach ($iniArray as $key => $value)
	{
// call recursion
		if (!array_key_exists('text',$value))
		{
			$iniString .= "[" . $key . "]" . "\r";

			$iniString .= olivini_array2string($value);
		}

		else
			$iniString .= $key . " ='" . $value['text'] . "'\r";
	}

	return $iniString;
}
?>
