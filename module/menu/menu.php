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
			$menu = status::pagestructure();

// permission to display menu
			if (OLIVRight::r($menu->$menuName))
			{
// load menu template
			  $template = OLIVModule::load_template($header);

// call menu parser
			  $menuXml = $this->parse($menu,$menuName,$templateName,$access,status::url());

				$this->template = $template;
				$this->content = $menuXml;

//echoall($menuXml);
			}
		}
  }


//------------------------------------------------------------------------------
// parse menu file
// create content xml-file for renderer

// menu ... xml with menu structure
// templateName ... name of template to use
// access ... module access permissions
// level ... sub menu level (used in recursion)
  private function parse($menus,$menuName,$templateName,$access,$url,$level = 0)
  {
		$menu = $menus->$menuName;
		$active = FALSE;
		
    if ($menu)
    {
// get name of menu
			$menuName = $menu->getName();
	    $menuXml = new simpleXmlElement("<menu></menu>");

// open path to actice menu
			$this->openPath($menus,$url);

//------------------------------------------------------------------------------
// loop over menu entries
      foreach ($menu->children() as $entry)
      {
		  	$visible = FALSE;
				$subMenuVisible = $entry['visible'];

				if ($subMenuVisible)
					$entry->visible = "visible";

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
// look for module link
						$mod = (string)$entry["mod"];
						$page = (string)$entry["page"];
						$name = $entry->getName();

						if ($mod and $page)
						{
							olivxml_insert($entry,OLIVModule::getContentFriendlyName($mod,$name),"ALL");
							olivxml_insert($entry,OLIVModule::getContentName($mod,$name),"ALL");
							olivxml_insert($entry,OLIVModule::getContentTitle($mod,$name),"ALL");

							$internUrl = "href():" . $page;

							$entry->url = $internUrl;
//							echoall($internUrl);
						}

						else
						{
// create correct url
							$internUrl = $name;

							$entry->url = $internUrl;
							$urlName = OLIVText::xml(OLIVRoute::getPageName(status::lang(),$internUrl));

// expand url with val
							if ($val = (string)$entry['val'])
							{
								$valArray = OLIVModule::parse_param_string($val);

	/*							if (array_key_exists("mod",$valArray) and array_key_exists("content",$valArray))
								{
									$contentName = OLIVModule::getContentName($valArray['mod'],$valArray['content']);
									olivxml_insert($entry->val,OLIVModule::getContentFriendlyName($valArray['mod'],$valArray['content']));
									$contentTitle = OLIVModule::getContentTitle($valArray['mod'],$valArray['content']);

									olivxml_insert($entry->title,$contentTitle);
									olivxml_insert($entry->name,$contentName);
								}*/
							}
							else
							{
								olivxml_insert($entry,OLIVRoute::getTitle($internUrl),"ALL");
								olivxml_insert($entry,OLIVRoute::getPageName(status::lang(),$internUrl),"ALL");
							}
						}
					}


//------------------------------------------------------------------------------
// set display class

//------------------------------------------------------------------------------
// aktive / inactive
			    if ($internUrl == $url)
			    {
			    	$visible = $url;
			    	$entry->status = "active";
			      $entry->class = "{$templateName} menu_{$templateName}_active";
					}
			    else
			      $entry->class = "{$templateName} menu_{$templateName}_inactive";

// submenu level
					if ($level)
						$entry->class = "menu{$level}_" . $entry->class;
					else
						$entry->class = "menu_" . $entry->class;
//------------------------------------------------------------------------------


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

// get submenu name
					$subName = (string)$entry['submenu'];

					if ($subName)
						$entry->submenu = $subName;
					
					
// create menu_item xml
					$menu_item = new simpleXmlElement("<menu_item_$templateName></menu_item_$templateName>");
					olivxml_insert($menu_item,$entry);

// insert menu_item into new menu structure
					olivxml_insert($menuXml,$menu_item,"ALL");



//------------------------------------------------------------------------------
// look if aktive menu is in submenu
// display sub menus
					if ($subName and $visible or $subMenuVisible)
					{
		// call menu recursive
						$subMenu = $this->parse($menus,$subName,$templateName,$access,$url,$level+1);
						olivxml_insert($menuXml,$subMenu);
					}

		    }
			}
//echoall($menuXml);
      return ($menuXml);
    }
//    else
//      OLIVError::fire("menu::parse - no menu defined");
  }


// get path to selected menu item
	private function openPath($menus,$url)
	{
		$node = $menus->XPath("*[$url]");

// has parent
		if ($node)
		{
			$parentName = $node[0]->getName();

// set node to visible
			if (!(string)$node[0]->$url->attributes()->visible)
			{
				$node[0]->$url->addAttribute('visible',"show");
			}

// recursion if has parent
			if ($parentName)
				$this->openPath($menus,$parentName);
		}

	}

}
?>
