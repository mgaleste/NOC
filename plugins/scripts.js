function popupForm(popuptype,sub_x,type)
{
	var file="";
	var title="";
	
	if(popuptype=="category"){	 
		file="popups/category.php?type="+type;
		title="List of Category";
	} 
	 
	var mywin = window.open(file,"","width=900,height=200,left=200,top=200,toolbar=0,status=0,scrollbars=1,resize=1");
	mywin.focus();
}
