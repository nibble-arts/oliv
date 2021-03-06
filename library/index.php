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
// load index idx xml object
  public function __construct()
  {
    global $_INDEX;

		if (!$_INDEX = sessionxml_load_file("index.idx"))
	    $_INDEX = new simpleXmlElement("<index><root/></index>");

//		echoall($_INDEX);
  }


//------------------------------------------------------------------------------
// check if index has entries
	public static function is_index()
	{
		global $_INDEX;

		if (count($_INDEX->root->children()))
			return TRUE;
		else
			return FALSE;
	}

	
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

// search the index

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

// return an xml with the links
	public static function search($string)
	{
		global $_INDEX;
		$search = new simpleXmlElement("<search/>");

// prepare string for search
		$string = strtolower(OLIVText::remove_accents($string));


// search index
		$result = OLIVIndex::_search($_INDEX->root,$string);


		if (count($result))
		{
// create search xml
			foreach ($result as $entry)
			{
				$resArray = explode(":",(string)$entry);

				$page = $resArray[0];
				$type = $resArray[1];
				$name = $resArray[2];
				$lang = $resArray[3];
				$value = $resArray[4];
				
				$newResult = new simpleXmlElement("<result><page>{$page}</page><type>{$type}</type><name>{$name}</name><lang>{$lang}</lang><value>{$value}</value></result>");

				olivxml_insert($search,$newResult,"ALL");
			}

			return $search;
		}
	}


//------------------------------------------------------------------------------
// search index for word
// return xml of matches
	private static function _search($node,$word)
	{
		if (!isset($result))
			$result = array();

		$char = substr($word,0,1);
		$restWord = substr($word,1);

		if ($char)
		{
// node found -> goto branch
			if ($node->$char)
			{
				return OLIVIndex::_search($node->$char,$restWord);
			}
		}
		else
		{
// collect all subnodes
			$retArray = array_unique($node->XPath(".//link"));

			return  $retArray;
		}
	}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

// create index

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// insert text
  public function insertText($text,$url="",$lang="")
  {
    // remove punctuation marks in text
    $text = preg_replace("![\.\,\;\:\(\)\[\]\{\}\-\_\/]!"," ",strtolower($text));
    
    // extract words
    $wordArray = explode(" ",$text);
    $wordArray = array_unique($wordArray);
    

		foreach($wordArray as $word)
		{
//	 		$word = strtolower(Normalizer::normalize($word,Normalizer::FORM_C));
			$word = strtolower($word);


//TODO replace all special characters with root
			$specialChar = array("ä","ö",);
			$replaceChar = array();

			$word = OLIVText::remove_accents($word);

//			$this->insertWord($word,$word);

			$suffArray = $this->makeSuffArray($word);

			foreach($suffArray as $suffix)
			{
				$this->insertWord($suffix,status::url() . ":" . $url . ":$lang:$word",$lang);
			}
		}

		global $_INDEX;

//echoall($_INDEX);

// write index to disk
		$path = session_path("");
		$name = "index.idx";
		
		if (file_exists($path))
		{
			$fh = fopen($path . $name,"w");
			if ($fh)
			{
				fwrite($fh,$_INDEX->asXML());
				fclose($fh);
			}
			else
				OLIVError::fire("index.php - no write permission");
		}
		else
			OLIVError::fire("index.php - directory $path don't exist");
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
	private function insertWord($word,$value,$lang="")
	{
		global $_INDEX;
//echo "<hr>";
//echoall("insert $word:");

		$this->_addWord($_INDEX->root,$word,$value,$lang);
	}


//------------------------------------------------------------------------------
// insert suffix array in suffix tree
	private function _addWord($node,$word,$value,$lang="")
	{
// create new node
		$char = substr($word,0,1);
		$restWord = substr($word,1);

// if something to insert, do it
		if ($char)
		{
			if ($node->$char)
			{
// follow node
				if (!count($node->$char->XPath("link[contains(.,'$value')]")))
				{
					$newNode = $node->$char->addChild("link",$value);
					$newNode->addAttribute("lang",$lang);
				}

				OLIVIndex::_addWord($node->$char,$restWord,$value,$lang);
			}
			else
			{
// create node
				$newNode = $node->addChild($char);

				$newLink = $newNode->addChild("link",$value);
				$newLink->addAttribute("lang",$lang);
				
				OLIVIndex::_addWord($newNode,$restWord,$value,$lang);
			}
		}
	}
}
