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

function olivfile_exists($file)
{
  return (file_exists(OLIV_CORE_PATH . $file));
}

function olivopendir($path)
{
  return (opendir(OLIV_CORE_PATH . $path));
}

function olivis_dir($path)
{
  return (is_dir(OLIV_CORE_PATH . $path));
}

//------------------------------------------------------------------------------

function session_path($file)
{
	return (OLIV_SESSION_PATH . $file);
//	return (OLIV_CORE_PATH . OLIV_SESSION_PATH . OLIV_SESSION . $file);
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
  return (is_dir(session_path($file)));
}


function sessionparse_ini_file($file)
{
	return (parse_ini_file(session_path($file)));
}

?>
