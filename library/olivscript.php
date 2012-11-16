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
// script language
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("page.php - OLIVCore not present");

$_OSCODE;
	
class OLIVScript
{
// initialize olivscript functionality
	public function __construct()
	{
		global $_OSCODE;
		
		if (olivfile_exists(system::OLIV_OLIVSCRIPT() . "define.xml"))
			$_OSCODE = olivxml_load_file(system::OLIV_OLIVSCRIPT() . "define.xml");
		else
			OLIVError::fire("OLIVScript::construct - olivscript definition not found");
	}


	public static function call($code)
	{
		global $_OSCODE;
		
		$pattern = "/\/\*(.*?)\*\//";

		preg_match_all($pattern,$code,$matches);
//echoall($matches);

// execute code
		return eval($code);
	}
}


?>
