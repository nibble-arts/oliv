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
if (!system::OLIVCORE()) die ("html.php - OLIVCore not present");
IF (!system::OLIVERROR()) die ("html.php - OLIVError not present");


system::set("OLIVHtml","alive");


//------------------------------------------------------------------------------
class OLIVHtml
{

  public function __construct()
  {
    echo $this->header();
  }


	public function header()
	{
    $base = system::OLIV_PROTOCOL() . system::OLIV_HOST() . system::OLIV_BASE();

    $o = "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>"; // set page to utf-8
		$o .= "<base href='{$base}'>"; // link base referenz
		$o .= "<title>" . status::oliv_page() . "</title>"; // page title in browser
    $o .= "<link href='" . OLIVImage::_(system::OLIV_ICON()) . "' type='image/x-icon' rel='shortcut icon'>"; // icon in browser

		return ($o);
	}
}
?>
