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
// indexing class
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("render.php - OLIVCore not present");
define ('OLIVINDEX',"alive");

$_INDEX;

class OLIVIndex
{
//------------------------------------------------------------------------------
// create index xml object
  public function __construct()
  {
    global $_INDEX;
    $_INDEX = new simpleXmlElement("<node url='' val='' value=''></node>");
  }


// load index xml
  static public function load($path,$name)
  {
    global $_INDEX;

    if (sessionfile_exists($path . $name))
    {
      $_INDEX = sessionxml_load_file($path . $name);
    }
    else
      OLIVERROR::render("index.php - index file $name not found");
  }


//------------------------------------------------------------------------------
// insert text
  public function insertText($text,$url="",$val="")
  {
    // remove punctuation marks in text
    $text = preg_replace("![\.\,\;\:\(\)\[\]\{\}\-\_\/]!"," ",strtolower($text));
    
    
    // extract words
    $wordArray = explode(" ",$text);
    $wordArray = array_unique($wordArray);


//TODO
// sort words for short tree



// remove excluded words
    $exclude = olivxml_load_file("searchexclude.xml");
    
    $textArray = array();
    foreach($wordArray as $word)
    {
    // remove punctuation marks in word
//      $word = preg_replace("![\.\,\;\:\(\)\[\]\{\}]!","",strtolower($word));

      if (!$exclude->$word->getName())
        array_push($textArray,$word);
    }


// create index
    foreach($textArray as $word)
    {
      if (strlen($word) > 2)
        $this->insert($word,$url,$val);
    }
    return ($cnt);
  }


//------------------------------------------------------------------------------
// insert value in index
  public function insert($value,$url="",$val="")
  {
    global $_INDEX;

    // insert only lowercase
    $value = strtolower($value);

    $node = $this->_find($_INDEX,$value);

    // insert if root
    if (!(string)$node->attributes()->value)
    {
      // insert value
      $node->attributes()->value = $value;
      $node->attributes()->url = $url;
      $node->attributes()->val = $val;
    }
    else
    {
      if ($value <= (string)$node->attributes()->value)
      {
        // insert left node
        if (!$node->left)
        {
          $newNode = new simpleXmlElement("<left url='$url' val='$val' value='$value' />");
          olivxml_insert($node,$newNode);
        }
      }
      else
      {
        // insert right node
        if (!$node->right)
        {
          $newNode = new simpleXmlElement("<right url='$url' val='$val' value='$value' />");
          olivxml_insert($node,$newNode);
        }
      }
    }
//print_r($_INDEX);
  }


//------------------------------------------------------------------------------
// find entry in index
  public function find($value)
  {
    // parse string for wildcards
//TODO
// find inside of word

    $startChar = $value[0];
    $endChar = $value[strlen($value)-1];

    $value = str_replace ("%","",$value); // remove wildcards

    if ($startChar == "%");
      else $startChar = "";
    if ($endChar == "%");
      else $endChar = "";

// create result xml
    $result = new simpleXmlElement ("<search></search>");
    $index = $_INDEX;
    do
    {
      $match = $this->_find($index,$value,$startChar,$endChar);
      $index = $match;

    } while ($match->left or $match->right);
  }

//------------------------------------------------------------------------------
// find entry recursive in index
  private function _find($index,$value,$startChar="",$endChar="")
  {
    if ($index)
    {
      // no value
      if (!(string)$index->attributes()->value)
        return ($index);
  
      // value found
      if ($value == (string)$index->attributes()->value)
        return ($index);
      
      // compare value
      $compString = substr((string)$index->attributes()->value,0,strlen($value));
      
      if ($value < $compString) // value smaller
      {
        if ($index->left) // left branch exists
        {
          // call left branch recursive
          return ($this->_find($index->left,$value,$startChar,$endChar));
        }
        else
          return ($index);
      }
      else
      {
        if ($index->right) // right branch exists
        {
          // call right branch recursive
          return ($this->_find($index->right,$value,$startChar,$endChar));
        }
        else
          return ($index);
      }
    }
  }
}