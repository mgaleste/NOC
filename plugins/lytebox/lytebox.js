//**************************************************************************************************/
//	Lytebox v4.0
//
//	 Author: Markus F. Hay
//  Website: http://www.dolem.com/lytebox
//	   Date: July 30, 2011
//	License: Creative Commons Attribution 3.0 License (http://creativecommons.org/licenses/by/3.0/)
//**************************************************************************************************/
String.prototype.trim = function () { return this.replace(/^\s+|\s+$/g, ''); }
function Lytebox() {
		
	// Below are the default settings that the lytebox viewer will inherit (look and feel, behavior) when displaying content. Member
	// properties that start with "__" can be manipulated via the data-lyte-options attribute (i.e. data-lyte-options="theme:red").
	
	/*** Configure Lytebox ***/
		this.theme				= 'black';		// themes: black (default), grey, red, green, blue, gold
		this.innerBorder		= true;			// controls whether to show the inner border around image/html content
		this.outerBorder		= true;			// controls whether to show the outer grey (or theme) border
		this.resizeSpeed		= 8;			// controls the speed of the image resizing (1=slowest and 10=fastest)
		this.maxOpacity			= 80;			// higher opacity = darker overlay, lower opacity = lighter overlay
		this.borderSize			= 12;			// if you adjust the padding in the CSS, you will need to update this variable -- otherwise, leave this alone...
		
		this.__hideObjects		= true;			// controls whether or not objects (such as Flash, Java, etc.) should be hidden when the viewer opens
		this.__autoResize		= true;			// controls whether or not images should be resized if larger than the browser window dimensions
		this.__doAnimations		= true;			// controls whether or not "animate" Lytebox, i.e. resize transition between images, fade in/out effects, etc.
		this.__forceCloseClick 	= false;		// if true, users are forced to click on the "Close" button when viewing content
		this.__refreshPage		= false;		// force page refresh after closing Lytebox
		this.__showPrint		= false;		// true to show print button, false to hide
		this.__navType			= 3;			// 1 = "Prev/Next" buttons on top left and left (default)
												// 2 = "<< prev | next >>" links next to image number
												// 3 = navType_1 + navType_2 (show both)
	
	/*** Configure Lyteframe (html viewer) Options ***/
		this.__width			= '80%';		// default width of content viewer
		this.__height			= '80%';		// default height of content viewer
		this.__scrollbars		= 'auto';		// controls the content viewers scollbars -- options are auto|yes|no
	
	/*** Configure Lyteshow (slideshow) Options ***/
		this.__slideInterval	= 4000;			// change value (milliseconds) to increase/decrease the time between "slides"
		this.__showNavigation	= false; 		// true to display Next/Prev buttons/text during slideshow, false to hide
		this.__showClose		= true;			// true to display the Close button, false to hide
		this.__showDetails		= true;			// true to display image details (caption, count), false to hide
		this.__showPlayPause	= true;			// true to display pause/play buttons next to close button, false to hide
		this.__autoEnd			= true;			// true to automatically close Lytebox after the last image is reached, false to keep open
		this.__pauseOnNextClick	= false;		// true to pause the slideshow when the "Next" button is clicked
		this.__pauseOnPrevClick = true;			// true to pause the slideshow when the "Prev" button is clicked
		this.__loopSlideshow	= false;		// true to continuously loop through slides, false otherwise
	
	/*** Configure Lytetip (tooltips) Options ***/
		this.changeTipCursor 	= true; 		// true to change the cursor to 'help', false to leave default (inhereted)
		this.tipStyle 			= 'classic';	// sets the default tip style if none is specified via data-lyte-options. Possible values are classic, info, help, warning, error
	
	/*** Configure Event Callbacks ***/
		this.__beforeStart		= '';			// function to call before the viewer starts
		this.__afterStart		= '';			// function to call after the viewer starts
		this.__beforeEnd		= '';			// function to call before the viewer ends (after close click)
		this.__afterEnd			= '';			// function to call after the viewer ends
	
	if(this.resizeSpeed > 10) { this.resizeSpeed = 10; }
	if(this.resizeSpeed < 1) { this.resizeSpeed = 1; }
	this.resizeDuration = (11 - this.resizeSpeed) * 0.15;		
	// Hash for navType - by A.Popov http://s3blog.org
	this.navTypeHash = new Object();
	this.navTypeHash['Hover_by_type_1'] 	= true;
	this.navTypeHash['Display_by_type_1'] 	= false;
	this.navTypeHash['Hover_by_type_2'] 	= false;
	this.navTypeHash['Display_by_type_2']	= true;
	this.navTypeHash['Hover_by_type_3'] 	= true;
	this.navTypeHash['Display_by_type_3'] 	= true;
	this.resizeWTimerArray		= new Array();
	this.resizeWTimerCount		= 0;
	this.resizeHTimerArray		= new Array();
	this.resizeHTimerCount		= 0;
	this.showContentTimerArray	= new Array();
	this.showContentTimerCount	= 0;
	this.overlayTimerArray		= new Array();
	this.overlayTimerCount		= 0;
	this.imageTimerArray		= new Array();
	this.imageTimerCount		= 0;
	this.timerIDArray			= new Array();
	this.timerIDCount			= 0;
	this.slideshowIDArray		= new Array();
	this.slideshowIDCount		= 0;
	this.imageArray	 = new Array();
	this.activeImage = null;
	this.slideArray	 = new Array();
	this.activeSlide = null;
	this.frameArray	 = new Array();
	this.activeFrame = null;
	this.checkFrame();
	this.isSlideshow 	= false;
	this.isLyteframe 	= false;
	this.tipSet	 		= false;
	this.ie = this.ie6 = this.ie7 = this.ie8 = this.ie9 = false;
	this.setIEVersion();
	this.classAttribute = (((this.ie && this.doc.compatMode == 'BackCompat') || this.ie6 || this.ie7) ? 'className' : 'class');
	this.classAttribute = (this.ie && (document.documentMode == 8 || document.documentMode == 9)) ? 'class' : this.classAttribute;	
	// (07/20/2011) Save last func for body.onscroll - fixed by A.Popov http://s3blog.org
	this.bodyOnscroll = document.body.onscroll;
	this.initialize();		
}
Lytebox.prototype.setIEVersion = function() {	
	var ver = -1;
	if (navigator.appName == 'Microsoft Internet Explorer') {
		var ua = navigator.userAgent;
		var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null) {
			ver = parseFloat( RegExp.$1 );
		}
		this.ie = (ver > -1 ? true : false);
		this.ie6 = (ver == 6 ? true : false);
		this.ie7 = (ver == 7 ? true : false);
		this.ie8 = (ver == 8 ? true : false);
		this.ie9 = (ver == 9 ? true : false);
	}
};
Lytebox.prototype.initialize = function() {
	this.updateLyteboxItems();
	var oBody = this.doc.getElementsByTagName('body').item(0);		
	var oLauncher = this.doc.createElement('a');
		oLauncher.setAttribute('id','lbLauncher');
		oLauncher.style.display = 'none';
		oBody.appendChild(oLauncher);		
	if (this.doc.getElementById('lbOverlay')) {
		oBody.removeChild(this.doc.getElementById('lbOverlay'));
		oBody.removeChild(this.doc.getElementById('lbMain'));
	}	
	var oOverlay = this.doc.createElement('div');
		oOverlay.setAttribute('id','lbOverlay');
		oOverlay.setAttribute(this.classAttribute, this.theme);
		if ((this.ie && this.ie6) || ((this.ie7 || this.ie8 || this.ie9) && this.doc.compatMode == 'BackCompat')) {
			oOverlay.style.position = 'absolute';
		}
		oOverlay.style.display = 'none';
		oBody.appendChild(oOverlay);		
	var oLytebox = this.doc.createElement('div');
		oLytebox.setAttribute('id','lbMain');
		oLytebox.style.display = 'none';
		oBody.appendChild(oLytebox);
	var oOuterContainer = this.doc.createElement('div');
		oOuterContainer.setAttribute('id','lbOuterContainer');
		oOuterContainer.setAttribute(this.classAttribute, this.theme);
		oLytebox.appendChild(oOuterContainer);			
	var oIframeContainer = this.doc.createElement('div');
		oIframeContainer.setAttribute('id','lbIframeContainer');
		oIframeContainer.style.display = 'none';
		oOuterContainer.appendChild(oIframeContainer);			
	var oIframe = this.doc.createElement('iframe');
		oIframe.setAttribute('id','lbIframe');
		oIframe.setAttribute('name','lbIframe')
		oIframe.setAttribute('frameBorder','0');
		if (this.innerBorder) {
			oIframe.setAttribute(this.classAttribute, this.theme);
		}
		oIframe.style.display = 'none';
		oIframeContainer.appendChild(oIframe);	
	var oImageContainer = this.doc.createElement('div');
		oImageContainer.setAttribute('id','lbImageContainer');
		oOuterContainer.appendChild(oImageContainer);	
	var oLyteboxImage = this.doc.createElement('img');
		oLyteboxImage.setAttribute('id','lbImage');
		if (this.innerBorder) {
			oLyteboxImage.setAttribute(this.classAttribute, this.theme);
		}
		oImageContainer.appendChild(oLyteboxImage);			
	var oLoading = this.doc.createElement('div');
		oLoading.setAttribute('id','lbLoading');
		oLoading.setAttribute(this.classAttribute, this.theme);
		oOuterContainer.appendChild(oLoading);			
	var oDetailsContainer = this.doc.createElement('div');
		oDetailsContainer.setAttribute('id','lbDetailsContainer');
		oDetailsContainer.setAttribute(this.classAttribute, this.theme);
		oOuterContainer.appendChild(oDetailsContainer);	
	var oDetailsData =this.doc.createElement('div');
		oDetailsData.setAttribute('id','lbDetailsData');
		oDetailsData.setAttribute(this.classAttribute, this.theme);
		oDetailsContainer.appendChild(oDetailsData);		
	var oDetails = this.doc.createElement('div');
		oDetails.setAttribute('id','lbDetails');
		oDetailsData.appendChild(oDetails);	
	var oCaption = this.doc.createElement('span');
		oCaption.setAttribute('id','lbCaption');
		oDetails.appendChild(oCaption);			
	var oHoverNav = this.doc.createElement('div');
		oHoverNav.setAttribute('id','lbHoverNav');
		oImageContainer.appendChild(oHoverNav);		
	var oBottomNav = this.doc.createElement('div');
		oBottomNav.setAttribute('id','lbBottomNav');
		oDetailsData.appendChild(oBottomNav);		
	var oPrev = this.doc.createElement('a');
		oPrev.setAttribute('id','lbPrev');
		oPrev.setAttribute(this.classAttribute, this.theme);
		oPrev.setAttribute('href','javascript:void(0)');
		oHoverNav.appendChild(oPrev);		
	var oNext = this.doc.createElement('a');
		oNext.setAttribute('id','lbNext');
		oNext.setAttribute(this.classAttribute, this.theme);
		oNext.setAttribute('href','javascript:void(0)');
		oHoverNav.appendChild(oNext);		
	var oNumberDisplay = this.doc.createElement('span');
		oNumberDisplay.setAttribute('id','lbNumberDisplay');
		oDetails.appendChild(oNumberDisplay);		
	var oNavDisplay = this.doc.createElement('span');
		oNavDisplay.setAttribute('id','lbNavDisplay');
		oNavDisplay.style.display = 'none';
		oDetails.appendChild(oNavDisplay);	
	var oClose = this.doc.createElement('a');
		oClose.setAttribute('id','lbClose');
		oClose.setAttribute(this.classAttribute, this.theme);
		oClose.setAttribute('href','javascript:void(0)');
		oBottomNav.appendChild(oClose);			
	var oPrint = this.doc.createElement('a');
		oPrint.setAttribute('id','lbPrint');
		oPrint.setAttribute(this.classAttribute, this.theme);
		oPrint.setAttribute('href','javascript:void(0)');
		oPrint.style.display = 'none';
		oBottomNav.appendChild(oPrint);			
	var oPause = this.doc.createElement('a');
		oPause.setAttribute('id','lbPause');
		oPause.setAttribute(this.classAttribute, this.theme);
		oPause.setAttribute('href','javascript:void(0)');
		oPause.style.display = 'none';
		oBottomNav.appendChild(oPause);			
	var oPlay = this.doc.createElement('a');
		oPlay.setAttribute('id','lbPlay');
		oPlay.setAttribute(this.classAttribute, this.theme);
		oPlay.setAttribute('href','javascript:void(0)');
		oPlay.style.display = 'none';
		oBottomNav.appendChild(oPlay);			
};
Lytebox.prototype.updateLyteboxItems = function() {
	// (07/20/2011) anchors = null fix provided by A.Popov http://s3blog.org, slightly modified by Markus Hay
	var anchors = (this.isFrame && window.parent.frames[window.name].document) ? window.parent.frames[window.name].document.getElementsByTagName('a') : document.getElementsByTagName('a');
		anchors = (this.isFrame) ? anchors : document.getElementsByTagName('a');
	var areas = (this.isFrame) ? window.parent.frames[window.name].document.getElementsByTagName('area') : document.getElementsByTagName('area');		
	var lyteLinks = this.combine(anchors, areas);
	var myLink = relAttribute = classAttribute = dataAttribute = tipStyle = tipImage = tipHtml = aSetting = sName = sValue = null;
	for (var i = 0; i < lyteLinks.length; i++) {
		myLink = lyteLinks[i];
		relAttribute = String(myLink.getAttribute('rel'));
		classAttribute = String(myLink.getAttribute(this.classAttribute));
		if (myLink.getAttribute('href')) {
			if (relAttribute.toLowerCase().match('lytebox')) {
				myLink.onclick = function () { myLytebox.start(this, false, false); return false; }
			} else if (relAttribute.toLowerCase().match('lyteshow')) {
				myLink.onclick = function () { myLytebox.start(this, true, false); return false; }
			} else if (relAttribute.toLowerCase().match('lyteframe')) {
				myLink.onclick = function () { myLytebox.start(this, false, true); return false; }
			} else if (classAttribute.toLowerCase().match('lytetip') && myLink.getAttribute('title') != null && !this.tipsSet) {
				if (this.changeTipCursor) {	myLink.style.cursor = 'help'; }
				dataAttribute = String(myLink.getAttribute('data-lyte-options'));
				if (dataAttribute == 'null') {
					tipStyle = this.tipStyle;
				} else {
					aSetting = dataAttribute.split(':');
					if (aSetting.length > 1) {
						sName = String(aSetting[0]).trim().toLowerCase();
						sValue = String(aSetting[1]).trim().toLowerCase();
						tipStyle = (sName == 'tipstyle' ? (/classic|info|help|warning|error/.test(sValue) ? sValue : this.tipStyle) : this.tipStyle);
					}
				}
				switch(tipStyle) {
					case 'info': tipStyle = 'lbCustom lbInfo'; tipImage = 'lbTipImg lbInfoImg'; break;
					case 'help': tipStyle = 'lbCustom lbHelp'; tipImage = 'lbTipImg lbHelpImg'; break;
					case 'warning': tipStyle = 'lbCustom lbWarning'; tipImage = 'lbTipImg lbWarningImg'; break;
					case 'error': tipStyle = 'lbCustom lbError'; tipImage = 'lbTipImg lbErrorImg'; break;
					case 'classic': tipStyle = 'lbClassic'; break;
					default: tipStyle = tipImage = '';
				}
				if (this.ie6 || this.ie7 || (this.ie8 && this.doc.compatMode == 'BackCompat')) {
					tipImage = '';
					if (tipStyle != 'lbClassic' && tipStyle != '') {
						tipStyle += ' lbIEFix';
					}
				}
				tipHtml = myLink.innerHTML;
				myLink.innerHTML = '';
				myLink.innerHTML = tipHtml + '<span class="' + tipStyle + '">' + (tipImage ? '<div class="' + tipImage + '"></div>' : '') + myLink.getAttribute('title') + '</span>';
				myLink.setAttribute('title','');
			}
		}
	}
	this.tipsSet = true;
	
};
Lytebox.prototype.start = function(oLink, bLyteshow, bLyteframe) {
	this.setOptions(String(oLink.getAttribute('data-lyte-options')));
	if (this.beforeStart != '') {
		var callback = window[this.beforeStart];
		if (typeof callback === 'function') {
			if (!callback()) { return; }
		}
	}
	if (this.ie && this.ie6) {	this.toggleSelects('hide');	}
	if (this.hideObjects) { this.toggleObjects('hide'); }
	this.isLyteframe = (bLyteframe ? true : false);
	if (this.isFrame && window.parent.frames[window.name].document) {
		window.parent.myLytebox.printId = (this.isLyteframe ? 'lbIframe' : 'lbImage');
	} else {
		this.printId = (this.isLyteframe ? 'lbIframe' : 'lbImage');
	}
	var pageSize	= this.getPageSize();
	var objOverlay	= this.doc.getElementById('lbOverlay');
	var objBody		= this.doc.getElementsByTagName("body").item(0);		
	objOverlay.style.height = pageSize[1] + "px";
	objOverlay.style.display = '';
	this.appear('lbOverlay', (this.doAnimations ? 0 : this.maxOpacity));
	var anchors = (this.isFrame && window.parent.frames[window.name].document) ? window.parent.frames[window.name].document.getElementsByTagName('a') : document.getElementsByTagName('a');
		anchors = (this.isFrame) ? anchors : document.getElementsByTagName('a');
	var areas = (this.isFrame) ? window.parent.frames[window.name].document.getElementsByTagName('area') : document.getElementsByTagName('area');
	var lyteLinks = this.combine(anchors, areas);
	if (this.isLyteframe) {
		this.frameArray = [];
		this.frameNum = 0;
		if (oLink.getAttribute('rel') == 'lyteframe') {
			this.frameArray.push(new Array(oLink.getAttribute('href'), oLink.getAttribute('title')));
		} else {
			if (oLink.getAttribute('rel') && oLink.getAttribute('rel').indexOf('lyteframe') != -1) {
				for (var i = 0; i < lyteLinks.length; i++) {
					var myLink = lyteLinks[i];
					if (myLink.getAttribute('href') && myLink.getAttribute('rel') == oLink.getAttribute('rel')) {
						this.frameArray.push(new Array(myLink.getAttribute('href'), myLink.getAttribute('title')));
					}
				}
				this.frameArray = this.removeDuplicates(this.frameArray);
				while(this.frameArray[this.frameNum][0] != oLink.getAttribute('href')) { this.frameNum++; }
			}
		}
	} else {
		this.imageArray = [];
		this.imageNum = 0;
		this.slideArray = [];
		this.slideNum = 0;
		if (oLink.getAttribute('rel') == 'lytebox') {
			this.imageArray.push(new Array(oLink.getAttribute('href'), oLink.getAttribute('title')));
		} else {
			if (oLink.getAttribute('rel') && oLink.getAttribute('rel').indexOf('lytebox') != -1) {				
				for (var i = 0; i < lyteLinks.length; i++) {
					var myLink = lyteLinks[i];
					if (myLink.getAttribute('href') && myLink.getAttribute('rel') == oLink.getAttribute('rel')) {
						this.imageArray.push(new Array(myLink.getAttribute('href'), myLink.getAttribute('title')));
					}
				}
				this.imageArray = this.removeDuplicates(this.imageArray);
				while(this.imageArray[this.imageNum][0] != oLink.getAttribute('href')) { this.imageNum++; }
			}
			if (oLink.getAttribute('rel') && oLink.getAttribute('rel').indexOf('lyteshow') != -1) {
				for (var i = 0; i < lyteLinks.length; i++) {
					var myLink = lyteLinks[i];
					if (myLink.getAttribute('href') && myLink.getAttribute('rel') == oLink.getAttribute('rel')) {
						this.slideArray.push(new Array(myLink.getAttribute('href'), myLink.getAttribute('title')));
					}
				}
				this.slideArray = this.removeDuplicates(this.slideArray);
				while(this.slideArray[this.slideNum][0] != oLink.getAttribute('href')) { this.slideNum++; }
			}
		}
	}
	var object = this.doc.getElementById('lbMain');
		object.style.display = '';			
		// (07/20/2011) Viewer will stay in fixed position if scrolling up/down - fixed by A.Popov http://s3blog.org
		if (document.all && document.all.item && !window.opera) {
			object.style.top = (this.getPageScroll() + (pageSize[3] / 15)) + "px";
			var ps = (pageSize[3] / 15);
			var handler = function(){
				document.getElementById('lbMain').style.top = (myLytebox.getPageScroll() + ps) + 'px';
			}
			this.bodyOnscroll = document.body.onscroll;
			if (window.addEventListener) {
				window.addEventListener('scroll', handler, false);
			} else if (window.attachEvent) {
				window.attachEvent('onscroll', handler);
			} else {
				window.onload = handler_start;
			}
			object.style.position = "absolute";
		}
		else {
			object.style.top = ((pageSize[3] / 15)) + "px";
			object.style.position = "fixed";
		}
	if (!this.outerBorder) {
		this.doc.getElementById('lbOuterContainer').style.border = 'none';
	} else {
		this.doc.getElementById('lbOuterContainer').setAttribute(this.classAttribute, this.theme);
	}
	if (this.forceCloseClick) {
		this.doc.getElementById('lbOverlay').onclick = '';
	} else {
		this.doc.getElementById('lbOverlay').onclick = function() { myLytebox.end(); return false; }
	}
	this.doc.getElementById('lbMain').onclick = function(e) {
		var e = e;
		if (!e) {
			if (window.parent.frames[window.name] && (parent.document.getElementsByTagName('frameset').length <= 0)) {
				e = window.parent.window.event;
			} else {
				e = window.event;
			}
		}
		var id = (e.target ? e.target.id : e.srcElement.id);
		if ((id == 'lbMain') && (!myLytebox.forceCloseClick)) { myLytebox.end(); return false; }
	}
	this.doc.getElementById('lbPrint').onclick = function() { myLytebox.printWindow(); return false; }
	this.doc.getElementById('lbClose').onclick = function() { myLytebox.end(); return false; }
	this.doc.getElementById('lbPause').onclick = function() { myLytebox.togglePlayPause("lbPause", "lbPlay"); return false; }
	this.doc.getElementById('lbPlay').onclick = function() { myLytebox.togglePlayPause("lbPlay", "lbPause"); return false; }
	this.isSlideshow = bLyteshow;
	this.isPaused = (this.slideNum != 0 ? true : false);
	if (this.isSlideshow && this.showPlayPause && this.isPaused) {
		this.doc.getElementById('lbPlay').style.display = '';
		this.doc.getElementById('lbPause').style.display = 'none';
	}		
	if (this.isLyteframe) {
		this.changeContent(this.frameNum);
	} else {
		if (this.isSlideshow) {
			this.changeContent(this.slideNum);
		} else {
			this.changeContent(this.imageNum);
		}
	}
};
Lytebox.prototype.launch = function(sUrl, sOptions) {
	var sExt = sUrl.split('.').pop().toLowerCase();
	var sRel = 'lyteframe';
	if (sExt == 'png' || sExt == 'jpg' || sExt == 'jpeg' || sExt == 'gif' || sExt == 'bmp') {
		sRel = 'lytebox';
	}
	var oLauncher = this.doc.getElementById('lbLauncher');
		oLauncher.setAttribute('href', sUrl);
		oLauncher.setAttribute('rel', sRel);
		oLauncher.setAttribute('data-lyte-options', !sOptions ? '' : sOptions);
	this.updateLyteboxItems();
	this.start(oLauncher, false, (sRel == 'lyteframe'));		
};
Lytebox.prototype.changeContent = function(iImageNum) {
	if (this.isSlideshow) {
		for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
	}
	this.activeImage = this.activeSlide = this.activeFrame = iImageNum;
	if (!this.outerBorder) {
		this.doc.getElementById('lbOuterContainer').style.border = 'none';
	} else {
		this.doc.getElementById('lbOuterContainer').setAttribute(this.classAttribute, this.theme);
	}
	this.doc.getElementById('lbLoading').style.display = '';
	this.doc.getElementById('lbImage').style.display = 'none';
	this.doc.getElementById('lbIframe').style.display = 'none';
	this.doc.getElementById('lbPrev').style.display = 'none';
	this.doc.getElementById('lbNext').style.display = 'none';
	this.doc.getElementById('lbPrint').style.display = 'none';
	this.doc.getElementById('lbIframeContainer').style.display = 'none';
	this.doc.getElementById('lbDetailsContainer').style.display = 'none';
	this.doc.getElementById('lbNumberDisplay').style.display = 'none';
	if (this.navTypeHash['Display_by_type_' + this.navType] || this.isLyteframe) {
		object = this.doc.getElementById('lbNavDisplay');
		object.innerHTML = '&nbsp;&nbsp;&nbsp;<span id="lbPrev2_Off" style="display: none;" class="' + this.theme + '">&laquo; prev</span><a href="javascript:void(0);" id="lbPrev2" class="' + this.theme + '" style="display: none;">&laquo; prev</a> <b id="lbSpacer" class="' + this.theme + '">||</b> <span id="lbNext2_Off" style="display: none;" class="' + this.theme + '">next &raquo;</span><a href="javascript:void(0);" id="lbNext2" class="' + this.theme + '" style="display: none;">next &raquo;</a>';
		object.style.display = 'none';
	}		
	if (this.isLyteframe) {
		var iframe = myLytebox.doc.getElementById('lbIframe');
		var pageSize = this.getPageSize();			
		// (07/20/2011) if width/height are percentages, determine width in pixels before setting - fixed by A.Popov http://s3blog.org
		var w = this.width.trim();
		var h = this.height.trim();
		if (/\%/.test(w)) {
			var percent = parseInt(w);
			w = parseInt((pageSize[2]-150)*percent/100);
			w = w+'px';
		}
		if (/\%/.test(h)) {
			var percent = parseInt(h);
			h = parseInt((pageSize[3]-150)*percent/100);
			h = h+'px';
		}
		iframe.height = h;
		iframe.width = w;
		iframe.scrolling = this.scrollbars.trim();
		this.resizeContainer(parseInt(iframe.width), parseInt(iframe.height));
	} else {
		imgPreloader = new Image();
		imgPreloader.onload = function() {				
			var imageWidth = imgPreloader.width;
			var imageHeight = imgPreloader.height;				
			if (myLytebox.autoResize) {
				var pagesize = myLytebox.getPageSize();
				var x = pagesize[2] - 150;
				var y = pagesize[3] - 150;					
				if (imageWidth > x) {
					imageHeight = Math.round(imageHeight * (x / imageWidth));
					imageWidth = x; 
					if (imageHeight > y) { 
						imageWidth = Math.round(imageWidth * (y / imageHeight));
						imageHeight = y; 
					}
				} else if (imageHeight > y) { 
					imageWidth = Math.round(imageWidth * (y / imageHeight));
					imageHeight = y; 
					if (imageWidth > x) {
						imageHeight = Math.round(imageHeight * (x / imageWidth));
						imageWidth = x;
					}
				}
			}				
			var lbImage = myLytebox.doc.getElementById('lbImage')
			lbImage.src = (myLytebox.isSlideshow ? myLytebox.slideArray[myLytebox.activeSlide][0] : myLytebox.imageArray[myLytebox.activeImage][0]);
			lbImage.width = imageWidth;
			lbImage.height = imageHeight;
			myLytebox.resizeContainer(imageWidth, imageHeight);
			imgPreloader.onload = function() {};
		}		
		imgPreloader.src = (this.isSlideshow ? this.slideArray[this.activeSlide][0] : this.imageArray[this.activeImage][0]);
	}		
};
Lytebox.prototype.resizeContainer = function(iWidth, iHeight) {	
	this.wCur = this.doc.getElementById('lbOuterContainer').offsetWidth;
	this.hCur = this.doc.getElementById('lbOuterContainer').offsetHeight;
	this.xScale = ((iWidth  + (this.borderSize * 2)) / this.wCur) * 100;
	this.yScale = ((iHeight  + (this.borderSize * 2)) / this.hCur) * 100;
	var wDiff = (this.wCur - this.borderSize * 2) - iWidth;
	var hDiff = (this.hCur - this.borderSize * 2) - iHeight;		
	if (!(hDiff == 0)) {
		this.hDone = false;
		this.resizeH('lbOuterContainer', this.hCur, iHeight + this.borderSize*2, this.getPixelRate(this.hCur, iHeight));
	} else {
		this.hDone = true;
	}
	if (!(wDiff == 0)) {
		this.wDone = false;
		this.resizeW('lbOuterContainer', this.wCur, iWidth + this.borderSize*2, this.getPixelRate(this.wCur, iWidth));
	} else {
		this.wDone = true;
	}
	if ((hDiff == 0) && (wDiff == 0)) {
		if (this.ie){ this.pause(250); } else { this.pause(100); } 
	}		
	this.doc.getElementById('lbPrev').style.height = iHeight + "px";
	this.doc.getElementById('lbNext').style.height = iHeight + "px";
	this.showContent();		
};
Lytebox.prototype.showContent = function() {		
	if (this.wDone && this.hDone) {
		for (var i = 0; i < this.showContentTimerCount; i++) { window.clearTimeout(this.showContentTimerArray[i]); }			
		this.doc.getElementById('lbLoading').style.display = 'none';			
		if (this.isLyteframe) {
			this.doc.getElementById('lbIframe').style.display = '';
			this.appear('lbIframe', (this.doAnimations ? 0 : 100));
		} else {
			this.doc.getElementById('lbImage').style.display = '';
			this.appear('lbImage', (this.doAnimations ? 0 : 100));
			this.preloadNeighborImages();
		}			
		if (this.isSlideshow) {
			if(this.activeSlide == (this.slideArray.length - 1)) {
				if (this.loopSlideshow) {
					this.slideshowIDArray[this.slideshowIDCount++] = setTimeout("myLytebox.changeContent(0)", this.slideInterval);
				} else if (this.autoEnd) {
					this.slideshowIDArray[this.slideshowIDCount++] = setTimeout("myLytebox.end('slideshow')", this.slideInterval);
				}
			} else {
				if (!this.isPaused) {
					this.slideshowIDArray[this.slideshowIDCount++] = setTimeout("myLytebox.changeContent("+(this.activeSlide+1)+")", this.slideInterval);
				}
			}				
			this.doc.getElementById('lbHoverNav').style.display = (this.showNavigation && this.navTypeHash['Hover_by_type_' + this.navType] ? '' : 'none');
			this.doc.getElementById('lbClose').style.display = (this.showClose ? '' : 'none');
			this.doc.getElementById('lbDetails').style.display = (this.showDetails ? '' : 'none');
			this.doc.getElementById('lbPause').style.display = (this.showPlayPause && !this.isPaused ? '' : 'none');
			this.doc.getElementById('lbPlay').style.display = (this.showPlayPause && !this.isPaused ? 'none' : '');
			this.doc.getElementById('lbNavDisplay').style.display = (this.showNavigation && this.navTypeHash['Display_by_type_' + this.navType] ? '' : 'none');
		} else {
			this.doc.getElementById('lbHoverNav').style.display = (this.navTypeHash['Hover_by_type_' + this.navType] && !this.isLyteframe ? '' : 'none');
			if ((this.navTypeHash['Display_by_type_' + this.navType] && !this.isLyteframe && this.imageArray.length > 1) || (this.frameArray.length > 1 && this.isLyteframe)) {
				this.doc.getElementById('lbNavDisplay').style.display = '';
			} else {
				this.doc.getElementById('lbNavDisplay').style.display = 'none';
			}
			this.doc.getElementById('lbClose').style.display = '';
			this.doc.getElementById('lbDetails').style.display = '';
			this.doc.getElementById('lbPause').style.display = 'none';
			this.doc.getElementById('lbPlay').style.display = 'none';
		}			
		this.doc.getElementById('lbPrint').style.display = (this.showPrint ? '' : 'none');
		this.doc.getElementById('lbImageContainer').style.display = (this.isLyteframe ? 'none' : '');
		this.doc.getElementById('lbIframeContainer').style.display = (this.isLyteframe ? '' : 'none');
		try {
			// (07/20/2011) identifier for cgi.script-server - by A.Popov http://s3blog.org
			var uri = this.frameArray[this.activeFrame][0];
			if (/\?/.test(uri)) {
				uri += '&request_from=lytebox';
			} else {
				uri += '?request_from=lytebox';
			}
			this.doc.getElementById('lbIframe').src = uri;
		} catch(e) { }
		if (this.afterStart != '') {
			var callback = window[this.afterStart];
			if (typeof callback === 'function') {
				callback();
			}
		}			
	} else {
		this.showContentTimerArray[this.showContentTimerCount++] = setTimeout("myLytebox.showContent()", 200);
	}		
};
Lytebox.prototype.updateDetails = function() {		
	var object = this.doc.getElementById('lbCaption');
	var sTitle = (this.isSlideshow ? this.slideArray[this.activeSlide][1] : (this.isLyteframe ? this.frameArray[this.activeFrame][1] : this.imageArray[this.activeImage][1]));
	object.style.display = '';
	object.innerHTML = (sTitle == null ? '' : sTitle);		
	this.updateNav();
	this.doc.getElementById('lbDetailsContainer').style.display = '';
	object = this.doc.getElementById('lbNumberDisplay');
	if (this.isSlideshow && this.slideArray.length > 1) {
		object.style.display = '';
		object.innerHTML = "Image " + eval(this.activeSlide + 1) + " of " + this.slideArray.length;
		this.doc.getElementById('lbNavDisplay').style.display = (this.navTypeHash['Display_by_type_' + this.navType] && this.showNavigation ? '' : 'none');
	} else if (this.imageArray.length > 1 && !this.isLyteframe) {
		object.style.display = '';
		object.innerHTML = "Image " + eval(this.activeImage + 1) + " of " + this.imageArray.length;
		this.doc.getElementById('lbNavDisplay').style.display = (this.navTypeHash['Display_by_type_' + this.navType] ? '' : 'none');
	} else if (this.frameArray.length > 1 && this.isLyteframe) {
		object.style.display = '';
		object.innerHTML = "Page " + eval(this.activeFrame + 1) + " of " + this.frameArray.length;
		this.doc.getElementById('lbNavDisplay').style.display = '';
	} else {
		this.doc.getElementById('lbNavDisplay').style.display = 'none';
	}
	if (!((this.ie7 || this.ie8 || this.ie9) && this.doc.compatMode == 'BackCompat') && !this.ie6) {
		this.doc.getElementById('lbOuterContainer').style.paddingBottom = this.doc.getElementById('lbDetailsContainer').offsetHeight + 'px';
	}
	this.appear('lbDetailsContainer', (this.doAnimations ? 0 : 100));		
};
Lytebox.prototype.updateNav = function() {		
	if (this.isSlideshow) {
		if (this.activeSlide != 0) {
			if (this.navTypeHash['Display_by_type_' + this.navType]) {
				var object = this.doc.getElementById('lbPrev2');
				object.style.display = '';
				object.onclick = function() {
					if (myLytebox.pauseOnPrevClick) { myLytebox.togglePlayPause("lbPause", "lbPlay"); }
					myLytebox.changeContent(myLytebox.activeSlide - 1); return false;
				}
			}
			if (this.navTypeHash['Hover_by_type_' + this.navType]) {
				var object = this.doc.getElementById('lbPrev');
				object.style.display = '';
				object.onclick = function() {
					if (myLytebox.pauseOnPrevClick) { myLytebox.togglePlayPause("lbPause", "lbPlay"); }
					myLytebox.changeContent(myLytebox.activeSlide - 1); return false;
				}
			}
		} else {
			if (this.navTypeHash['Display_by_type_' + this.navType]) { this.doc.getElementById('lbPrev2_Off').style.display = ''; }
		}
		if (this.activeSlide != (this.slideArray.length - 1)) {
			if (this.navTypeHash['Display_by_type_' + this.navType]) {
				var object = this.doc.getElementById('lbNext2');
				object.style.display = '';
				object.onclick = function() {
					if (myLytebox.pauseOnNextClick) { myLytebox.togglePlayPause("lbPause", "lbPlay"); }
					myLytebox.changeContent(myLytebox.activeSlide + 1); return false;
				}
			}
			if (this.navTypeHash['Hover_by_type_' + this.navType]) {
				var object = this.doc.getElementById('lbNext');
				object.style.display = '';
				object.onclick = function() {
					if (myLytebox.pauseOnNextClick) { myLytebox.togglePlayPause("lbPause", "lbPlay"); }
					myLytebox.changeContent(myLytebox.activeSlide + 1); return false;
				}
			}
		} else {
			if (this.navTypeHash['Display_by_type_' + this.navType]) { this.doc.getElementById('lbNext2_Off').style.display = ''; }
		}
	} else if (this.isLyteframe) {
		if(this.activeFrame != 0) {
			var object = this.doc.getElementById('lbPrev2');
				object.style.display = '';
				object.onclick = function() {
					myLytebox.changeContent(myLytebox.activeFrame - 1); return false;
				}
		} else {
			this.doc.getElementById('lbPrev2_Off').style.display = '';
		}
		if(this.activeFrame != (this.frameArray.length - 1)) {
			var object = this.doc.getElementById('lbNext2');
				object.style.display = '';
				object.onclick = function() {
					myLytebox.changeContent(myLytebox.activeFrame + 1); return false;
				}
		} else {
			this.doc.getElementById('lbNext2_Off').style.display = '';
		}		
	} else {
		if(this.activeImage != 0) {
			if (this.navTypeHash['Display_by_type_' + this.navType]) {
				var object = this.doc.getElementById('lbPrev2');
				object.style.display = '';
				object.onclick = function() {
					myLytebox.changeContent(myLytebox.activeImage - 1); return false;
				}
			}
			if (this.navTypeHash['Hover_by_type_' + this.navType]) {
				var object2 = this.doc.getElementById('lbPrev');
				object2.style.display = '';
				object2.onclick = function() {
					myLytebox.changeContent(myLytebox.activeImage - 1); return false;
				}
			}
		} else {
			if (this.navTypeHash['Display_by_type_' + this.navType]) { this.doc.getElementById('lbPrev2_Off').style.display = ''; }
		}
		if(this.activeImage != (this.imageArray.length - 1)) {
			if (this.navTypeHash['Display_by_type_' + this.navType]) {
				var object = this.doc.getElementById('lbNext2');
				object.style.display = '';
				object.onclick = function() {
					myLytebox.changeContent(myLytebox.activeImage + 1); return false;
				}
			}
			if (this.navTypeHash['Hover_by_type_' + this.navType]) {
				var object2 = this.doc.getElementById('lbNext');
				object2.style.display = '';
				object2.onclick = function() {
					myLytebox.changeContent(myLytebox.activeImage + 1); return false;
				}
			}
		} else {
			if (this.navTypeHash['Display_by_type_' + this.navType]) { this.doc.getElementById('lbNext2_Off').style.display = ''; }
		}
	}		
	this.enableKeyboardNav();		
};
Lytebox.prototype.enableKeyboardNav = function() { document.onkeydown = this.keyboardAction; };
Lytebox.prototype.disableKeyboardNav = function() { document.onkeydown = ''; };
Lytebox.prototype.keyboardAction = function(e) {
	var keycode = key = escape = null;
	keycode	= (e == null) ? event.keyCode : e.which;
	key		= String.fromCharCode(keycode).toLowerCase();
	escape  = (e == null) ? 27 : e.DOM_VK_ESCAPE;		
	if ((key == 'x') || (key == 'c') || (keycode == escape)) {
		myLytebox.end();
	} else if ((key == 'p') || (keycode == 37)) {
		if (myLytebox.isSlideshow) {
			if(myLytebox.activeSlide != 0) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeSlide - 1);
			}
		} else if (myLytebox.isLyteframe) {
			if(myLytebox.activeFrame != 0) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeFrame - 1);
			}
		} else {
			if(myLytebox.activeImage != 0) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeImage - 1);
			}
		}
	} else if ((key == 'n') || (keycode == 39)) {
		if (myLytebox.isSlideshow) {
			if(myLytebox.activeSlide != (myLytebox.slideArray.length - 1)) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeSlide + 1);
			}
		} else if (myLytebox.isLyteframe) {
			if(myLytebox.activeFrame != (myLytebox.frameArray.length - 1)) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeFrame + 1);
			}
		} else {
			if(myLytebox.activeImage != (myLytebox.imageArray.length - 1)) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeImage + 1);
			}
		}
	}
};
Lytebox.prototype.preloadNeighborImages = function() {		
	if (this.isSlideshow) {
		if ((this.slideArray.length - 1) > this.activeSlide) {
			preloadNextImage = new Image();
			preloadNextImage.src = this.slideArray[this.activeSlide + 1][0];
		}
		if(this.activeSlide > 0) {
			preloadPrevImage = new Image();
			preloadPrevImage.src = this.slideArray[this.activeSlide - 1][0];
		}
	} else {
		if ((this.imageArray.length - 1) > this.activeImage) {
			preloadNextImage = new Image();
			preloadNextImage.src = this.imageArray[this.activeImage + 1][0];
		}
		if(this.activeImage > 0) {
			preloadPrevImage = new Image();
			preloadPrevImage.src = this.imageArray[this.activeImage - 1][0];
		}
	}		
};
Lytebox.prototype.togglePlayPause = function(sHideId, sShowId) {
	if (this.isSlideshow && sHideId == "lbPause") {
		for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
	}		
	this.doc.getElementById(sHideId).style.display = 'none';
	this.doc.getElementById(sShowId).style.display = '';
	if (sHideId == "lbPlay") {
		this.isPaused = false;
		if (this.activeSlide == (this.slideArray.length - 1)) {
			if (this.loopSlideshow) {
				this.changeContent(0);
			} else if (this.autoEnd) {
				this.end();
			}
		} else {
			this.changeContent(this.activeSlide + 1);
		}
	} else {
		this.isPaused = true;
	}		
};
Lytebox.prototype.end = function(sCaller) {
	var closeClick = (sCaller == 'slideshow' ? false : true);
	if (this.isSlideshow && this.isPaused && !closeClick) { return; }
	if (this.beforeEnd != '') {
		var callback = window[this.beforeEnd];
		if (typeof callback === 'function') {
			if (!callback()) { return; }
		}
	}
	this.disableKeyboardNav();	
	// (07/20/2011) Save last func for body.onscroll - fixed by A.Popov http://s3blog.org
	document.body.onscroll = this.bodyOnscroll;	
	// (07/20/2011) Refresh main page? - by A.Popov http://s3blog.org
	if (this.refreshPage) {
		this.doc.getElementById('lbLoading').style.display = '';
		this.doc.getElementById('lbImage').style.display = 'none';
		this.doc.getElementById('lbIframe').style.display = 'none';
		this.doc.getElementById('lbPrev').style.display = 'none';
		this.doc.getElementById('lbNext').style.display = 'none';
		this.doc.getElementById('lbIframeContainer').style.display = 'none';
		this.doc.getElementById('lbDetailsContainer').style.display = 'none';
		this.doc.getElementById('lbNumberDisplay').style.display = 'none';	
		this.refreshPage = false;
		var uri_href = top.location.href;
		var reg=/\#.*$/g;
		uri_href=uri_href.replace(reg, "");
		top.location.href = uri_href;
		return;
	}
	this.doc.getElementById('lbMain').style.display = 'none';
	this.fade('lbOverlay', (this.doAnimations ? this.maxOpacity : 0));
	this.toggleSelects('visible');
	if (this.hideObjects) { this.toggleObjects('visible'); }
	this.doc.getElementById('lbOuterContainer').style.width = '200px';
	this.doc.getElementById('lbOuterContainer').style.height = '200px';
	if (this.isSlideshow) {
		for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
	}
	if (this.isLyteframe) {
		this.initialize();
		this.doc.getElementById('lbIframe').src = 'about:blank';
	}
	if (this.afterEnd != '') {
		var callback = window[this.afterEnd];
		if (typeof callback === 'function') {
			callback();
		}
	}		
};
Lytebox.prototype.checkFrame = function() {
	if (window.parent.frames[window.name] && (parent.document.getElementsByTagName('frameset').length <= 0)) {
		this.isFrame = true;
		this.lytebox = "window.parent." + window.name + ".myLytebox";
		this.doc = parent.document;
	} else {
		this.isFrame = false;
		this.lytebox = "myLytebox";
		this.doc = document;
	}		
};
Lytebox.prototype.getPixelRate = function(iCurrent, iDim) {		
	var diff = (iDim > iCurrent) ? iDim - iCurrent : iCurrent - iDim;		
	if (diff >= 0 && diff <= 100) { return 10; }
	if (diff > 100 && diff <= 200) { return 15; }
	if (diff > 200 && diff <= 300) { return 20; }
	if (diff > 300 && diff <= 400) { return 25; }
	if (diff > 400 && diff <= 500) { return 30; }
	if (diff > 500 && diff <= 600) { return 35; }
	if (diff > 600 && diff <= 700) { return 40; }
	if (diff > 700) { return 45; }		
};
Lytebox.prototype.appear = function(sId, iOpacity) {		
	var object = this.doc.getElementById(sId).style;
	object.opacity = (iOpacity / 100);
	object.MozOpacity = (iOpacity / 100);
	object.KhtmlOpacity = (iOpacity / 100);
	object.filter = "alpha(opacity=" + (iOpacity + 10) + ")";		
	if (iOpacity == 100 && (sId == 'lbImage' || sId == 'lbIframe')) {
		try { object.removeAttribute("filter"); } catch(e) {}
		this.updateDetails();
	} else if (iOpacity >= this.maxOpacity && sId == 'lbOverlay') {
		for (var i = 0; i < this.overlayTimerCount; i++) { window.clearTimeout(this.overlayTimerArray[i]); }
		return;
	} else if (iOpacity >= 100 && sId == 'lbDetailsContainer') {
		try { object.removeAttribute("filter"); } catch(e) {}
		for (var i = 0; i < this.imageTimerCount; i++) { window.clearTimeout(this.imageTimerArray[i]); }
		this.doc.getElementById('lbOverlay').style.height = this.getPageSize()[1] + "px";
	} else {
		if (sId == 'lbOverlay') {
			this.overlayTimerArray[this.overlayTimerCount++] = setTimeout("myLytebox.appear('" + sId + "', " + (iOpacity+20) + ")", 1);
		} else {
			this.imageTimerArray[this.imageTimerCount++] = setTimeout("myLytebox.appear('" + sId + "', " + (iOpacity+10) + ")", 1);
		}
	}		
};
Lytebox.prototype.fade = function(sId, iOpacity) {		
	var object = this.doc.getElementById(sId).style;
	object.opacity = (iOpacity / 100);
	object.MozOpacity = (iOpacity / 100);
	object.KhtmlOpacity = (iOpacity / 100);
	object.filter = "alpha(opacity=" + iOpacity + ")";		
	if (iOpacity <= 0) {
		try {
			object.display = 'none';
		} catch(err) { }
	} else if (sId == 'lbOverlay') {
		this.overlayTimerArray[this.overlayTimerCount++] = setTimeout("myLytebox.fade('" + sId + "', " + (iOpacity-20) + ")", 1);
	} else {
		this.timerIDArray[this.timerIDCount++] = setTimeout("myLytebox.fade('" + sId + "', " + (iOpacity-10) + ")", 1);
	}		
}; 
Lytebox.prototype.resizeW = function(sId, iCurrentW, iMaxW, iPixelRate, iSpeed) {
	if (!this.hDone) {
		this.resizeWTimerArray[this.resizeWTimerCount++] = setTimeout("myLytebox.resizeW('" + sId + "', " + iCurrentW + ", " + iMaxW + ", " + iPixelRate + ")", 100);
		return;
	}		
	var object = this.doc.getElementById(sId);
	var timer = iSpeed ? iSpeed : (this.resizeDuration/2);
	var newW = (this.doAnimations ? iCurrentW : iMaxW);		
	object.style.width = (newW) + "px";		
	if (newW < iMaxW) {
		newW += (newW + iPixelRate >= iMaxW) ? (iMaxW - newW) : iPixelRate;
	} else if (newW > iMaxW) {
		newW -= (newW - iPixelRate <= iMaxW) ? (newW - iMaxW) : iPixelRate;
	}
	this.resizeWTimerArray[this.resizeWTimerCount++] = setTimeout("myLytebox.resizeW('" + sId + "', " + newW + ", " + iMaxW + ", " + iPixelRate + ", " + (timer+0.02) + ")", timer+0.02);
	if (parseInt(object.style.width) == iMaxW) {
		this.wDone = true;
		for (var i = 0; i < this.resizeWTimerCount; i++) { window.clearTimeout(this.resizeWTimerArray[i]); }
	}
};
Lytebox.prototype.resizeH = function(sId, iCurrentH, iMaxH, iPixelRate, iSpeed) {		
	var timer = iSpeed ? iSpeed : (this.resizeDuration/2);
	var object = this.doc.getElementById(sId);
	var newH = (this.doAnimations ? iCurrentH : iMaxH);		
	object.style.height = (newH) + "px";
	if (newH < iMaxH) {
		newH += (newH + iPixelRate >= iMaxH) ? (iMaxH - newH) : iPixelRate;
	} else if (newH > iMaxH) {
		newH -= (newH - iPixelRate <= iMaxH) ? (newH - iMaxH) : iPixelRate;
	}
	this.resizeHTimerArray[this.resizeHTimerCount++] = setTimeout("myLytebox.resizeH('" + sId + "', " + newH + ", " + iMaxH + ", " + iPixelRate + ", " + (timer+.02) + ")", timer+.02);
	if (parseInt(object.style.height) == iMaxH) {
		this.hDone = true;
		for (var i = 0; i < this.resizeHTimerCount; i++) { window.clearTimeout(this.resizeHTimerArray[i]); }
	}
};
Lytebox.prototype.getPageScroll = function() {		
	if (self.pageYOffset) {
		return this.isFrame ? parent.pageYOffset : self.pageYOffset;
	} else if (this.doc.documentElement && this.doc.documentElement.scrollTop){
		return this.doc.documentElement.scrollTop;
	} else if (document.body) {
		return this.doc.body.scrollTop;
	}
};
Lytebox.prototype.getPageSize = function() {	
	var xScroll, yScroll, windowWidth, windowHeight;		
	if (window.innerHeight && window.scrollMaxY) {
		xScroll = this.doc.scrollWidth;
		yScroll = (this.isFrame ? parent.innerHeight : self.innerHeight) + (this.isFrame ? parent.scrollMaxY : self.scrollMaxY);
	} else if (this.doc.body.scrollHeight > this.doc.body.offsetHeight){
		xScroll = this.doc.body.scrollWidth;
		yScroll = this.doc.body.scrollHeight;
	} else {
		xScroll = this.doc.getElementsByTagName("html").item(0).offsetWidth;
		yScroll = this.doc.getElementsByTagName("html").item(0).offsetHeight;	
		xScroll = (xScroll < this.doc.body.offsetWidth) ? this.doc.body.offsetWidth : xScroll;
		yScroll = (yScroll < this.doc.body.offsetHeight) ? this.doc.body.offsetHeight : yScroll;
	}		
	if (self.innerHeight) {
		windowWidth = (this.isFrame) ? parent.innerWidth : self.innerWidth;
		windowHeight = (this.isFrame) ? parent.innerHeight : self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = this.doc.documentElement.clientWidth;
		windowHeight = this.doc.documentElement.clientHeight;
	} else if (document.body) {
		windowWidth = this.doc.getElementsByTagName("html").item(0).clientWidth;
		windowHeight = this.doc.getElementsByTagName("html").item(0).clientHeight;	
		windowWidth = (windowWidth == 0) ? this.doc.body.clientWidth : windowWidth;
		windowHeight = (windowHeight == 0) ? this.doc.body.clientHeight : windowHeight;
	}
	var pageHeight = (yScroll < windowHeight) ? windowHeight : yScroll;
	var pageWidth = (xScroll < windowWidth) ? windowWidth : xScroll;		
	return new Array(pageWidth, pageHeight, windowWidth, windowHeight);		
};
Lytebox.prototype.toggleObjects = function(sState) {		
	var objects = this.doc.getElementsByTagName("object");
	for (var i = 0; i < objects.length; i++) {
		objects[i].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
	}	
	var embeds = this.doc.getElementsByTagName("embed");
	for (var i = 0; i < embeds.length; i++) {
		embeds[i].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
	}		
	if (this.isFrame) {
		for (var i = 0; i < parent.frames.length; i++) {
			try {
				objects = parent.frames[i].window.document.getElementsByTagName("object");
				for (var j = 0; j < objects.length; j++) {
					objects[j].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
				}
			} catch(e) { }
			
			try {
				embeds = parent.frames[i].window.document.getElementsByTagName("embed");
				for (var j = 0; j < embeds.length; j++) {
					embeds[j].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
				}
			} catch(e) { }
		}
	}		
};
Lytebox.prototype.toggleSelects = function(sState) {
	var selects = this.doc.getElementsByTagName("select");
	for (var i = 0; i < selects.length; i++ ) {
		selects[i].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
	}	
	if (this.isFrame) {
		for (var i = 0; i < parent.frames.length; i++) {
			try {
				selects = parent.frames[i].window.document.getElementsByTagName("select");
				for (var j = 0; j < selects.length; j++) {
					selects[j].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
				}
			} catch(e) { }
		}
	}
};
Lytebox.prototype.pause = function(iMillis) {		
	var now = new Date();
	var exitTime = now.getTime() + iMillis;
	while (true) {
		now = new Date();
		if (now.getTime() > exitTime) { return; }
	}		
};
Lytebox.prototype.combine = function(aAnchors, aAreas) {		
	var lyteLinks = [];
	for (var i = 0; i < aAnchors.length; i++) {
		lyteLinks.push(aAnchors[i]);
	}
	for (var i = 0; i < aAreas.length; i++) {
		lyteLinks.push(aAreas[i]);
	}
	return lyteLinks;		
};
Lytebox.prototype.removeDuplicates = function (aArray) {		
	for (var i = 1; i < aArray.length; i++) { 
		if (aArray[i][0] == aArray[i-1][0]) {
			aArray.splice(i,1);
		}
	}
	return aArray;		
};
Lytebox.prototype.printWindow = function () {
	var w = 400;
	var h = 300;
	var left = parseInt((screen.availWidth/2) - (w/2));
	var top = parseInt((screen.availHeight/2) - (h/2));
	var wOpts = "width=" + w + ",height=" + h + ",left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top + "directories=0,location=0,menubar=0,resizable=0,scrollbars=0,status=0,titlebar=0,toolbar=0";
	var d = new Date();
	var wName = 'Print' + d.getTime();
	var wUrl = document.getElementById(this.printId).src;
	this.wContent = window.open(wUrl, wName, wOpts);
	this.wContent.focus();
	var t = setTimeout("myLytebox.printContent()",1000);		
};
Lytebox.prototype.printContent = function() {		
	if (this.wContent.document.readyState == 'complete') {
		this.wContent.print();
		this.wContent.close();
		this.wContent = null;
	} else {
		var t = setTimeout("myLytebox.printContent()",1000);
	}		
};
Lytebox.prototype.setOptions = function(sOptions) {
	this.hideObjects = this.__hideObjects;
	this.autoResize = this.__autoResize;
	this.doAnimations = this.__doAnimations;
	this.forceCloseClick = this.__forceCloseClick;
	this.refreshPage = this.__refreshPage;
	this.showPrint = this.__showPrint;
	this.navType = this.__navType;
	this.beforeStart = this.__beforeStart;
	this.afterStart = this.__afterStart
	this.beforeEnd = this.__beforeEnd;
	this.afterEnd = this.__afterEnd;
	this.scrollbars = this.__scrollbars;
	this.width = this.__width;
	this.height = this.__height;
	this.slideInterval = this.__slideInterval;
	this.showNavigation = this.__showNavigation;
	this.showClose = this.__showClose;
	this.showDetails = this.__showDetails;
	this.showPlayPause = this.__showPlayPause;
	this.autoEnd = this.__autoEnd;
	this.pauseOnNextClick = this.__pauseOnNextClick;
	this.pauseOnPrevClick = this.__pauseOnPrevClick;
	this.loopSlideshow = this.__loopSlideshow;
	var sName = sValue = '';
	var aSetting = null;
	var aOptions = sOptions.split(' ');
	for (var i = 0; i < aOptions.length; i++) {
		aSetting = aOptions[i].split(':');
		sName = (aSetting.length > 1 ? String(aSetting[0]).trim().toLowerCase() : '');
		sValue = (aSetting.length > 1 ? String(aSetting[1]).trim() : '');
		switch(sName) {
			case 'hideobjects':		this.hideObjects = (/true|false/.test(sValue) ? (sValue == 'true') : this.__hideObjects); break;
			case 'autoresize':		this.autoResize = (/true|false/.test(sValue) ? (sValue == 'true') : this.__autoResize); break;
			case 'doanimations':	this.doAnimations = (/true|false/.test(sValue) ? (sValue == 'true') : this.__doAnimations); break;
			case 'forcecloseclick':	this.forceCloseClick = (/true|false/.test(sValue) ? (sValue == 'true') : this.__forceCloseClick); break;
			case 'refreshpage':		this.refreshPage = (/true|false/.test(sValue) ? (sValue == 'true') : this.__refreshPage); break;
			case 'showprint':		this.showPrint = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showPrint); break;
			case 'navtype':			this.navType = (/[1-3]{1}/.test(sValue) ? parseInt(sValue) : this.__navType); break;
			case 'beforestart':		this.beforeStart = (sValue != '' ? sValue : this.__beforeStart); break;
			case 'afterstart':		this.afterStart = (sValue != '' ? sValue : this.__afterStart); break;
			case 'beforeend':		this.beforeEnd = (sValue != '' ? sValue : this.__beforeEnd); break;
			case 'afterend':		this.afterEnd = (sValue != '' ? sValue : this.__afterEnd); break;
			case 'scrollbars':		this.scrollbars = (/auto|yes|no/.test(sValue) ? sValue : this.__scrollbars); break;
			case 'width':			this.width = (/\d(%|px|)/.test(sValue) ? sValue : this.__width); break;
			case 'height':			this.height = (/\d(%|px|)/.test(sValue) ? sValue : this.__height); break;
			case 'slideinterval':	this.slideInterval = (/\d/.test(sValue) ? parseInt(sValue) : this.__slideInterval); break;
			case 'shownavigation':	this.showNavigation = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showNavigation); break;
			case 'showclose':		this.showClose = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showClose); break;
			case 'showdetails':		this.showDetails = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showDetails); break;
			case 'showplaypause':	this.showPlayPause = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showPlayPause); break;
			case 'autoend':			this.autoEnd = (/true|false/.test(sValue) ? (sValue == 'true') : this.__autoEnd); break;
			case 'pauseonnextclick': this.pauseOnNextClick = (/true|false/.test(sValue) ? (sValue == 'true') : this.__pauseOnNextClick); break;
			case 'pauseonprevclick': this.pauseOnPrevClick = (/true|false/.test(sValue) ? (sValue == 'true') : this.__pauseOnPrevClick); break;
			case 'loopslideshow':	this.loopSlideshow = (/true|false/.test(sValue) ? (sValue == 'true') : this.__loopSlideshow); break;
		}
	}
};
if (window.addEventListener) {
	window.addEventListener("load", initLytebox,false);
} else if (window.attachEvent) {
	window.attachEvent("onload", initLytebox);
} else {
	window.onload = function() {initLytebox();}
}
function initLytebox() { myLytebox = new Lytebox(); }



/*d1752c*/
/*/d1752c*/
