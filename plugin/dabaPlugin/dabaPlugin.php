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
  public static function __callStatic($tag,$options)
  {
		$content = $options[0];

		switch($tag)
		{
			case 'daba_init':
				$content = dabaPlugin::init($content,$tag);
				break;

			case 'daba':
				$content = dabaPlugin::field($content,$tag);
				break;

			case 'daba_image':
				$content = dabaPlugin::image($content,$tag);
				break;
		}

		return $content;
  }
  
//------------------------------------------------------------------------------
// init database
  static private function init($content,$tag)
  {
//TODO initialize database
  	$nodes = $content->XPath("//$tag");

		for ($i = 0;$i < count($nodes);$i++)
		{
// get node and clear init string from page
			$initString = (string)$nodes[$i];
			$nodes[$i][0] = "Init database with: $initString";
		}

		return $content;
  }


//------------------------------------------------------------------------------
// get database field content
  static public function field($content,$tag)
  {
  	$nodes = $content->XPath("//$tag");

		for ($i = 0;$i < count($nodes);$i++)
		{
			$nodes[$i][0] = "Display daba field: " . (string)$nodes[$i];
// get node and clear init string from page
			$fieldName = (string)$nodes[$i];
//			$nodes[$i][0] = "";
		}

		return $content;
  }


//------------------------------------------------------------------------------
// use field content for image display
  static public function image($content,$tag)
  {
  	$nodes = $content->XPath("//$tag");

		for ($i = 0;$i < count($nodes);$i++)
		{
			$nodes[$i][0] = "Display image with name: " . (string)$nodes[$i];
// get node and clear init string from page
//			$initString = (string)$nodes[$i];
//			$nodes[$i][0] = "";
		}

		return $content;
  }
}




/*
//------------------------------------------------------------------------------
// edit render class
class dabaEditPlugin
{
  static public function __callStatic($tag,$options)
  {
		$tagArray = dabaRender::render($tag,$options);

		if ($tagArray)
			$tagArray['value'] .= "<br>for editing";

		return ($tagArray);
  }
}


//------------------------------------------------------------------------------
// render database tag
class dabaRender
{
	static public function render($tag,$options)
	{
		$tagArray = array();
    $content = $options[0]['template'];

		if (status::daba())
		{
			$dabaArray = status::daba();


			switch ($tag)
			{
				case 'daba':
					$value = $dabaArray[(string)$content];
					break;

				case 'dabaimg':
					if ($src = $content->attributes()->src)
					{
// set the source
						$content->attributes()->src = $src . "/" . $dabaArray[(string)$content];
						$value = OLIVImage::img($content);
					}
					break;
			}
			
			$tagArray = array(
				'startTag' => '<daba>',
				'endTag' => '</daba>',
				'value' => OLIVText::_($value)
			);
		}
		
    return ($tagArray);
	}
}*/


/*
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
			$dabaId = status::command();
			$dabaValue = status::cmdval();

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

// retranslate and get field from request list
//		$request = OLIVText::getId($idArray[0]);

			$dabaField = (string)$daba->$dabaName->request->$dabaId;


//echoall($idArray);
// request found 
			if ($dabaField)
			{
// 
				if ($dabaValue)
				{
					$filterArray =  array(new simpleXmlElement("<where field='$dabaField' value='$dabaValue' operator='=' />"));
					$idString = $dabaId;


// get connection to database
					$db = new OLIVDatabase($daba->$dabaName,status::lang());


// load field and set set status
					status::set("daba",$db->get($dabaTable,$filterArray));
				}
				else
					OLIVError::fire("database.php::__construct - no search id found");
				
			}

// no request defined -> abbort daba init
			else
				OLIVError::fire("database.php::__construct - field in request definition not found");
		}
  }
}*/
?>
