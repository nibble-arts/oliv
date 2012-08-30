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

if (!system::OLIVCORE()) die ("mod_admin::admin.php - OLIVCore not present");
if (!system::OLIVTEXT()) die ("mod_admin::admin.php - OLIVText not present");
if (!system::OLIVERROR()) die ("mod_admin::admin.php - OLIVError not present");


class admin extends OLIVCore
{
	var $o = "";

//------------------------------------------------------------------------------
  function __construct($header)
  {
  	$content = "";
  	
    $template = OLIVModule::load_template($header);

		if (system::OLIV_CONTENT_EDIT())
	    $content = $this->displayAdmin($template);


		if ($content)
		{
//echoall($template);
//echoall($content);
		  $this->o = OLIVRender::template($template,$content);
		}
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

		$command = status::command();

// create empty content
		$content = new simpleXmlElement("<content></content>");


//------------------------------------------------------------------------------
// show action button
// parse status
		switch ($command)
		{
			case 'edit':
				$image = OLIVImage::_("admin_cancel");
				$url = status::url();
//				$val = "save/" . OLIV_VAL;
				break;

			default:
		}

		if ($image)
		{
			olivxml_insert($content,new simpleXmlElement("<admin_action url='$url' title='cancel'></admin_action>"));
			olivxml_insert($content->admin_action,new simpleXmlElement("<img src='admin_cancel' />"));
		}

//------------------------------------------------------------------------------
// show edit mode
		if (system::OLIV_CONTENT_EDIT())
		{
			olivxml_insert($content,new simpleXmlElement("<admin_content_edit></admin_content_edit>"));
			olivxml_insert($content->admin_content_edit,new simpleXmlElement("<img src='admin_content_edit' />"));
		}


		if (system::OLIV_TEMPLATE_EDIT())
		{
			$image = OLIVImage::_("admin_template_edit");
//			olivxml_insert($content,new simpleXmlElement("<admin_template_edit><img src='$image' /></admin_template_edit>"));
		}

//echoall($content);
		return $content;
	} 
}

?>
