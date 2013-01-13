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
	public static function xml($text)
	{
//		OLIVText::getLanguages($text);

		if ($text)
		{
// multilingual text found
			if ($text->text)
			{
				$textNode = $text->XPath("../.");

				$xpath = "text[@lang='" . status::lang() . "']";
				$default_xpath = "text[@lang='" . system::OLIV_DEFAULT_LANG() . "']";

				$tempText = $text->XPath($xpath);
				$defaultTempText = $text->XPath($default_xpath);


// return language test
				if (count($tempText) > 0)
				{
					return (String)$tempText[0];
				}
// return default language
				else
				{
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


//------------------------------------------------------------------------------
// write source to all text nodes
	static public function writeSource($xml,$path)
	{
		$UUID = 0;
		$nodes = $xml->XPath("//*/text");

		foreach($nodes as $entry)
		{
			$parentNode = $entry->XPath("..");

			if (!$parentNode[0]['source'])
				$parentNode[0]['source'] = $path . "::" . $UUID++;
		}
	}
}

?>
