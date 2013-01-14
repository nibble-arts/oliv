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
if (!system::OLIVERROR()) die ("route.php - OLIVError not present");


$_PAGES = array();



//TODO
// insert: also use normal links

class OLIVRoute
{

  public function __construct()
  {
    $this->scan(status::lang());

// set referer
// complete url to get back
		if (array_key_exists('HTTP_REFERER',$_SERVER))
			status::set("OLIV_REFERER",$_SERVER['HTTP_REFERER']);
		else
			status::set("OLIV_REFERER",OLIVRoute::url(system::lang(),system::oliv_index_page(),status::val()));

    $this->route();
  }


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// ROUTER

// get link id from language coded /module/value
  private function route()
  {
// separate url and values
// look for url in page.xml
		$tempArray = $this->parseUrl(status::url());

		status::set('url',$tempArray['url']);
 		status::set('val',$tempArray['val']); // add values to val-parameter


// routable url found
    if (status::url())
    {
      if ($newVal = OLIVRoute::getUrl(status::url()))
			{
        status::set('url',$newVal);
			}
      else
      {
        OLIVError::fire ("route.php::route - rescan route information");
      }
    }

//------------------------------------------------------------------------------
// no site defined -> call OLIV_404_PAGE
    else
    {
    	if (system::OLIV_404_PAGE())
	      status::set('url',system::OLIV_404_PAGE());
			else
	      status::set('url',system::OLIV_INDEX_PAGE());
    }


// set page name in correct language
		status::set('OLIV_PAGE',system::OLIV_SITE_NAME() . " " . OLIVText::xml($this->getPageName(status::lang(),status::url())));
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
    $param = "";
    $class = "";
    $lang = "";
    $linkParamString = "";


// link parameters
		foreach ($options['link'] as $key => $entry)
		{
// filter url and var parameters
			switch ($key)
			{
				case 'url':
					if ($url = strtolower($entry));
					else
						return ($text);

					break;

				case 'lang':
					$lang = $entry;
					break;
					
				case 'val':
					$val = $entry;
					break;

				default:
					$linkParamString .= "$key='$entry' ";
					break;
			}
		}


// get common parameters
    if (isset($options['param'])) $param = $options['param'];
    if (isset($options['class'])) $class = $options['class'];

    if (!$lang) $lang = status::lang(); // if no language use current


//------------------------------------------------------------------------------
// create extern link
		if (link_is_extern($url))
			$path = $url;


//------------------------------------------------------------------------------
// create intern link
		else
		{
		  $path = OLIVRoute::makeUrl($lang,$url) . "/";
		  if ($val) $path .= $val . "/";
		}


// insert clipboard
		if ($clipBoard = OLIVClipboard::_())
			$clipBoard = "?clipboard=$clipBoard ";


// create parameter string
    $param = OLIVRoute::makeParam($param);


// insert class
    if ($class) $class = "class='{$class}'";

// return href string
    return ("<a $class href='{$path}{$clipBoard}' $linkParamString $param>$text</a>");
  }


//------------------------------------------------------------------------------
// get page title
	static public function getTitle($url)
	{
		global $_PAGES;

		return $_PAGES->$url->title;
	}
	

//------------------------------------------------------------------------------
//TODO make url basis for intern, extern, form, e.i.
// create form url
  static public function url($lang,$url,$val)
  {
    $url = strtolower($url);

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

// use friendly name for url
		  $val = OLIVRoute::translateFriendlyName($lang,$url);
		  
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
// get page name grom url and lang
  static public function getPageName($lang,$url)
  {
    global $_PAGES;

// return page name
		if ($name = $_PAGES->$url->name)
			return $name;

		else
// return url
			return new simpleXmlElement("<name>$url</name>");
  }


//------------------------------------------------------------------------------
// translate url to lang pageName
  static public function translateFriendlyName($lang,$url)
  {
    global $_PAGES;

		if ($name = OLIVText::xml($_PAGES->$url->friendly_name,$lang))
			return $name;
		else

// return untranslated
			return $url;
  }


//------------------------------------------------------------------------------
// translate pageName to url id
// use the $_PAGES xml
  static public function getUrl($name)
  {
    global $_PAGES;
    $id = "";

		foreach($_PAGES as $page)
		{
// check if page id is given
			$node = $page->XPath(".");
			if ($node[0]->getName() == $name)
				return $name;

// get node containing $name
			$node = $page->XPath("friendly_name/text[contains(.,'$name')]");

			if ($node)
			{
// get page id from node
				$pageNode = $node[0]->XPath("../..");
				$pageId = $pageNode[0]->getName();

				if ($pageId)
// return page id
					return $pageId;
			}
		}
// page not found
		return FALSE;
  }




//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//TODO
// parse url for subpages
// extract and returns parameters for modules
//
// get information from page.xml for this purpose
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


// retranslate to page id
      if (!OLIVRoute::getUrl($urlString))
        array_push($paramArray,$entry); // insert parameter part
      else
        array_push($retArray,$entry); // insert url part
    }

    return (array("url" => implode("/",$retArray),"val" => implode("/",$paramArray))); // return parameters
  }



//------------------------------------------------------------------------------
// return array of pages
	public static function getPages()
	{
		global $_PAGES;
		$retArray = array();


		foreach ($_PAGES as $key => $value)
		{
			$retArray[$key] = $value['text'];
		}
		return($retArray);
	}


//------------------------------------------------------------------------------
// get list of existing pages
  public function scan($lang)
  {
		global $_PAGES;
		$path = system::OLIV_PAGE_PATH() . "page.xml";
		
		if (sessionfile_exists($path))
		{
			$_PAGES = sessionxml_load_file($path);
			OLIVText::writeSource($_PAGES,$path);
		}

		else
      OLIVError::fire("page::scan - page.xml not found -> rescan");
  }
}



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


//------------------------------------------------------------------------------
// check if link is extern with http / https protocoll or mailto
function link_is_extern($url)
{
	$ret = FALSE;

	if (strstr($url,"http:")) $ret = "http:";
	if (strstr($url,"https:"))	$ret = "https:";
	if (strstr($url,"mailto:"))	$ret = "mailto:";

	return ($ret);
}
?>
