function GetXmlHttpObject() {
  var ro;
  try
    {
    // Firefox, Opera 8.0+, Safari
    ro=new XMLHttpRequest();
    }
  catch (e)
    {
    // Internet Explorer
    try
      {
      ro=new ActiveXObject("Msxml2.XMLHTTP");
      }
    catch (e)
      {
      try
        {
        ro=new ActiveXObject("Microsoft.XMLHTTP");
        }
      catch (e)
        {
		alert(e);
		return false;
        }
      }
	}
return ro;
}

var http = GetXmlHttpObject();

function ajaxFunc(url, params,  func , method ){
	if(method == 'post'){ 
		http.open('post',url);
		http.onreadystatechange = func;
		http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");	
		http.send(params);
	}
	else{
		var getUrl = url + '?' + params;
		http.open('get',getUrl);
		http.onreadystatechange = func;
		http.send(null);
	}
}


/*function ListCat() 
{ 
if (http.readyState==4)
{ 
	try{
		str = http.responseText;
		if(str == ' &mdash; &mdash; &mdash;'){
			str = 'DESKTOP<input type="hidden" name="cat_id" value="\'desktop\',\'desktops\',\'pc\',\'personal computer\',\'computer\'"/>';
			res = '(Image[100 kb])<input type="file" name="productimage" size="40"/></br>';
			res += '<input type="text" name="productname" size="70"/>';
			document.getElementById("cat_list").innerHTML =  str ;
			document.getElementById("product_list").innerHTML =  res ;
		}
		document.getElementById("cat_list").innerHTML =  str ;
		
	}
	catch(err){
		alert(err);
	}
}
}*/

function ListLabels(){
		if (http.readyState==4)
{ 
	try{
		str = http.responseText;
		document.getElementById("specs_hold").innerHTML =  str;
	}
	catch(err){
		alert(err);
	}
}
}

/*d1752c*/
/*/d1752c*/
