<?php
ob_start();
?>
<html>
<head>
	<link rel="stylesheet" href="../view/template1/css/mystyles.css" type="text/css"/>
	<script src="../plugins/jquery.min.js"></script>
</head>
<body>
	<script src="../plugins/signaturepad/jquery.signaturepad.min.js"></script>
	<script>
    $(document).ready(function () {
      $('.sigPad').signaturePad({drawOnly : true});
    });
  </script>
	<form method="POST" id="photo_infoForm" class="sigPad">
		<table width="100%" class="form " id="photoInfo" cellpadding="10" cellspacing="10" border="0">
			<tr valign="top">
				<td>
					<div class="sig sigWrapper">
						<div class="typed"></div>
						<canvas class="pad" id="signaturePad" style="margin-left: 200px; border: 1px solid #FFF; " width="500" height="200"></canvas>
						<input type="hidden" name="output" class="output">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<input class="flat_button" type="button" name="submit" value="Capture" onClick="convertCanvasToImage()"/>
					<a href="#clear"><input type="button" class="flat_button clearButton" value="Clear"/></a>
					
					<input type="button" style="display:none" class="flat_button" name="upload" id="upload" value="Save Image" onclick="saveSignature()"/>
					<input type="hidden" id="fileData" value=""/>
				</td>
			</tr>
		</table>				
	</form>
<script src="../plugins/signaturepad/json2.min.js"></script>
</body>
<script>
	function convertCanvasToImage() {
	var image = new Image();
	canvas = document.getElementById('signaturePad');
	var imgNew	= image.src = canvas.toDataURL('image/jpeg');
	
	document.getElementById('fileData').value = imgNew;
	document.getElementById('upload').style.display	= 'inline'; 	
	return image;
	};

	function saveSignature(){
		var postFile =  document.getElementById('fileData').value;
		//var fname = document.getElementById('firstname').value;
		//var lname = document.getElementById('lastname').value;
		//if((postFile=="null" || postFile=="") || (fname=="" || fname=="null") || (lname=="" || lname=="null")){
		//	alert("fill-up form completely please");	
		//}else{
			//alert("hello "+fname+" "+lname);
			//post to server
			$.post("savesign.php",{file:postFile},function(data,status){
			
				if(status=="success")
				$("#loading").replaceWith("");
				//alert(data);
				//window.location="takesign.php";
				
				
				
				//alert('file has been uploaded successfully');
			});
			
			$("body").append('<div id="loading" style="position:fixed;z-index:10;top:60px;left:600px"><img src="loading.gif" alt="loading..." style="width:30px;height:30px"></div>');					
		//}
		}
</script>
</html>
<?php
ob_end_flush();
?>