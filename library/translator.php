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


if (!system::OLIVCORE()) die ("language.php - OLIVCore not present");
if (!system::OLIVTEXT()) die ("language.php - OLIVTEXT not present");
if (!system::OLIVERROR()) die ("language.php - OLIVError not present");


class OLIVTranslator
{

	public function __construct()
	{
	}


	public function process($page)
	{
		$content = $page->structure();

		if ($content)
		{
			$lang = status::lang();
			$default_lang = system::OLIV_DEFAULT_LANG();

			$texts = $content->XPath("//*/*[text]");

	// translate all texts
			for ($i = 0;$i < count($texts);$i++)
			{
				$text = OLIVText::xml($texts[$i]);

	// set correct language in node
				if (is_array($texts))
				{
					if (array_key_exists($i,$texts))
					{
						if ($texts[$i]->text)
						{
							unset($texts[$i]->text);
							$texts[$i]->text = $text;
						}
					}
				}
			}
		}

//echoall($content);
	}
}

?>
