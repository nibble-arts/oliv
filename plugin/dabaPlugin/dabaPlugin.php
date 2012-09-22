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


class dabaPlugin
{
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($tag,$options)
  {
  	$dbData = array();
    $content = $options[0]['template'];


		$table = $content->attributes()->table;
		$field = $content->attributes()->field;


//TODO get database connection data from xml file
//		$mysql = mysql_connect("localhost","iggmp","grumblfurz34");
//		$sqlData = mysql_query("SELECT * FROM $table");
		$dbData['server'] = "localhost";
		$dbData['name'] = $content->attributes()->db;
		$dbData['user'] = "iggmp";
		$dbData['password'] = "grumblfurz34";


		$db = new OLIVDatabase($dbData);

//echoall(mysql_num_rows($sqlData));
$text = "render daba";
//		$text = "render db=$db table=$table field=$field";

		$tagArray = array(
			'startTag' => '<daba>',
			'endTag' => '</daba>',
			'value' => $text
		);

    return ($tagArray);
  }
}


//------------------------------------------------------------------------------
// edit render class
class dabaEditPlugin
{
  static public function __callStatic($tag,$options)
  {
  }
}
?>
