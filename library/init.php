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
// system environment definitions
//
// Version 0.1
//------------------------------------------------------------------------------


//DEBUG
//echoall($_SERVER[HTTPS]);

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// set document root path
$pathArray = explode("/",$_SERVER['SCRIPT_FILENAME']);

system::set('OLIV_SCRIPT_NAME',array_pop($pathArray));
system::set('OLIV_DOCUMENT_ROOT', implode("/",$pathArray) . "/");

$pathArray = explode("/",$_SERVER['SCRIPT_NAME']);

array_pop($pathArray);
if (!$pathArray[0]) array_shift($pathArray);

system::set('OLIV_BASE', implode("/",$pathArray) . "/");
// clear / if base is root directory
if (system::OLIV_BASE() == "/") system::set('OLIV_BASE',"");

system::set('OLIV_HOST', $_SERVER['HTTP_HOST'] . "/");


//------------------------------------------------------------------------------
// set http / https protocol
if (isset($_SERVER['HTTPS']))
	system::set('OLIV_PROTOCOL',"https://");
else
	system::set('OLIV_PROTOCOL',"http://");



//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// load system definitions

if (file_exists(system::OLIV_CORE_PATH() . "system.xml"))
	$coreXml = simplexml_load_file(system::OLIV_CORE_PATH() . "system.xml");
else
	die ("init.php - system.xml not found");


//------------------------------------------------------------------------------
// set system constants
if ($coreXml)
{
  if ($coreXml->system->children())
  {
    foreach($coreXml->system->children() as $key => $value)
    {
			system::set($key,(string)$value);
    }
  }
  else
    die ("init.php - no constant definitions found");


//------------------------------------------------------------------------------
// system includes
  $part = $coreXml->xpath("includes");

  if (count($part[0]->children()))
  {
    foreach($part[0]->children() as $entry)
    {
      OLIVCore::loadScript($entry, system::OLIV_INCLUDE_PATH());
    }
  }
  else
    die ("init.php - no include definitions found");


//------------------------------------------------------------------------------
// country definitions
	if (count($coreXml->country))
		system::set('country',$coreXml->country);


//------------------------------------------------------------------------------
// imagepath definitions
	if (count($coreXml->imagepath))
		system::set('imagepath',$coreXml->imagepath);
}
else
  die ("init.php - configure.xml not found");



// valid image extensions
global $_imgType;
$_imgType = $coreXml->image;


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// load session definitions

if (file_exists("session.xml"))
	$sessionXml = simplexml_load_file("session.xml");
else
	die ("init.php - session.xml not found");


//------------------------------------------------------------------------------
// set session constants
if ($sessionXml)
{
  if ($sessionXml->system->children())
  {
    foreach($sessionXml->system->children() as $key => $value)
    {
			system::set($key,(string)$value);
    }
  }
  else
    die ("init.php - no session constant definitions found");
}
else
  die ("init.php - session.xml not found");


//------------------------------------------------------------------------------
// load session user rights
global $_access;

if (system::OLIV_ACCESS_FILE() == "local")
{
  if (sessionfile_exists("access.xml"))
    $_access = sessionxml_load_file("access.xml");
  else
    die("init.php - session access.xml not found");
}
else
{
  if (olivfile_exists("access.xml"))
    $_access = olivxml_load_file("access.xml");
  else
    die("init.php - session access.xml not found");
}




//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// set page arguments


// insert GET POST messages in argv
foreach($_GET as $key => $value)
{
	argv::set($key,$value);
}

foreach($_POST as $key => $value)
{
	argv::set($key,$value);
}


// set status lang,url,val from argv
// and remove these parameters from argv
status::set('lang',argv::lang());
argv::remove('lang');

status::set('url',argv::url());
argv::remove('url');


//TODO create command and cmd-val
if (argv::val())
{
	$valArray = explode("/",argv::val());
	status::set('command',$valArray[0]);

	array_shift($valArray);
	status::set('cmdval',implode("/",$valArray));
}


//TODO val created by router
status::set('val',argv::val());
argv::remove('val');


//------------------------------------------------------------------------------
// decode friendly url to parameters without mod_rewrite
//TODO look if .htaccess exist

if (isset($_SERVER['PATH_INFO']))
  $pathInfo = OLIVRoute::decode($_SERVER['PATH_INFO'],array("lang","url"));
else
	$pathInfo = array();


// set language from friendly url
if (array_key_exists('lang',$pathInfo))
	status::set('lang',$pathInfo['lang']);

// if no language set -> use default
if (!status::lang())
	status::set('lang',system::OLIV_DEFAULT_LANG());


// set url from friendly url
if (array_key_exists('url',$pathInfo))
	status::set('url',$pathInfo['url']);


// set val from friendly url
if (array_key_exists('val',$pathInfo))
	status::set('val',$pathInfo['val']);



//------------------------------------------------------------------------------
// update clipboard


//------------------------------------------------------------------------------
// language definition => loaded from argv
//TODO
// if not in parameters
//    look for HTTP_USER_AGENT
//    set to default language



//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// from here all scrips are available

system::set('OLIVENV','alive');





//------------------------------------------------------------------------------
// display noscript message
?>
<noscript><div id="noscript">Kein Javascript</div></noscript>
<?PHP




//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// login state
//   define USER for session
if (array_key_exists('user',$_SESSION))
  $user = $_SESSION['user'];
else
  $user = $_SESSION['user'] = "";


// change user login state    
switch(argv::action())
{
  case 'login':
    if (OLIVUser::checkPassword(argv::login(),argv::password()))
    {
      $user = argv::login();

    // remove login parameters
      argv::remove('action');
      argv::remove('login');
      argv::remove('password');
    }
    break;

  case 'logout':
    $user = "";
    break;

  default:
  	break;
}


// set user status
$_SESSION['user'] = $user;
status::set('OLIV_USER',$_SESSION['user']);



// set superuser state
if (OLIVUser::superUser(status::OLIV_USER()))
  status::set('OLIV_SU',TRUE);
else
  status::set('OLIV_SU',FALSE);


//echoall(system::getAll());
//echoall(status::getAll());

//------------------------------------------------------------------------------
?>
