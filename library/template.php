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
// Template class
//
// Version 0.1
//------------------------------------------------------------------------------


if (!system::OLIVCORE()) die ("template.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("template.php - OLIVError not present");


//------------------------------------------------------------------------------
// create template object
class OLIVTemplate
{
  public $stylesheet;
  var $path;
  var $name;
  
  public function __construct()
  {
  	$this->stylesheet = new XSLTProcessor();
  }


	public function load($path,$name)
	{
		$this->path = $path;
		$this->name = $name;
	}


//------------------------------------------------------------------------------
// return path/name of template
	public function filename()
	{
		return $this->path . $this->name;
	}

  
// load css file from path
// look for name.xml - if not exists load default.xml
  public static function link_css($name)
  {
		$path = pathinfo($name);
		$cssPath = $path['dirname'] . "/";
		$cssName = $path['filename'] . ".css";

    if (!sessionfile_exists($cssPath . $cssName))
    {
      $cssName = "default.css"; // default.css class
    }

// css file found
    if (sessionfile_exists($cssPath))
    {
      echo "<link href='" . session_path($cssPath . $cssName) . "' rel='stylesheet' type='text/css'>"; // link css to site
    }
// no css found
    else
      OLIVError::warning ("template::link_css.php - no stylesheet found");
  }  

}
  
?>
