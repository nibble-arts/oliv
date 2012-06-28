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

defined('OLIVCORE') or die ("template.php - OLIVCore not present");
defined('OLIVERROR') or die ("template.php - OLIVError not present");
defined('OLIVHtml') or die ("template.php - OLIVHtml not present");


//------------------------------------------------------------------------------
// create template object
class OLIVTemplate
{
  private $template;
  
  public function __construct($path,$name)
  {
    $this->template = $this->load($path,$name);
  }


  // load template and corresponding css
  // with name from path
  // if no css with name -> use default.css
  public function load($path,$name)
  {
//echoall("template - link css: " . $path . $name);
    OLIVTemplate::link_css($path,$name);

//echoall("template - load template: " . $path . $name);
    if (sessionfile_exists($path . $name . ".xml"))
      return (sessionxml_load_file($path . $name . ".xml"));
  }


  // return structure array
  public function xml()
  {
    return ($this->template);
  }
  
  
// load css file from path
// look for name.xml - if not exists load default.xml
  public static function link_css($path,$name)
  {
    if (sessionfile_exists($path . $name . ".css"))
      $cssPath = $path . $name . ".css"; // named css.php class
    else
      $cssPath = $path . "default.css"; // default.css class


// css file found
    if (sessionfile_exists($cssPath))
    {
      echo "<link href='" . session_path($cssPath) . "' rel='stylesheet' type='text/css'>"; // link css to site
    }
// no css found
    else
      OLIVError::warning ("template::link_css.php - no stylesheet found");
  }  

}
  
?>
