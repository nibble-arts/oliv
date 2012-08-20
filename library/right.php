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
// Rights management
//
// V0.1
//------------------------------------------------------------------------------

defined('OLIVCORE') or die ("right.php - OLIVCore not present");


class OLIVRight
{

// class call for right checking
// method calls
//   r ... returns true if read permission
//   w ... returns true if write permission
//   x ... returns true if execute permission
// all possible combinations rw, rx, wx, rwx returns true if all permissions
//
//   _ ... returns associative array with r,w,x values
  public static function __callStatic($m,$access)
  {
    $right = OLIVRight::checkAccess($access[0]);

    switch($m)
    {
      case 'r':
        return $right['r'];
        break;

      case 'w':
        return $right['w'];
        break;

      case 'x':
        return $right['x'];
        break;

      case 'rw':
        return ($right['r'] & $right['w']);
        break;

      case 'rx':
        return ($right['r'] & $right['x']);
        break;

      case 'wx':
        return ($right['w'] & $right['x']);
        break;

      case 'rwx':
        return ($right['r'] & $right['w'] & $right['x']);
        break;

      case '_':
        return $right;
        break;
    }
  }


// check for valid rights
// simpleXmlElement access
//   access is the simpleXmlElement of the tag using rights
//
// returns true if access granted
// returns false if no access
  private static function checkAccess($access)
  {
    $owner = "000";
    $group = "000";
    $rights = "";
    $ownRight = "";
    $groupRight = "";
    $allRight = "";
    

//------------------------------------------------------------------------------
// get requested rights
		if (isset($access['owner'])) $owner = (string)$access->attributes()->owner;
    if (isset($access['group'])) $group = (string)$access->attributes()->group;
  
    // decode requested rights
		if (isset($access['access'])) $rights = (string)$access->attributes()->access;
    
    $ownRight = intval(substr($rights,0,1));
    $groupRight = intval(substr($rights,1,1));
    $allRight = intval(substr($rights,2,1));


//------------------------------------------------------------------------------
// get user rights
    $userRight = OLIVUser::getRight(OLIV_USER);
    $userOwner = $userRight->getName();
    $userGroup = OLIVUser::getGroup(OLIV_USER);


//------------------------------------------------------------------------------
// no rights requested
    if (!$ownRight and !$groupRight and !$allRight) return array("r" => 1,"w" => 1,"x" => 1);


//print_r($userGroup->asXML());
//------------------------------------------------------------------------------
// check owner rights
    $r = $w = $x = 0;


// give all rights if superuser
    if (OLIV_SU)
    {
      $r = $w = $x = 1;
    }


    if (OLIV_USER)
    {
      if ($owner == $userOwner)
      {
//      echo "set owner right";
        if($ownRight & 4) $r = 1;
        if($ownRight & 2) $w = 1;
        if($ownRight & 1) $x = 1;
      }
    }
    
// check group rights
    if ($userGroup)
    {
      if ($group == (string)$userGroup->$group->getName())
      {
//    echo "set group right";
        if($groupRight & 4) $r = 1;
        if($groupRight & 2) $w = 1;
        if($groupRight & 1) $x = 1;
      }
    }
    
// check all rights
    if ($access)
    {
//    echo "set all right";
      if($allRight & 4) $r = 1;
      if($allRight & 2) $w = 1;
      if($allRight & 1) $x = 1;
    }

    return (array("r" => $r,"w" => $w,"x" => $x));
  }
}
?>
