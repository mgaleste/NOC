function ValidateForm(dml,chkName){
	len = dml.elements.length;
	var i=0;
	for( i=0 ; i<len ; i++) {
		if ((dml.elements[i].name==chkName) && (dml.elements[i].checked==1))
			return confirm('Are you sure you want to delete your selection(s)?');	
	}
		alert("Please select at least one record to be deleted")
		return false;
}

function ValidateDisabler(dml,chkName){
	len = dml.elements.length;
	var i=0;
	for( i=0 ; i<len ; i++) {
		if ((dml.elements[i].name==chkName) && (dml.elements[i].checked==1))
			return confirm('Are you sure you want to disable/suspend your selection(s)?');	
	}
		alert("Please select at least one user to be suspended")
		return false;
}
