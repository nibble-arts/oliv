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
// System core
//
// Version 0.1
//------------------------------------------------------------------------------


// core initialisation completed
// set core alive
define ("OLIVCORE","alive");


//------------------------------------------------------------------------------
class OLIVCore
{
  private $daba; // database object
  private $module; // module object
  private $plugin; // module object
  private $template; // template object
  private $page; // page object
  private $route; // router object
	private $processor; // registered plugins
  private $render; // renderer engine
  private $html; // html class

  public function __construct()
  {
  }


//------------------------------------------------------------------------------
// initialise  core system
//
//
//------------------------------------------------------------------------------

  public function init($session)
  {
// set time zone
    date_default_timezone_set ("Europe/Paris");


// set session
		if ($session)
			define ('OLIV_SESSION',$session . "/");
		else
			die("***FATAL ERROR: init.php - no session defined");

// core path for multisession defined
    defined ('OLIV_CORE_PATH') or die ("Core path not defined");

// load basic system methods
    $this->loadScript("library/init.php");
    defined ('OLIVENV') or die ("Environment not set");

//------------------------------------------------------------------------------
// load system language
    defined ('OLIVTEXT') or die ("INIT: OLIVTEXT not found");
    OLIVText::load(OLIV_LANGUAGE_PATH,OLIV_CORE_TEXT);


//------------------------------------------------------------------------------
// initialice main components
//------------------------------------------------------------------------------
// connect to database
//    $this->daba = new OLIVDatabase($this->coreXml->_("core.database"));


// initialise router
    $this->route = new OLIVRoute();

// initialise html
    $this->html = new OLIVHtml();

// load module metadata
    $this->module = new OLIVModule();

// load plugin metadata
    $this->plugin = new OLIVPlugin();

// load site template
    $this->template = new OLIVTemplate(OLIV_TEMPLATE_PATH . OLIV_TEMPLATE . "/",OLIV_TEMPLATE);

// initialise page
    $this->page = new OLIVPage();

// initialise preprocessor
    $this->processor = new OLIVProcessor();
// initialise renderer
    $this->render = new OLIVRender();
  }


  // get call parameters
  public function argv()
  {
    global $_argv;
    return ($_argv);
  }


// load page
  public function loadContent()
  {
    $this->page->load();
  }

// start preprocessor
  public function preProcessor()
  {
// call preprocessor
    $this->processor->process($this->page,$this->template,$this->module);
  }


  // start render engine
  public function render()
  {
//global $_TEXT;
//echoall($_TEXT);

    $this->render->page($this->template,$this->page,$this->processor);
  }


//------------------------------------------------------------------------------
// get name of page
	public function getPageName()
	{
		return ($this->page->getName());
	}

	// get structure of page
	public function getPageContent()
	{
		return ($this->page->structure());
	}

	// render html header
	public function htmlHeader()
	{
		$this->html->header();
	}


//------------------------------------------------------------------------------
// load script and execute
  public function loadScript($file,$path="")
  {
    $path = OLIV_CORE_PATH . $path; // redirect to core root directory

    if (file_exists($path . $file))
    {
// check for filetype
      $fileInfo = pathInfo($file);
      
//debug
//print_r("load script " . $path . $file . "<br>");
      switch($fileInfo['extension'])
      {
        case 'php':
          include_once ($path . $file);
          break;

        case 'js':
          echo "<script type='text/javascript' src='{$path}{$file}'></script >";
          break;
          
        default:
          OLIVError::fire("core::loadScript - script {$path}{$file} unknown filetype");
      }
    }
    else
      OLIVError::fire("core::loadScript - script {$path}{$file} not found");
  }
}
?>
