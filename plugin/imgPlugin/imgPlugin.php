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

class imgPlugin
{
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($method,$options)
  {
  	$content = $options[0];
  	$tag = $options[1];


//------------------------------------------------------------------------------
// look for images
  	$nodes = $content->XPath("//img");

// loop through all nodes
		for ($i = 0;$i < count($nodes);$i++)
		{
			$imgName = (string)$nodes[$i]["src"];

// language version defined 
			$imgLang = (string)$nodes[$i]["lang"];

// get correct path to image
			$imgPath = OLIVImage::_($imgName,$imgLang);


			if($imgPath);
			{
//TODO include javaScript content menu
// include editor call <a>
				$nodes[$i]["src"] = $imgPath;
			}
		}



//------------------------------------------------------------------------------
// look for background images
  	$nodes = $content->XPath("//@background-image");


// loop through all nodes
		for ($i = 0;$i < count($nodes);$i++)
		{
			$imgName = (string)$nodes[$i];

// get correct path to image
			$imgPath = OLIVImage::_($imgName);

			if($imgPath);
			{
//TODO include javaScript content menu
// include editor call <a>
				$nodes[$i]->addAttribute("style","background-image:url(" . $imgPath . ")");
			}
		}

		return($content);
  }
}


//------------------------------------------------------------------------------
// edit render class
class imgEditPlugin
{
  static public function __callStatic($tag,$options)
  {
    $content = $options[0];
    $value = $options[0]['template'];

    return (imgEditRender::tagEditString($tag,$value,$content));

//  	return (imgPlugin::$tag($options));
  }
}


//------------------------------------------------------------------------------
// render tag string
class imgRender
{
  static public function tagString($tag,$value,$options)
  {
		$class = "";	
    $retArray = array();


//echoall($options);
    $content = $options['template'];

// get language code of text snippet and mark field if not translated
		$lang = OLIVText::_($value,"lang");
    $ownerLang = $content->attributes()->lang;

		$imgUrl = OLIVImage::_((string)$content->attributes()->src);


// set link if permission granted
		if (OLIVRight::x($content) and $url = $content->attributes()->url)
		{
			$retArray['link']['url'] = (string)$url;

			if ($urlLang = $content->attributes()->urllang)
				$retArray['link']['lang'] = (string)$urlLang;
			else
				$retArray['link']['lang'] = (string)status::lang();

			if ($urlTitle = $content->attributes()->urltitle)
				$retArray['link']['title'] = OLIVText::_((string)$urlTitle,"lang=" . $urlLang);
		}


// check for permissions to display translation mark
    if (OLIVRight::w($content) and $ownerLang and ($ownerLang != system::OLIV_LANG()))
    {

  // mark for no translation
  		if (($lang != system::OLIV_LANG()) and OLIVText::_((string)$value))
  			$class = "oliv_not_translated";
    }

		$imgUrl = OLIVImage::img($value);
		
    $retArray['startTag'] = "";
    $retArray['value'] = $imgUrl; // textPlugin::getText($value,$header);
    $retArray['endTag'] = "";

    return ($retArray);
  }
}

//------------------------------------------------------------------------------
// render tag to edit
class imgEditRender
{
	static public function tagEditString($tag,$value,$options)
	{
    $content = $options['template'];
		$source = $content->attributes()->source;

//echoall($options);
		$retArray = imgRender::tagString($tag,$value,$options);

		
// set link if permission granted && source found
		if (OLIVRight::x($content) and $source)
		{
			$retArray['link']['url'] = status::url();
			$retArray['link']['val'] = OLIVText::_("edit");
			$retArray['link']['lang'] = status::lang();
			$retArray['value'] = "edit";
		}
		

		return ($retArray);
	}
}
?>
