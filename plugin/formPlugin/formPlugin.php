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
// html form plugin
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("render.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("render.php - OLIVError not present");

class formPlugin
{
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($method,$options)
  {
  	$content = $options[0];
  	$tag = $options[1];


// get include information
		$nodes = $content->XPath("//form");


// loop through all nodes
		for ($i = 0;$i < count($nodes);$i++)
		{
			$formMethod = $nodes[$i]["action"];
			
// if no action -> use index.php
			if(!$formMethod)
			{
				$nodes[$i]["action"] = "index.php";
				$nodes[$i]["method"] = "post";

// insert hidden url parameter
				if (status::url())
				{
					$newNode = $nodes[$i]->addChild("input");
					$newNode["type"] = "hidden";
					$newNode["name"] = "url";
					$newNode["value"] = status::url();
				}

// insert hidden val parameter
				if (status::val())
				{
					$newNode = $nodes[$i]->addChild("input");
					$newNode["type"] = "hidden";
					$newNode["name"] = "val";
					$newNode["value"] = status::val();
				}				

// insert hidden lang parameter
				if (status::lang())
				{
					$newNode = $nodes[$i]->addChild("input");
					$newNode["type"] = "hidden";
					$newNode["name"] = "lang";
					$newNode["value"] = status::lang();
				}				
			}

		}

		return($content);
  }
}
?>
