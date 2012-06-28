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



function olivxml_load_file($file)
{
//echoall(OLIV_CORE_PATH . $file);
  return (simplexml_load_file(OLIV_CORE_PATH . $file));
}


function sessionxml_load_file($file)
{
// local session directory
  return (simplexml_load_file(OLIV_SESSION_PATH . $file));

// oliv core session directory
//  return (simplexml_load_file(OLIV_CORE_PATH . OLIV_SESSION_PATH . OLIV_SESSION . $file));
}


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
   
//    simple_xml_insert($xml_temp, $xml_from);
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
?>
