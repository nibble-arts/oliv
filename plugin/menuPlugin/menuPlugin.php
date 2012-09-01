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
		$tagArray = menuRender::createTag($template,$content);
		$value = (string)$content;
		$source = $content->attributes()->source;
		$itemUrl = (string)$content->attributes()->url;


		$tagArray['link']['title'] = OLIVText::_("edit_menu_item");
		$tagArray['link']['url'] = status::url();
		$tagArray['link']['val'] = OLIVText::_("edit") . "/$source:$value";


//------------------------------------------------------------------------------
// get action parameters
		$command = status::command();
		$partName = explode(":",status::val());


		$partSource = $partName[0];
		if (count($partName) > 1)
			$partItem = $partName[1];


// start editor
// if menu and item selected
		if (($partSource == $source) and ($partItem == $value) and $command == 'edit')
		{
//TODO if extern link: display hyperlink, name and title for change
// make possible to insert or delete entries

//TODO if intern link: display a list of available pages to link
// make possible to change, insert or delete entries

// change of text changes title of page (possible only if owner of page)
// display if not translated -> create a new language file for page


			if (link_is_extern($itemUrl))
			{
				$editString = "<form method='" . status::url() . "'>";

				$editString .= "<div id='menu_edit_frame'>";

					$editString .= OLIVText::_("menu_name") . "<br>";
					$editString .= "<textarea name='$value'>";
						$editString .= $value;
					$editString .= "</textarea><br>";

					$editString .= OLIVText::_("menu_link") . "<br>";
					$editString .= "<textarea name='$itemUrl'>";
						$editString .= $itemUrl;
					$editString .= "</textarea>";
					
					$editString .= "<input type='submit' value='" . OLIVText::_("save") . "'>";
					$editString .= "<input type='submit' value='" . OLIVText::_("cancel") . "'>";

				$editString .= "</div>";

				$editString .= "<input type='hidden' name='val' value='" . OLIVText::_("save") . "/" . status::url() . "'>";
				$editString .= "</form>";
				
				unset($tagArray['link']);
			}
			else
//------------------------------------------------------------------------------
// intern page link
			{
// get page list

				$pages = OLIVRoute::getPages();

//------------------------------------------------------------------------------
//TODO do it with the form method
				$editString = "<form><select name='$value' size='1'>";

				foreach ($pages as $entry)
				{
					if (array_key_exists('NAME',$entry))
						$editString .= "<option>" . $entry['NAME']['text'] . "</option>";
				}

				$editString .= "</select></form>";


			}
//TODO
//------------------------------------------------------------------------------

			$tagArray['value'] = $editString;
		}


    return ($tagArray);
  }
}

?>
