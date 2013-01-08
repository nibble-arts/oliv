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
// User management
//
// V0.1
//------------------------------------------------------------------------------


if (!system::OLIVCORE()) die ("user.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("error.php - OLIVError not present");


class OLIVUser extends OLIVCore
{
//------------------------------------------------------------------------------
// get full name from user
// return userName if no full name defined
  public static function getName($userName)
  {
    $user = OLIVUser::getRight($userName);
    
    $full = (string)$user->attributes()->full;

    if ($full) return ($full); // return full name
    else return ($userName); // return userName
  }


//------------------------------------------------------------------------------
// check login parameters
// on success set user to session
  public static function checkPassword($user,$password)
  {
    $userRight = OLIVUser::getRight($user);

//echoall((string)$userRight->attributes()->password) . "<br>";
//echoall($user . md5($password));

    if ($userRight)
    {
      if(md5($password) == (string)$userRight->attributes()->password)
        return true;
    }
    else
      return false;
  }


// get user rights
  public static function getRight($user)
  {
    global $_access;

    return ($_access->user->$user);
  }


//------------------------------------------------------------------------------
// get user group
// returns a xml object with the groups the user is assigned to
  public static function getGroup($user)
  {
    global $_access;
    $groupArray = array();
    $groupXml = new simpleXmlElement("<group></group>");
    
    $groups = $_access->group;
    foreach($groups->children() as $entry)
    {
      $groupName = $entry->getName();
      if ($entry->$user)
        $groupXml->addChild($groupName);
    }

    return ($groupXml);
  }


//------------------------------------------------------------------------------
// check if superuser
  public static function superUser($user)
  {
    $userRight = (OLIVUser::getRight($user));

    if ($userRight)
    {
      if ($userRight->attributes()->su) return (TRUE);
      else return (FALSE);
    }
    return (FALSE);
  }
}
?>
