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

if (!system::OLIVCORE()) die ("route.php - OLIVCore not present");


$_PAGES = array();



//TODO
// insert: also use normal links

class OLIVRoute
{

  public function __construct()
  {
    $this->scan(status::lang());
    $this->route();
  }


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// ROUTER

// get link id from language coded /module/value
  private function route()
  {

		$tempArray = $this->parseUrl(status::url()); // extract values from url

		status::set('url',$tempArray['url']);
 		status::append('val',$tempArray['val']); // add values to val-parameter


// routable url found
    if (status::url())
    {
      if ($newVal = OLIVRoute::getUrl(status::url()))
			{
				status::set('OLIV_PAGE',system::OLIV_SITE_NAME() . " " . status::url()); // set page title
        status::set('url',$newVal);
			}
      else
      {
        OLIVError::fire ("route.php::route - rescan route information");
      }
    }
// no site defined -> call OLIV_INDEX
    else
    {
//echoall("INDEX");
      status::set('url',system::OLIV_INDEX_PAGE());
			status::set('OLIV_PAGE',system::OLIV_SITE_NAME() . " " . $this->translatePageName(status::lang(),status::url())); // set page title
    }
  }


//------------------------------------------------------------------------------
// decode friendly url
// $url ... friendly string (index.php/part1/part2...)
// $names ... optional array of names for associative array
//						if count($urlArray) > count($names), the rest is returned in the last parameter of names 
	public static function decode($url,$names = array())
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


//------------------------------------------------------------------------------
// create intern link
// options array contains url information and href options
// array(array(mod,urletc.),array(id,title,etc.))
  static public function link($text,$options = array())
  {
    $url = "";
    $val = "";
    $title = "";
    $target = "";
    $param = "";
    $class = "";
    $lang = "";


//echoall($options['link']);
// link parameters
    if (isset($options['link']['url'])) $url = strtolower($options['link']['url']);
		else
		{
// return without link
	    return ($text);
		}


// get link parameters
    if (isset($options['link']['val'])) $val = $options['link']['val'];
    if (isset($options['link']['title'])) $title = $options['link']['title'];
    if (isset($options['link']['target'])) $target = $options['link']['target'];


// get common parameters
    if (isset($options['param'])) $param = $options['param'];
    if (isset($options['class'])) $class = $options['class'];
    if (isset($options['lang'])) $lang = $param['lang']; // get link language

    if (!$lang) $lang = status::lang(); // if no language use current


//------------------------------------------------------------------------------
// create extern link
		if (substr($url,0,7) == "http://" or substr($url,0,8) == "https://")
			$path = $url;


//------------------------------------------------------------------------------
// create intern link
		else
		{
		  $path = OLIVRoute::makeUrl($lang,$url) . "/";
		  if ($val) $path .= $val . "/";
		}

// create parameter string
    $param = OLIVRoute::makeParam($param);


// insert class
    if ($class) $class = "class='{$class}'";


    // return href string
    return ("<a $class href='$path' title='$title' target='$target' $param>$text</a>");
  }


//------------------------------------------------------------------------------
// get page title
	static public function translateTitle($url)
	{
		global $_PAGES;

		if (array_key_exists($url,$_PAGES)) // url defined
			if (array_key_exists('text',$_PAGES[$url])) // text passage found
			{
				return ($_PAGES[$url]['text']['TITLE']['text']);
			}
	}
	

//------------------------------------------------------------------------------
//TODO make url basis for intern, extern, form, e.i.
// create form url
  static public function url($text,$options="")
  {
    $url = "";
    $val = "";
    $lang = "";
    
    if (array_key_exists('url',$options)) $url = strtolower($options['url']);
    if (array_key_exists('val',$options)) $val = $options['val'];
    if (array_key_exists('lang',$options)) $lang = $options['lang']; // get link language

    if (!$lang) $lang = status::lang(); // if no language use current
    
    $path = OLIVRoute::makeUrl($lang,$url) . "/";
    if ($val) $path .= $val . "/";

    // return url string
    return ($path);
  }



//------------------------------------------------------------------------------
// return parameter string for link
  static public function makeUrl($lang,$url)
  {
		if ($url)
		{
		  $routeArray = array(system::OLIV_PROTOCOL() . system::OLIV_HOST() . system::OLIV_BASE() . system::OLIV_SCRIPT_NAME());

		  if ($lang) array_push($routeArray,$lang);
		  else
		    $lang = status::lang();

		  $val = OLIVRoute::translatePageName($lang,$url);
		  
		  if ($url[strtolower($val)]) array_push($routeArray,$val);
		  return (implode("/",$routeArray));
		}
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
    global $_PAGES;

		if (array_key_exists($url,$_PAGES))
	    return ($_PAGES[$url]['text']['FRIENDLY_NAME']['text']);
		else
// return untranslated
			return $url;
  }


//------------------------------------------------------------------------------
// translate pageName to url id
  static public function getUrl($name)
  {
    global $_PAGES;


    foreach($_PAGES as $key => $entry)
    {
      if(array_key_exists("FRIENDLY_NAME",$entry['text']))
      {
        $friendlyName = $entry['text']['FRIENDLY_NAME']['text'];

        if ($friendlyName == $name)
        {
          return ($entry['text']['ID']['text']);
        }
      }
    }
  }


//------------------------------------------------------------------------------
//TODO
// parse url for subpages
// extract and returns parameters for modules
//
// get information from content.xml for this purpose
  static public function parseUrl($url)
  {
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

    return (array("url" => implode("/",$retArray),"val" => implode("/",$paramArray))); // return parameters
  }


//------------------------------------------------------------------------------
// get list of existing pages
  public function scan($lang)
  {
		global $_PAGES;


    $path = system::OLIV_PAGE_PATH();
    if ($pageDir = sessionopendir ($path))
    {
      while ($file = readdir($pageDir))
      {
        if (sessionis_dir($path . $file) and $file != "." and $file != "..")
        {
          // get define.xml
          if (sessionfile_exists($path . $file . "/$file.xml"))
          {
            $xml = sessionxml_load_file($path . $file . "/$file.xml");
            $pageText = OLIVText::_load("",$path . "$file/language/",$file);

            $_PAGES[$file]['define'] = $xml;
            $_PAGES[$file]['text'] = $pageText['PAGE'];
          }
        }
      }
      closedir ($pageDir);
    }
    else
      OLIVError::fire("page::scan - directory $path not found");
  }}



//------------------------------------------------------------------------------
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
