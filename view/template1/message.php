
<script type="text/javascript" src="{plug}/menu/jquery.min.js"></script>
		
<script> 	
			$(document).ready(function(e) {			
			
				// Check if there's a message	
				if($('.message').length) {
					// the timeout is there to make things work properly in IE
					// If we have no timeout IE will trigger mousemove instantly
					setTimeout(removeMessage, 1000);		    
				} 	
			});
			
			function removeMessage(){
				$(document).one('click mousemove keypress', function(e) {
				    // Fade the message away after 500 ms
					$('.message').animate({ opacity: 1.0 }, 1000).fadeOut();
				});		
			} 
		  
		</script> 