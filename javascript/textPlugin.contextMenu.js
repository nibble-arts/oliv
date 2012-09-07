//
// OLIV
//
// This file is part of the OLIV Content Management System.
//
// Copyright(c) 2012 Thomas H Winkler
// thomas.winkler@iggmp.net
//
// This file may be licensed under the terms of of the
// GNU General Public License Version 3 (the ``GPL').
//
// Software distributed under the License is distributed
// on an ``AS IS' basis, WITHOUT WARRANTY OF ANY KIND, either
// express or implied. See the GPL for the specific language
// governing rights and limitations.
//
// You should have received a copy of the GPL along with this
// program. If not, go to http://www.gnu.org/licenses/gpl.html
// or write to the Free Software Foundation, Inc.,
// 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
//

//------------------------------------------------------------------------------
// context javascript
//
// Version 0.1
//------------------------------------------------------------------------------

function textPlugin_contextMenu(name)
{
	$(document).ready( function() {
		$("div[name=" + name + "]").contextMenu({
			menu: name
		},
			function(action, el, pos)
			{
				var actionArray;
				var linkPath;


				actionArray = action.split(";");

				cmd = actionArray[0]; // command for processing
				command = actionArray[1]; // command for link (translation)
				url = actionArray[2];
				value = actionArray[3];

				linkPath = url + command + "/";

// area for javascript actions
				switch(cmd)
				{
					case 'add':
						window.location=linkPath + value;
						break;

					case 'move':
						alert ("move " + value);
						break;

					case 'edit':
						window.location=linkPath + value;
						break;

					case 'delete':
						window.location=linkPath + value;
						break;

					case 'cut':
						window.location=linkPath + "menuitem/" + value;
						break;

					case 'copy':
						window.location=linkPath + "menuitem/" + value;
						break;

					case 'paste':
						alert ("paste " + value);
						break;
				}
				
/*				alert(
					'Action: ' + action + '\n\n' +
					'Element ID: ' + $(el).attr('id') + '\n\n' +
					'X: ' + pos.x + '  Y: ' + pos.y + ' (relative to element)\n\n' +
					'X: ' + pos.docX + '  Y: ' + pos.docY+ ' (relative to document)'
				);*/

// end of actions
		 });
	});
}
