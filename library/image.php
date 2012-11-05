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
if (!system::OLIVTEXT()) die ("image.php - OLIVText not present");
if (!system::OLIVERROR()) die ("image.php - OLIVError not present");


class OLIVImage
{
//------------------------------------------------------------------------------
// call image
// if exist use language version
	public static function _($image,$lang = "")
	{
		global $_MODULES;

    if ($image)
    {
//------------------------------------------------------------------------------
// image is correct path
  		if ($tempImage = img_exists("",$image,$lang))
  		{
  			return (session_path($tempImage));
  		}


//------------------------------------------------------------------------------
// look in system image path
    	if ($tempImage = img_exists(system::OLIV_IMAGES_PATH(),$image,$lang))
      {
  			return (session_path($tempImage));
      }


//------------------------------------------------------------------------------
// look in system template path
    	if ($tempImage = img_exists(system::OLIV_TEMPLATE_PATH() . system::OLIV_TEMPLATE() . "/images/",$image,$lang))
        return (session_path($tempImage));
      elseif ($tempImage = img_exists(system::OLIV_TEMPLATE_PATH() . "/default/images/",$image,$lang))
        return (session_path($tempImage));


//------------------------------------------------------------------------------
// look in module paths
      if (is_array($_MODULES))
      {
      
        foreach ($_MODULES as $entry)
  			{
  				$path = OLIVModule::getImagePath((string)$entry->name);

  				if ($tempImage = img_exists($path,$image,$lang))
					{
						return (session_path($tempImage));
	  			}
  			}
  		}
    }

// image not found
		return false;
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

		  $path = OLIVImage::_($src,$lang);
		  
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
// end style

		  return ($o);
		}
  }
}




//------------------------------------------------------------------------------
// GLOBAL FUNCTIONS
//
// looks if image exists
// check if language coded images exist
// if no extension, look throught defined extensions
function img_exists($path,$image,$lang)
{
  global $_imgType;

  $imgType = "";

// extract extension
	$parts = explode(".",$image);

  if (isset($parts[0]))
   	$image = $parts[0];
  if (isset($parts[1]))
   	$imgType = $parts[1];

// extension found
	if ($imgType)
	{
		$image = $image . "." . $imgType;

    // try language version
    $tempImage = img_lang_exists($path,$image,$lang);
  	if (sessionfile_exists($tempImage))
  		return ($tempImage);

    // use normal version
    $tempImage = $path . $image;
  	if (sessionfile_exists($tempImage))
  		return ($tempImage);
	}

// no image type defined
	else
	{
    if (count($_imgType->imgtype))
    {
      foreach($_imgType->imgtype as $entry)
      {
	 		  $tempImage = img_lang_exists($path,$image . "." . (string)$entry,$lang);

        // match found
        if (sessionfile_exists($tempImage))
        {
          return ($tempImage);
        }
      }
    }
	}
  return false;
}


//------------------------------------------------------------------------------
// checks if a language version exists
// language version: directory is image name
//									 imagename = langCode.extension
// return path imageName
function img_lang_exists($path,$image,$lang)
{
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
		  return($langPath . $langImage);
	}

// no language subdirektory
// use normal image
  return($path . $image);
}
?>
