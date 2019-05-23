<?
//error_reporting(0);

class imageFunctions extends recordUpdate{

				 public function resizeRatio($maxwidth,$maxheight,$src){
						
						$RESIZEHEIGHT = $RESIZEWIDTH ="";
						$width 	= @imagesx($src);
						$height = @imagesy($src);
						if(($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight))
						{
							if($maxwidth && $width > $maxwidth)
							{	$widthratio = $maxwidth/$width;
								$RESIZEWIDTH=true;
							}
							if($maxheight && $height > $maxheight)
							{	$heightratio = $maxheight/$height;
								$RESIZEHEIGHT=true;
							}
							if($RESIZEWIDTH && $RESIZEHEIGHT)
							{	if($widthratio < $heightratio){
									$ratio = $widthratio;
								}else{
									$ratio = $heightratio;
								}
							}elseif($RESIZEWIDTH){
								  $ratio = $widthratio;
							}elseif($RESIZEHEIGHT){
							   $ratio = $heightratio;
							}
							$newwidth = $width * $ratio;
							$newheight = $height * $ratio;
						}else{
							$newwidth = $width;
							$newheight = $height;
						}
						
						return $newwidth .'-'. $newheight ;				 
				}
  
				public function retrieveSettings($type){
						//Retrieve settings value main max width, main max height, thumb max width, thumb max height, max kb per type						
						$arrayValues 	= array('id','ref_id','ref_name','name','remarks1','remarks2');
						$retArray 		= self::retrieveEntry("reference",$arrayValues,"","ref_name='$type'");
						
						$detailArr  	= array();
						foreach($retArray as $retIndex=>$retValue){								 
							$$retIndex 		= $retValue . "<br/>";	
							$mainArr 		= explode('|',$$retIndex);				 				 	 							
							$detailArr[] 	= $mainArr[4]; 
						}					
						return $detailArr;				
				}
				
				
				public function uploadPic($type="",$imgname="",$fname=""){
				 	
					//Retrieve Settings Values
					$detailArr = self::retrieveSettings($type);
					foreach($detailArr as $detailIndex => $detailValue){
							$img_height_large	= $detailArr[0];
							$img_width_large	= $detailArr[1];
							$img_height_thumb	= $detailArr[2];
							$img_width_thumb	= $detailArr[3];
							$pathlarge			= $detailArr[4];
							$paththumb			= $detailArr[5];
							$maxsize			= $detailArr[6];
					}

					
					//check if there's image upload or not
					if(!empty($imgname)){
						$image 			=	$_FILES[$imgname]['name'];
						$uploadedfile 	=	$_FILES[$imgname]['tmp_name'];
						$fimage_type 	=	$_FILES[$imgname]['type'];
					}
				 
					if($image){
					
						$filenames = stripslashes($_FILES[$imgname]['name']);
						$size=filesize($_FILES[$imgname]['tmp_name']);

							if ($size > $maxsize*1024){
								$change='<div class="msgdiv">You have exceeded the size limit!</div> ';
								return $change;
							}
							 
							if($fimage_type == "image/pjpeg" || $fimage_type == "image/jpeg" || $fimage_type == "image/jpg"){									 
								$src = imagecreatefromjpeg($uploadedfile);
								$ext = "jpg";
							}else if($fimage_type == "image/x-png" || $fimage_type == "image/png"){									 
								$src = imagecreatefrompng($uploadedfile);								
								$ext = "png";
							}else if($fimage_type == "image/gif"){	
								$src = imagecreatefromgif($uploadedfile);
								$ext = "gif";
							}
						
							list($width,$height)=getimagesize($uploadedfile);
							//Size for large
							$maxwidth 	= $img_width_large;
							$maxheight	= $img_height_large;
							/************************/
							$sizeValue 	= self::resizeRatio($maxwidth,$maxheight,$src);
							$sizeArr 	= explode('-',$sizeValue);
							$newwidth 	= $sizeArr[0];
							$newheight 	= $sizeArr[1];
							/************************/			
							//Size for thumb
							$maxwidth_thumb 	= $img_width_thumb;
							$maxheight_thumb	= $img_height_thumb;
							/************************/
							$sizeValue_thumb 	= self::resizeRatio($maxwidth_thumb,$maxheight_thumb,$src);
							$sizeArr_thumb	 	= explode('-',$sizeValue_thumb);
							$newwidth1 			= $sizeArr_thumb[0];
							$newheight1		 	= $sizeArr_thumb[1];
							 
							$tmp1				=	imagecreatetruecolor($newwidth1,$newheight1); //thumb			
							$tmp 				=	imagecreatetruecolor($newwidth,$newheight); //large			 
						  
							 
					switch($ext):
					case "png":								 							 
							$transparency = imagecolortransparent($src);
						  
							imagealphablending($tmp, false);
							imagealphablending($tmp1, false);
							$color = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
							$color1 = imagecolorallocatealpha($tmp1, 0, 0, 0, 127);
							imagefill($tmp, 0, 0, $color);
							imagefill($tmp1, 0, 0, $color1);
							imagesavealpha($tmp, true);
							imagesavealpha($tmp1, true);					 
					 
							imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
							imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);
							
							$fullpathlarge 	= $pathlarge. $fname.'.'.$ext;
							$fullpaththumb 	= $paththumb. $fname.'.'.$ext;
						if(empty($change)){					 
							imagepng($tmp, $fullpathlarge);
							imagepng($tmp1, $fullpaththumb);
						}
					break;	
							
					case "jpg":	
							//thumb
							$white1 = imagecolorallocate($tmp1, 255, 255, 255);
							imagefilledrectangle($tmp1, 0, 0, $newwidth1, $newheight1, $white1);
							//large						
							$white = imagecolorallocate($tmp, 255, 255, 255);
							imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $white);
								 
							imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height); //for large
							imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height); //for thumbnail
					
							$fullpathlarge 	= $pathlarge. $fname.'.'.$ext;
							$fullpaththumb 	= $paththumb. $fname.'.'.$ext;					
						if(empty($change)){
							imagejpeg($tmp,$fullpathlarge,100);
							imagejpeg($tmp1,$fullpaththumb,100);
						}
				 
					break;	

					case "gif":	
							 								
						$black = imagecolorallocate($tmp, 0, 0, 0);
						$black1 = imagecolorallocate($tmp1, 0, 0, 0);

						// Make the background transparent
						imagecolortransparent($tmp, $black);
						imagecolortransparent($tmp1, $black1);
						 
						imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
						imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);
						
						$fullpathlarge 	= $pathlarge. $fname.'.'.$ext;
						$fullpaththumb 	= $paththumb. $fname.'.'.$ext;
					if(empty($change)){						 
						imagegif($tmp, $fullpathlarge);
						imagegif($tmp1, $fullpaththumb);		
					}					 
					break;
				endswitch;							
					  	
							imagedestroy($src);
							imagedestroy($tmp);
							imagedestroy($tmp1);
						
						return $ext;
					}					
					
				}
				
				
				
				public function  removePicfromFolder($folder,$filename){				
					if(!empty($filename))
					unlink($folder.$filename);
				}
				
				
				public function resizePic($type="",$imgname="",$fname="",$imgtype="",$startwidth=0,$startheight=0){
				 	
					//Retrieve Settings Values
					$detailArr = self::retrieveSettings($type);
					foreach($detailArr as $detailIndex => $detailValue){
							$img_height_large	= $detailArr[0];
							$img_width_large	= $detailArr[1];
							$img_height_thumb	= $detailArr[2];
							$img_width_thumb	= $detailArr[3];
							$pathlarge			= $detailArr[4];
							$paththumb			= $detailArr[5];
							$maxsize			= $detailArr[6];
					}

					
					//check if there's image upload or not
					if(!empty($imgname)){			
						  $image 			=	$fname;
						  $uploadedfile 	=	$imgname;						
						  $fimage_type 		=	$imgtype;						 
					}
				 
					if($image){		
							$size = filesize($uploadedfile);
							if ($size > $maxsize*1024){
								$change='<div class="msgdiv">You have exceeded the size limit!</div> ';
								return $change;
							}
							 
							if($fimage_type == "jpg"){									 
								$src = @imagecreatefromjpeg($uploadedfile);
								$ext = "jpg";
							}else if($fimage_type == "png"){									 
								$src = @imagecreatefrompng($uploadedfile);								
								$ext = "png";
							}else if($fimage_type == "gif"){	
								$src = @imagecreatefromgif($uploadedfile);
								$ext = "gif";
							}
						
							list($width,$height)=getimagesize($uploadedfile);
							//Size for large
							$maxwidth 	= $img_width_large;
							$maxheight	= $img_height_large;
							/************************/
							$sizeValue 	= self::resizeRatio($maxwidth,$maxheight,$src);
							$sizeArr 	= explode('-',$sizeValue);
							$newwidth 	= $sizeArr[0];
							$newheight 	= $sizeArr[1];
							/************************/			
							//Size for thumb
							$maxwidth_thumb 	= $img_width_thumb;
							$maxheight_thumb	= $img_height_thumb;
							/************************/
							$sizeValue_thumb 	= self::resizeRatio($maxwidth_thumb,$maxheight_thumb,$src);
							$sizeArr_thumb	 	= explode('-',$sizeValue_thumb);
							$newwidth1 			= $sizeArr_thumb[0];
							$newheight1		 	= $sizeArr_thumb[1];
							 
							$tmp1				=	@imagecreatetruecolor($newwidth1,$newheight1); //thumb			
							$tmp 				=	@imagecreatetruecolor($newwidth,$newheight); //large			 
						  
							 
					switch($ext):
					case "png":								 							 
							$transparency = @imagecolortransparent($src);
						  
							@imagealphablending($tmp, false);
							@imagealphablending($tmp1, false);
							$color 	= @imagecolorallocatealpha($tmp, 0, 0, 0, 127);
							$color1 = @imagecolorallocatealpha($tmp1, 0, 0, 0, 127);
							@imagefill($tmp, 0, 0, $color);
							@imagefill($tmp1, 0, 0, $color1);
							@imagesavealpha($tmp, true);
							@imagesavealpha($tmp1, true);					 
					 
							@imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); //for large
							@imagecopyresized($tmp1, $src, 0, 0, $startwidth, $startheight, $newwidth1, $newheight1, $width, $height); //for thumbnail
							
							//$fullpathlarge 	= $pathlarge. $fname;
							//$fullpaththumb 	= $paththumb. $fname;

							$fullpathlarge 	= $pathlarge. $fname.'.'.$ext;
							$fullpaththumb 	= $paththumb. $fname.'.'.$ext;
						if(empty($change)){					 
							@imagepng($tmp, $fullpathlarge);
							@imagepng($tmp1, $fullpaththumb);
						}
					break;	
							
					case "jpg":	
							//thumb
							//$white1 = @imagecolorallocate($tmp1, 255, 255, 255);
							//@imagefilledrectangle($tmp1, 0, 0, $newwidth1, $newheight1, $white1);
							//large						
							$white = @imagecolorallocate($tmp, 255, 255, 255);
							@imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $white);
								 
							@imagecopyresized($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height); //for large
							//@imagecopyresized($tmp1,$src,0,0,$startwidth,$startheight,$newwidth1,$newheight1,$width,$height); //for thumbnail
							@imagecopyresized($tmp1,$src,0,0,$startwidth,$startheight,$newwidth1,$newheight1,$width,$height); //for thumbnail
							
							
							//imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
							
					 
							$fullpathlarge 	= $pathlarge. $fname.'.'.$ext;
							$fullpaththumb 	= $paththumb. $fname.'.'.$ext;					
						if(empty($change)){
							@imagejpeg($tmp,$fullpathlarge,100);
							@imagejpeg($tmp1,$fullpaththumb,100);
						}
				 
					break;	

					case "gif":	
							 								
						$black 	= @imagecolorallocate($tmp, 0, 0, 0);
						$black1 = @imagecolorallocate($tmp1, 0, 0, 0);

						// Make the background transparent
						@imagecolortransparent($tmp, $black);
						@imagecolortransparent($tmp1, $black1);
						  
						@imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); //for large
						@imagecopyresized($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height); //for thumbnail
						
							$fullpathlarge 	= $pathlarge. $fname.'.'.$ext;
							$fullpaththumb 	= $paththumb. $fname.'.'.$ext;
					if(empty($change)){						 
						@imagegif($tmp, $fullpathlarge);
						@imagegif($tmp1, $fullpaththumb);		
					}					 
					break;
				endswitch;							
					  	
							@imagedestroy($src);
							@imagedestroy($tmp);
							@imagedestroy($tmp1);
						
						//return $ext;
					}					
					
				}
}
?>