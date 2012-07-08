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
// Article module
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("mod_menu::menu.php - OLIVCore not present");
defined('OLIVTEXT') or die ("mod_menu::menu.php - OLIVText not present");
defined('OLIVERROR') or die ("mod_menu::menu.php - OLIVError not present");


$_ARTICLES;


class article extends OLIVCore
{
	var $o = "";
  var $path;
  var $header;
  var $editExclude;
  var $command;
  var $paramArray;
  
  var $editor;
  
  public function __construct($header)
  {
    global $_argv;
    
    $this->header = $header;
    $this->path = OLIV_MODULE_PATH . "article/";
    $this->scan();

    // load index file
    OLIVIndex::load($this->path,"article.idx");

    $articleName = (string)$header;
    $langPath = $this->path . (string)$header->script->language;
    $contentPath = $this->path . (string)$header->script->content;

    OLIVText::load($langPath,$articleName);


// parse for commands and parameters
    if ($_argv[val])
    {
  // load editor
      $this->loadScript("articleEdit.php",OLIV_MODULE_PATH . "article/");
      $this->editor = new ArticleEditor();

  // extract cmd and param
      $this->paramArray = explode("/",cut_slash($_argv[val]));

  // retranslate command
      $this->command = OLIVText::getId($this->paramArray[0]);
      array_shift($this->paramArray);
    }

// else render article

// load content
    if (sessionfile_exists($contentPath . "$articleName.xml"))
    {
      $article = sessionxml_load_file($contentPath . "$articleName.xml");
      $this->parse($article);
    }
    else
      $this->o .= OLIVError::render("article.php - content for <b>'$articleName'</b> not found");
  }
  

//------------------------------------------------------------------------------
// parse text and create output
  private function parse($text)
  {
    global $_argv;

    if ($text)
    {
      $text = $text->text;

      $owner = $text->attributes()->owner;
      $ownerLang = $text->attributes()->lang;


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// area for write, edit, admin
// if write permission and original language differtent from OLIV_LANG
      if (OLIVRight::w($text) and $ownerLang and $ownerLang != OLIV_LANG)
      {
        $flag = new simpleXmlElement("<flag><img src='flag' lang='$ownerLang' width='25px' float='right' margin_side='0.5em'/></flag>");

        $options = array(
          'url' => $_argv[url],
          'param' => array(
            "title" => "orig_lang",
            "lang" => $ownerLang,
            "val" => $_argv[val]
          )
        );
        $this->o .= OLIVRoute::intern(OLIVImage::img($flag->img),$options);

      }
      

      $options = array(
        'url' => $_argv[url],
        'param' => array(
          "title" => "edit",
          "val" => $_argv[val]
        )
      );

//      $editIcon = new simpleXmlElement("<edit><img src='oliv_edit' width='20px' float='right' margin_side='0.5em'/></edit>");
//			$this->o .= OLIVRoute::intern(OLIVImage::img($editIcon->img),$options);

//TODO
//echoall("owner: $owner");

//------------------------------------------------------------------------------
      $this->o .= $this->_parse($text);
    }
  }
  
  
// parse text part recursive
  private function _parse($text)
  {
    global $_PLUGIN;
    global $_argv;

    $indexTimeStamp = (string)$text->attributes()->index;
    $index = new OLIVIndex();

    if ($text and count($text->children()))
    {
      foreach($text->children() as $entry)
      {
        $key = (string)$entry->getName();
        $value = (string)$entry;

//------------------------------------------------------------------------------
// get render plugin if registered
        $plugin = $_PLUGIN->render->func->$key;

//print_r($key);
        if ((string)$plugin)
        {
          $pluginFunc = (string)$plugin;
          $pluginScript = (string)$plugin->attributes()->script;
//echoall("call function $pluginFunc in plugin $pluginScript.php");

          include_once (OLIV_CORE_PATH . OLIV_PLUGIN_PATH . $pluginScript . "/$pluginScript.php");

          
        }

//------------------------------------------------------------------------------
        switch($key)
        {
// call render plugins
          case img:
            $o .= OLIVImage::img($value->img);
            break;

            
          default:
// insert text in index
//            $index->insertText(OLIVText::_((string)$value),$_argv[url],$_argv[val]);


//------------------------------------------------------------------------------
// insert edit field
						$options = array(
							'url' => $_argv[url],
              'val' => OLIVText::_("edit") . "/$value",
							'param' => array(
								"title" => "edit",
							)
						);

    // call editor
            if ($this->paramArray[0] == $value)
            {
              switch ($this->command)
              {
                case edit:
                  if ($this->paramArray[0] == $value)
                    $o .= $this->editor->open($value,OLIVText::_((string)$value));
                  break;
                  
                case save:
                  $this->editor->save($value);
                  break;
              }
            }

//------------------------------------------------------------------------------
// render text
            else
            {
// output tag
              $oTemp = "<$key name='$value'>"; // start tag
                // print language string if defined
                if ($value)
                  $oTemp .= OLIVText::_((string)$value);
    
                // call recursive if children
                if (count($entry->children())) // recursive call
                  $oTemp .= $this->_parse($entry);
  
              $oTemp .= "</$key>"; // end tag
              
              $o .= OLIVRoute::intern($oTemp,$options);
            }
          break;
        }
      }
    }
    $path = OLIV_CORE_PATH . OLIV_SESSION_PATH . OLIV_SESSION . $this->path;

// write index;
/*    $xml = $index->index->asXML();
    $fHandler = fopen($path . "article.idx","w");
    fputs($fHandler,$xml);
    fclose($fHandler);*/
    
    return $o;
  }
  
  
// scan session for articles
// module must be loaded
  private function scan()
  {
		global $_ARTICLES;

		$_ARTICLES = array();

    $path = $this->path . "content/";
    if ($pageDir = sessionopendir ($this->path))
    {

      while ($file = readdir($pageDir))
      {
        if (is_dir($this->path . $file) and $file != "." and $file != "..")
        {
          // get define.xml
          if (sessionfile_exists($this->path . $file . "/$file.xml"))
          {
            $xml = sessionxml_load_file($this->path . $file . "/$file.xml");
            $_ARTICLES[$file] = $xml;
          }
        }
      }
      closedir ($pageDir);
    }
    else
      OLIVError::fire("page::scan - directory $path not found");
  }
}

?>
