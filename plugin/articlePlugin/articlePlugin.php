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

if (!system::OLIVCORE()) die ("plugin::textPlugin.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("plugin::textPlugin.php - OLIVError not present");


class articlePlugin
{
  var $editor;
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($func,$options)
  {
  	$content = $options[0];
  	$tag = $options[1];

//TODO
// edit mode only if permission

		$nodes = $content->XPath("//*[@articlesource]");

		for ($i = 0;$i < count($nodes);$i++)
		{
// if source, make edit possible
			$tag = $nodes[$i]->getName();
			$text = (string)$nodes[$i];
			$source = (string)$nodes[$i]->attributes()->articlesource;
			$name = (string)$nodes[$i]->attributes()->articlename;

//TODO include javaScript content menu
// include editor call <a>

			$nodes[$i][0] = "";
			$nodes[$i] = $nodes[$i][0]->addChild("a",$text);

			$nodes[$i]["title"] = "call editor for '$name' in '$source.xml'";
		}

	  return($content);
  }
}


//------------------------------------------------------------------------------
// edit render class
class articleEditPlugin
{
  static public function __callStatic($tag,$options)
  {
/*
		$command = "";
		$partName = "";

    $content = $options[0];
    $template = $content['template'];
    $value = (string)$template;
		$source = $template->attributes()->source;

    $tagArray = textRender::tagString($tag,$value,$content,TRUE);


// no source for editing found
		if (!$source)
			return $tagArray;

			
//------------------------------------------------------------------------------
// get action parameters
		$command = status::command();
		$partName = status::cmdval();


//------------------------------------------------------------------------------
// save changed text and reload
		if (($partName == $value) and $command == 'save')
		{
			if (status::text());
			{
				textEditPlugin::saveSnippet(status::lang(),$partName,argv::text());
				$tagArray['value'] = OLIVText::_((string)$value);
			}
		}


//------------------------------------------------------------------------------
// open editor
    if (($partName == $value) and $command == 'edit')
    {
			$editor = new textEditor;
      $tagArray['value'] = $editor->open($value,OLIVText::_((string)$value));
    }
		else
		{
//------------------------------------------------------------------------------
// render text
// create context menu for editing
//			$context = new OLIVContext("textPlugin",$value,$value);
//			$context->draw();


// set link to call editor
			$tagArray['link'] = array(
				'url' => status::url(),
				'title' => OLIVText::_("edit_text"),
      	'val' => OLIVText::_("edit") . "/" . (string)$value,
      	'value' => $value
      );
		}

//		return ($tagArray);*/
  }



//------------------------------------------------------------------------------
// save text part
  static private function saveSnippet($lang,$partName,$text)
  {
		$partNameArray = explode("_",$partName);

		$path = system::OLIV_MODULE_PATH() . "article/language/";
		$nameSpace = $partNameArray[0] . "_" . $partNameArray[1];
		$file = strtolower($partNameArray[1]);


		OLIVText::update(array($partName => strip_tags($text)),$nameSpace,$path,$file,$lang);
  }
}



//------------------------------------------------------------------------------
// render tag string
class textRender
{
  static public function tagString($tag,$value,$options,$edit = "")
  {
		$class = "";	
    $retArray = array();


    $content = $options['template'];
// get language code of text snippet and mark field if not translated
		$lang = OLIVText::_($value,"lang");
    $ownerLang = $content->attributes()->lang;

    $tagClass = (string)$content->attributes()->class;


// get languages of text
		$langArray = OLIVText::_($value,'languages');


// add class definition
		if ($tagClass) $class .= " " . $tagClass;


// add edit class cursor
		if ($edit) $class .= " oliv_edit_cursor";


// check for permissions
    if (OLIVRight::w($content) and $ownerLang and ($ownerLang != status::lang()))
    {


// mark for no translation
  		if (($lang != status::lang()) and OLIVText::_($value))
  			$class .= " oliv_not_translated";
    }

// call markup parser
    $retArray['value'] = textRender::markup(OLIVText::_($value));


// write tags
    $retArray['startTag'] = "<$tag name='$value' class='$class'>";
    $retArray['endTag'] = "</$tag>";

    return ($retArray);
  }



//------------------------------------------------------------------------------
// parse markup
	static private function markup($text)
	{
		$search = array("{","}");
		$replace = array("<",">");
		
		$text = str_replace($search,$replace,$text);

		return $text;
	}
}






//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// text editor class
class textEditor
{
  var $o;
	var $article;

  function __construct()
  {
		$lang = OLIVLang::family(status::lang()); // lang code for tinyMCE


// load scripts for tinyMCE
    OLIVCore::loadScript("jquery-1.7.1.js",system::OLIV_JAVASCRIPT_PATH());
    OLIVCore::loadScript("tiny_mce.js",system::OLIV_JAVASCRIPT_PATH() . "tiny_mce/");


// create editor
?>
		<script type="text/javascript">
			tinyMCE.init({
				mode : "textareas",
				plugins : "save",
				width : "480",
				theme : "advanced",
				language : "<?PHP echo $lang; ?>",
				theme_advanced_buttons1 : "save",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left"
			});
		</script>

<?PHP
  }
  

//------------------------------------------------------------------------------
// open editor
  function open($name,$text)
  {
    $o = "";


//TODO insert page link for positioning of scrolling to editor field
    $options = array(
      "url" => status::url(),
      "val" => OLIVText::_("save") . "/$name",
    );

    $actionUrl = OLIVRoute::url(status::lang(),$options);


//TODO use form method
    $o .= "<form action='$actionUrl'><textarea name='text' width='50'>";
      $o .= OLIVText::_((string)$text);
    $o .= "</textarea></form>";

    return $o;
  }
}
?>