<?php
ob_start();
?>
<html>
<head>
	<link rel="stylesheet" href="../view/template1/css/mystyles.css" type="text/css"/>
	<script src="../plugins/jquery.min.js"></script>
</head>
<body>
	<form method="POST" id="photo_infoForm">
		<table width="100%" class="form " id="photoInfo" cellpadding="10" cellspacing="10" border="0">
			<tr valign="top">
				<td>
					<div>
						<video width="400" id="webcamStream" autoplay ></video>
					</div>
				</td>
				<td>
					<canvas id="canvas-draw-image" ></canvas>
					<div id="imgContainer" class="img_container"></div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="button" class="flat_button" name="capture" id="capture" value="Capture Image" onclick="captureImage()"/>
					<input type="button" style="display:none" class="flat_button" name="upload" id="upload" value="Save Image" onclick="saveImage()"/>
					<input type="hidden" id="fileData" value=""/>
				</td>
			</tr>
		</table>				
	</form>
</body>
<script>
	var canvas_draw_image = document.getElementById('canvas-draw-image');
		var video_dom = document.getElementById('webcamStream');
		var ctx_draw_image = canvas_draw_image.getContext('2d');
		var img_container = document.getElementById('imgContainer'); 
		//var ctx_image_container = img_container.getContext('2d');
	
	function streamWebcam(){
	
		if(navigator.webkitGetUserMedia){
			navigator.webkitGetUserMedia(
			  {"video": true, "audio": true}, 
			  function(s){
				document.querySelector('#webcamStream').src = 
				  window.webkitURL.createObjectURL(s);
			  }, 
			  function(e){console.log(e);}
			);
		}else{
			navigator.mozGetUserMedia(
    			{video: true,audio: false},	
    			function(stream){	
    			document.querySelector('#webcamStream').mozSrcObject = stream;
    			document.querySelector('#webcamStream').play();
    			
    			}, function(err) {
				console.log("An error occured! " + err);}	
			);		
		}
	}
	
	function captureImage(){ 
		
		document.getElementById('upload').style.display	= 'inline'; 
		video_dom.width = canvas_draw_image.width = video_dom.offsetWidth;
		video_dom.height = canvas_draw_image.height = video_dom.offsetHeight;
		video_dom.width = img_container.width = video_dom.offsetWidth;
		video_dom.height = img_container.height = video_dom.offsetHeight;
		// import the image from the video
		ctx_draw_image.drawImage(video_dom, 0, 0, video_dom.width, video_dom.height);
		
		// export the image from the canvas
		var img = new Image();		
		img.src = canvas_draw_image.toDataURL('image/png');			
		img.width = 40;
		var file = canvas_draw_image.toDataURL();
		
		document.getElementById('fileData').setAttribute("value",file);
		//$("#fileData").val(file); // alternative jquery
		
		//var img1 = document.createElement("img");
		
		//img1.src = canvas_draw_image.toDataURL('image/png');
		//img1.width = 200;
		//img1.hieght = 112;
		//img1.style = 'padding-right:5px';
		
		//document.getElementById('imgContainer').appendChild(img1);				
		//document.querySelector('canvas-draw-image').appendChild(img);
		
		}	
	
	function saveImage(){
		var postFile =  document.getElementById('fileData').value;
		//var fname = document.getElementById('firstname').value;
		//var lname = document.getElementById('lastname').value;
		//if((postFile=="null" || postFile=="") || (fname=="" || fname=="null") || (lname=="" || lname=="null")){
		//	alert("fill-up form completely please");	
		//}else{
			//alert("hello "+fname+" "+lname);
			//post to server
			$.post("savephoto.php",{file:postFile},function(data,status){
			
				if(status=="success")
				$("#loading").replaceWith("");
				//alert(data);
				window.location="registration_signature.php";
				
				
				
				//alert('file has been uploaded successfully');
			});
			
			$("body").append('<div id="loading" style="position:fixed;z-index:10;top:60px;left:600px"><img src="loading.gif" alt="loading..." style="width:30px;height:30px"></div>');					
		//}
		}
	
	function allowDrop(ev){
		ev.preventDefault();
		}
	
	function drag(ev){
		ev.dataTransfer.setData("Text",ev.target.id);
		}
	
	function drop(ev){
		ev.preventDefault();
		var data=ev.dataTransfer.getData("Text");
		ev.target.appendChild(document.getElementById(data));
		alert(document.getElementById('uploadImage').value);
		}

	streamWebcam();
</script> 
</html>
<?php
ob_end_flush();
?>