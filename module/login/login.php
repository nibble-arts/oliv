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

if (!system::OLIVCORE()) die ("mod_login::login.php - OLIVCore not present");
if (!system::OLIVTEXT()) die ("mod_login::login.php - OLIVText not present");
if (!system::OLIVERROR()) die ("mod_login::login.php - OLIVError not present");


class login extends OLIVModule
{
  function __construct($header)
  {
//TODO use https when loggin in
// use ssh for login
//		$host = "https://" . OLIV_SSH_HOST . "/" . OLIV_BASE . "index.php";
		$host = "http://" . system::OLIV_SSH_HOST() . "/" . system::OLIV_BASE() . "index.php";


// load login content xml
		$this->content = OLIVModule::load_content($header);


// select template for logged of not logged
		if (status::OLIV_USER())
		{
			$header->param->template = "logged";
			$this->content->username = OLIVUser::getName(status::OLIV_USER());

			if (status::OLIV_SU())
			{
				$this->content->su = status::OLIV_SU();
			}
			else
			{
				$this->content->user_groups = OLIVUser::getGroupName(status::OLIV_USER());
			}
		}
		else
		{
// check if wrong login
			if (argv::action() == "login")
			{
				$header->param->template = "incorrect";
			}
		}

// load correct template
		$this->template = OLIVModule::load_template($header);

  }
}

?>
