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

defined('OLIVCORE') or die ("render.php - OLIVCore not present");
defined('OLIVERROR') or die ("render.php - OLIVError not present");

class textPlugin
{
  var $editor;
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($tag,$options)
  {
    $content = $options[0];
    $value = $options[0]['template'];

    return (textRender::tagString($tag,$value,$content));
  }
}


//------------------------------------------------------------------------------
// edit render class
class textEditPlugin
{
  static public function __callStatic($tag,$options)
  {
  	global $_argv;
  	
  	$o = "";
		$command = "";
		$partName = "";

    $content = $options[0];
    $value = (string)$options[0]['template'];
		
    $tagArray = textRender::tagString($tag,$value,$content);


// extract cmd and param
    $paramArray = explode("/",cut_slash($_argv['val']));


// retranslate command
		if (count($paramArray) > 1)
		{
		  $command = OLIVText::getId($paramArray[0]);
		  $partName = $paramArray[1];
		}


//TODO get information from article for saving
//------------------------------------------------------------------------------
// call editor
    if ($partName == $value)
    {
			$editor = new textEditor;
      switch ($command)
      {
        case 'edit':
          $tagArray['value'] = $editor->open($value,OLIVText::_((string)$value));
          break;
          
        case 'save':
          $editor->saveSnippet($value);
          break;
      }
    }
		else
		{
//------------------------------------------------------------------------------
// render text
// set link to start editor
		  $urlOptions = array(
		    "url" => $_argv['url'],
		    "val" => OLIVText::_("edit") . "/$value",
		  );

		  $editUrl = OLIVRoute::url(OLIV_LANG,$urlOptions);

			$tagArray['value'] = OLIVRoute::intern($tagArray['value'],$urlOptions);
		}

		return ($tagArray);
  }
}



//------------------------------------------------------------------------------
// render tag string
class textRender
{
  static public function tagString($tag,$value,$options)
  {
		$class = "";	
    $retArray = array();


    $content = $options['template'];
// get language code of text snippet and mark field if not translated
		$lang = OLIVText::_($value,"lang");
    $ownerLang = $content->attributes()->lang;


//echoall("lang: $lang, ownerLang: $ownerLang, OLIV_LANG: " . OLIV_LANG);
// check for permissions
    if (OLIVRight::w($content) and $ownerLang and ($ownerLang != OLIV_LANG))
    {

  // mark for no translation
  		if (($lang != OLIV_LANG) and OLIVText::_((string)$value))
  			$class = "oliv_not_translated";
    }

    $retArray['startTag'] = "<$tag name='$value' class='$class'>";
    $retArray['value'] = OLIVText::_((string)$value); // textPlugin::getText($value,$header);
    $retArray['endTag'] = "</$tag>";

    return ($retArray);
  }
}


//------------------------------------------------------------------------------
// text editor class
class textEditor
{
  var $o;
	var $article;

  function __construct()
  {
		$lang = OLIVLang::family(OLIV_LANG); // lang code for tinyMCE


// load scripts for tinyMCE
    OLIVCore::loadScript("jquery-1.7.1.js",OLIV_JAVASCRIPT_PATH);
    OLIVCore::loadScript("tiny_mce.js",OLIV_JAVASCRIPT_PATH . "tiny_mce/");


// create editor
?>
		<script type="text/javascript">
			tinyMCE.init({
				mode : "textareas",
				plugins : "save,paste",
				width : "480",
				theme : "advanced",
				language : "<?PHP echo $lang; ?>",
				theme_advanced_buttons1 : "save,cancel,|,undo,redo,|,cut,copy,paste",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_toolbar_location : "bottom",
				theme_advanced_toolbar_align : "left"
			});
		</script>

<?PHP
  }
  

//------------------------------------------------------------------------------
// open editor
  function open($name,$text)
  {
    global $_argv;
    $o = "";


//TODO insert page link for positioning of scrolling to editor field
    $options = array(
      "url" => $_argv['url'],
      "val" => OLIVText::_("save") . "/$name",
    );

    $actionUrl = OLIVRoute::url(OLIV_LANG,$options);


//TODO use form method
    $o .= "<form action='$actionUrl'><textarea name='text' width='50'>";
      $o .= OLIVText::_((string)$text);
    $o .= "</textarea></form>";

    return $o;
  }
  


//------------------------------------------------------------------------------
// save text part
  function saveSnippet($value)
  {
    global $_argv;

//TODO save text in language file
		echo("textPlugin::save => ");

		olivxml_writeFile($value,$this->article['path'] . $this->article['name']);
		echoall(" in " . $this->article['lang']); 
  }
}
?>
