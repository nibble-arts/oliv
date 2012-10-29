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
// make current language bigger
				$id = "oliv_lang_flag";
			
				if ($entry->getName() == status::lang())
					$id = "oliv_lang_flag_selected";


// create flag image
				$img = new simpleXmlElement("<img url='" . status::url() . "' urllang='" . $entry->getName() . "' urltitle='change_language' src='flag' id='{$id}' lang='" . $entry->getName() . "' />");

// insert image

				olivxml_insert($langSelector->articlelang,$img);
			}

			return $langSelector;
		}
	}
}

?>
