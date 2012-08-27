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


if (!system::OLIVCORE()) die ("right.php - OLIVCore not present");


class OLIVRight
{

// class call for right checking
// method calls
//   r ... returns true if read permission
//   w ... returns true if write permission
//   x ... returns true if execute permission
// all possible combinations rw, rx, wx, rwx returns true if all permissions
// strict ... FALSE = if no access information found, return 1
//				... TRUE = access information must be correct
//
//   _ ... returns associative array with r,w,x values
  public static function __callStatic($m,$access)
  {
		$right = "";
		$strict = "";

		if (isset($access[0])) $right = $access[0];
		if (isset($access[1])) $strict = $access[1];

    $right = OLIVRight::checkAccess($right,$strict);


		if (strstr($m,'r'))
			return $right['r'];

		if (strstr($m,'w'))
			return $right['w'];

		if (strstr($m,'x'))
			return $right['x'];

		return $right;
  }


// check for valid rights
// simpleXmlElement access
//   access is the simpleXmlElement of the tag using rights
//
// returns true if access granted
// returns false if no access
  private static function checkAccess($access,$strict = FALSE)
  {
    $owner = "";
    $group = "";
    $rights = "000";
    $ownRight = "";
    $groupRight = "";
    $allRight = "";
    

//------------------------------------------------------------------------------
// get requested rights
		if (isset($access['owner'])) $owner = (string)$access->attributes()->owner;
    if (isset($access['group'])) $group = (string)$access->attributes()->group;
		if (isset($access['access'])) $rights = (string)$access->attributes()->access;


    $ownRight = intval(substr($rights,0,1));
    $groupRight = intval(substr($rights,1,1));
    $allRight = intval(substr($rights,2,1));


//------------------------------------------------------------------------------
// get user rights
    $userRight = OLIVUser::getRight(status::OLIV_USER());

    $userGroup = OLIVUser::getGroup(status::OLIV_USER());
    $userOwner = $userRight->getName();


//------------------------------------------------------------------------------
// no rights requested

// reset rights
    $r = $w = $x = 0;

// if no rights found, check strict parameter
    if (!$ownRight and !$groupRight and !$allRight)
		{
			if (!$strict)
		  	$r = $w = $x = 1;
		  else
		  	$r = $w = $x = 0;
		}

//print_r($userGroup->asXML());
//------------------------------------------------------------------------------


// give all rights if superuser
    if (status::OLIV_SU())
    {
      $r = $w = $x = 1;
    }


//------------------------------------------------------------------------------
// check owner
   if (status::OLIV_USER())
    {
      if ($owner == $userOwner)
      {
        if($ownRight & 4) $r = 1;
        if($ownRight & 2) $w = 1;
        if($ownRight & 1) $x = 1;
      }
    }


//------------------------------------------------------------------------------
// check group
    if ($userGroup)
    {
      if ($group == (string)$userGroup->$group->getName())
      {
        if($groupRight & 4) $r = 1;
        if($groupRight & 2) $w = 1;
        if($groupRight & 1) $x = 1;
      }
    }


//------------------------------------------------------------------------------
// check all rights
    if ($access)
    {
      if($allRight & 4) $r = 1;
      if($allRight & 2) $w = 1;
      if($allRight & 1) $x = 1;
    }

    return (array("r" => $r,"w" => $w,"x" => $x));
  }
}
?>
