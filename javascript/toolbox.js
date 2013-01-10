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

//------------------------------------------------------------------------------
function showToolbox()
{
	toolboxObj.addEventListener('click',hideToolbox,false);
	toolboxAnimation(0);

	toolgripImgObj.src = setImageIn(toolgripImgObj.src);
}

//------------------------------------------------------------------------------
function hideToolbox()
{
	toolboxObj.addEventListener('click',showToolbox,false);
	toolboxAnimation(toolboxHidden);

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
function timeToolbox()
{
	setTimeout("hideToolbox();",1000);
}


function centerToolbar()
{
	screenHeight = parseInt(document.body.offsetHeight);
	toolboxYPos = (screenHeight - toolboxHeight) / 2;
	toolboxObj.style.marginTop = toolboxYPos + "px";
}


function centerToolgrip()
{
	toolgripHeight = toolgripObj.offsetHeight;
	toolgripYPos = (toolboxHeight - toolgripHeight) / 2;
	toolgripObj.style.top = -toolgripYPos + "px";
}


function setImageIn(image)
{
	image.replace(/out/gi,"in");
	return image;
}


function setImageOut(image)
{
	image.replace(/in/gi,"out");
	return image;
}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// check if page is loaded
function checkLoad()
{
	if (document.readyState === "complete")
	{
		toolboxObj = document.getElementById("toolbox");

		toolboxWidth = parseInt(toolboxObj.offsetWidth);
		toolboxHeight = parseInt(toolboxObj.offsetHeight);
		toolboxHidden = parseInt(toolboxWidth) - parseInt(toolboxVisibleBorder);
		toolgripObj = document.getElementById("toolbox_grip");
		toolgripImgObj = document.getElementById("toolgrip_img");

		toolboxObj.style.marginRight = "0px";

		centerToolgrip();
		centerToolbar();
		hideToolbox();		
	}
	else
	{
		setTimeout('checkLoad();', 500)
	}
}
