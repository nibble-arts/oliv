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

// ------------------------------------------------------------------------------
// System core
//
// Version 0.1
// ------------------------------------------------------------------------------

// core initialisation completed
// set core alive
$_STATUS = array ();

// ------------------------------------------------------------------------------
class OLIVCore {
	// private $daba; // database object
	private $module; // module object
	private $plugin; // plugin object
	private $template; // template object
	private $page; // page object
	private $route; // router object
	private $processor; // registered plugins
	private $render; // renderer engine
	private $html; // html class
	public function __construct($corePath) {
		// TODO set system timezone
		// set time zone
		system::set ( 'OLIV_CORE_PATH', $corePath );
		
		date_default_timezone_set ( "Europe/Paris" );
		system::set ( "OLIVCORE", "alive" );
	}
	
	// ------------------------------------------------------------------------------
	// initialise core system
	//
	//
	// ------------------------------------------------------------------------------
	public function init($session) {
		
		// ------------------------------------------------------------------------------
		// set session
		if ($session)
			system::set ( 'OLIV_SESSION', $session . "/" );
		else
			die ( "***FATAL ERROR: init.php - no session defined" );
			
			// core path for multisession defined
		if (! system::OLIV_CORE_PATH ())
			die ( "Core path not defined" );
			
			// ------------------------------------------------------------------------------
			// ------------------------------------------------------------------------------
			// load basic system methods
		
		OLIVCore::loadScript ( "library/init.php" );
		if (! system::OLIVENV ())
			die ( "Environment not set" );
			
			// ------------------------------------------------------------------------------
			// ------------------------------------------------------------------------------
			
		// ------------------------------------------------------------------------------
			
		// load system language
		if (! system::OLIVTEXT ())
			die ( "INIT: OLIVTEXT not found" );
		
		OLIVText::load ( system::OLIV_LANGUAGE_PATH (), system::OLIV_CORE_TEXT () );
		
		// ------------------------------------------------------------------------------
		// initialice main components
		// ------------------------------------------------------------------------------
		// connect to database
		// $this->daba = new OLIVDatabase($this->coreXml->_("core.database"));
		
		// initialise router
		$this->route = new OLIVRoute ();
		
		// initialise html
		$this->html = new OLIVHtml ();
		
		// load module metadata
		$this->module = new OLIVModule ();
		
		// load plugin metadata
		$this->plugin = new OLIVPlugin ();
		
		// load site template
		$this->template = new OLIVTemplate ( system::OLIV_TEMPLATE_PATH () . system::OLIV_TEMPLATE () . "/", system::OLIV_TEMPLATE () );
		
		// initialise page
		$this->page = new OLIVPage ();
		
		// initialise preprocessor
		$this->processor = new OLIVProcessor ();
		
		// initialise renderer
		$this->render = new OLIVRender ();
		
		// ------------------------------------------------------------------------------
		// set core status
		// ------------------------------------------------------------------------------
		// extract command and value
		if (status::val ()) {
			// extract cmd and param
			$cmdArray = explode ( "/", cut_slash ( status::val () ) );
			
			// TODO define commands in system section ???
			// search for command and retranslate
			if (count ( $cmdArray )) {
				$cmd = OLIVText::getId ( $cmdArray [0] );
				status::set ( 'command', $cmd ); // save command
			}
			
			// if command found, extract command from val
			if ((count ( $cmdArray ) > 1) and $cmd) {
				array_shift ( $cmdArray ); // remove command from val
				
				status::set ( 'val', implode ( "/", $cmdArray ) ); // save val
			}
		}
		
		// ------------------------------------------------------------------------------
		// initialize clipboard
		// get content from clipboard parameter
		if ($clipboard = argv::clipboard ())
			OLIVClipboard::_ ( $clipboard );
			
			// get clipboard from cut/copy command
		$command = status::command ();
		if ($command)
			OLIVClipboard::$command ( status::val () );
	}
	
	// ------------------------------------------------------------------------------
	// system calls
	// load page
	public function loadContent() {
		$this->page->load ();
	}
	
	// start preprocessor
	public function preProcessor() {
		$this->processor->process ( $this->page, $this->template, $this->module );
	}
	
	// start render engine
	public function render() {
		$this->render->page ( $this->template, $this->page, $this->processor );
	}
	
	// display render result
	public function display() {
		echo $this->render->display ();
	}
	
	// ------------------------------------------------------------------------------
	// load script and execute
	static public function loadScript($file, $path = "") {
		$path = system::OLIV_CORE_PATH () . $path; // redirect to core root directory
		
		if (file_exists ( $path . $file )) {
			// check for filetype
			$fileInfo = pathInfo ( $file );
			
			// debug
			// print_r("load script " . $path . $file . "<br>");
			switch ($fileInfo ['extension']) {
				case 'php' :
					include_once ($path . $file);
					break;
				
				case 'js' :
					echo "<script type='text/javascript' src='{$path}{$file}'></script >";
					break;
				
				default :
					OLIVError::fire ( "core::loadScript - script {$path}{$file} unknown filetype" );
					return FALSE;
			}
			
			return $file;
		} else
			OLIVError::fire ( "core::loadScript - script {$path}{$file} not found" );
		return FALSE;
	}
}

$_STATUS = array ();

// ------------------------------------------------------------------------------
// ------------------------------------------------------------------------------
class system {
	// get status variable
	static public function __callStatic($m, $parameters) {
		return value::get ( $m, 'system' );
	}
	// return array of status values
	static public function getAll() {
		return value::getAll ( 'system' );
	}
	// set values
	static public function set($name, $val) {
		value::set ( $name, 'system', $val );
	}
	// append $name value with $val
	static public function append($name, $val) {
		value::append ( $name, 'system', $val );
	}
	// remove value
	static public function remove($name) {
		value::remove ( $name, 'system' );
	}
}

// ------------------------------------------------------------------------------
// ------------------------------------------------------------------------------
class status {
	// get status variable
	static public function __callStatic($m, $parameters) {
		return value::get ( $m, 'status' );
	}
	// return array of status values
	static public function getAll() {
		return value::getAll ( 'status' );
	}
	// set values
	static public function set($name, $val) {
		value::set ( $name, 'status', $val );
	}
	// append $name value with $val
	static public function append($name, $val) {
		value::append ( $name, 'status', $val );
	}
	// remove value
	static public function remove($name) {
		value::remove ( $name, 'status' );
	}
}

// ------------------------------------------------------------------------------
// ------------------------------------------------------------------------------
class argv {
	// get status variable
	static public function __callStatic($m, $parameters) {
		return value::get ( $m, 'argv' );
	}
	// return array of status values
	static public function getAll() {
		return value::getAll ( 'argv' );
	}
	// set values
	static public function set($name, $val) {
		value::set ( $name, 'argv', $val );
	}
	// append $name value with $val
	static public function append($name, $val) {
		value::append ( $name, 'argv', $val );
	}
	// remove value
	static public function remove($name) {
		value::remove ( $name, 'argv' );
	}
}

// ------------------------------------------------------------------------------
// ------------------------------------------------------------------------------
class value {
	
	// get value with type and name
	static public function get($name, $type) {
		global $_STATUS;
		
		// check for type
		if (array_key_exists ( strtoupper ( $type ), $_STATUS )) {
			$typeArray = $_STATUS [strtoupper ( $type )];
			
			if (array_key_exists ( strtoupper ( $name ), $typeArray ))
				return ($typeArray [strtoupper ( $name )]);
			else
				return FALSE;
		}
		
		// type not defined
		return FALSE;
	}
	
	// ------------------------------------------------------------------------------
	// return array of status values
	static public function getAll($type = "") {
		global $_STATUS;
		
		// return type part
		if ($type) {
			if (array_key_exists ( strtoupper ( $type ), $_STATUS ))
				return $_STATUS [strtoupper ( $type )];
			else
				return FALSE;
		} 		

		// return all
		else
			return $_STATUS;
	}
	
	// ------------------------------------------------------------------------------
	// set values
	static public function set($name, $type, $val) {
		global $_STATUS;
		
		$_STATUS [strtoupper ( $type )] [strtoupper ( $name )] = $val;
	}
	
	// ------------------------------------------------------------------------------
	// append $name value with $val
	// create if not exists
	static public function append($name, $type, $val) {
		global $_STATUS;
		
		if (array_key_exists ( $type, $_STATUS )) {
			$typeArray = $_STATUS [$type];
			
			if (array_key_exists ( strtoupper ( $name ), $_STATUS ))
				$_STATUS [strtoupper ( $type )] [strtoupper ( $name )] .= $val; // append value
			else
				value::set ( $name, $type, $val ); // create new entry
		}
	}
	
	// ------------------------------------------------------------------------------
	// remove value from type
	static public function remove($name, $type) {
		global $_STATUS;
		
		// type exists
		if (array_key_exists ( strtoupper ( $type ), $_STATUS )) {
			$typeArray = $_STATUS [strtoupper ( $type )];
			
			// value exists => remove entry
			if (array_key_exists ( strtoupper ( $name ), $typeArray )) {
				unset ( $_STATUS [strtoupper ( $type )] [strtoupper ( $name )] );
			}
		}
	}
}
?>