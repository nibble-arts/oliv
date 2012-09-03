function contextMenu()
{
	// Nur für IE 5+ und NN 6+

	ie5=(document.getElementById && document.all && document.styleSheets)?1:0;
	nn6=(document.getElementById && !document.all)?1:0;

// Kontextmenü initialisieren

	if (ie5 || nn6) {
		      menuWidth=122, menuHeight=183;
		      menuStatus=0;

		      sp2="  ";
		      sp5=sp2+sp2+" "; // Leerzeichen als Abstandshalter (flexibler und code-sparender als eine aufwendige Tabellenkonstruktion)

		      oF="onfocus='if(this.blur)this.blur()'"; // Um hässlichen Linkrahmen in einigen Browsern zu vermeiden

		      document.write(
		              "<div id='oliv_context_menu'>"+
		              "<table id='oliv_context_table' cellpadding='5' cellspacing='0' width='"+menuWidth+"' height='"+menuHeight+"'>"+
		              "<tr><td id='oliv_context_item'><a class='menu' href='javascript:history.back()'"+oF+"> Zurück"+sp5+sp5+sp2+"</a></td></tr>"+
		              "<tr><td id='oliv_context_item'><a class='menu' href='javascript:history.forward()'"+oF+"> Vorwärts"+sp5+sp2+sp2+"</a></td></tr>"+
		              "<tr><td id='oliv_context_item'><hr class='menu'><a class='menu' href='javascript:location.reload()'"+oF+"> Aktualisieren"+sp2+sp2+"</a></td></tr>"+
		              "<tr><td id='oliv_context_item'><a class='menu' href='javascript:viewSource()'"+oF+"> Quelltext"+sp5+sp2+sp2+"</a></td></tr>"+
		              "<tr><td id='oliv_context_item'><a class='menu' href='javascript:print()'"+oF+"> Drucken"+sp5+sp5+"</a></td></tr>"+
		              "<tr><td id='oliv_context_item'><hr class='menu'><a class='menu' href='javascript:openFrameInNewWindow()' "+oF+"> Neues Fenster"+sp2+"</a></td></tr>"+
		              "</table></div>");


		      // Rechter Mausklick: Menü anzeigen, linker Mausklick: Menü verstecken
		      document.oncontextmenu=showMenu; //oncontextmenu geht nicht bei NN 6.01
		      document.onmouseup=hideMenu;
	}
}

// Kontextmenü anzeigen
function showMenu(e) {
        if(ie5) {
                if(event.clientX>menuWidth) xPos=event.clientX-menuWidth+document.body.scrollLeft;
                else xPos=event.clientX+document.body.scrollLeft;
                if (event.clientY>menuHeight) yPos=event.clientY-menuHeight+document.body.scrollTop;
                else yPos=event.clientY+document.body.scrollTop;
        }
        else {
                if(e.pageX>menuWidth+window.pageXOffset) xPos=e.pageX-menuWidth;
                else xPos=e.pageX;
                if(e.pageY>menuHeight+window.pageYOffset) yPos=e.pageY-menuHeight;
                else yPos=e.pageY;
        }
        document.getElementById("oliv_context_menu").style.left=xPos;
        document.getElementById("oliv_context_menu").style.top=yPos;
        menuStatus=1;
        return false;
}

// Kontextmenü verstecken
function hideMenu(e) {
        if (menuStatus==1 && ((ie5 && event.button==1) || (nn6 && e.which==1))) {
                setTimeout("document.getElementById('oliv_context_menu').style.top=-250",250);
                menuStatus=0;
        }
}

// Quelltext anzeigen
function viewSource() {
        var w=window.open("view-source:"+window.location,'','resizable=1,scrollbars=1');
}

// Seite in neuem Fenster öffnen
function openFrameInNewWindow() {
        var w=window.open(window.location,'','resizable=1,scrollbars=1,status=1,location=1,menubar=1,toolbar=1');
}


