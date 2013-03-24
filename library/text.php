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
if (!system::OLIVERROR()) die ("text.php - OLIVError not present");


system::set("OLIVTEXT","alive");


// global static text xml
$_TEXT;


//------------------------------------------------------------------------------

class OLIVText
{
// returns language text, defined by status::lang
// if not present, use system::oliv_default_lang
// if no text is found at all, FALSE is returned
//
// param text ... xml node containing text
//                <node>Textstring</node> = single language
//                <node>
//                  <text lang="langcode1">Textstring lang 1</text>
//                  <text lang="langcode2">Textstring lang 2</text>
//                  <text lang="...">...</text>
//								</node>
	public static function xml($text,$lang="")
	{
		if (!$lang)
			$lang = status::lang();

		if ($text)
		{
	
// multilingual text found
			if ($text->text)
			{
				$textNode = $text->XPath("../.");

				$xpath = "text[@lang='$lang']";
				$default_xpath = "text[@lang='" . system::OLIV_DEFAULT_LANG() . "']";

				$tempText = $text->XPath($xpath);
				$defaultTempText = $text->XPath($default_xpath);


// return language test
				if (count($tempText) > 0)
					return (String)$tempText[0];

// return default language
				else
				{
					if (count($defaultTempText))
						return (String)$defaultTempText[0];
				}
			}
			else

// only single language text
			{
				return (string)$text;
			}
		}
		return FALSE;
	}
	

//------------------------------------------------------------------------------
// get system text
	static public function _($text,$lang="")
	{
		$systemText = system::systemtext();
		$textXml = $systemText->XPath("//{$text}");

		return OLIVText::xml($textXml[0],$lang);
	}

//------------------------------------------------------------------------------
// return languages of text xml
//   <lang><lang1/><lang2>...</lang>
	static public function getLanguages($text)
	{
		$langs = new simpleXmlElement("<languages></languages>");
		$nodes = $text->XPath("//text/@lang");

		if ($nodes)
		{
			foreach($nodes as $entry)
			{
				$lang = (string)$entry['lang'];
				if (!$langs->$lang)
					$langs->addChild($lang);
			}

			return $langs;
		}

		return FALSE;
	}


//TODO move method to module class
//------------------------------------------------------------------------------
// write source to all text nodes
	static public function writeSource($xml,$path)
	{
		$UUID = 0;


// process permissions
		$nodes = $xml->XPath("//*[@access]");

		foreach($nodes as $entry)
		{
			$parentNode = $entry->XPath(".");
//			$parentName = $parentNode[0]->getName();
			$parentName = $entry->getName();


// if access-tag -> display/hide whole content
			if ($parentName == "access")
			{
//TODO remove all nodes
			}

// node with access attributes
// hide content of tag
			else
			{
				if (!OLIVRight::x($entry))
					unset($entry->href);

				if (!OLIVRight::r($entry))
				{
					$childArray = array();
					foreach($entry->children() as $child)// ($x = 0;$x < count($entry);$x++)
					{
						array_push($childArray,$child->getName());
					}
					foreach($childArray as $child)
					{
						unset($entry->$child);
					}
				}
			}

//TODO use edit attribute to set attribute -> source="path"
// plugin uses edit and source for editor call





		}
	}


//------------------------------------------------------------------------------
// highlight a string
// text ... multilingual original text string
// string ... lowercase text without accesnts
// overhang ... length before and after the searchstring to be displayed

// return hightlighted string or false if nothing done
	static public function highlight($text,$searchString,$class,$overhang = 0)
	{
		
// parse string without accesnts and lowercase for searchstring
		$stringArray = array();
		$textArray = explode(" ",$text);
		$retArray = array();

		$pattern = "~$searchString~";
		$string = strtolower(OLIVText::remove_accents((string)$text));

		preg_match_all($pattern,$string,$matches,PREG_OFFSET_CAPTURE);

// split the string
		$start = 0;
		$offset = 0;

		if ($matches)
		{
			if (count($matches[0]))
			{
				return $text;
//TODO solve the problem with the utf8 chars

/*				foreach ($matches[0] as $match)
				{
					$delimitor = "";
					
// insert part from previous start
					$length = $match[1] - $start;

					if ($overhang)
					{
						if ($length > $overhang)
						{
	// text longer than 2 times the overhang
	// cut away the rest
							if ($length > 2*$overhang)
							{
								array_push($stringArray,$delimitor . substr((string)$text,$start,$overhang) . "... ");

								$start = $match[1] - $overhang;
								$length = $overhang;
								$delimitor = " ...";
							}
						}
					}

					if ($length)
						array_push($stringArray,$delimitor . substr((string)$text,$start,$length));

// insert hightlighted searchstring
					$length = strlen($searchString);
					$start = $match[1];

					if ($length)
						array_push($stringArray,"{span class='$class'}" . substr((string)$text,$start,$length) . "{/span}");

					$start = $start + $length;
				}*/
			}
			else
				return FALSE;


	// rest of text longer than overhang
	// cut away the rest
/*			$length = strlen($text) - $start;
			
			if ($overhang)
			{
				if ($length > $overhang)
				{
					$length = $overhang;
					$delimitor = "...";
				}
			}

			array_push($stringArray,substr((string)$text,$start,$length) . $delimitor);

			return implode("",$stringArray);*/
		}
	}


//------------------------------------------------------------------------------
// remove diacritics from text
	static public function remove_accents($string)
	{
		$trans = array(
			'À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Ç'=>'C','È'=>'E',
			'É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N',
			'Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U',
			'Û'=>'U','Ü'=>'U','Ý'=>'Y','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a',
			'å'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i',
			'î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
			'ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y','Ā'=>'A',
			'ā'=>'a','Ă'=>'A','ă'=>'a','Ą'=>'A','ą'=>'a','Ć'=>'C','ć'=>'c','Ĉ'=>'C',
			'ĉ'=>'c','Ċ'=>'C','ċ'=>'c','Č'=>'C','č'=>'c','Ď'=>'D','ď'=>'d','Đ'=>'D',
			'đ'=>'d','Ē'=>'E','ē'=>'e','Ĕ'=>'E','ĕ'=>'e','Ė'=>'E','ė'=>'e','Ę'=>'E',
			'ę'=>'e','Ě'=>'E','ě'=>'e','Ĝ'=>'G','ĝ'=>'g','Ğ'=>'G','ğ'=>'g','Ġ'=>'G',
			'ġ'=>'g','Ģ'=>'G','ģ'=>'g','Ĥ'=>'H','ĥ'=>'h','Ħ'=>'H','ħ'=>'h','Ĩ'=>'I',
			'ĩ'=>'i','Ī'=>'I','ī'=>'i','Ĭ'=>'I','ĭ'=>'i','Į'=>'I','į'=>'i','İ'=>'I',
			'ı'=>'i','Ĵ'=>'J','ĵ'=>'j','Ķ'=>'K','ķ'=>'k','Ĺ'=>'L','ĺ'=>'l','Ļ'=>'L',
			'ļ'=>'l','Ľ'=>'L','ľ'=>'l','Ŀ'=>'L','ŀ'=>'l','Ł'=>'L','ł'=>'l','Ń'=>'N',
			'ń'=>'n','Ņ'=>'N','ņ'=>'n','Ň'=>'N','ň'=>'n','ŉ'=>'n','Ō'=>'O','ō'=>'o',
			'Ŏ'=>'O','ŏ'=>'o','Ő'=>'O','ő'=>'o','Ŕ'=>'R','ŕ'=>'r','Ŗ'=>'R','ŗ'=>'r',
			'Ř'=>'R','ř'=>'r','Ś'=>'S','ś'=>'s','Ŝ'=>'S','ŝ'=>'s','Ş'=>'S','ş'=>'s',
			'Š'=>'S','š'=>'s','Ţ'=>'T','ţ'=>'t','Ť'=>'T','ť'=>'t','Ŧ'=>'T','ŧ'=>'t',
			'Ũ'=>'U','ũ'=>'u','Ū'=>'U','ū'=>'u','Ŭ'=>'U','ŭ'=>'u','Ů'=>'U','ů'=>'u',
			'Ű'=>'U','ű'=>'u','Ų'=>'U','ų'=>'u','Ŵ'=>'W','ŵ'=>'w','Ŷ'=>'Y','ŷ'=>'y',
			'Ÿ'=>'Y','Ź'=>'Z','ź'=>'z','Ż'=>'Z','ż'=>'z','Ž'=>'Z','ž'=>'z','ƀ'=>'b',
			'Ɓ'=>'B','Ƃ'=>'B','ƃ'=>'b','Ƈ'=>'C','ƈ'=>'c','Ɗ'=>'D','Ƌ'=>'D','ƌ'=>'d',
			'Ƒ'=>'F','ƒ'=>'f','Ɠ'=>'G','Ɨ'=>'I','Ƙ'=>'K','ƙ'=>'k','ƚ'=>'l','Ɲ'=>'N',
			'ƞ'=>'n','Ɵ'=>'O','Ơ'=>'O','ơ'=>'o','Ƥ'=>'P','ƥ'=>'p','ƫ'=>'t','Ƭ'=>'T',
			'ƭ'=>'t','Ʈ'=>'T','Ư'=>'U','ư'=>'u','Ʋ'=>'V','Ƴ'=>'Y','ƴ'=>'y','Ƶ'=>'Z',
			'ƶ'=>'z','ǅ'=>'D','ǈ'=>'L','ǋ'=>'N','Ǎ'=>'A','ǎ'=>'a','Ǐ'=>'I','ǐ'=>'i',
			'Ǒ'=>'O','ǒ'=>'o','Ǔ'=>'U','ǔ'=>'u','Ǖ'=>'U','ǖ'=>'u','Ǘ'=>'U','ǘ'=>'u',
			'Ǚ'=>'U','ǚ'=>'u','Ǜ'=>'U','ǜ'=>'u','Ǟ'=>'A','ǟ'=>'a','Ǡ'=>'A','ǡ'=>'a',
			'Ǥ'=>'G','ǥ'=>'g','Ǧ'=>'G','ǧ'=>'g','Ǩ'=>'K','ǩ'=>'k','Ǫ'=>'O','ǫ'=>'o',
			'Ǭ'=>'O','ǭ'=>'o','ǰ'=>'j','ǲ'=>'D','Ǵ'=>'G','ǵ'=>'g','Ǹ'=>'N','ǹ'=>'n',
			'Ǻ'=>'A','ǻ'=>'a','Ǿ'=>'O','ǿ'=>'o','Ȁ'=>'A','ȁ'=>'a','Ȃ'=>'A','ȃ'=>'a',
			'Ȅ'=>'E','ȅ'=>'e','Ȇ'=>'E','ȇ'=>'e','Ȉ'=>'I','ȉ'=>'i','Ȋ'=>'I','ȋ'=>'i',
			'Ȍ'=>'O','ȍ'=>'o','Ȏ'=>'O','ȏ'=>'o','Ȑ'=>'R','ȑ'=>'r','Ȓ'=>'R','ȓ'=>'r',
			'Ȕ'=>'U','ȕ'=>'u','Ȗ'=>'U','ȗ'=>'u','Ș'=>'S','ș'=>'s','Ț'=>'T','ț'=>'t',
			'Ȟ'=>'H','ȟ'=>'h','Ƞ'=>'N','ȡ'=>'d','Ȥ'=>'Z','ȥ'=>'z','Ȧ'=>'A','ȧ'=>'a',
			'Ȩ'=>'E','ȩ'=>'e','Ȫ'=>'O','ȫ'=>'o','Ȭ'=>'O','ȭ'=>'o','Ȯ'=>'O','ȯ'=>'o',
			'Ȱ'=>'O','ȱ'=>'o','Ȳ'=>'Y','ȳ'=>'y','ȴ'=>'l','ȵ'=>'n','ȶ'=>'t','ȷ'=>'j',
			'Ⱥ'=>'A','Ȼ'=>'C','ȼ'=>'c','Ƚ'=>'L','Ⱦ'=>'T','ȿ'=>'s','ɀ'=>'z','Ƀ'=>'B',
			'Ʉ'=>'U','Ɇ'=>'E','ɇ'=>'e','Ɉ'=>'J','ɉ'=>'j','ɋ'=>'q','Ɍ'=>'R','ɍ'=>'r',
			'Ɏ'=>'Y','ɏ'=>'y','ɓ'=>'b','ɕ'=>'c','ɖ'=>'d','ɗ'=>'d','ɟ'=>'j','ɠ'=>'g',
			'ɦ'=>'h','ɨ'=>'i','ɫ'=>'l','ɬ'=>'l','ɭ'=>'l','ɱ'=>'m','ɲ'=>'n','ɳ'=>'n',
			'ɵ'=>'o','ɼ'=>'r','ɽ'=>'r','ɾ'=>'r','ʂ'=>'s','ʄ'=>'j','ʈ'=>'t','ʉ'=>'u',
			'ʋ'=>'v','ʐ'=>'z','ʑ'=>'z','ʝ'=>'j','ʠ'=>'q','ͣ'=>'a','ͤ'=>'e','ͥ'=>'i',
			'ͦ'=>'o','ͧ'=>'u','ͨ'=>'c','ͩ'=>'d','ͪ'=>'h','ͫ'=>'m','ͬ'=>'r','ͭ'=>'t',
			'ͮ'=>'v','ͯ'=>'x','ᵢ'=>'i','ᵣ'=>'r','ᵤ'=>'u','ᵥ'=>'v','ᵬ'=>'b','ᵭ'=>'d',
			'ᵮ'=>'f','ᵯ'=>'m','ᵰ'=>'n','ᵱ'=>'p','ᵲ'=>'r','ᵳ'=>'r','ᵴ'=>'s','ᵵ'=>'t',
			'ᵶ'=>'z','ᵻ'=>'i','ᵽ'=>'p','ᵾ'=>'u','ᶀ'=>'b','ᶁ'=>'d','ᶂ'=>'f','ᶃ'=>'g',
			'ᶄ'=>'k','ᶅ'=>'l','ᶆ'=>'m','ᶇ'=>'n','ᶈ'=>'p','ᶉ'=>'r','ᶊ'=>'s','ᶌ'=>'v',
			'ᶍ'=>'x','ᶎ'=>'z','ᶏ'=>'a','ᶑ'=>'d','ᶒ'=>'e','ᶖ'=>'i','ᶙ'=>'u','᷊'=>'r',
			'ᷗ'=>'c','ᷚ'=>'g','ᷜ'=>'k','ᷝ'=>'l','ᷠ'=>'n','ᷣ'=>'r','ᷤ'=>'s','ᷦ'=>'z',
			'Ḁ'=>'A','ḁ'=>'a','Ḃ'=>'B','ḃ'=>'b','Ḅ'=>'B','ḅ'=>'b','Ḇ'=>'B','ḇ'=>'b',
			'Ḉ'=>'C','ḉ'=>'c','Ḋ'=>'D','ḋ'=>'d','Ḍ'=>'D','ḍ'=>'d','Ḏ'=>'D','ḏ'=>'d',
			'Ḑ'=>'D','ḑ'=>'d','Ḓ'=>'D','ḓ'=>'d','Ḕ'=>'E','ḕ'=>'e','Ḗ'=>'E','ḗ'=>'e',
			'Ḙ'=>'E','ḙ'=>'e','Ḛ'=>'E','ḛ'=>'e','Ḝ'=>'E','ḝ'=>'e','Ḟ'=>'F','ḟ'=>'f',
			'Ḡ'=>'G','ḡ'=>'g','Ḣ'=>'H','ḣ'=>'h','Ḥ'=>'H','ḥ'=>'h','Ḧ'=>'H','ḧ'=>'h',
			'Ḩ'=>'H','ḩ'=>'h','Ḫ'=>'H','ḫ'=>'h','Ḭ'=>'I','ḭ'=>'i','Ḯ'=>'I','ḯ'=>'i',
			'Ḱ'=>'K','ḱ'=>'k','Ḳ'=>'K','ḳ'=>'k','Ḵ'=>'K','ḵ'=>'k','Ḷ'=>'L','ḷ'=>'l',
			'Ḹ'=>'L','ḹ'=>'l','Ḻ'=>'L','ḻ'=>'l','Ḽ'=>'L','ḽ'=>'l','Ḿ'=>'M','ḿ'=>'m',
			'Ṁ'=>'M','ṁ'=>'m','Ṃ'=>'M','ṃ'=>'m','Ṅ'=>'N','ṅ'=>'n','Ṇ'=>'N','ṇ'=>'n',
			'Ṉ'=>'N','ṉ'=>'n','Ṋ'=>'N','ṋ'=>'n','Ṍ'=>'O','ṍ'=>'o','Ṏ'=>'O','ṏ'=>'o',
			'Ṑ'=>'O','ṑ'=>'o','Ṓ'=>'O','ṓ'=>'o','Ṕ'=>'P','ṕ'=>'p','Ṗ'=>'P','ṗ'=>'p',
			'Ṙ'=>'R','ṙ'=>'r','Ṛ'=>'R','ṛ'=>'r','Ṝ'=>'R','ṝ'=>'r','Ṟ'=>'R','ṟ'=>'r',
			'Ṡ'=>'S','ṡ'=>'s','Ṣ'=>'S','ṣ'=>'s','Ṥ'=>'S','ṥ'=>'s','Ṧ'=>'S','ṧ'=>'s',
			'Ṩ'=>'S','ṩ'=>'s','Ṫ'=>'T','ṫ'=>'t','Ṭ'=>'T','ṭ'=>'t','Ṯ'=>'T','ṯ'=>'t',
			'Ṱ'=>'T','ṱ'=>'t','Ṳ'=>'U','ṳ'=>'u','Ṵ'=>'U','ṵ'=>'u','Ṷ'=>'U','ṷ'=>'u',
			'Ṹ'=>'U','ṹ'=>'u','Ṻ'=>'U','ṻ'=>'u','Ṽ'=>'V','ṽ'=>'v','Ṿ'=>'V','ṿ'=>'v',
			'Ẁ'=>'W','ẁ'=>'w','Ẃ'=>'W','ẃ'=>'w','Ẅ'=>'W','ẅ'=>'w','Ẇ'=>'W','ẇ'=>'w',
			'Ẉ'=>'W','ẉ'=>'w','Ẋ'=>'X','ẋ'=>'x','Ẍ'=>'X','ẍ'=>'x','Ẏ'=>'Y','ẏ'=>'y',
			'Ẑ'=>'Z','ẑ'=>'z','Ẓ'=>'Z','ẓ'=>'z','Ẕ'=>'Z','ẕ'=>'z','ẖ'=>'h','ẗ'=>'t',
			'ẘ'=>'w','ẙ'=>'y','ẚ'=>'a','Ạ'=>'A','ạ'=>'a','Ả'=>'A','ả'=>'a','Ấ'=>'A',
			'ấ'=>'a','Ầ'=>'A','ầ'=>'a','Ẩ'=>'A','ẩ'=>'a','Ẫ'=>'A','ẫ'=>'a','Ậ'=>'A',
			'ậ'=>'a','Ắ'=>'A','ắ'=>'a','Ằ'=>'A','ằ'=>'a','Ẳ'=>'A','ẳ'=>'a','Ẵ'=>'A',
			'ẵ'=>'a','Ặ'=>'A','ặ'=>'a','Ẹ'=>'E','ẹ'=>'e','Ẻ'=>'E','ẻ'=>'e','Ẽ'=>'E',
			'ẽ'=>'e','Ế'=>'E','ế'=>'e','Ề'=>'E','ề'=>'e','Ể'=>'E','ể'=>'e','Ễ'=>'E',
			'ễ'=>'e','Ệ'=>'E','ệ'=>'e','Ỉ'=>'I','ỉ'=>'i','Ị'=>'I','ị'=>'i','Ọ'=>'O',
			'ọ'=>'o','Ỏ'=>'O','ỏ'=>'o','Ố'=>'O','ố'=>'o','Ồ'=>'O','ồ'=>'o','Ổ'=>'O',
			'ổ'=>'o','Ỗ'=>'O','ỗ'=>'o','Ộ'=>'O','ộ'=>'o','Ớ'=>'O','ớ'=>'o','Ờ'=>'O',
			'ờ'=>'o','Ở'=>'O','ở'=>'o','Ỡ'=>'O','ỡ'=>'o','Ợ'=>'O','ợ'=>'o','Ụ'=>'U',
			'ụ'=>'u','Ủ'=>'U','ủ'=>'u','Ứ'=>'U','ứ'=>'u','Ừ'=>'U','ừ'=>'u','Ử'=>'U',
			'ử'=>'u','Ữ'=>'U','ữ'=>'u','Ự'=>'U','ự'=>'u','Ỳ'=>'Y','ỳ'=>'y','Ỵ'=>'Y',
			'ỵ'=>'y','Ỷ'=>'Y','ỷ'=>'y','Ỹ'=>'Y','ỹ'=>'y','Ỿ'=>'Y','ỿ'=>'y','ⁱ'=>'i',
			'ⁿ'=>'n','ₐ'=>'a','ₑ'=>'e','ₒ'=>'o','ₓ'=>'x','⒜'=>'a','⒝'=>'b','⒞'=>'c',
			'⒟'=>'d','⒠'=>'e','⒡'=>'f','⒢'=>'g','⒣'=>'h','⒤'=>'i','⒥'=>'j','⒦'=>'k',
			'⒧'=>'l','⒨'=>'m','⒩'=>'n','⒪'=>'o','⒫'=>'p','⒬'=>'q','⒭'=>'r','⒮'=>'s',
			'⒯'=>'t','⒰'=>'u','⒱'=>'v','⒲'=>'w','⒳'=>'x','⒴'=>'y','⒵'=>'z','Ⓐ'=>'A',
			'Ⓑ'=>'B','Ⓒ'=>'C','Ⓓ'=>'D','Ⓔ'=>'E','Ⓕ'=>'F','Ⓖ'=>'G','Ⓗ'=>'H','Ⓘ'=>'I',
			'Ⓙ'=>'J','Ⓚ'=>'K','Ⓛ'=>'L','Ⓜ'=>'M','Ⓝ'=>'N','Ⓞ'=>'O','Ⓟ'=>'P','Ⓠ'=>'Q',
			'Ⓡ'=>'R','Ⓢ'=>'S','Ⓣ'=>'T','Ⓤ'=>'U','Ⓥ'=>'V','Ⓦ'=>'W','Ⓧ'=>'X','Ⓨ'=>'Y',
			'Ⓩ'=>'Z','ⓐ'=>'a','ⓑ'=>'b','ⓒ'=>'c','ⓓ'=>'d','ⓔ'=>'e','ⓕ'=>'f','ⓖ'=>'g',
			'ⓗ'=>'h','ⓘ'=>'i','ⓙ'=>'j','ⓚ'=>'k','ⓛ'=>'l','ⓜ'=>'m','ⓝ'=>'n','ⓞ'=>'o',
			'ⓟ'=>'p','ⓠ'=>'q','ⓡ'=>'r','ⓢ'=>'s','ⓣ'=>'t','ⓤ'=>'u','ⓥ'=>'v','ⓦ'=>'w',
			'ⓧ'=>'x','ⓨ'=>'y','ⓩ'=>'z','Ⱡ'=>'L','ⱡ'=>'l','Ɫ'=>'L','Ᵽ'=>'P','Ɽ'=>'R',
			'ⱥ'=>'a','ⱦ'=>'t','Ⱨ'=>'H','ⱨ'=>'h','Ⱪ'=>'K','ⱪ'=>'k','Ⱬ'=>'Z','ⱬ'=>'z',
			'Ɱ'=>'M','ⱱ'=>'v','Ⱳ'=>'W','ⱳ'=>'w','ⱴ'=>'v','ⱸ'=>'e','ⱺ'=>'o','ⱼ'=>'j',
			'Ꝁ'=>'K','ꝁ'=>'k','Ꝃ'=>'K','ꝃ'=>'k','Ꝅ'=>'K','ꝅ'=>'k','Ꝉ'=>'L','ꝉ'=>'l',
			'Ꝋ'=>'O','ꝋ'=>'o','Ꝍ'=>'O','ꝍ'=>'o','Ꝑ'=>'P','ꝑ'=>'p','Ꝓ'=>'P','ꝓ'=>'p',
			'Ꝕ'=>'P','ꝕ'=>'p','Ꝗ'=>'Q','ꝗ'=>'q','Ꝙ'=>'Q','ꝙ'=>'q','Ꝛ'=>'R','ꝛ'=>'r',
			'Ꝟ'=>'V','ꝟ'=>'v','Ａ'=>'A','Ｂ'=>'B','Ｃ'=>'C','Ｄ'=>'D','Ｅ'=>'E','Ｆ'=>'F',
			'Ｇ'=>'G','Ｈ'=>'H','Ｉ'=>'I','Ｊ'=>'J','Ｋ'=>'K','Ｌ'=>'L','Ｍ'=>'M','Ｎ'=>'N',
			'Ｏ'=>'O','Ｐ'=>'P','Ｑ'=>'Q','Ｒ'=>'R','Ｓ'=>'S','Ｔ'=>'T','Ｕ'=>'U','Ｖ'=>'V',
			'Ｗ'=>'W','Ｘ'=>'X','Ｙ'=>'Y','Ｚ'=>'Z','ａ'=>'a','ｂ'=>'b','ｃ'=>'c','ｄ'=>'d',
			'ｅ'=>'e','ｆ'=>'f','ｇ'=>'g','ｈ'=>'h','ｉ'=>'i','ｊ'=>'j','ｋ'=>'k','ｌ'=>'l',
			'ｍ'=>'m','ｎ'=>'n','ｏ'=>'o','ｐ'=>'p','ｑ'=>'q','ｒ'=>'r','ｓ'=>'s','ｔ'=>'t',
			'ｕ'=>'u','ｖ'=>'v','ｗ'=>'w','ｘ'=>'x','ｙ'=>'y','ｚ'=>'z',);


// do transformation
		$string = strtr($string, $trans);

// remove all remaining incorrect characters
		return preg_replace('~[^a-zA-Z0-9 .,;:!?\-_]+~', '', $string);
	}


	
}

?>
