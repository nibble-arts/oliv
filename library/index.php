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

if (!system::OLIVCORE()) die ("index.php - OLIVCore not present");

system::set('OLIVINDEX',"alive");

$_INDEX;

class OLIVIndex
{
//------------------------------------------------------------------------------
// create index xml object
  public function __construct()
  {
    global $_INDEX;

//		if (!$_INDEX = sessionxml_load_file("index.xml"))
	    $_INDEX = new simpleXmlElement("<index/>");

//		echoall($_INDEX);
  }


//------------------------------------------------------------------------------
// search the index
// return an xml with the links
	public static function search($string)
	{
		global $_INDEX;
		
		$string = OLIVText::remove_accents($string);
//		echoall("search for $string");

		OLIVIndex::_search($_INDEX,$string);
	}


	private static function _search($node,$string)
	{
		$suffix = substr($string,strlen($string)-1);
		$string = substr($string,0,strlen($string));

//		echoall ($node->$suffix);
		if ($node->$suffix)
		{
			if ($node->$suffix->link)
				echoall($node->$suffix->link);

			$retVal = OLIVIndex::_search($node->suffix,$string);
		}
		else
		{
			$retVal = (string)$node;
		}

		return $retVal;
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
    

		foreach($wordArray as $word)
		{
//	 		$word = strtolower(Normalizer::normalize($word,Normalizer::FORM_C));

//TODO replace all special characters with root
			$specialChar = array("ä","ö",);
			$replaceChar = array();

			$word = OLIVText::remove_accents($word);

//			$this->insertWord($word,$word);

			$suffArray = $this->makeSuffArray($word);

			foreach($suffArray as $suffix)
			{
				$this->insertWord($suffix,$url . ":$word");
			}
		}

		global $_INDEX;

// write index to disk
		$fh = fopen("session/index.xml","w");
		fwrite($fh,$_INDEX->asXML());
		fclose($fh);
	}


//------------------------------------------------------------------------------
// make suffix array
	private function makeSuffArray($word)
	{
		$suffArray = array();

		for ($x = strlen($word)-1;$x >= 0;$x--)
		{
			array_push($suffArray,substr($word,$x));
		}

		asort($suffArray);
		
		return $suffArray;
	}


//------------------------------------------------------------------------------
// create suffix array from word
	private function insertWord($word,$value)
	{
		global $_INDEX;
//echo "<hr>";
//echoall("insert $word:");

		$this->_addWord($_INDEX,$word,$value);

	}


//------------------------------------------------------------------------------
// insert suffix array in suffix tree
	private function _addWord($node,$word,$value)
	{
	global $_INDEX;
// create new node
		$suffix = substr($word,strlen($word)-1,1);
		$word = substr($word,0,strlen($word)-1);

		if (!$node)
		{
			$node->addChild("right",$suffix);
		}
//echoall($node);


		if ($suffix)
		{
// check existing nod
//			else
//			{
	// go right
				if ($suffix > (string)$node)
				{
					if ($node->right->getName())
					{
//		echoall("goto right");
						OLIVIndex::_addWord($node->right,$word,$value);
					}
					else
					{
//		echoall("add $suffix right");
						$newNode = new simpleXmlElement("<right>$suffix</right>");
						olivxml_insert($node,$newNode);
			echoall($node);
						OLIVIndex::_addWord($node->right,$word,$value);
					}
				}

	// go left
				else
				{
					if ($node->left->getName())
					{
//		echoall("goto left");
						OLIVIndex::_addWord($node->left,$word,$value);
					}
					else
					{
//		echoall("add $suffix left");
						$newNode = new simpleXmlElement("<left>$suffix</left>");
						olivxml_insert($node,$newNode);
						OLIVIndex::_addWord($node->left,$word,$value);
					}
//				}
			}
			
/*			if (!$node->$suffix->getName())
			{
				$newNode = $node->addChild($suffix);
			}
			else
			{
				$newNode = $node->$suffix;
			}


	// call recursive
			if (strlen($word) > 0)
			{
				$this->_addWord($newNode,$word,$value);
			}
			else
			{
				$newNode->addChild('link',$value);
			}*/
		}
	}


/*
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
  }*/
}
