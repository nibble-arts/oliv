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
// debug methods
//
// Version 0.1
//------------------------------------------------------------------------------



//------------------------------------------------------------------------------
// intelligent display for debug
function echoall($string)
{
//  echo "<div id='oliv_debug'>";  
  if (is_array($string))
  {
    echo count($string) . " elements";
    echo "<pre>" . renderall($string) . "</pre>";
  }

  elseif (is_object($string))
  {
    echo "<pre>" . renderall($string) . "</pre>";
  }

  else
  {
    switch ($string)
    {
      case 'NULL':
        echo "*NULL";
        break;
  
      case "":
        echo "*empty";
        break;
  
      case 'FALSE':
        echo "*FALSE";
        break;
  
      default:
        echo $string;
        break;
    }
  }
  echo "<br>";
//  echo "</span>";
}


// convert array to string
function renderall($string)
{
	return print_r($string,TRUE);
}
?>
