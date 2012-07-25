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

defined('OLIVCORE') or die ("mod_menu::menu.php - OLIVCore not present");


// makes link out of element with id
class link extends OLIVCore
{
  var $o = "";


  // header[value] contains {link|mod:module|val:page} description
  public function __construct($header)
  {
//  print_r((string)$header);
    // parse expression
    $expr = new OLIVExpression((string)$header);
    $value = $expr->getExpression();

    $options[mod] = $value[0][param][mod];
    $options[val] = $value[0][param][val];
    $options[id] = $header->area;

    $this->o = OLIVRoute::intern("",$options); // id='$area'></a>";*/
  }
}

