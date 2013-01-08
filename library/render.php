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
// Page rendering engine
//
// Version 0.1
//------------------------------------------------------------------------------
//TODO the whole class is unused, due to change to XSLT support

if (!system::OLIVCORE()) die ("render.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("render.php - OLIVError not present");


class OLIVRender extends OLIVCore
{
  var $o = "";

//------------------------------------------------------------------------------
// constructor
  public function __construct()
  {
    $this->o = "";
  }



//------------------------------------------------------------------------------
// return render result
  public function display()
  {
    return ($this->o);
  }


//------------------------------------------------------------------------------
// main rendering routine
// render page to this->o
//------------------------------------------------------------------------------
  public function page($page,$template)
  {
// set parameters for stylesheet display
		$template->stylesheet->setParameter("","lang",status::lang());
		$template->stylesheet->setParameter("","user",status::OLIV_USER());


// start stylesheet processor
		return $template->stylesheet->transformToXML($page->structure());
  }
}
?>
