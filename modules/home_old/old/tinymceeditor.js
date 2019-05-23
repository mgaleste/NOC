
 
 
<script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
<script type=\"text/javascript\">
	google.load(\"jquery\", \"1\");
</script>


<script type=\"text/javascript\" src=\"plugins/jscripts/tiny_mce/jquery.tinymce.js\"></script>
<script type=\"text/javascript\">
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : 'plugins/jscripts/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull, fontselect,fontsizeselect\",
			theme_advanced_buttons2 : \"pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor\",
			theme_advanced_buttons3 : \"tablecontrols,|,removeformat,|,media,|,fullscreen,styleprops\",			
			theme_advanced_toolbar_location : \"top\",
			theme_advanced_toolbar_align : \"left\",
			theme_advanced_statusbar_location : \"bottom\",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : \"plugins/jscripts/tiny_mce/css/content.css\"
		});
	});
</script>


