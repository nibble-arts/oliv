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


class OLIVDatabase extends OLIVCore
{
  private $resource;
  private $active;
  private $prefix;

  // create database
  // $database = array(server,name,user,password)
  function __construct($db)
  {
    $this->resource = @MYSQL_CONNECT($db['server'],$db['user'],$db['password']);
      if (!$this->resource) die('no database connection');

    $this->select = @MYSQL_SELECT_DB($db['name']);
    if (array_key_exists('prefix',$db))
    	$this->prefix = $db['prefix'];
  }

  function __destruct()
  {
    return(mysql_close($this->resource));
  }


//------------------------------------------------------------------------------




//------------------------------------------------------------------------------
// return daba values
  function prefix()
  {
    return ($this->prefix);
  }

  function resource()
  {
    return ($this->resource);
  }

  function active()
  {
    return ($this->active);
  }
}

?>
