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
  static public function _img($value,$options = array())
  {
    return ("<img>" . standard::getText($value,$options) . "</img>");
  }
  
  static public function _p($value,$options = array())
  {
    return ("<p>" . standard::getText($value,$options) . "</p>");
  }

  static public function _h1($value,$options = array())
  {
    return ("<h1>" . standard::getText($value,$options) . "</h1>");
  }

  static public function _h2($value,$options = array())
  {
    return ("<h2>" . standard::getText($value,$options) . "</h2>");
  }

  static public function _h3($value,$options = array())
  {
    return ("<h3>" . standard::getText($value,$options) . "</h3>");
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


// get language code of text snippet and mark field if not translated
		$lang = OLIVText::_($value,"lang");


// parse for commands and parameters
    if ($_argv['val'])
    {
  // load editor
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


//echoall($options);
    $oTemp = OLIVText::_((string)$value);



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

    $o .= OLIVRoute::intern($oTemp,$options);

    return ($o);
  }
}

?>