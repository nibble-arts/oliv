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


class path extends OLIVModule
{
  function __construct($header)
  {
		$this->content = OLIVModule::load_content($header);

// insert start page link
		$homePage = OLIVRoute::translateFriendlyName(status::lang(),system::OLIV_INDEX_PAGE());
		$newNode = $this->content->addChild("path_point",$homePage);
		$newNode->addAttribute("href",system::OLIV_INDEX_PAGE());

// insert all links in hyrarchy
		$path = status::path();
		foreach ($path as $page)
		{
			$pageName = OLIVRoute::translateFriendlyName(status::lang(),$page);
			$newNode = $this->content->addChild("path_point",$pageName);
			$newNode->addAttribute("href",$page);
		}

		$this->template = OLIVModule::load_template($header);
  }
}

?>
