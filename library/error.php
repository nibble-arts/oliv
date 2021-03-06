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
// Error handling class
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("error.php - OLIVCore not present");


system::set('OLIVERROR',"alive");


$_error = "";
$_warning = "";
$_debug = "";

class OLIVError
{
//------------------------------------------------------------------------------
// fires message immediately  
  static public function fire($text)
  {
    echo OLIVError::renderError($text);
  }


//------------------------------------------------------------------------------
// render a text using the oliv_error id
  static public function renderError($text)
  {
    $o = "<div id='oliv_error'>";
      $o .=  $text;
    $o .=  "</div>";

    return ($o);
  }


//------------------------------------------------------------------------------
// render a text using the oliv_warning id
  static public function renderWarning()
  {
    global $_warning;

    echo "<div id='oliv_info'>";
      echo $_warning;
    echo "</div>";
  }


//------------------------------------------------------------------------------
// render a text using the oliv_debug id
  static public function renderDebug()
  {
    global $_debug;

    echo "<div id='oliv_info'>";
      echo $_debug;
    echo "</div>";
  }


//------------------------------------------------------------------------------
// writes warning to warning string
  static public function error($text)
  {
    global $_error;
    
    $time = date("Y-m-d h:m:s",time());
    $_error .= $time . ": " . $text . "<br>";
  }


  static public function warning($text)
  {
    global $_warning;
    
    $time = date("Y-m-d h:m:s",time());
    $_warning .= $time . ": " . $text . "<br>";
  }


// writes debug text to debug string
  static public function debug($text)
  {
    global $_debug;
    
    $time = date("Y-m-d h:m:s",time());
    $_debug .= $time . ": " . $text . "<br>";
  }
}

?>
