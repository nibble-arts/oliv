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
// Administration module
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("mod_menu::menu.php - OLIVCore not present");
defined('OLIVTEXT') or die ("mod_menu::menu.php - OLIVText not present");
defined('OLIVERROR') or die ("mod_menu::menu.php - OLIVError not present");


class admin extends OLIVCore
{
	var $o = "";

  function __construct($header)
  {
    $template = OLIVModule::load_template($header);

		if (OLIV_CONTENT_EDIT)
	    $this->o = $this->displayAdmin($template);
  }
  

	private function displayAdmin($template)
	{
		$link = array("link" => array(
				"url" => OLIV_PAGE,
				"val" => "save"
			)
		);

		$ok = OLIVRoute::link("OK",$link);

		return $ok;
	} 
}

?>
