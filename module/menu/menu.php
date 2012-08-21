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
// Menu module
//
// Version 0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("mod_menu::menu.php - OLIVCore not present");
defined('OLIVTEXT') or die ("mod_menu::menu.php - OLIVText not present");
defined('OLIVERROR') or die ("mod_menu::menu.php - OLIVError not present");

class menu extends OLIVCore
{
	var $o; // output string
  var $menuXml;
  var $template;
  var $templateName;


//------------------------------------------------------------------------------
// create menu
  function __construct($header)
  {
    global $_argv;
    $bg_img = "";
    

// load menu items
    $menuName = (string)$header;


		$menu = sessionxml_load_file(OLIV_MODULE_PATH . "menu/menu.xml");


// load menu template
    $this->templateName = (string)$menu->$menuName->attributes()->template;
    $this->template = OLIVModule::load_template($header,$this->templateName);


    $this->menuXml = $this->parse($menu->$menuName,$_argv['url']);


// call renderer
    $this->o .= OLIVRender::template($this->template,$this->menuXml);
  }


//------------------------------------------------------------------------------
// parse menu file
// create content xml-file for renderer
  private function parse($menu,$url,$level = 0)
  {
    $menuXml = new simpleXmlElement("<menu></menu>");

    $templateName = (string)$menu->attributes()->template;


    if ($menu)
    {

//------------------------------------------------------------------------------
// loop over menu entries
      foreach ($menu->children() as $entry)
      {
				$itemName = "";
				$itemUrl = "";
				$itemTitle = "";
				$itemTarget = "";


//------------------------------------------------------------------------------
// is extern link
				if ($entry->attributes()->url)
				{
					$itemUrl = (string)$entry->attributes()->url;
					if ($entry->attributes()->name)
						$itemName = OLIVText::_((string)$entry->attributes()->name);
				}


// is intern link
				else
				{
		      $itemUrl = $entry->getName(); //TODO insert correct link
		      $itemName = OLIVRoute::translatePageName(OLIV_LANG,$itemUrl);
				}


// insert url title and target
				if ($entry->attributes()->title)
					$itemTitle = OLIVText::_((string)$entry->attributes()->title);
				if ($entry->attributes()->target)
					$itemTarget = OLIVText::_((string)$entry->attributes()->target);
				

// aktive menuitem found
	      if ($itemUrl == $url)
	        $active = "menu_{$templateName}_activ";
	      else
	        $active = "menu_{$templateName}_inactive";
        

// check access
				if (OLIVRight::r($entry))
				{
					if (!OLIVRight::x($entry))
						$itemUrl = "";



// create menu item entry
			    $tempXml = new simpleXmlElement("<menu_item_{$this->templateName}
			      name='menu_$itemUrl'
			      url='$itemUrl'
			      title = '$itemTitle'
			      target = '$itemTarget'
			      class='$active'>
			        $itemName
			      </menu_item_{$this->templateName}>");

	//        olivxml_insert($menuXml,$tempXml);

	//TODO
	// sub menus found
	// display when active
	/*        if(count($entry))
		      {
		        $subXml = menu::parse($entry,$url,$level+1);
		        $menuName = "menu_item_" . $this->templateName;
		        olivxml_insert($tempXml->$menuName,$subXml);
		      }*/
		      olivxml_insert($menuXml,$tempXml);
		    }
			}
 
//echoall($tempXml);
//echoall($menuXml->asXML());
      return ($menuXml);
    }
    else
      OLIVError::fire("menu::parse - no menu defined");
  }
}
?>
