// init routine
// hide toolbox

var toolbox_clipboard;
var toolboxObj;
var toolgripObj;
var toolgripImgObj;

var toolboxVisibleBorder = 0;
var toolboxAnimationSpeed = 2;
var toolboxWidth;
var toolboxHeight;
var toolboxPos = 0;


checkLoad();


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// call from the module
function toolbox(func)
{
	alert("call method " + func);
}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// move toolbox out of hidden position animated
function showToolbox()
{
	toolboxAnimation(0);

	toolboxObj.addEventListener('click',hideToolbox,false);
	toolgripImgObj.src = setImageIn(toolgripImgObj.src);
}

//------------------------------------------------------------------------------
// hide toolbox animatied
function hideToolbox()
{
	toolboxAnimation(toolboxHidden);

	toolboxObj.addEventListener('click',showToolbox,false);
	toolgripImgObj.src = setImageOut(toolgripImgObj.src);
}

//------------------------------------------------------------------------------
// animation timer
function toolboxAnimation(end)
{
// get toolbox position
	pos = Math.abs(parseInt(toolboxObj.style.marginRight));

// end animation if near target
	if (Math.abs(pos - end) <= toolboxAnimationSpeed)
	{
		pos = end;
		toolboxObj.style.marginRight = -end + "px";
		toolboxObj.style.visibility = "visible";
	}


// animate up
	if (pos < end)
	{
		pos += toolboxAnimationSpeed;
		toolboxObj.style.marginRight = -pos + "px";
		setTimeout(function() { toolboxAnimation(end); },1);
	}

// animate down
	if (pos > end)
	{
		pos -= toolboxAnimationSpeed;
		toolboxObj.style.marginRight = -pos + "px";
		setTimeout(function() { toolboxAnimation(end); },1);
	}
}


//------------------------------------------------------------------------------
// set timeout timer to hide toolbox
function timeToolbox()
{
	setTimeout("hideToolbox();",1000);
}


//------------------------------------------------------------------------------
// position the toolbox vertically in the browser 
function centerToolbar()
{
	screenHeight = parseInt(document.body.offsetHeight);
	toolboxYPos = (screenHeight - toolboxHeight) / 2;
	toolboxObj.style.marginTop = toolboxYPos + "px";
}

//------------------------------------------------------------------------------
// position the grip vertically relative to the toolbox
function centerToolgrip()
{
	toolgripHeight = toolgripObj.offsetHeight;
	toolgripYPos = (toolboxHeight - toolgripHeight) / 2;
	toolgripObj.style.top = -toolgripYPos + "px";
}


//TODO not working
//------------------------------------------------------------------------------
// change the grip image to in
function setImageIn(image)
{
	return image.replace(/(_out)/gi,"_in");
}


//------------------------------------------------------------------------------
// change the grip image to out
function setImageOut(image)
{
	return image.replace(/(_in)/gi,"_out");
}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// init toolbox when page is loaded
function checkLoad()
{
	if (document.readyState === "complete")
	{
		toolboxObj = document.getElementById("toolbox");
		toolboxObj.style.marginRight = "0px";
		toolboxWidth = parseInt(toolboxObj.offsetWidth);
		toolboxHeight = parseInt(toolboxObj.offsetHeight);
		toolboxHidden = parseInt(toolboxWidth) - parseInt(toolboxVisibleBorder);

		toolgripObj = document.getElementById("toolbox_grip");
		toolgripImgObj = document.getElementById("toolgrip_img");


		centerToolgrip();
		centerToolbar();
		hideToolbox();		
	}
	else
	{
		setTimeout('checkLoad();', 500)
	}
}
