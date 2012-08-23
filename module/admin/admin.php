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

//------------------------------------------------------------------------------
  function __construct($header)
  {
    $template = OLIVModule::load_template($header);

		if (OLIV_CONTENT_EDIT)
	    $content = $this->displayAdmin($template);


		if ($content)
		  $this->o = OLIVRender::template($template,$content);
  }
  


//------------------------------------------------------------------------------
// display admin field
	private function displayAdmin($template)
	{
		global $_argv;

		$o = "";
		$text = "";
		$url = "";
		$val = "";
		$image = "";

		$command = OLIV_COMMAND;


// create empty content
		$content = new simpleXmlElement("<admin></admin>");


// parse status
		switch ($command)
		{
			case 'edit':
				$image = OLIVImage::_("admin_cancel");
				$url = $_argv['url'];
//				$val = "save/" . OLIV_VAL;
				break;

			default:
		}


//------------------------------------------------------------------------------
// show action button
		if ($image)
		{
			olivxml_insert($content,new simpleXmlElement("<admin_action background-image='$image' url='$url'>x</admin_action>"));
		}

//------------------------------------------------------------------------------
// show edit mode
		if (OLIV_CONTENT_EDIT)
		{
			$image = OLIVImage::_("admin_content_edit");
			olivxml_insert($content,new simpleXmlElement("<admin_content_edit background-image='$image'>c</admin_content_edit>"));
		}


		if (OLIV_TEMPLATE_EDIT)
		{
			$image = OLIVImage::_("admin_template_edit");
			olivxml_insert($content,new simpleXmlElement("<admin_template_edit background-image='$image'>t</admin_template_edit>"));
		}


//hoall($content);
		return $content;
	} 
}

?>
