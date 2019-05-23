<style>
#map-wrapper{position:relative;}
#ovelay-dots{position:absolute;}
</style>
<div class="idTabs">
  <ul>
    <li><a href="#clientinfo" tabindex="-1">Live Site Information</a></li>
  </ul>
<div class="items">
<form method="POST" id="clientinfo_Form">
<table width="100%" class="form " id="clientinfo" cellpadding="10" cellspacing="10" border="0">
<tr><td align="center" class="req"><?=$error_messages;?></td></tr>
<tr valign="top" >
		
			
				
        <td colspan="3" id="coordMapContainer">
            <div id="map-wrapper">
            <img src="<?=IMAGES;?>map.jpg" id="coordMap" />
            </div><div id="overlay-dots"></div>

        </td>
 </tr>
</table>

</form>
</div>
<style>
</style>
<script type="text/javascript">
<!--

function FindPosition(oElement)
{
  if(typeof( oElement.offsetParent ) != "undefined")
  {
    for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent)
    {
      posX += oElement.offsetLeft;
      posY += oElement.offsetTop;
    }
      return [ posX, posY ];
    }
    else
    {
      return [ oElement.x, oElement.y ];
    }
}

function GetCoordinates(e)
{
  var PosX = 0;
  var PosY = 0;
  var ImgPos;
  ImgPos = FindPosition(myImg);
  if (!e) var e = window.event;
  if (e.pageX || e.pageY)
  {
    PosX = e.pageX;
    PosY = e.pageY;
  }
  else if (e.clientX || e.clientY)
    {
      PosX = e.clientX + document.body.scrollLeft
        + document.documentElement.scrollLeft;
      PosY = e.clientY + document.body.scrollTop
        + document.documentElement.scrollTop;
    }
  PosX = PosX - ImgPos[0];
  PosY = PosY - ImgPos[1];
  dPosX = PosX +ImgPos[0];
  dPosY = PosY + ImgPos[1];
  document.getElementById("xCoordinate").value = PosX;
  document.getElementById("yCoordinate").value = PosY;
 document.getElementById("X").innerHTML = PosX;
  document.getElementById("Y").innerHTML = PosY;
    var dot = $('<img src="<?=IMAGES;?>dotNew.jpg" />');
    //var dot = $('<div></div>');
    dot.css({
        position: 'absolute',
        height: '5px',
        width: '5px',
        left: dPosX + "px",
        top: dPosY + "px",
        background : '#00000F'
    });
    $("#overlay-dots").html("");
    $("#overlay-dots").append(dot);
    //document.getElementById("coordMapContainer").appendChild(dot);
}

function writeDot(myImg,x,y,status){
    ImgPos = FindPosition(myImg);
    dPosX = x +ImgPos[0];
    dPosY = y + ImgPos[1];
    var dot = $('<img src="<?=IMAGES;?>'+status+'" />');
    //var dot = $('<div></div>');
    dot.css({
        position: 'absolute',
        height: '5px',
        width: '5px',
        left: dPosX + "px",
        top: dPosY + "px",
        background : '#00000F'
    });
    
    $("#overlay-dots").append(dot);
}
//-->
</script>


<script type="text/javascript">
<!--

<?
$result = mysql_query("select xCoordinate, yCoordinate, s.name name from site m join status s using (statusId) ");
$num_rows = @mysql_num_rows($result);
for($i=0;$i<$num_rows;$i++){
     $name = mysql_result($result, $i, "name");
    $xCoordinate = mysql_result($result, $i, "xCoordinate");
    $yCoordinate = mysql_result($result, $i, "yCoordinate");

    switch($name){
        case 'Up':
            $stats = 'dotUp.jpg';
        break;
        case 'Down':
            $stats = 'dotDown.gif';
        break;
        case 'Alert':
            $stats = 'dotAlert.jpg';
        break;
        case 'Maintenance':
            $stats = 'dotMaintenance.jpg';
        break;

        case 'New':
        default:
             $stats = 'dotNew.jpg';
        break;


    }
    echo 'var myImg = document.getElementById("coordMap");';
    echo 'writeDot(myImg,'.$xCoordinate.','.$yCoordinate.',\''.$stats.'\');';

}
?>
//-->
</script>
<script type="text/javascript">
/**VALIDATION FOR NUMERIC FIELDS**/
	function isNumberKey(evt,exemptChar) {
		if(evt.which != 0){
			var charCode = (evt.which) ? evt.which : event.keyCode
			if(charCode == exemptChar) return true;
			if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
	}
/**CANCEL CHANGES**/
	function cancelChanges(url)
	{
		var task= document.getElementById('task').value;
		if(task != 'view'){
			
			var prompt_text = "Are you sure you want to cancel all changes?";
			$.prompt(prompt_text,
			{buttons:{Yes:1, No:0},
			submit: function(e,v,m,f){
				if(v==1){
					$.prompt.close();
					if(task == 'create'){
						location.href = url;
					}else if(task == 'edit'){
						location.href = url;
					}
				}
			}
			});
		}else{
			location.href = url;
		}
		localStorage.clear();
	}

	/**REDIRECT**/
	function redirect(url)
	{
		location.href = url;
	}
</script>