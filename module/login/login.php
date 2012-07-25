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
// Login module
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("mod_menu::menu.php - OLIVCore not present");
defined('OLIVTEXT') or die ("mod_menu::menu.php - OLIVText not present");
defined('OLIVERROR') or die ("mod_menu::menu.php - OLIVError not present");

class login extends OLIVCore
{
	var $o; // output string

  function __construct($header)
  {
//print_r($header);
    $template = OLIVModule::load_template($header);

// use ssh for login
    global $_argv;

//		$host = "https://" . OLIV_SSH_HOST . "/" . OLIV_BASE . "index.php";
		$host = "http://" . OLIV_SSH_HOST . "/" . OLIV_BASE . "index.php";

//TODO change to form class using router
// create formular
    $this->o = "<form name='login' method='post' action='$host'>";
      $this->o .= "<table>";


        $this->o .= "<tr>";
        
// logged in
          if (OLIV_USER)
          {
            $this->o .= "<td>" . OLIVUser::getName(OLIV_USER) . "</td>";
            $this->o .= "<td><input type='submit' class='oliv_button' value='" . OLIVText::_("log_out") . "'></td>";
            
            $this->o .= "<input type='hidden' name='action' value='logout'>";
          }
// not logged in
          else
          {
            $this->o .= "<td><input type='text' name='login' size='13' value='" . OLIVText::_("login") . "'></td>";
            $this->o .= "<td><input type='password' name='password' size='13' value='" . OLIVText::_("password") . "'></td>";

            $this->o .= "<td><input src='flag' type='submit' class='oliv_button' value='" . OLIVText::_("log_in") . "'></td>";
  
            $this->o .= "<input type='hidden' name='action' value='login'>";

            if (isset($_argv['action']))
            {
              if ($_argv['action'] == "login")
              {
                $this->o .= "</tr>";
                $this->o .= "<tr>";
                if ($_argv['login'] and $_argv['password'])
                  $this->o .= "<td colspan='3'><span class='oliv_error'>" . OLIVText::_("login_incorrect") . "</span></td>";
                else
                  $this->o .= "<td colspan='3'><span class='oliv_error'>" . OLIVText::_("no_login") . "</span></td>";
              }
            }
          }
        $this->o .= "</tr>";
      $this->o .= "</table>";

      $this->o .= "<input type='hidden' name='lang' value='" . OLIV_LANG . "'>";
      $this->o .= "<input type='hidden' name='url' value='" . $_argv['url'] . "'>";
      $this->o .= "<input type='hidden' name='val' value='" . $_argv['val'] . "'>";
      
    $this->o .= "</form>";
  }
}

?>
