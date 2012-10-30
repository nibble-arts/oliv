<?PHP session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?PHP
//
// OLIV-CMS
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
// Main routine
//
// Version 0.1
//------------------------------------------------------------------------------
?>

<html>
  <head>
    <?PHP

/*if ($sessionXml->system->children())
{
  foreach($sessionXml->system->children() as $key => $value)
  {
//  	system::set($key,(string)$value);
    define($key,$value);
  }
}
else
  die ("init.php - no session constant definitions found");*/

// init core
//			include("init.php");
			if (file_exists("session.xml"))
				$sessionXml = simplexml_load_file("session.xml");
			else
				die ("session_init.php - session.xml not found");

			$corePath = ((string)$sessionXml->system->OLIV_CORE_PATH);

// include system core
			include($corePath . "library/core.php");

      $core = new OLIVCore($corePath);
      $core->init("documentation");

// load page content
      $core->loadContent();
  
// call preprocessor
      $core->preProcessor();
    ?>
  </head>
  
  <body>
    <?PHP
// render site
    $core->render(); // render site
//echoall(value::getAll());
    $core->display(); // display site
    
//    OLIVError::renderDebug();
    ?>
  </body>
</html>