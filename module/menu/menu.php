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
    
//echoall($_argv);
    

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
//echoall($menu);
    $templateName = (string)$menu->attributes()->template;


    if ($menu)
    {
      foreach ($menu->children() as $entry)
      {
  // Page definition
        $pageName = $entry->getName();
        $itemName = OLIVRoute::translatePageName(OLIV_LANG,$pageName);


// aktive menuitem found
        if ($pageName == $url)
          $active = "menu_{$templateName}_activ";
        else
          $active = "menu_{$templateName}_inactive";
          

//echo "<hr>";
//echoall($this->templateName);
        $tempXml = new simpleXmlElement("<menu_item_{$this->templateName}
          name='menu_$pageName'
          url='$pageName'
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

 
//echoall($tempXml);
//echoall($menuXml->asXML());
      return ($menuXml);
    }
    else
      OLIVError::fire("menu::parse - no menu defined");
  }



/*
//------------------------------------------------------------------------------
// loop trough template recursive and find repeat parameters
  private function _repeat(&$template)
  {
    $templateName = $template->getName();


    foreach($template as $entry)
    {
      $entryArray = array();


    // repeat parameter found
      $id = (string)$entry->attributes()->id;
      $menuEntries = $this->menuXml->$id;


  // call recursion
      if (count($entry->children()))
      {
        $this->_repeat($entry);
      }
      elseif ($id == (string)$menuEntries->getName())
      {
    // loop if more than one item
        if (count($menuEntries) > 1)
        {

//TODO change index of first entry => 0
          
          
          for ($x = 1;$x < count($menuEntries); $x++)
          {
      // insert entry in array
            $tag = $entry->getName();
            $entryXml = new simpleXmlElement("<$tag id='{$id}'></$tag>");

            array_push($entryArray,$entryXml);
          }
        }
      }
    }

// insert new items in template
    foreach($entryArray as $entry)
    {
      olivxml_insert($template,$entry);
    }

    return $entryArray;
  }
*/


//TODO move to menu plugin
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// render item
// $area ... name of area to be rendered in
// $item ... simpleXmlElement of content
/*  public function renderItem($area,$item)
  {
    global $_argv;
//print_r($item);
    $text = OLIVText::_((string)$item);

    $url = (string)$item->attributes()->url;
    $img = (string)$item->attributes()->img;
    $class = (string)$item->attributes()->class;
    $itemTitle = (string)$item->attributes()->title;

    if ($img)
    {
      if ($_argv['url'] == $url)
        $text = "<img src='" . OLIV_MODULE_PATH . "menu/template/" . OLIV_TEMPLATE . "/images/" . $img . ".png' alt='$url'>";
      else
        $text = "<img src='" . OLIV_MODULE_PATH . "menu/template/" . OLIV_TEMPLATE . "/images/" . $img . "_inactive.png' alt='$url'>";
    }

    $option = array(
      "url" => $url,
      "param" => array(
        "title" => $itemTitle,
        "class" => $class)
    );
    
    // create link
    $o .= OLIVRoute::intern($text,$option);

    return ($o);
  }*/


}




//echoall($this->template);
//echoall($this->menuXml);
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// process menu content

//TODO create menu content from template and menuXml
// id ... menu_item + template name
// look for repeat parameter for row / colum


// create temporary menu xml
/*    $tempMenuXml = new simpleXmlElement("<$menuName></$menuName>");
    foreach ($this->menuXml->attributes() as $key => $val)
    {
      $tempMenuXml->addAttribute($key,$val);
    }


// create temporary template xml
    $tempTemplateXml = new simpleXmlElement("<template></template>");



//------------------------------------------------------------------------------
// loop menu children
    $x = 0;
    foreach($this->menuXml->children() as $entry)
    {
      // set style for aktive / inaktive
      $area = (string)$entry->getName(); // count menu_items

      $url = (string)$entry->attributes()->url;
      $img = (string)$entry->attributes()->img;
      $background_img = (string)$entry->attributes()->background_image;


// check if right to display menuItem
      if (OLIVRight::r($entry))
      {
//------------------------------------------------------------------------------
// item aktive
        if ($_argv['url'] == $url)
        {
    // set background image and class for aktive display
          if ($background_img)
            $bg_img = $background_img;
          $class = "menu_{$menuName}_active";
        }


//------------------------------------------------------------------------------
// item inactive
        else
        {
    // set background image and class for inaktive display
          if ($background_img)
            $bg_img = $background_img . "_inactive";
          $class = "menu_{$menuName}_inactive";
        }


// set attributes for menu items
        switch ($area)
        {
          case 'menu_item':
    // change if image is inactive
            if ($bg_img)
            {
              $this->menuXml->menu_item[$x]['background_image'] = $bg_img;
            }

    // add class for inactive item
            if ($class) $this->menuXml->menu_item[$x]->addAttribute("class",$class);
  

    // change item node name
            olivxml_insert($tempMenuXml,olivxml_changeNode("menu_item_{$this->templateName}",$this->menuXml->menu_item[$x]));
            $x++;
            break;
          
          case 'menu_background':
            olivxml_insert($tempMenuXml,olivxml_changeNode("menu_background_{$this->templateName}",$entry));
            break;
        }
      }
    }
*/
//echoall($tempMenuXml);
//    $this->menuXml = $tempMenuXml;
//echoall($this->menuXml);
//echoall($this->template);

// render menu template
?>
