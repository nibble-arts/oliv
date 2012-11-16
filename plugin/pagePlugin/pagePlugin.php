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
// Preprocessor object
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("plugin::textPlugin.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("plugin::textPlugin.php - OLIVError not present");


class pagePlugin
{
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($func,$options)
  {
		$pluginData = (string)$options[0]['template']->$func;
		$pageXml = $options[0]['template'];

//TODO remove $func entry from $pageXml entry
		$pageXml->$func = "";
		
echoall($func);
// call methods
		switch ($func)
		{
			case 'redirect':
// run script for condition check for redirect
				$redirect = OLIVScript::call($pluginData);

// get redirected page
				if ($redirect)
				{
					$pageXml = new OLIVPage;
					$pageXml->load($redirect);
//					$pageXml = $page->structure();
				}
				break;


// add masterpage content to page
			case 'masterpage':
				$masterPage = new OLIVPage;
				$masterPage->load($pluginData);

				olivxml_insert($pageXml,$masterPage->structure());
				break;
		}
	}
}


?>
