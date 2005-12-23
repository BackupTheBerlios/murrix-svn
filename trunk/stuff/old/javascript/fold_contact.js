
if (document.getElementById)
{
	document.write('<style type="text/css">\n')
	document.write('.submenu{display: none;}\n')
	document.write('</style>\n')

	document.write('<style type="text/css">\n')
	document.write('.menutitlem{display: none;}\n')
	document.write('</style>\n')

	document.write('<style type="text/css">\n')
	document.write('.menutitlep{display: block;}\n')
	document.write('</style>\n')
}

var aryImages = new Array(2);
aryImages[0] = "img/icons/16/down.png";
aryImages[1] = "img/icons/16/up.png";

for (i = 0; i < aryImages.length; i++)
{
	var preload = new Image();
	preload.src = aryImages[i];
}

function swapFoldArrow(imgId, x)
{
	document[imgId].src = aryImages[x];
}

function SwitchCard(objId)
{
	if (document.getElementById)
	{
		var el = document.getElementById("card" + objId);
		
		if (el.style.display != "block")
		{
			el.style.display = "block";
			swapFoldArrow('img_card' + objId, 1);
		}
		else
		{
			el.style.display = "none";
			swapFoldArrow('img_card' + objId, 0);
		}
	}
}

function SwitchAllCards(state)
{
	if (document.getElementById)
	{
		var ar = document.getElementById("master_cards_div").getElementsByTagName("span");
		
		
		
		for (var i = 0; i < ar.length; i++)
		{
			if (ar[i].className == "submenu")
			{
				if (state == true)
				{
					ar[i].style.display = "block";
					swapFoldArrow('img_card' + i, 1);
				}
				else
				{
					ar[i].style.display = "none";
					swapFoldArrow('img_card' + i, 0);
				}
			}
		}

	}
}