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

class standard
{
  var $editor;
  
//------------------------------------------------------------------------------
// render functions
  static public function __callStatic($tag,$options)
  {
    $value = $options[0];
    $header = $options[1];

    return (standard::tagString($tag,$value,$header));
  }






//------------------------------------------------------------------------------
// create tag string
  static private function tagString($tag,$value,$header)
  {
		$class = "";								

//echoall($header);
// get language code of text snippet and mark field if not translated
		$lang = OLIVText::_($value,"lang");
    $ownerLang = $header->ownerLang;


// check for permissions
    if (OLIVRight::w($header) and $ownerLang and ($ownerLang != OLIV_LANG))
    {
  // mark for no translation
  		if (($lang != OLIV_LANG) and OLIVText::_((string)$value))
  			$class = "oliv_not_translated";
    }

    $o = "<$tag name='$value' class='$class'>";
      $o .= standard::getText($value,$header);
    $o .= "</$tag>";

    return ($o);
  }


//------------------------------------------------------------------------------
// get language text
// include editor if edit mode and rights
  static private function getText($value,$header)
  {
    global $_argv;
    
    $o = "";
    $paramArray = array();

    $articleName = (string)$header;
    $path = OLIV_CORE_PATH . OLIV_SESSION_PATH . OLIV_SESSION . $header->path;
    $langPath = $header->path . (string)$header->script->language;

    $oTemp = OLIVText::_((string)$value);


// parse for commands and parameters
    if ($_argv['val'])
    {
// load editor class
      OLIVCore::loadScript("articleEdit.php",OLIV_MODULE_PATH . "article/");

			$article = array(
				"path" => $langPath,
				"name" => $articleName,
				"lang" => $_argv['lang']
			);

      $editor = new ArticleEditor($article);

  // extract cmd and param
      $paramArray = explode("/",cut_slash($_argv['val']));

  // retranslate command
      $command = OLIVText::getId($paramArray[0]);
      array_shift($paramArray);
    }




// call editor
    if (count($paramArray))
    {
      if ($paramArray[0] == $value)
      {
        switch ($command)
        {
          case 'edit':
            if ($paramArray[0] == $value)
              $o .= $editor->open($value,OLIVText::_((string)$value));
            break;
            
          case 'save':
            $editor->saveSnippet($value);
            break;
        }
      }
    }
    
		$options = array(
			'url' => $_argv['url'],
      'val' => OLIVText::_("edit") . "/$value",
			'param' => array(
				"title" => "edit",
			)
		);

// create link to open editor
    $o .= OLIVRoute::intern($oTemp,$options);

    return ($o);
  }
}

?>