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
// Footer module
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("mod_footer::footer.php - OLIVCore not present");
if (!system::OLIVTEXT()) die ("mod_footer::footer.php - OLIVText not present");
if (!system::OLIVERROR()) die ("mod_footer::footer.php - OLIVError not present");

class footer
{
  public $o;
  
  function __construct($header)
  {
    $this->o = OLIVText::_((string)$header);
  }
}

?>
