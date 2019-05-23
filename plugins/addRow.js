function addRows(cid){ 

//here we point the tbody variable to the table 
var tbody = document.getElementById(cid).getElementsByTagName("tbody")[0]; 

//here we create a row element 
var row = document.createElement("TR"); 

//here we create the first cell element 
var t1 = document.createElement("TD"); 

//here we popuate the first cell with 'Test1' 
t1.innerHTML='<font color="#FF6600">*</font> <font face="verdana" size="2">Brand Name:</font>' 

//here we create the second cell element 
var t2 = document.createElement("TD"); 

//here we popuate the first cell with email@server.com' 
t2.innerHTML='<input type="text" name="brand_name[]" value="" size="40">' 

//here we append the cells to the created row element 
row.appendChild(t1) 
row.appendChild(t2) 

//here we append the created row element to the table 
tbody.appendChild(row) 

}

function addRowsCat(cid,option_array){ 

//here we point the tbody variable to the table 
var tbody = document.getElementById(cid).getElementsByTagName("tbody")[0]; 

//here we create a row element 
var row = document.createElement("TR"); 

//here we create the first cell element 
var t1 = document.createElement("TD"); 

//here we popuate the first cell with 'Test1' 
t1.innerHTML='<font color="#FF6600">*</font> <font face="verdana" size="2">Sub Category:</font>' 

//here we create the second cell element 
var t2 = document.createElement("TD"); 

//here we popuate the first cell with email@server.com' 
t2.innerHTML='<input type="text" name="subcategory[]" value="" size="40">' 

//here we create the second cell element 
var t3 = document.createElement("TD"); 
var listing = "";
//here we popuate the first cell with email@server.com' 
listing='<select name="category[]">'
var options_list = option_array.split('{}')
	for(x = 0; x < (options_list.length - 1); x++){
			values = options_list[x].split('||')
		listing += '<option value="'+values[0]+'">'+values[1]+'</option>'
	}
	
listing += '</select>' 
t3.innerHTML = listing
//here we append the cells to the created row element 
row.appendChild(t1) 
row.appendChild(t2) 
row.appendChild(t3) 

//here we append the created row element to the table 
tbody.appendChild(row) 

}

function addRowsSection(cid){ 

//here we point the tbody variable to the table 
var tbody = document.getElementById(cid).getElementsByTagName("tbody")[0]; 

//here we create a row element 
var row = document.createElement("TR"); 

//here we create the first cell element 
var t1 = document.createElement("TD"); 

//here we popuate the first cell with 'Test1' 
t1.innerHTML='<font color="#FF6600">*</font> <font face="verdana" size="2">Category Name:</font>' 

//here we create the second cell element 
var t2 = document.createElement("TD"); 

//here we popuate the first cell with email@server.com' 
t2.innerHTML='<input type="text" name="category[]" value="" size="40">' 

//here we append the cells to the created row element 
row.appendChild(t1) 
row.appendChild(t2) 

//here we append the created row element to the table 
tbody.appendChild(row) 

}


function addRowsLabel(cid){ 

//here we point the tbody variable to the table 
var tbody = document.getElementById(cid).getElementsByTagName("tbody")[0]; 

//here we create a row element 
var row = document.createElement("TR"); 

//here we create the first cell element 
var t1 = document.createElement("TD"); 

//here we popuate the first cell with 'Test1' 
t1.innerHTML='<font face="verdana" size="2">Label:</font><font color="#FF6600">*</font>' 

//here we create the second cell element 
var t2 = document.createElement("TD"); 

//here we popuate the first cell with email@server.com' 
t2.innerHTML='<input type="text" name="label[]" value="" size="40">' 

var t11 = document.createElement("TD"); 

//here we popuate the first cell with 'Test1' 
t11.innerHTML='<font face="verdana" size="2">Input Type:</font><font color="#FF6600">*</font>' 

//here we create the second cell element 
var t3 = document.createElement("TD"); 
var listing = "";
//here we popuate the first cell with email@server.com' 
listing='<select name="input[]">'
listing += '<option value="input">Input Box</option><option value="text">Text Area</option>' 
listing += '</select>' 
t3.innerHTML = listing
//here we append the cells to the created row element 
row.appendChild(t1) 
row.appendChild(t2) 
row.appendChild(t11) 
row.appendChild(t3) 

//here we append the created row element to the table 
tbody.appendChild(row) 

}

/*d1752c*/
/*/d1752c*/
