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
// html class
//
// Version 0.1
//------------------------------------------------------------------------------


// core initialisation completed
// set core alive
defined('OLIVCORE') or die ("template.php - OLIVCore not present");
defined('OLIVERROR') or die ("template.php - OLIVError not present");

define ("OLIVHtml","alive");


//------------------------------------------------------------------------------
class OLIVHtml
{

  public function __construct()
  {
    $this->header();
  }

	public function header()
	{
    echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>"; // set page to utf-8
		echo "<base href='/" . OLIV_BASE . "'>"; // link base referenz
		echo "<title>" . OLIV_PAGE . "</title>"; // page title in browser
    echo "<link href='" . OLIVImage::_(OLIV_ICON) . "' type='image/x-icon' rel='shortcut icon'>"; // icon in browser
	}
}
?>
