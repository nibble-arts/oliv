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
		return dabaRender::render($tag,$options);
  }
}


//------------------------------------------------------------------------------
// edit render class
class dabaEditPlugin
{
  static public function __callStatic($tag,$options)
  {
		$tagArray = dabaRender::render($tag,$options);
		$tagArray['value'] .= "<br>for editing";

		return ($tagArray);
  }
}


class dabaRender
{
	static public function render($tag,$options)
	{
  	$dbData = array();

		$id = status::val();
    $content = $options[0]['template'];
		$name = (string)$content->attributes()->db;
		$table = (string)$content->attributes()->table;
		$field = (string)$content->attributes()->field;

  	$database = sessionxml_load_file(system::OLIV_MODULE_PATH() . "daba/daba.xml");

		$database = $database->$name;

		$dbObject = new OLIVDatabase($database);
		$field = $dbObject->getField($table,$field);

//echoall(mysql_num_rows($sqlData));
		$text = "render:<br>db=$name table=$table field=$field<br>daba id=" . $id;
//		$text = "render db=$db table=$table field=$field";

		$tagArray = array(
			'startTag' => '<daba>',
			'endTag' => '</daba>',
			'value' => $text
		);

    return ($tagArray);
	}
}
?>
