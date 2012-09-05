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
	var $contextArray = array();

	
//------------------------------------------------------------------------------
// init context menu
	public function __construct($name)
	{
		if ($name)
		{
// load javascripts
			OLIVCore::loadScript("jquery-1.7.1.js",system::OLIV_JAVASCRIPT_PATH());
			OLIVCore::loadScript("jquery.contextMenu.js",system::OLIV_JAVASCRIPT_PATH());

		  OLIVTemplate::link_css(system::OLIV_TEMPLATE_PATH() . system::OLIV_TEMPLATE() . "/","jquery.contextMenu.css");


// set javascript action for context display
			OLIVCore::loadScript("menuPlugin.contextMenu.js",system::OLIV_JAVASCRIPT_PATH());
?>
	<script language='javascript'>
			menuPlugin_contextMenu('<?PHP echo $name; ?>');
	</script>
<?PHP
// set context menu name
		  $this->contextArray['name'] = $name;
		}
		else
			return FALSE;
	}


//------------------------------------------------------------------------------
// insert entry in context menu
// $class ... display class
// $command ... command to be executed
// $val ... value for execution -> format #$command/$val
// $text ... text to be displayed
	public function insert($url,$class,$command,$val,$text)
	{
		$entryArray = array(
			'url' => $url,
			'cmd' => $command,
			'command' => OLIVText::_($command),
			'val' => $val,
			'text' => OLIVText::_($text),
			'class' => $class,
			'id' => $this->contextArray['name']
		);

		$this->contextArray['entry'][$class] = $entryArray;
	}


//------------------------------------------------------------------------------
// disable context menu item
	public function disable($name)
	{
		$this->contextArray['entry'][$name]['display'] = "disabled";
	}


//------------------------------------------------------------------------------
// output context menu content
	public function draw()
	{
		$o = "<ul id='" . $this->contextArray['name'] . "' class='contextMenu'>";

// create ouput string from array
			foreach ($this->contextArray['entry'] as $entry)
			{
				$class = $entry['class'];
				if (array_key_exists('display',$entry)) $class .= " " . $entry['display'];

				$o .= "<li class='$class'>";
					$o .= "<a href='#" . $entry['cmd'] . ";" . $entry['command'] . ";" . OLIVRoute::url("",array("url" => $entry['url'])) . ";" . $entry['val'] . "'>" . $entry['text'] . "</a>";
				$o .= "</li>";
			}

		$o .= "</ul>";

// output to display
		echo($o);
	}
}
?>


