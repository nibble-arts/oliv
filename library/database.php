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
	private $name;
  private $prefix;
  private $insertId;
	private $relation;
	private $id;
		

// create database
// $database = simpleXmlElement(server,name,user,password)
  public function __construct($db)
  {
		$this->name = $db->getName();

// load relation list for table
		$this->relation = $db->relation;


// establish connection to db server
    $this->dbResource = @MYSQL_CONNECT($db->server,$db->user,$db->password);

    if (!$this->dbResource)
    {
    	OLIVError::fire('no database connection');
			return FALSE;
		}
		else
		{
// connection established
// select database and set prefix
		  @MYSQL_SELECT_DB($this->name);

		  if (array_key_exists('prefix',$db))
		  	$this->prefix = $db['prefix'];

			return $this->dbResource;
		}
  }


// close connection
  public function __destruct()
  {
  	if ($this->dbResource)
	    return(mysql_close($this->dbResource));
  }



//------------------------------------------------------------------------------
// get row of table
	public function get($table,$filter)
	{
		$this->query(status::lang(),"SELECT",$table,"",$filter);

/*		while ($entry = $this->fetch())
		{
echoall($entry);
		}*/

		return ($this->fetch());
	}


//------------------------------------------------------------------------------
// get filter array
	public function whereArray()
	{
		$tempArray = array();
		
		if ($this->id)
		{
			foreach($this->id as $entry)
			{
				$field = $entry->attributes()->field;
				$value = $entry->attributes()->value;
				$op = $entry->attributes()->operator;

				array_push($tempArray,"{$field}{$op}{$value}");
			}
		}
		return $tempArray;
	}


//------------------------------------------------------------------------------
// get filter string
	public function whereString()
	{
		return (implode(" and ",$this->whereArray()));
	}


//------------------------------------------------------------------------------
// create and send query and return resource
	private function query($lang,$func,$table,$field="",$where="",$data="",$sort="")
	{
		$queryString = "";
		$tableArray = array($table);
		$fieldArray = array();
		$filterArray = array();

		$queryTable = "";
		$queryField = "";
		$queryWhere = "";


/*echoall("lang: $lang, func: $func, table: $table, field: $field");
echoall($where);
echoall("data: $data, sort: $sort");*/

//------------------------------------------------------------------------------
// create relation table list
		foreach($this->relation->children() as $entry)
		{
			array_push($tableArray,(string)$entry->attributes()->table);
		}

		$queryTable = implode(",",$tableArray);


//------------------------------------------------------------------------------
//TODO insert prefix to all table names


//------------------------------------------------------------------------------
// create where relation list
		if (is_array($where))
		{

// loop over all where clauses
			foreach($where as $entry)
			{
				$whereField = (string)$entry->attributes()->field;
				$whereValue = (string)$entry->attributes()->value;
				$whereOp = (string)$entry->attributes()->operator;


// relation field definition found
				if ($this->relation->$whereField)
				{
					$relTableName = (string)$this->relation->$whereField->attributes()->table;
					$relTableId = (string)$this->relation->$whereField->attributes()->id;
					$relTableDisplay = (string)$this->relation->$whereField->attributes()->display;

					array_push($filterArray,"{$relTableName}.content{$whereOp}'{$whereValue}' and {$table}.{$whereField}={$relTableName}.{$relTableId}");
				}
				else
				{
					array_push($filterArray,"{$table}.{$whereField}{$whereOp}{$whereValue}");
				}
			}

			$idFilter = implode(" and ",$filterArray);
		}


//------------------------------------------------------------------------------
// create field relation list
// get field list
		$fieldList = mysql_list_fields($this->name,$table);

// loop fields
		if ($fieldList)
		{
			for ($x = 0;$x < mysql_num_fields($fieldList);$x++)
			{
//TODO look if field defined or all fields

				$fieldName = mysql_field_name($fieldList,$x);

// relation field found
				if ($this->relation->$fieldName)
				{
					$relationId = $this->relation->$fieldName->attributes()->id;
					$relationTable = $this->relation->$fieldName->attributes()->table;


// insert alias for relation
					array_push($fieldArray,$relationTable . ".content AS " . $fieldName);
					array_push($fieldArray,$relationTable . ".lang AS " . $fieldName . "_lang"); // language referenz

// insert filter parameters for relation
					array_push($filterArray,"{$relationTable}.{$relationId}={$table}.{$fieldName}");


//------------------------------------------------------------------------------
// check for language
// filter string for entry filtering
					$filterString = "{$relationTable}.{$relationId}={$table}.{$fieldName} and " . $idFilter;

// create where clause for language check
					if ($filterString)
					{
// get database entry default language
						$langQuery = "SELECT {$table}.lang FROM {$table},{$relationTable} WHERE $filterString";// and lang='{$lang}'";
						$langRes = mysql_query($langQuery);
						$defaultLang = mysql_fetch_array($langRes);
						$defaultLang = $defaultLang['lang'];


// check if lang entry is present
// if not use entry default language
						$langQuery = "SELECT {$table}.lang FROM {$table},{$relationTable} WHERE $filterString and {$relationTable}.lang='{$lang}'";
						$langRes = mysql_query($langQuery);


// load current language
						if(mysql_num_rows($langRes))
							array_push($filterArray,"{$relationTable}.lang='{$lang}'");

// load default language
						else
							array_push($filterArray,"{$relationTable}.lang='$defaultLang'");
					}
				}
// no relation
				else
				{
					array_push($fieldArray,$table . "." . $fieldName . " AS " . $fieldName);
				}
			}
			$queryField = implode(",",$fieldArray);
		}


//------------------------------------------------------------------------------
// recreate where clause for query
		if ($filterArray);
			$queryWhere = implode(" and ",$filterArray);


//echoall($queryWhere);
//------------------------------------------------------------------------------
// parse command
		if ($queryWhere) $queryWhere = "WHERE " . $queryWhere;

		switch (strtolower($func))
		{
			case 'select':
				$queryString = "SELECT $queryField FROM $queryTable $queryWhere $sort";
				break;

			case 'insert':
				$queryString = "INSERT INTO $queryTable SET $data";
				break;

			case 'update':
				$queryString = "UPDATE $queryTable SET $data WHERE $queryWhere";
				break;

			case 'delete':
				$queryString = "DELETE FROM $queryTable WHERE $queryWhere";
				break;
		}

		if ($queryString)
		{


//echoall($queryString);
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// mysql query call
			$this->sqlResource = mysql_query($queryString);
			$this->insertId = mysql_insert_id($this->dbResource);
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
		}
	}


//------------------------------------------------------------------------------
// return found elements as associative array
	private function fetch()
	{
		if ($this->sqlResource)
		{
			return mysql_fetch_assoc($this->sqlResource);
		}
		else
			return FALSE;
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
		return FALSE;
	}



//------------------------------------------------------------------------------
// set prefix values
  public function prefix($prefix)
  {
    $this->prefix = $prefix;
  }


//------------------------------------------------------------------------------
// return prefix values
  public function getPrefix()
  {
    return ($this->prefix);
  }
}

?>
