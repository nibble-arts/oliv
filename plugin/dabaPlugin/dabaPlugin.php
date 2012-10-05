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

if (!system::OLIVCORE()) die ("plugin::textPlugin.php - OLIVCore not present");
if (!system::OLIVERROR()) die ("plugin::textPlugin.php - OLIVError not present");


class dabaPlugin
{
  
//------------------------------------------------------------------------------
// render class
  static public function __callStatic($tag,$options)
  {
		return dabaRender::render($tag,$options);
  }
}


//------------------------------------------------------------------------------
// edit render class
class dabaEditPlugin
{
  static public function __callStatic($tag,$options)
  {
		$tagArray = dabaRender::render($tag,$options);
		$tagArray['value'] .= "<br>for editing";

		return ($tagArray);
  }
}


//------------------------------------------------------------------------------
class dabaRender
{
	static public function render($tag,$options)
	{
		$tagArray = array();
    $content = (string)$options[0]['template'];

		if (status::daba())
		{
			$dabaArray = status::daba();

			$tagArray = array(
				'startTag' => '<daba>',
				'endTag' => '</daba>',
				'value' => $dabaArray[$content]
			);
		}
		
    return ($tagArray);
	}
}


//------------------------------------------------------------------------------
class dabaInit
{
// init database connection
// load field

  public static function __callStatic($tag,$options)
  {
// load daba definitions
		$daba = sessionxml_load_file(system::OLIV_PLUGIN_PATH() . "daba/daba.xml");
    $content = $options[0]['template'];

		if ($content)
		{
// get parameters from header
	 		$dabaParam = (string)$content;
			$dabaArray = explode(";",$dabaParam);


// TODO translate field with request entry in daba definition
// get val parameter
			$dabaId = status::val();
			$idArray = explode("/",$dabaId);



// parse parameters
			foreach ($dabaArray as $entry)
			{
				$entryArray = explode("=",$entry);
			
				switch ($entryArray[0])
				{
					case 'db':
						$dabaName = $entryArray[1];
						break;

					case 'table':
						$dabaTable = $entryArray[1];
						break;
				}
			}

//echoall("db: $dabaName, table: $dabaTable");
// the field used for the id parameter is defined in the daba.xml in the
// request section

// get field from request list
//		$request = OLIVText::getId($idArray[0]);

			$request = $idArray[0];
			$dabaField = (string)$daba->$dabaName->request->$request;

echoall($idArray);
// request found 
			if ($dabaField)
			{
// 
				if (count($idArray) > 1)
				{
					$dabaId =  array(new simpleXmlElement("<where field='$dabaField' value='$idArray[1]' operator='=' />"));
// get connection to database
					$db = new OLIVDatabase($daba->$dabaName,status::lang());

// load field and set set status
					status::set("daba",$db->get($dabaTable,$dabaId));
				}
				else
					OLIVError::fire("database.php::__construct - no search id found");
				
			}

// no request defined -> abbort daba init
			else
				OLIVError::fire("database.php::__construct - field in request definition not found");
		}
  }
}
?>
