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
// Preprocessor object
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("render.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("render.php - OLIVError not present");

class routePlugin
{
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($method,$options)
  {
  	$content = $options[0];
  	$tag = $options[1];


//------------------------------------------------------------------------------
// look for href expressions
  	$nodes = $content->XPath("//*[@href]");

// loop through all nodes
		for ($i = 0;$i < count($nodes);$i++)
		{
// if not extern link
// route
			if (!link_is_extern($href = (string)$nodes[$i]['href']))
			{
// set lang for link call
				if (!($lang = (string)$nodes[$i]['lang']))
					$lang = status::lang();

				$href = str_replace(array(";"),array("&"),$href);

				$hrefArray = explode(":",$href);

				switch($hrefArray[0])
				{
					case 'referer()':
						$nodes[$i]['href'] = status::oliv_referer();
						break;

					case 'current()':
						$nodes[$i]['href'] = OLIVRoute::url($lang,status::url(),status::val());
						
// insert additional parameters
						if (count($hrefArray) > 1)
							$nodes[$i]['href'] = substr($nodes[$i]['href'],0,-1) . "?" . $hrefArray[1];
						break;

					case 'javascript()':
						$nodes[$i]['href'] = "javascript:toolbox('" . (string)$hrefArray[1] . "')";
						break;

					default:
						$nodes[$i]['href'] = OLIVRoute::url($lang,$hrefArray[0],status::val());
						break;
				}
			}
		}

		return($content);
  }
}
?>
