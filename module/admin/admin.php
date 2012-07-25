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
// Administration module
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("mod_menu::menu.php - OLIVCore not present");
defined('OLIVTEXT') or die ("mod_menu::menu.php - OLIVText not present");
defined('OLIVERROR') or die ("mod_menu::menu.php - OLIVError not present");


class admin extends OLIVCore
{
	var $o = "";

  function __construct($header)
  {
    $template = OLIVModule::load_template($header);
    
    $this->info();
  }
  

//------------------------------------------------------------------------------
// returns core information
  public function info()
  {
    global $_TEXT;
    global $_PAGES;

    
    $this->o .=  "<table border='1' align='center'>";
//------------------------------------------------------------------------------
// CORE STATUS
      $this->o .=  "<tr>";

        if (defined(OLIVENV) and defined(OLIVCORE) and defined(OLIVTEXT) and defined(OLIVERROR) and defined(OLIVDABA))
          $this->o .=  "<td class='admin_true'>";
        else
          $this->o .=  "<td class='admin_false'>";
        
          $this->o .=  "<h1>OLIV-CMS Core Status</h1>";
        $this->o .=  "</td>";
      $this->o .=  "</tr>";

      $this->o .=  "<tr><td>";
        $this->o .= OLIVText::assocArray($_PAGES);
      $this->o .=  "</td></tr>";

//------------------------------------------------------------------------------
//SYSTEM DEFINITIONS
      $constList = get_defined_constants(true);

      $this->o .=  "<tr>";
        if (count($constList))
          $this->o .=  "<td class='admin_true'>";
        else
          $this->o .=  "<td class='admin_false'>";

          $this->o .=  "<h2>SYSTEM DEFINITIONS</h2>";
        $this->o .=  "</td>";
      $this->o .=  "</tr>";
      $this->o .=  "<tr>";
        $this->o .=  "<td>";
          $this->o .= OLIVText::assocArray($constList[user]);
        $this->o .=  "</td>";
      $this->o .=  "</tr>";


//------------------------------------------------------------------------------
// INCLUDED SCRIPTS
      $scriptList = array();

      $this->o .=  "<tr>";
        if (count($scriptList))
          $this->o .=  "<td class='admin_true'>";
        else
          $this->o .=  "<td class='admin_false'>";

          $this->o .=  "<h2>INCLUDED SCRIPTS</h2>";
        $this->o .=  "</td>";
      $this->o .=  "</tr>";
      $this->o .=  "<tr>";
        $this->o .=  "<td>";
          $this->o .= OLIVText::assocArray($this->includes);
        $this->o .=  "</td>";
      $this->o .=  "</tr>";
      

//------------------------------------------------------------------------------
// LOADED MODULES
      $moduleList = array();

      $this->o .=  "<tr>";
        if (count($moduleList))
          $this->o .=  "<td class='admin_true'>";
        else
          $this->o .=  "<td class='admin_false'>";

          $this->o .=  "<h2>LOADED MODULES</h2>";
        $this->o .=  "</td>";
      $this->o .=  "</tr>";
      $this->o .=  "<tr>";
        $this->o .=  "<td>";
//          $this->o .= OLIVText::assocArray($this->module->getNameList());
        $this->o .=  "</td>";
      $this->o .=  "</tr>";


//------------------------------------------------------------------------------
// DATABASE SETTINGS
      $database = false;
      
      $this->o .=  "<tr>";
        if ($database)
          $this->o .=  "<td class='admin_true'>";
        else
          $this->o .=  "<td class='admin_false'>";

          $this->o .=  "<h2>DATABASE SETTINGS</h2>";
        $this->o .=  "</td>";
      $this->o .=  "</tr>";
      
      if (OLIVDABA)
      {
        $this->o .=  "<tr>";
          $this->o .=  "<td style='background-color:lightblue;'>";
//            $this->o .=  "table prefix: " . $this->daba->prefix() . "<br>";
//            $this->o .=  "database id: " . $this->daba->resource() . "<br>";
//            $this->o .=  "database select: " . $this->daba->active() . "<br>";
          $this->o .=  "</td>";
        $this->o .=  "</tr>";
      }
      else
      {
        $this->o .=  "<tr>";
          $this->o .=  "<td class='admin_false'>";
            $this->o .=  "*** DATABASE NOT INITIALIZED";
          $this->o .=  "</td>";
        $this->o .=  "</tr>";
      }


// LANGUAGE SETTINGS
      $textList = $_TEXT;

      $this->o .=  "<tr>";
        if (count($textList))
          $this->o .=  "<td class='admin_true'>";
        else
          $this->o .=  "<td class='admin_false'>";

          $this->o .=  "<h2>LANGUAGE SETTINGS</h2>";
        $this->o .=  "</td>";
      $this->o .=  "</tr>";
      $this->o .=  "<tr>";
        $this->o .=  "<td>";
          $this->o .= OLIVText::assocArray(array("current language" => OLIV_LANG,"default language" => OLIV_DEFAULT_LANG));

          $this->o .= OLIVText::assocArray($textList);
        $this->o .=  "</td>";
      $this->o .=  "</tr>";
    $this->o .=  "</table>";
  }
}

?>
