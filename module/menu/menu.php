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

  function __construct($header)
  {
    global $_argv;
    $bg_img = "";
    
    // load menu items
    $menuName = (string)$header;
		$this->menuXml = sessionxml_load_file(OLIV_MODULE_PATH . "menu/content/$menuName.xml");

    // load menu template
    $templateName = (string)$this->menuXml->attributes()->template;
    $template = OLIVModule::load_template($header,$templateName);

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// process menu content

// create temporary xml
    $tempXml = new simpleXmlElement("<$menuName></$menuName>");
    foreach ($this->menuXml->attributes() as $key => $val)
    {
      $tempXml->addAttribute($key,$val);
    }

//print_r($tempXml);
//------------------------------------------------------------------------------
// loop children
    $x = 0;
    foreach($this->menuXml->children() as $entry)
    {
      // set style for aktive / inaktive
      $area = (string)$entry->getName(); // count menu_items

//print_r($entry->getName());
      $url = $entry->attributes()->url;
      $img = $entry->attributes()->img;
      $background_img = (string)$entry->attributes()->background_image;

// check if right to display menuItem
      if (OLIVRight::r($entry))
      {

//------------------------------------------------------------------------------
// item aktive
        if ($_argv['url'] == $url)
        {
          if ($background_img)
            $bg_img = $background_img;
          $class = "menu_{$menuName}_active";
        }
  //------------------------------------------------------------------------------
  // item inactive
        else
        {
          if ($background_img)
            $bg_img = $background_img . "_inactive";
          // add class for active item
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
            olivxml_insert($tempXml,olivxml_changeNode("menu_item_{$templateName}",$this->menuXml->menu_item[$x]));
            $x++;
            break;
          
          case 'menu_background':
            olivxml_insert($tempXml,olivxml_changeNode("menu_background_{$templateName}",$entry));
            break;
        }
      }
    }
//print_r($tempXml);
    $this->menuXml = $tempXml;
//print_r($this->menuXml);
//print_r($template->asXML());


// render menu template
    	$this->o .= OLIVRender::template($template,$this->menuXml);
  }






//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// render item
// $area ... name of area to be rendered in
// $item ... simpleXmlElement of content
  public function renderItem($area,$item)
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
  }
}

?>
