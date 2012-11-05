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
// Article module
//
// Version 0.1
//------------------------------------------------------------------------------


if (!system::OLIVCORE())
	die ("language.php - OLIVCore not present");


class OLIVLang
{


//------------------------------------------------------------------------------
// get the language family from a lang string
	static public function family($lang)
	{
		return (substr($lang,0,2));
	}


//------------------------------------------------------------------------------
// create language selector from lang xml definition
	static public function selector($langXml)
	{
		// create lang selector
		$langSelector = new simpleXmlElement("<article><articlelang /></article>");


		if ($langXml)
		{
			foreach($langXml as $entry)
			{
				$langCode = $entry->getName();

//echoall($langCode);
				$countryCode = OLIVLang::defaultCountry($langCode);


// make current language bigger
				$id = "oliv_lang_flag";
			
				if ($langCode == status::lang())
					$id = "oliv_lang_flag_selected";

//get string for url title language name
				$title = OLIVText::_("change_language","lang=$langCode");
				$title .= " - " . OLIVText::_($langCode,"lang=$langCode"); // full language name


// create flag image
				$img = new simpleXmlElement("<img url='" . status::url() . "' urllang='" . $langCode . "' urltitle='{$title}' src='oliv_flag' id='{$id}' lang='" . $countryCode . "' />");

// insert image

				olivxml_insert($langSelector->articlelang,$img);
			}

			return $langSelector;
		}
	}


//------------------------------------------------------------------------------
// get languages of country
	static public function languageOfCountry($country)
	{
		$countryXml = system::country();

		$country = strtoupper($country);
		return $countryXml->$country;
	}
	


//------------------------------------------------------------------------------
// get countries of language
	static public function countryOfLanguage($lang)
	{
		$countryXml = system::country();

		return $countryXml->xpath("//country/*/{$lang}");
	}


//------------------------------------------------------------------------------
// get default country for language
	static public function defaultCountry($lang)
	{
		$countryXml = system::country();

// look for default value
		$retArray = $countryXml->xpath("//country/*/{$lang}[@default='TRUE']");

// if no default, take first entry
		if (!count($retArray))
			$retArray = $countryXml->xpath("//country/*/{$lang}");


		if (count($retArray))
		{
			$parent = $retArray[0]->xpath("..");
			return $parent[0]->getName();
		}
	}
}

?>
