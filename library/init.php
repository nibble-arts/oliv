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
// set document root path
$pathArray = explode("/",$_SERVER['SCRIPT_FILENAME']);
define (OLIV_SCRIPT_NAME, array_pop($pathArray));
define (OLIV_DOCUMENT_ROOT, implode("/",$pathArray) . "/");

$pathArray = explode("/",$_SERVER['SCRIPT_NAME']);

array_pop($pathArray);
if (!$pathArray[0]) array_shift($pathArray);

define (OLIV_BASE, implode("/",$pathArray) . "/");
define (OLIV_HOST, $_SERVER['HTTP_HOST'] . "/");



//------------------------------------------------------------------------------
// set http / https protocol
if ($_SERVER['HTTPS'])
	define (OLIV_PROTOCOL,"https://");
else
	define (OLIV_PROTOCOL,"http://");



//------------------------------------------------------------------------------
// load system definitions
//
//------------------------------------------------------------------------------
if (file_exists(OLIV_CORE_PATH . "configure.xml"))
	$this->coreXml = simplexml_load_file(OLIV_CORE_PATH . "configure.xml");
else
	die ("init.php - configure.xml not found");


//------------------------------------------------------------------------------
// create constants
if ($this->coreXml)
{
//------------------------------------------------------------------------------
// set system constants
  if ($this->coreXml->system->children())
  {
    foreach($this->coreXml->system->children() as $key => $value)
    {
      define($key,$value);
    }
  }
  else
    die ("init.php - no constant definitions found");


//------------------------------------------------------------------------------
// system includes
  $part = $this->coreXml->xpath("includes");

  if (count($part[0]->children()))
  {
    foreach($part[0]->children() as $entry)
    {
      $this->loadScript($entry, OLIV_INCLUDE_PATH);
    }
  }
  else
    die ("init.php - no include definitions found");
}
else
  die ("init.php - configure.xml not found");


//------------------------------------------------------------------------------
// valid image extensions
global $_imgType;
$_imgType = $this->coreXml->image;

//------------------------------------------------------------------------------
// set global argument array
global $_argv;
$_argv = array();


foreach($_GET as $key => $value)
{
  $_argv[$key] = $value;
}

foreach($_POST as $key => $value)
{
  $_argv[$key] = $value;
}

//echoall($_argv);










//------------------------------------------------------------------------------
// load session user rights
global $_access;

if (OLIV_ACCESS_FILE == "local")
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

//print_r($_access);


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// from here all scrips are available

define('OLIVENV','alive');

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------



//------------------------------------------------------------------------------
// login state
//   define USER for session
$user = $_SESSION["user"];


// change user login state    
switch($_argv[action])
{
  case login:
    if (OLIVUser::checkPassword($_argv[login],$_argv[password]))
    {
      $user = $_argv[login];
    }
    break;

  case logout:
    $user = "";
    break;
}

$_SESSION["user"] = $user;
define(OLIV_USER,$_SESSION["user"]);

//echoall($_SESSION);

//------------------------------------------------------------------------------
// language definition => loaded from argv
// if not in parameters
//    look for HTTP_USER_AGENT
//    set to default language




//debug
if ($_argv[lang])
  define (OLIV_LANG, $_argv[lang]);
else
  define (OLIV_LANG, OLIV_DEFAULT_LANG);



//------------------------------------------------------------------------------
// intelligent display for debug
function echoall($string)
{
//  echo "<div id='oliv_debug'>";  
  if (is_array($string))
  {
    echo count($string) . " elements";
    echoarray($string);
  }
    
  switch ($string)
  {
    case NULL:
      echo "*NULL";
      break;

    case "":
      echo "*empty";
      break;

    case FALSE:
      echo "*FALSE";
      break;

    default:
      echo $string;
      break;
  }
  echo "<br>";
//  echo "</span>";
}


// displays array in code-style
function echoarray($entry)
{
  echo "<pre>";
    print_r($entry);
  echo "</pre>";
}


?>
