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
// Article module
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("mod_menu::menu.php - OLIVCore not present");
defined('OLIVTEXT') or die ("mod_menu::menu.php - OLIVText not present");
defined('OLIVERROR') or die ("mod_menu::menu.php - OLIVError not present");


class ArticleEditor extends article
{
  var $o;

  function __construct()
  {
    $this->loadScript("jquery-1.7.1.js",OLIV_JAVASCRIPT_PATH);
    $this->loadScript("tiny_mce.js",OLIV_JAVASCRIPT_PATH . "tiny_mce/");

?>
    <script type="text/javascript">

    tinyMCE.init({
      mode : "textareas",
      plugins : "save,paste",
      width : "480",
	    theme : "advanced",
	    theme_advanced_buttons1 : "save,cancel,|,undo,redo,|,cut,copy,paste",
      theme_advanced_buttons2 : "",
      theme_advanced_buttons3 : "",
	    theme_advanced_toolbar_location : "bottom",
	    theme_advanced_toolbar_align : "left"
    });
    </script>

<?PHP
  }
  

//------------------------------------------------------------------------------
// open editor
  function open($name,$text)
  {
    global $_argv;

    $options = array(
      "url" => $_argv[url],
      "val" => OLIVText::_("save") . "/$name"
    );

    $actionUrl = OLIVRoute::url(OLIV_LANG,$options);
    
    $this->o .= "<form action='$actionUrl'><textarea name='$name' width='50'>";
      $this->o .= OLIVText::_((string)$text);
    $this->o .= "</textarea></form>";

    return $this->o;
  }
  


  function save($value)
  {
    global $_argv;
    
    echoall($value);
  }
}
?>