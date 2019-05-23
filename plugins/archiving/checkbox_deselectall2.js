//==========================================
// Check All boxes
//==========================================
function CheckAll(fmobj)
{
	for (var i=0;i<fmobj.elements.length;i++)
	{
		var e = fmobj.elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox') && (!e.disabled))
		{
			e.checked = fmobj.allbox.checked;
		}
	}
}

//==========================================
// Check all or uncheck all?
//==========================================
function CheckCheckAll(fmobj)
{	
	var TotalBoxes = 0;
	var TotalOn = 0;
	for (var i=0;i<fmobj.elements.length;i++)
	{
		var e = fmobj.elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		{
			TotalBoxes++;
			if (e.checked)
			{
				TotalOn++;
			}
		}
	}
	
	if (TotalBoxes==TotalOn)
	{
		fmobj.allbox.checked=true;
	}
	else
	{
		fmobj.allbox.checked=false;
	}
}