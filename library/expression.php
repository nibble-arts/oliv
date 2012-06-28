<?PHP
//
// OLIV
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
// Exoression object class
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("route.php - OLIVCore not present");

class OLIVExpression
{
	private $exprArray = array();

	public function __construct($expr)
	{
		$this->parse($expr);
	}


  public function __toString()
  {
		if (is_array($this->exprArray))
		{
			$o = "Array (<br>";
			foreach($this->exprArray as $key=>$value)
			{
				$o .= "[$key] = $value<br>";
			}
			$o .= "<br>)";
		}
		else
//DEBUG
			$o = "empty";
    return($o);
  }


	// extract {} expressions from string
	// store expression arrays in local array
	private function parse($value)
	{
		$pattern = "#\{(.*?)\}#";

		preg_match_all($pattern, $value, $matches);
		if (is_array($matches[1]))
		{
			foreach ($matches[1] as $entry)
			{
				array_push($this->exprArray,$this->createElement($entry));
			}
		}
	}


	// return expression array from func|attr|value string
	private function createElement($value)
	{
		// extract data from value
		$tempArray = explode("|",$value);
		$func = array_shift($tempArray);

		// make assoziative parameters array
		if (count($tempArray))
		{
			foreach ($tempArray as $entry)
			{
				$temp = explode(":",$entry);
				if ($temp[1])
					$paramArray[$temp[0]] = $temp[1]; // parameter
				else $value = $temp[0];
			}
		}
		return (array("func" => $func,"param" => $paramArray,"value" => $value));
	}


	// return expression array
	public function getExpression()
	{
		return ($this->exprArray);
	}
}
?>
