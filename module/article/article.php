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

if (!system::OLIVCORE()) die ("mod_article::article.php - OLIVCore not present");
if (!system::OLIVTEXT()) die ("mod_article::article.php - OLIVText not present");
if (!system::OLIVERROR()) die ("mod_article::article.php - OLIVError not present");


$_ARTICLES;


//------------------------------------------------------------------------------
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
    $this->header->path = system::OLIV_MODULE_PATH() . "article/";

		$template = OLIVModule::load_template($header);
//    $this->scan();

// load index file
//    OLIVIndex::load($this->header->path,"article.idx");

    $articleName = (string)$header;
    $langPath = $this->header->path . "language/";
    $contentPath = $this->header->path . (string)$header->attributes()->content;



// load content
  	$article = OLIVModule::load_xml($header,(string)$header->attributes()->content,"$articleName.xml");

// add source attribute recursive
		if ($article)
		{
			olivxml_addAttribute_recursive($article,'source',$this->header->path . "content/$articleName");

// load text
		$textXml = OLIVText::_load($langPath,$articleName);
    OLIVText::insert($textXml);


// get article languages
			$langXml = OLIVText::getLanguages($textXml);
			$langSelector = OLIVLang::selector($langXml);


//------------------------------------------------------------------------------
// create temporary template
// TODO load template from file
//			$template = new simpleXmlElement("<template name='article'><div id='articlelang' /></template>");


//echoall($template);
//echoall($langSelector->asXML());
// insert langselector, langtext into content
//			olivxml_insert($temp,$langSelector,'ALL');
    	$this->o .= OLIVRender::template($template,$langSelector);


//------------------------------------------------------------------------------
// render article
    	$this->o .= OLIVRender::template($article);
		}
	  else
	    $this->o .= OLIVError::renderError("article.php - content for <b>'$articleName'</b> not found");
  }
  
  
//------------------------------------------------------------------------------
// scan session for articles
// module must be loaded
  private function scan()
  {
		global $_ARTICLES;

		$_ARTICLES = array();

    $path = $this->header->path . "content/";
    if ($pageDir = sessionopendir ($this->header->path))
    {

      while ($file = readdir($pageDir))
      {
        if (is_dir($this->header->path . $file) and $file != "." and $file != "..")
        {
          // get define.xml
          if (sessionfile_exists($this->header->path . $file . "/$file.xml"))
          {
            $xml = sessionxml_load_file($this->header->path . $file . "/$file.xml");
            $_ARTICLES['$file'] = $xml;
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
