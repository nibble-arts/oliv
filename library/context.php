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
// GNU General Public License Version 3 (the ``GPL').
//
// Software distributed under the License is distributed
// on an ``AS IS' basis, WITHOUT WARRANTY OF ANY KIND, either
// express or implied. See the GPL for the specific language
// governing rights and limitations.
//
// You should have received a copy of the GPL along with this
// program. If not, go to http://www.gnu.org/licenses/gpl.html
// or write to the Free Software Foundation, Inc.,
// 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
//

//------------------------------------------------------------------------------
// context menu class
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("context - OLIVCore not present");
if (!system::OLIVERROR()) die ("context - OLIVError not present");

class OLIVContext
{
	var $context = array();

	
//------------------------------------------------------------------------------
// init context menu
	public function __construct($menuName,$name,$value = "")
	{
		if ($menuName and $name)
		{
// load javascripts
			OLIVCore::loadScript("jquery-1.7.1.js",system::OLIV_JAVASCRIPT_PATH());
			OLIVCore::loadScript("jquery.contextMenu.js",system::OLIV_JAVASCRIPT_PATH());

// load css
		  OLIVTemplate::link_css(system::OLIV_TEMPLATE_PATH() . system::OLIV_TEMPLATE() . "/","jquery.contextMenu.css");


// load menu item definition
			$this->context = olivxml_load_file(system::OLIV_CONTEXT_PATH() . "$menuName.xml",system::OLIV_CORE_PATH());


//------------------------------------------------------------------------------
// load context menu plugin script
			OLIVCore::loadScript("{$menuName}.contextMenu.js",system::OLIV_JAVASCRIPT_PATH());


// set javascript action for context display
?>
	<script language='javascript'>
// get function name and parameters
			menuFunc="<?PHP echo $menuName; ?>_contextMenu";
			menuParam = "<?PHP echo $name; ?>";

// call contextMenu script
			window[menuFunc](menuParam);
	</script>

<?PHP
//------------------------------------------------------------------------------


// set context menu name
		  $this->context->addAttribute('name',$name);
		  if ($value) $this->context->addAttribute('value',$value);


// disable paste
			if (!OLIVClipboard::_())
				$this->disable("paste");
		}
		else
			return FALSE;
	}


//------------------------------------------------------------------------------
// disable context menu item
	public function disable($name)
	{
		$this->context->$name->addAttribute('display','disabled');
	}


//------------------------------------------------------------------------------
// output context menu content
	public function draw()
	{
		$display = "";

		$o = "<ul id='" . $this->context->attributes()->name . "' class='contextMenu'>";

// create ouput string from xml
		foreach($this->context as $entry)
		{
// disable entry
			if ($display = (string)$entry->attributes()->display) $display = " " . $display;
			

// set entry to url or status::url
			if (($url = (string)$entry->attributes()->url) == "#CURRENT") $url = status::url();

// set val
			if ($val = (string)$this->context->attributes()->value);
			else $val = (string)$this->context->attributes()->name;

// set parameters
			$class = (string)$entry->attributes()->class;
			$cmd = (string)$entry->attributes()->cmd;
			$command = OLIVText::_($cmd);
			$text = OLIVText::_($entry->getName());

			$o .= "<li class='{$class}{$display}'>";
				$o .= "<a href='#{$cmd};{$command};" . OLIVRoute::url("",array("url" => $url)) . ";{$val}'>{$text}</a>";
			$o .= "</li>";
			
		}

		$o .= "</ul>";

// output to display
		echo($o);
	}
}
?>


