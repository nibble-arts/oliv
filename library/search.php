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
// search engine
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("search.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("search.php - OLIVError not present");
if (!system::OLIVINDEX()) die ("search.php - OLIVIndex not present");

class OLIVSearch
{
	public function __construct()
	{
		global $_INDEX;

// check for index
		if (!OLIVIndex::is_index())
			OLIVError::fire("search.php - no index found");
	}


	public function getSearch()
	{
		$content = new simpleXmlElement("<search></search>");
		
		if (argv::action() == "search")
		{
			OLIVPlugin::call($content,"search");

			status::set("search",$content);
		}
	}
}
?>
