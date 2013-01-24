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


class menu extends OLIVModule
{

//------------------------------------------------------------------------------
// create menu
  function __construct($header)
  {
  	$menuName = "";
  	$templateName = "";

// load menu items
		$access = $header->access;
		$menuName = (string)$header->param->menu;
		$templateName = (string)$header->param->template;

		if ($templateName and $menuName)
		{
			$menu = OLIVModule::load_xml($header,"","menu.xml");

// permission to display menu
			if (OLIVRight::r($menu->$menuName))
			{
// load menu template
			  $template = OLIVModule::load_template($header);

// call menu parser
			  $menuXml = $this->parse($menu->$menuName,$templateName,$access,status::url());

				$this->template = $template;
				$this->content = $menuXml;

//echoall($menuXml);
			}
		}
  }


//------------------------------------------------------------------------------
// parse menu file
// create content xml-file for renderer
  private function parse($menu,$templateName,$access,$url,$level = 0)
  {

//    $templateName = (string)$menu->attributes()->template;

//TODO	parse intern links -> set url-node
//			access rights	->
//											no r: clear from xml
//											no x: clear link -> set class to disabled
//			active/inactive -> set the class to active/inactive


    if ($menu)
    {
// get name of menu
			$menuName = $menu->getName();
//	    $menuXml = new simpleXmlElement("<menu_$menuName></menu_$menuName>");
	    $menuXml = new simpleXmlElement("<menu></menu>");

//------------------------------------------------------------------------------
// loop over menu entries
      foreach ($menu->children() as $entry)
      {
//------------------------------------------------------------------------------
// display item if read permission
				if (OLIVRight::r($entry) and OLIVRight::r($menu))
				{
					$internUrl = "";

					$menuItemName = $entry->getName();
				
//------------------------------------------------------------------------------
// is intern link
					if (!$entry->url)
					{
	// create correct url
						$internUrl = (string)$entry->getName();
						$entry->url = $internUrl;

						olivxml_insert($entry,OLIVRoute::getTitle($internUrl),"ALL");
				    olivxml_insert($entry,OLIVRoute::getPageName(status::lang(),$internUrl),"ALL");
					}

//------------------------------------------------------------------------------
// set display class aktive / inactive

			    if ($internUrl == $url)
			      $entry->class = "menu_{$templateName}_active";
			    else
			      $entry->class = "menu_{$templateName}_inactive";


// remove link if no x permission
// check for menu_item, menu and page permissions
// set display class to disabled
						$pageXAccess = (string)$access->x;
						$menuXAccess = OLIVRight::x($menu);

						if (!(OLIVRight::x($entry) and $menuXAccess and $pageXAccess))
						{
							$entry->url = "";
							$entry->class = "menu_{$templateName}_disabled";
						}

// create menu_item xml
						$menu_item = new simpleXmlElement("<menu_item_$templateName></menu_item_$templateName>");
						olivxml_insert($menu_item,$entry);

// insert menu_item into new menu structure
						olivxml_insert($menuXml,$menu_item,"ALL");

	//TODO
	// sub menus foun
	// display when active
	/*        if(count($entry))
		      {
		        $subXml = menu::parse($entry,$url,$level+1);
		        $menuName = "menu_item_" . $this->templateName;
		        olivxml_insert($tempXml->$menuName,$subXml);
		      }*/
//		      olivxml_insert($menuXml,$tempXml);
		    }
			}

      return ($menuXml);
    }
    else
      OLIVError::fire("menu::parse - no menu defined");
  }
}
?>
