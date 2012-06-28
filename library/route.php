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

class OLIVRoute extends OLIVCore
{

  public function __construct()
  {
    global $_argv;
    global $_ROUTE;

    $path = OLIV_PAGE_PATH . "content.xml";

    $_ROUTE = sessionxml_load_file($path);

    $this->route(&$_argv);
  }


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// ROUTER

// get link id from language coded /module/value
  public function route($argv)
  {
    global $_ROUTE;

		$argv[val] = $this->parseUrl($argv[url]);

    // routable url found
    if ($argv[url])
    {
      if ($newVal = OLIVRoute::getUrl($argv[url]))
			{
				define (OLIV_PAGE,OLIV_SITE_NAME . " " . $argv[url]); // set page title
        $argv[url] = $newVal;
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
//    echoall("INDEX");
      $argv[url] = OLIV_INDEX_PAGE;
			define (OLIV_PAGE,OLIV_SITE_NAME . " " . $this->getPageName(OLIV_LANG,$argv[url])); // set page title
    }
  }


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// GENERATOR
//
// create intern link
// options array contains url information and href options
// array(array(mod,urletc.),array(id,title,etc.))
  public function intern($text,$options="")
  {
    $url = strtolower($options[url]);
    $param = $options[param];
    $class = $options["class"];
    $lang = $param["lang"]; // get link language

    if (!$lang) $lang = OLIV_LANG; // if no language use current
    
    $path = OLIVRoute::makeUrl($lang,$url) . "/";
    $param = OLIVRoute::makeParam($param);
    if ($class) $class = "class='{$class}'";

    // return href string
    return ("<a $class href='$path' $param>$text</a>");
  }


//------------------------------------------------------------------------------
// return parameter string for link
  private function makeUrl($lang,$url)
  {
// create absolute url
// for correct Apache modrewrite
    $routeArray = array(OLIV_PROTOCOL . OLIV_HOST . OLIV_BASE . OLIV_SCRIPT_NAME);

    if ($lang) array_push($routeArray,$lang);
    else
      $lang = OLIV_LANG;

    $val = OLIVRoute::getPageName($lang,$url);
    
    if ($url[strtolower(val)]) array_push($routeArray,$val);
    return (implode("/",$routeArray));
  }


//------------------------------------------------------------------------------
// return parameter string for link
  private function makeParam($param)
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
  public function getPageName($lang,$url)
  {
    global $_ROUTE;
    return ((string)$_ROUTE->id->$url->$lang);
  }


  public function getUrl($name)
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
  public function parseUrl(&$url)
  {
		global $_ROUTE;

    $retArray = array();
    $paramArray = array();
    $tempArray = array();
    
		$urlArray = explode("/",$url);

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
?>
