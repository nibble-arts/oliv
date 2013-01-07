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
// html form plugin
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("render.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("render.php - OLIVError not present");

class formPlugin
{
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($method,$options)
  {
  	$content = $options[0];
  	$tag = $options[1];


		switch($tag)
		{
			case 'form':
// get form information
				$nodes = $content->XPath("//form");


// loop through all nodes
				for ($i = 0;$i < count($nodes);$i++)
				{
					$formMethod = $nodes[$i]["action"];


// if no action -> insert correct url
					if(!$formMethod)
					{
						$nodes[$i]["action"] = OLIVRoute::makeUrl(status::lang(),status::url());
						$nodes[$i]["method"] = "post";
						$nodes[$i]["accept-charset"] = "utf-8";
					}
				}
				break;

			case 'input':
// if $xxx in value of input tag
// replace by argv::xxx() value
				$nodes = $content->XPath("//input[contains(@value,'$')]");

				foreach($nodes as $entry)
				{
					if ($entry)
					{
						$val = substr((string)$entry['value'],1);
						$entry['value'] = argv::$val();
					}
				}


// set checked value for radio TODO and checkbox
				$nodes = $content->XPath("//input[@type = 'radio']");

				foreach($nodes as $entry)
				{
					$name = (string)$entry['name'];
					$value = (string)$entry['value'];

					$arg = argv::$name();

					if($arg == $value)
					{
						$entry['checked'] = "checked";
					}
				}

				break;
		}

		return($content);
  }
}
?>
