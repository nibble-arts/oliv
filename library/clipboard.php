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
// clipboard object
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("plugin::textPlugin.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("plugin::textPlugin.php - OLIVError not present");


$clipBoard = array();


class OLIVClipboard
{

//------------------------------------------------------------------------------
// get clipboard status
	public static function __callStatic($m,$options)
	{
		global $clipBoard;
		$type = "";


// set type parameter
		if (isset($options[0]))
			$type = $options[0];

// if no type, return if clipboard has entry
		elseif (!$clipBoard)
			return FALSE;

		
		switch ($m)
		{
			case '_':
				
				break;
		}
	}


//------------------------------------------------------------------------------
// get clipboard entry
	public static function get($type)
	{
		global $clipBoard;

		if ($clipBoard)
			return $clipBoard['type'];
	}


//------------------------------------------------------------------------------
// set clipboard
	public static function set($type,$value)
	{
		global $clipBoard;

		$clipBoard['type'] = $type;
		$clipBoard['value'] = $value;
	}
}
?>
