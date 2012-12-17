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


//------------------------------------------------------------------------------
// create menu
  function __construct($header)
  {
// load menu items
    $menuName = (string)$header->param;

		$menu = OLIVModule::load_xml($header,"","menu.xml");

// permission to display menu
		if (OLIVRight::r($menu->$menuName))
		{

// load menu template
		  $templateName = (string)$menu->$menuName->attributes()->template;
		  $template = OLIVModule::load_template($header,$templateName);

// call menu parser
	    $menuXml = $this->parse($menu->$menuName,status::url());

			$this->o['template'] = $template;
			$this->o['content'] = $menuXml;
		}
  }


//------------------------------------------------------------------------------
// parse menu file
// create content xml-file for renderer
  private function parse($menu,$url,$level = 0)
  {
    $menuXml = new simpleXmlElement("<menu></menu>");

//    $templateName = (string)$menu->attributes()->template;

//TODO	parse intern links -> set url-node
//			access rights	->
//											no r: clear from xml
//											no x: clear link -> set class to disabled
//			active/inactive -> set the class to active/inactive

		$templateName = (string)$menu->attributes()->template;

    if ($menu)
    {
// get name of menu
			$menuName = $menu->getName();

//------------------------------------------------------------------------------
// loop over menu entries
      foreach ($menu->children() as $entry)
      {
				$internUrl = "";

				$menuItemName = $entry->getName();
				
//------------------------------------------------------------------------------
// is intern link
				if (!$entry->url)
				{
	// create correct url
					$internUrl = (string)$entry->getName();
					$entry->url = (string)OLIVRoute::url(status::lang(),$internUrl,status::val());

					$entry->title = OLIVRoute::translateTitle($internUrl);
		      $entry->name = OLIVRoute::translatePageName(status::lang(),$internUrl);
				}

//------------------------------------------------------------------------------
// translate name and title
				$entry->title = OLIVText::xml($entry->title);				
				$entry->name = OLIVText::xml($entry->name);				

//------------------------------------------------------------------------------
// set display class aktive / inactive
	      if ($internUrl == $url)
	        $entry->class = "menu_{$templateName}_active";
	      else
	        $entry->class = "menu_{$templateName}_inactive";


//------------------------------------------------------------------------------
// display item if read permission
				if (OLIVRight::r($entry))
				{
// remove link if no x permission
// set display class to disabled
					if (!OLIVRight::x($entry))
					{
						$entry->url = "";
						$entry->class = "menu_{$templateName}_disabled";
					}

// create menu_item xml
						$menu_item = new simpleXmlElement("<menu_item></menu_item>");
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
//echoall($menuXml);
      return ($menuXml);
    }
    else
      OLIVError::fire("menu::parse - no menu defined");
  }
}
?>
