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
// Link/site route system
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("route.php - OLIVCore not present");

global $_ROUTE;


//TODO
// insert: also use normal links

class OLIVRoute extends OLIVCore
{

  public function __construct()
  {
    global $_ROUTE;

    $path = OLIV_PAGE_PATH . "content.xml";

    $_ROUTE = sessionxml_load_file($path);

    $this->route();
  }


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// ROUTER

// get link id from language coded /module/value
  public function route()
  {
    global $_argv;
    global $_ROUTE;

		$val = $this->parseUrl($_argv['url']); // extract values from url
		$_argv['val'] .= $this->parseUrl($_argv['url']); // add values to val-parameter

    // routable url found
    if ($_argv['url'])
    {
      if ($newVal = OLIVRoute::getUrl($_argv['url']))
			{
				define (OLIV_PAGE,OLIV_SITE_NAME . " " . $_argv['url']); // set page title
        $_argv['url'] = $newVal;
			}
      else
      {
//------------------------------------------------------------------------------
//TODO
// rescan route information if not found
        OLIVError::fire ("route.php::route - rescan route information");
      }
    }
// no site defined -> call OLIV_INDEX
    else
    {
//echoall("INDEX");
      $_argv['url'] = OLIV_INDEX_PAGE;
			define ('OLIV_PAGE',OLIV_SITE_NAME . " " . $this->translatePageName(OLIV_LANG,$_argv['url'])); // set page title
    }
  }


// decode friendly url
// $url ... friendly string (index.php/part1/part2...)
// $names ... optional array of names for associative array
//						if count($urlArray) > count($names), the rest is returned in the last parameter of names 
	static function decode($url,$names = array())
	{
		if ($url)
		{
			$urlArray = explode("/",cut_slash($url));

			// return assoziative array if names defined
			if (is_array($names))
			{
				$x = 0;
				$retArray = array();
				$length = count($urlArray);

				while ($x	< $length)
				{
					if (isset($names[$x]))
					{
						$rest = implode("/",$urlArray);
						$value = array_shift($urlArray);
						$retArray[$names[$x]] = $value; // insert url part
					}
					else
					{
						if ($x)
							$retArray[$names[$x-1]] = $rest; // insert rest of url
						break;
					}
					$x++;
				}
				return($retArray);
			}
			else
				return($urlArray);
		}
	}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// GENERATOR
//
// create intern link
// options array contains url information and href options
// array(array(mod,urletc.),array(id,title,etc.))
  static public function intern($text,$options="")
  {
    $url = "";
    $val = "";
    $param = "";
    $class = "";
    $lang = "";
    
    if (isset($options['url'])) $url = strtolower($options['url']);
    if (isset($options['val'])) $val = $options['val'];
    if (isset($options['param'])) $param = $options['param'];
    if (isset($options['class'])) $class = $options['class'];
    if (isset($options['lang'])) $lang = $param['lang']; // get link language

    if (!$lang) $lang = OLIV_LANG; // if no language use current
    
    $path = OLIVRoute::makeUrl($lang,$url) . "/";
    if ($val) $path .= $val . "/";
    
    $param = OLIVRoute::makeParam($param);

    if ($class) $class = "class='{$class}'";

    // return href string
    return ("<a $class href='$path' $param>$text</a>");
  }


//TODO make url basis for intern, extern, form, e.i.
//------------------------------------------------------------------------------
// create form url
  static public function url($text,$options="")
  {
    $url = strtolower($options['url']);
    $val = $options['val'];
    $lang = $param['lang']; // get link language

    if (!$lang) $lang = OLIV_LANG; // if no language use current
    
    $path = OLIVRoute::makeUrl($lang,$url) . "/";
    if ($val) $path .= $val . "/";

    // return url string
    return ($path);
  }



//------------------------------------------------------------------------------
// return parameter string for link
  static public function makeUrl($lang,$url)
  {
// create absolute url
// for correct Apache modrewrite
    $routeArray = array(OLIV_PROTOCOL . OLIV_HOST . OLIV_BASE . OLIV_SCRIPT_NAME);

    if ($lang) array_push($routeArray,$lang);
    else
      $lang = OLIV_LANG;

    $val = OLIVRoute::translatePageName($lang,$url);
    
    if ($url[strtolower($val)]) array_push($routeArray,$val);
    return (implode("/",$routeArray));
  }


//------------------------------------------------------------------------------
// return parameter string for link
  static private function makeParam($param)
  {
    $paramArray = array();

    if (is_array($param))
    {
      foreach ($param as $key => $value)
      {
        if ($key == "title") // use multilingual expression for title
          $value = OLIVText::_($value);
        array_push($paramArray,"$key='$value'");
      }
    }
    return (implode(" ",$paramArray));
  }
  

//------------------------------------------------------------------------------
// translate url to lang.pageName
  static public function translatePageName($lang,$url)
  {
    global $_ROUTE;
    return ((string)$_ROUTE->id->$url->$lang);
  }


  static public function getUrl($name)
  {
    global $_ROUTE;
    return ((string)$_ROUTE->name->$name);
  }


//------------------------------------------------------------------------------
//TODO
// parse url for subpages
// extract and returns parameters for modules
//
// get information from content.xml for this purpose
  static public function parseUrl(&$url)
  {
		global $_ROUTE;

    $retArray = array();
    $paramArray = array();
    $tempArray = array();
    
		$urlArray = explode("/",cut_slash($url));

    // search for url matches
    foreach($urlArray as $entry)
    {
      array_push($tempArray,$entry);
      $urlString = implode(".",$tempArray);

      if (!OLIVRoute::getUrl($urlString))
        array_push($paramArray,$entry); // insert parameter part
      else
        array_push($retArray,$entry); // insert url part
    }

    $url = implode("/",$retArray); // update url
    return (implode("/",$paramArray)); // return parameters
  }
}



// removes slash from start and end of string
function cut_slash($url)
{
  if (substr($url,0,1) == "/") $url = substr($url,1);
  if (strlen($url) > 0)
    if ($url[strlen($url)-1] == "/")
      $url = substr($url,0,strlen($url)-1);

  return ($url);
}
?>
