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

if (!system::OLIVCORE()) die ("mod_menu::menu.php - OLIVCore not present");
if (!system::OLIVTEXT()) die ("mod_menu::menu.php - OLIVText not present");
if (!system::OLIVERROR()) die ("mod_menu::menu.php - OLIVError not present");


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
// load menu items
    $menuName = (string)$header;

		$menu = OLIVModule::load_xml($header,"","menu.xml");

//		$menu = sessionxml_load_file(system::OLIV_MODULE_PATH() . "menu/menu.xml");

// permission to display menu
		if (OLIVRight::r($menu->$menuName))
		{

// load menu template
		  $this->templateName = (string)$menu->$menuName->attributes()->template;
		  $this->template = OLIVModule::load_template($header,$this->templateName);


	    $this->menuXml = $this->parse($menu->$menuName,status::url());

//echoall($this->menuXml->asXML());
// call renderer
	    $this->o .= OLIVRender::template($this->template,$this->menuXml);
		}
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
// get name of menu
		$menuName = $menu->getName();

//------------------------------------------------------------------------------
// loop over menu entries
      foreach ($menu->children() as $entry)
      {
				$itemName = "";
				$itemUrl = "";
				$itemTitle = "";
				$itemTarget = "";
		    $paramString = "";

				$menuItemName = $entry->getName();
				
//------------------------------------------------------------------------------
// is extern link
				if ($entry->attributes()->url)
				{
					$itemUrl = (string)$entry->attributes()->url;
					if ($entry->attributes()->name)
						$itemName = OLIVText::_((string)$entry->attributes()->name);
				}


// is intern link
//TODO get title from page definition
				else
				{
		      $itemUrl = $entry->getName();
					$itemTitle = OLIVRoute::translateTitle($itemUrl);
		      $itemName = OLIVRoute::translatePageName(status::lang(),$itemUrl);
				}


// insert url title and target
				if ($entry->attributes()->title)
					$itemTitle = OLIVText::_((string)$entry->attributes()->title);
				if ($entry->attributes()->target)
					$itemTarget = OLIVText::_((string)$entry->attributes()->target);


// set display class aktive / inactive
	      if ($itemUrl == $url)
	        $displayClass = "menu_{$templateName}_active";
	      else
	        $displayClass = "menu_{$templateName}_inactive";
        

//------------------------------------------------------------------------------
// display item if read permission
				if (OLIVRight::r($entry))
				{
// insert link if x permission
					if (OLIVRight::x($entry))
					{
						$paramString = "url='$itemUrl'";

						if ($itemTitle)
							$paramString .= " title = '$itemTitle'";
						if ($itemTarget)
							$paramString .= " target = '$itemTarget'";
					}
// set display class to disabled
					else
						$displayClass = "menu_{$templateName}_disabled";


// insert name and format
					$paramString .= " name='{$menuName}_{$itemName}' class='$displayClass'";


// create menu item entry
			    $tempXml = new simpleXmlElement("<menu_item_{$this->templateName} source='$menuName' $paramString>$itemName</menu_item_{$this->templateName}>");

	//        olivxml_insert($menuXml,$tempXml);

	//TODO
	// sub menus foun
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
