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

if (!system::OLIVCORE()) die ("render.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("render.php - OLIVError not present");

class imgPlugin
{
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($tag,$options)
  {
    $content = $options[0];
    $value = $options[0]['template'];


    return (imgRender::tagString($tag,$value,$content));
  }
}


//------------------------------------------------------------------------------
// edit render class
class imgEditPlugin
{
  static public function __callStatic($tag,$options)
  {
  }
}


//------------------------------------------------------------------------------
// render tag string
class imgRender
{
  static public function tagString($tag,$value,$options)
  {
		$class = "";	
    $retArray = array();


//echoall($options);
    $content = $options['template'];
// get language code of text snippet and mark field if not translated
		$lang = OLIVText::_($value,"lang");
    $ownerLang = $content->attributes()->lang;


		$imgUrl = OLIVImage::_((string)$content->attributes()->src);

//echoall("lang: $lang, ownerLang: $ownerLang, OLIV_LANG: " . OLIV_LANG);
// check for permissions
    if (OLIVRight::w($content) and $ownerLang and ($ownerLang != system::OLIV_LANG()))
    {


  // mark for no translation
  		if (($lang != system::OLIV_LANG()) and OLIVText::_((string)$value))
  			$class = "oliv_not_translated";
    }

    $retArray['startTag'] = "<img src='$imgUrl'>";
    $retArray['value'] = ""; // textPlugin::getText($value,$header);
    $retArray['endTag'] = "</img>";

    return ($retArray);
  }
}
?>
