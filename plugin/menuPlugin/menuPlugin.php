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
// Preprocessor object
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("plugin::textPlugin.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("plugin::textPlugin.php - OLIVError not present");


class menuPlugin
{
  var $editor;
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($tag,$options)
  {
    $content = $options[0]['content'];
	  $template = $options[0]['template'];
    
		return (menuRender::createTag($template,$content));
	}
}


//------------------------------------------------------------------------------
// edit render class
class menuEditPlugin
{
  static public function __callStatic($tag,$options)
  {
    $content = $options[0]['content'];
	  $template = $options[0]['template'];

//TODO insert edit call

		return (menuEditRender::createTag($template,$content));
  }
}


//------------------------------------------------------------------------------
// render menu item
class menuRender
{

	static public function createTag($template,$content)
	{
		$paramString = "";

// create content
		if ((string)$content->attributes()->url)
		{
			$tagArray['link']['url'] = (string)$content->attributes()->url;
			if ((string)$content->attributes()->title)
				$tagArray['link']['title'] = (string)$content->attributes()->title;
		}



		$itemClass = (string)$content->attributes()->class;
		$itemName = (string)$content->attributes()->name;


// create template tag
		foreach ($template->attributes() as $entry)
		{
			if ($entry->getName() == "tag") $tag = (string)$entry;
			else
			{
				$paramString .= $entry->getName() . "='" . (string)$entry . "' ";
			}
		}


// create return Array
		$tagArray['startTag'] = "<$tag $paramString name='$itemName' class='$itemClass'>";
		$tagArray['value'] = (string)$content;
		$tagArray['endTag'] = "</$tag>";

    return ($tagArray);
  }
}


//------------------------------------------------------------------------------
// render menu item for editing
class menuEditRender
{

	static public function createTag($template,$content)
	{
		$o = "";
		$partSource = "";
		$partItem = "";
		
		$tagArray = menuRender::createTag($template,$content);
		$value = (string)$content;
		$source = $content->attributes()->source;
		$itemUrl = (string)$content->attributes()->url;


// set link parameters for editor
		$tagArray['link']['title'] = OLIVText::_("edit_menu_item");
//		$tagArray['link']['url'] = status::url();
//		$tagArray['link']['val'] = OLIVText::_("edit") . "/$source:$value";
//		$tagArray['link']['onMouseDown'] = "editClick(event)";

//------------------------------------------------------------------------------
// get action parameters
		$command = status::command();
		$partName = explode(":",status::val());


		$partSource = $partName[0];
		if (count($partName) > 1)
			$partItem = $partName[1];


// execute common actions
		switch ($command)
		{
//------------------------------------------------------------------------------
// save changes
			case 'save':
				break;


//------------------------------------------------------------------------------
// remove item
			case 'remove':
				break;

//------------------------------------------------------------------------------
// insert new item
			case 'add':
				break;

//------------------------------------------------------------------------------
// cut item
			case 'cut':
				break;

//------------------------------------------------------------------------------
// copy item
			case 'copy':
				break;

//------------------------------------------------------------------------------
// paste item
			case 'cut':
				break;


			default:
		}


//------------------------------------------------------------------------------
// start editor action
// if menu and item selected
		if (($partSource == $source) and ($partItem == $value))
		{
			switch ($command)
			{

//------------------------------------------------------------------------------
//TODO if extern link: display hyperlink, name and title for change
// make possible to insert or delete entries
				case 'edit':
					$o = "<div id='menu_edit_frame'>";
						$o .= "<form method='post' action='" . OLIVRoute::url("",array('url' => status::url())) . "'>";


// extern page link edit
							if (link_is_extern($itemUrl))
							{
								$o .= OLIVText::_("menu_name") . "<br>";
								$o .= "<textarea name='$value'>";
									$o .= $value;
								$o .= "</textarea><br>";

								$o .= OLIVText::_("menu_link") . "<br>";
								$o .= "<textarea name='$itemUrl'>";
									$o .= $itemUrl;
								$o .= "</textarea>";
				
								$o .= "<input type='submit' value='" . OLIVText::_("save") . "'>";
//							$o .= "<input type='submit' value='" . OLIVText::_("cancel") . "'>";

								$o .= "<input type='hidden' name='val' value='" . OLIVText::_("save") . "/" . $partSource . ":" . $partItem . "'>";
							}
							else

//------------------------------------------------------------------------------
// intern page link edit


//TODO if intern link: display a list of available pages to link
// make possible to change, insert or delete entries

// change of text changes title of page (possible only if owner of page)
// display if not translated -> create a new language file for page
							{

// get page list
								$pages = OLIVRoute::getPages();

//TODO do it with the form method
								$o .= "<select name='page' size='1'>";
									foreach ($pages as $entry)
									{
										if (array_key_exists('NAME',$entry))
											$o .= "<option>" . $entry['NAME']['text'] . "</option>";
									}
								$o .= "</select>";

								$o .= "<input type='submit' value='" . OLIVText::_("save") . "'>";

								$o .= "<input type='hidden' name='val' value='" . OLIVText::_("save") . "/" . $partSource . ":" . $partItem . "'>";
							}

						$o .= "</form>";
					$o .= "</div>";

// set ouput
					$tagArray['value'] = $o;


// remove link on editor
					unset($tagArray['link']);
					break;


				default:
					break;

			}
		}
		else


//------------------------------------------------------------------------------
// display item
		{
// context menu content
//TODO change context class to load file from /context/

			$contextMenu = new OLIVContext("menuPlugin","{$source}_{$value}","{$source}:{$value}");

			if (!OLIVClipboard::_())
				$contextMenu->disable("paste");

			$contextMenu->draw();


// mark extern links
			if (link_is_extern($itemUrl))
			{
				$tagArray['value'] = $tagArray['value'] . " <i class='oliv_small'>extern</i>";
			}
		}


    return ($tagArray);
  }
}

?>
