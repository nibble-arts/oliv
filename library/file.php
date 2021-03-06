<?PHP
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
// oliv session file functions
//
// Version 0.1
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
// system functions
function olivfile_exists($file)
{
	return (file_exists(system::OLIV_CORE_PATH() . $file));
}

function olivopendir($path)
{
  return (opendir(system::OLIV_CORE_PATH() . $path));
}

function olivis_dir($path)
{
  return (is_dir(system::OLIV_CORE_PATH() . $path));
}

function olivxml_load_file($file)
{
	return simplexml_load_file(system::OLIV_CORE_PATH() . $file);
//	$xml = @simplexml_load_file(system::OLIV_CORE_PATH() . $file);
//	if ($xml) return $xml;
//		die ("olivxml_load_file in file.php - fatal error: xml parse error in $file");
}




//------------------------------------------------------------------------------
// session functions
function session_path($file)
{
	return (system::OLIV_SESSION_PATH() . $file);
}

function sessionfile_exists($file)
{
	return (file_exists(session_path($file)));
}

function sessionopendir($file)
{
  return (opendir(session_path($file)));
}

function sessionis_dir($file)
{
  if (is_dir(session_path($file)))
  {
  	return TRUE;
	}
	else
		return FALSE;
}

function sessionparse_ini_file($file,$param="")
{
	return (parse_ini_file(session_path($file),$param));
}

// load from session directory
function sessionxml_load_file($file)
{
	if (file_exists(session_path($file)))
	  return (simplexml_load_file(session_path($file)));
}


// return permissions of file
// -1 ... file don't exist
function get_permission($path)
{
	if (file_exists($path))
		return substr(sprintf('%o', fileperms($path)),4);

	return -1;
}

?>
