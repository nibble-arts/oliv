<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?PHP
//
// IG-CMS
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
    date_default_timezone_set ("Europe/Paris");
    $data = "";
    echo md5("Jitka");
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="template/default/default.css">
    <link href="images/browser.ico" type="image/x-icon" rel="shortcut icon">
  
    <form method='post' action='md5.php'>
      Passwort <input type="text" name="data" value=<?PHP echo $data; ?>>
      <input type="submit" value="MD5">

      <?PHP if (true) echo " <b>" . md5("gast"); ?></b>
      
    </form>
      
  </body>
</html>