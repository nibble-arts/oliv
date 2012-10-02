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
// database class
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("database.php - OLIVCore not present");
system::set('OLIVDABA', 'alive');


class OLIVDatabase
{
  private $dbResource;
  private $sqlResource;
  private $active;
  private $prefix;
  private $insertId;


// create database
// $database = simpleXmlElement(server,name,user,password)
  public function __construct($db)
  {
    $this->dbResource = @MYSQL_CONNECT($db->server,$db->user,$db->password);

    if (!$this->dbResource)
    	OLIVError::fire('no database connection');
		else
		{
		  $this->select = @MYSQL_SELECT_DB($db['name']);
		  if (array_key_exists('prefix',$db))
		  	$this->prefix = $db['prefix'];
		}
  }

  public function __destruct()
  {
  	if ($this->dbResource)
	    return(mysql_close($this->dbResource));
  }


//------------------------------------------------------------------------------
// create query and return resource
	private function query($func,$field,$table,$where,$data,$sort)
	{
		$queryString = "";
		
		switch (strtolower($func))
		{
			case 'select':
				$queryString = "SELECT $field FROM $tabel $data $sort";
				break;

			case 'insert':
				$queryString = "INSERT INTO $tabel SET $data";
				break;

			case 'update':
				$queryString = "UPDATE $table SET $data WHERE $where";
				break;

			case 'delete':
				$queryString = "DELETE FROM $table WHERE $where";
				break;
		}

		if ($queryString)
		{
			$this->sqlResource = mysql_query($queryString);
			$this->insertId = mysql_insert_id;
		}
	}


//------------------------------------------------------------------------------
// get count of found elements
	public function num_rows()
	{
		if ($this->sqlResource)
		{
			return (mysql_num_rows($this->sqlResource));
		}
	}


//------------------------------------------------------------------------------
// get field content from table->field
	public function getField($field)
	{
//TODO call query
//		OLIVDatabase
		if ($this->sqlResource)
		{
			return mysql_fetch_array($this->sqlResource);
		}
	}



//------------------------------------------------------------------------------
// return daba values
  public function prefix()
  {
    return ($this->prefix);
  }

  public function resource()
  {
    return ($this->dbResource);
  }

  public function active()
  {
    return ($this->active);
  }
}

?>
