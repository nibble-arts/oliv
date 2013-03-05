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
 		status::set('path',$tempArray['path']); // add values to val-parameter

//echoall($tempArray);
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
 // use 404 page or if not defined use index page
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
		$pages = status::pages();

		return $pages->$url->title;
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
  	$valArray = array();
  	
		if ($url)
		{
		  $routeArray = array(system::OLIV_PROTOCOL() . system::OLIV_HOST() . system::OLIV_BASE() . system::OLIV_SCRIPT_NAME());

		  if ($lang) array_push($routeArray,$lang);
		  else
		    $lang = status::lang();


// use friendly name for url
			$path = OLIVRoute::makePath($url);


// tranlsate and combine path
			foreach ($path as $entry)
			{
				array_push($valArray,OLIVRoute::translateFriendlyName($lang,$entry));
			}
		  $val = implode("/",$valArray);

		  if ($val)
		  	array_push($routeArray,$val);

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
    $pages = status::pages();

// return page name
		if ($name = $pages->$url->name)
			return $name;

		else
// if no entry => insert in _PAGES from page definition.xml
		{
			$pageInfo = OLIVRoute::updatePageXml($url);
			if ($pageInfo)
			{
				return $pageInfo->define->name;
			}
// page dont exist
			else
				return new simpleXmlElement("<name>$url</name>");
		}
  }


//------------------------------------------------------------------------------
// translate url to lang pageName
  static public function translateFriendlyName($lang,$url)
  {
    $pages = status::pages();

// return friendly name
		if ($name = OLIVText::xml($pages->$url->friendly_name,$lang))
		{
// get page path from status::pages()
//			$name = OLIVRoute::getRootPath($url);
			
			return $name;
		}
		else


// if no entry => insert in status::pages from page definition.xml
		{
			$pageInfo = OLIVRoute::updatePageXml($url);

			if ($pageInfo)
				return OLIVText::xml($pageInfo->friendly_name);

// page dont exist
			else
				return FALSE;
		}
  }


//------------------------------------------------------------------------------
// write page definition from id to page.xml
// return pageInfo
	static private function updatePageXml($url)
	{
// get page information xml
		$pageInfo = OLIVPage::getPageInfo($url);

// page exists => write pageInfo in page.xml
		if ($pageInfo)
		{
			$pageXml = sessionxml_load_file(system::OLIV_PAGE_PATH() . "page.xml");

// insert if url don't exist
			if (!$pageXml->define->$url)
			{
				olivxml_insert($pageXml->define->$url,$pageInfo->define);

// write file back to disk
				$pageXml->asXML(session_path(system::OLIV_PAGE_PATH() . "page.xml"));
			}
			return $pageInfo;
		}
	}


//------------------------------------------------------------------------------
// translate pageName to url id
// use the $_PAGES xml
  static public function getUrl($name)
  {
    $pages = status::pages();
    $id = "";
		foreach($pages->children() as $page)
		{
// check if page id is given
			if ((string)$page->getName() == $name)
				return $name;

// get node containing $name
			$node = $page->XPath("//friendly_name/text[contains(.,'$name')]");

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
// translate url id to pageName
	static public function getName($id)
	{
		$PAGES = status::pages();

		return $pages->$id->name;
	}
	

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// parse url for subpages
// extract and returns parameters for modules
//
// get information from page.xml for this purpose
  static public function parseUrl($url)
  {
    $retArray = array();
    $tempArray = array();

		$urlArray = explode("/",cut_slash($url));

// get the page
		$page = OLIVRoute::getPage($urlArray);

// get the path to the page to open
		$path = OLIVRoute::makePath($page);

// if rest, create val parameter
		for($x = 0;$x < count($path);$x++)
		{
			array_shift($urlArray);
		}

// return route information aray
    return (array("url" => $page,"val" => implode("/",$urlArray),"path" => $path)); // return parameters
  }


//------------------------------------------------------------------------------
// make path from page up to root
	static public function makePath($page,$path = array())
	{
		$oldpath = array();
		$newpath = array();
		
		$pathArray = OLIVRoute::_makePath($page,$path);
		array_pop($pathArray);
		$pathArray = array_reverse($pathArray);

		$oldpath = $pathArray;

// check if path is stored in session var
		if (array_key_exists("path",$_SESSION))
		{
			if (isset($_SESSION['path']))
				$oldpath = $_SESSION['path'];
		}
// init path
		else
			$_SESSION['path'] = $pathArray;


// check oldpath for matches
		for ($x = 0;$x < count($pathArray);$x++)
		{
			if ($x >= count($oldpath))
			{
				$_SESSION['path'] = $pathArray;
				break;
			}
			
			elseif ($pathArray[$x] != $oldpath[$x])
			{
				$_SESSION['path'] = $pathArray;
				break;
			}
		}

		return $pathArray;
	}
	

// recursice part of makePath
	static private function _makePath($page,$path)
	{
    $struct = status::pagestructure();
		$parent = "";
		$parentName = "";
		
		if ($page)
		{
// insert friendly_url of page in path array
			array_push($path,$page);


// if parent => recursion
			$node = $struct->$page->XPath("//*[@submenu = '$page']");

			if ($node)
				$parent = $node[0]->XPath("..");

			if ($parent)
				$parentName = $parent[0]->getName();

			if ($parentName)
			{
				return OLIVRoute::_makePath($parentName,$path);
			}
		}

		return $path;
	}


//------------------------------------------------------------------------------
// parse the url array to get the page to load out of the url array
// the latest entry containing a valid page id
	public static function getPage($urlArray)
	{
		$urlPart = array_pop($urlArray);

		$urlId = (string)OLIVRoute::getUrl($urlPart);

// url found => return ID
		if ($urlId)
			return $urlId;

// url not found => call recursion
		else
		{
			if (count($urlArray))
				return OLIVRoute::getPage($urlArray);

// nothing found
			else
				return FALSE;
		}
	}

	
//------------------------------------------------------------------------------
// return array of pages
	public static function getPages()
	{
		$retArray = array();
		$pages = status::pages();
		
		foreach ($pages as $key => $value)
		{
			$retArray[$key] = $value['text'];
		}
		return($retArray);
	}


//------------------------------------------------------------------------------
// get list of existing pages
  public function scan($lang)
  {
		$path = system::OLIV_PAGE_PATH() . "page.xml";
		
		if (sessionfile_exists($path))
		{
			$pageXml = sessionxml_load_file($path);
			status::set("pages",$pageXml->define);
			status::set("pagestructure",$pageXml->structure);

			OLIVText::writeSource(status::pages(),$path);
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
