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

defined('OLIVCORE') or die ("render.php - OLIVCore not present");
defined('OLIVTEXT') or die ("render.php - OLIVText not present");
define ('OLIVERROR',"alive");

$_debug = "";
$_warning = "";

class OLIVError
{
//------------------------------------------------------------------------------
  public function render($text)
  {
    $o = "<div id='oliv_error'>";
      $o .=  $text;
//      OLIVText::_($text);
    $o .=  "</b></div>";

    return ($o);
  }


  public function renderWarning()
  {
    global $_warning;

    echo "<div id='oliv_info'>";
      echo $_warning;
    echo "</div>";
  }


  public function renderDebug()
  {
    global $_debug;

    echo "<div id='oliv_info'>";
      echo $_debug;
    echo "</div>";
  }


//------------------------------------------------------------------------------
// fires message immediately  
  public function fire($text)
  {
    echo OLIVError::render($text);
  }


//------------------------------------------------------------------------------
// writes warning to warning string
  public function warning($text)
  {
    global $_warning;
    
    $time = date("Y-m-d h:m:s",time());
    $_warning .= $time . ": " . $text . "<br>";
  }


// writes debug text to debug string
  public function debug($text)
  {
    global $_debug;
    
    $time = date("Y-m-d h:m:s",time());
    $_debug .= $time . ": " . $text . "<br>";
  }
}

?>
