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
// Header module
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("image.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("image.php - OLIVError not present");


class OLIVImage
{
//------------------------------------------------------------------------------
// call image
// if exist use language version
	public static function _($image,$lang = "")
	{
		global $_MODULES;
		$o = FALSE;


    if ($image)
    {
//------------------------------------------------------------------------------
// look in imagepaths defined in system.xml
			$imagePath = system::imagepath();

			if ($imagePath)
			{
				foreach($imagePath->children() as $entry)
				{
					$tempPath = explode(".",(string)$entry);

					$tempVal = "";
					foreach($tempPath as $pathPart)
					{
// use system variables
						if (system::$pathPart())
							$tempVal .= system::$pathPart();
						else
							$tempVal .= $pathPart;
					}
					if ($tempImage = img_exists($tempVal,(string)$image,$lang))
					{
		  			$o = session_path($tempImage);
		  			break;
					}
				}
			}


//------------------------------------------------------------------------------
// look in module paths
      if (is_array($_MODULES))
      {
        foreach ($_MODULES as $entry)
  			{
  				$path = OLIVModule::getImagePath((string)$entry->name);

  				if ($tempImage = img_exists($path,$image,$lang))
  				{
						$o = session_path($tempImage);
						break;
					}
  			}
  		}
    }

// return image path-name
		return $o;
	}


//------------------------------------------------------------------------------
// create html img string out of img xml
  static public function img($image,$lang="")
  {
    $o = "";

		if (is_object($image))
		{
		  $id = (string)$image->attributes()->id; // tag id
		  $src = (string)$image->attributes()->src; // image name -> src path via OLIVImage
		  $lang = (string)$image->attributes()->lang; // image language
		  $alt = (string)$image->attributes()->alt; // alternative text
		  $width = (string)$image->attributes()->width; // image width
		  $height = (string)$image->attributes()->height; // image height
		  $link = (string)$image->attributes()->link; // link an image
		  $float = (string)$image->attributes()->float; // float definition: left, right
		  $margin = (string)$image->attributes()->margin; // margin distance
		  $margin_left = (string)$image->attributes()->margin_left; // margin distance
		  $margin_right = (string)$image->attributes()->margin_right; // margin distance
		  $margin_top = (string)$image->attributes()->margin_top; // margin distance
		  $margin_bottom = (string)$image->attributes()->margin_bottom; // margin distance
		  $margin_side = (string)$image->attributes()->margin_side; // margin distance

		  if ($path = OLIVImage::_($src,$lang))
		  {

				$style = "";
				// create inline style
				if ($float)
				{
				  $style .= "float:$float;";
				  
// intelligent side margin
				  if ($margin_side)
				  {
				    switch ($float)
				    {
				      case 'left':
				        $style .= "margin-right:$margin_side";
				        break;
				      case 'right':
				        $style .= "margin-left:$margin_side";
				        break;
				    }
				  } 
				}
				else
				{
				  if ($margin_side)
				    $style .= "margin-right:$margin_side;margin-left:$margin_side";
				}


				if ($margin) $style .= "margin:$margin;";
				if ($margin_left) $style .= "margin-left:$margin_left;";
				if ($margin_right) $style .= "margin-right:$margin_right;";
				if ($margin_top) $style .= "margin-top:$margin_top;";
				if ($margin_bottom) $style .= "margin-bottom:$margin_bottom;";


// insert data in parameter string
			  $o .= "<img src='$path'";
			    if ($alt) $o .= " alt='$alt'";
			    if ($height) $o .= " height='$height'";
			    if ($width) $o .= " width='$width'";
					if ($style) $o .= " style='$style'";
					if ($id) $o .= " id='$id'";
			  $o .= ">";
			}
// end style
//echoall($path);
//echoall($o);
		  return ($o);
		}
  }
}




//------------------------------------------------------------------------------
// GLOBAL FUNCTIONS
//
// looks if image exists in path
// check if language coded images exist
// if no extension, look throught defined extensions

function img_exists($path,$image,$lang)
{
  global $_imgType;
  $imgType = "";
  $o = FALSE;


// extract extension and basename
	$imgType = pathinfo($image,PATHINFO_EXTENSION);
	$image = pathinfo($image,PATHINFO_FILENAME);


// extension found
	if ($imgType)
	{
		$image = $image . "." . $imgType;

    // try language version
    $tempImage = img_lang_exists($path,$image,$lang);

  	if (sessionfile_exists($tempImage))
		{
  		$o = $tempImage;
		}
		
    // use normal version
  	elseif (sessionfile_exists($tempImage = $path . $image))
  	{
  		$o = $tempImage;
		}
	}


// no image type defined
// call with extensions recursive
	else
	{
    if (count($_imgType->imgtype))
    {
      foreach($_imgType->imgtype as $entry)
      {
				$o = img_exists($path,$image . "." . (string)$entry,$lang);

				if ($o) break;
      }
    }
	}
//echoall($o);
  return $o;
}


//------------------------------------------------------------------------------
// checks if a language version exists
// language version: directory is image name
//									 imagename = langCode.extension
// return path imageName
//				of FALSE if not found
function img_lang_exists($path,$image,$lang)
{
	$o = $path . $image;


// check for language code
  if (!$lang) $lang = status::lang();


//look directory with image name exist -> language versions
// language code subdirectory found
	if (sessionis_dir($path . $image))
	{
// create path and filename for language version
		$langPath = $path . $image . "/";
		$ext = pathinfo($image, PATHINFO_EXTENSION);

    $langImage = strtolower($lang) . "." . $ext;

		// language version found
		if (sessionfile_exists($langPath . $langImage))
		  $o = $langPath . $langImage;
		else
			$o = "";
	}


// no language subdirektory
// return unchanged parameters
  return($o);
}
?>
