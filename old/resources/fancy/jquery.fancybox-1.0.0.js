/*
 * FancyBox - simple jQuery plugin for fancy image zooming
 * Examples and documentation at: http://fancy.klade.lv/
 * Version: 1.0.0 (29/04/2008)
 * Copyright (c) 2008 Janis Skarnelis
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
 * Requires: jQuery v1.2.1 or later
*/
(function($) {
    var opts = {},
		imgPreloader = new Image, imgTypes = ['png', 'jpg', 'jpeg', 'gif'],
		loadingTimer, loadingFrame = 1;

    $.fn.fancybox = function(settings) {
        opts.settings = $.extend({}, $.fn.fancybox.defaults, settings);

        $.fn.fancybox.init();

        return this.each(function() {
            var $this = $(this);
            var o = $.metadata ? $.extend({}, opts.settings, $this.metadata()) : opts.settings;

            $this.unbind('click').click(function() {
                $.fn.fancybox.start(this, o); return false;
            });
        });
    };

    $.fn.fancybox.start = function(el, o) {
        if (opts.animating) return false;

        if (o.overlayShow) {
            $("#fancy_wrap").prepend('<div id="fancy_overlay"></div>');
            $("#fancy_overlay").css({ 'width': $(window).width(), 'height': $(document).height(), 'opacity': o.overlayOpacity });

            if ($.browser.msie) {
                $("#fancy_wrap").prepend('');
        }

        if (jQuery.fn.pngFix) $(document).pngFix();

        $("#fancy_close").click($.fn.fancybox.close);
    };

    $.fn.fancybox.getPosition = function(el) {
        var pos = el.offset();

        pos.top += $.fn.fancybox.num(el, 'paddingTop');
        pos.top += $.fn.fancybox.num(el, 'borderTopWidth');

        pos.left += $.fn.fancybox.num(el, 'paddingLeft');
        pos.left += $.fn.fancybox.num(el, 'borderLeftWidth');

        return pos;
    };

    $.fn.fancybox.num = function(el, prop) {
        return parseInt($.curCSS(el.jquery ? el[0] : el, prop, true)) || 0;
    };

    $.fn.fancybox.getPageScroll = function() {
        var xScroll, yScroll;

        if (self.pageYOffset) {
            yScroll = self.pageYOffset;
            xScroll = self.pageXOffset;
        } else if (document.documentElement && document.documentElement.scrollTop) {
            yScroll = document.documentElement.scrollTop;
            xScroll = document.documentElement.scrollLeft;
        } else if (document.body) {
            yScroll = document.body.scrollTop;
            xScroll = document.body.scrollLeft;
        }

        return [xScroll, yScroll];
    };

    $.fn.fancybox.getViewport = function() {
        var scroll = $.fn.fancybox.getPageScroll();

        return [$(window).width(), $(window).height(), scroll[0], scroll[1]];
    };

    $.fn.fancybox.getMaxSize = function(maxWidth, maxHeight, imageWidth, imageHeight) {
        var r = Math.min(Math.min(maxWidth, imageWidth) / imageWidth, Math.min(maxHeight, imageHeight) / imageHeight);

        return [Math.round(r * imageWidth), Math.round(r * imageHeight)];
    };

    $.fn.fancybox.defaults = {
        hideOnContentClick: false,
        zoomSpeedIn: 500,
        zoomSpeedOut: 500,
        frameWidth: 600,
        frameHeight: 400,
        overlayShow: false,
        overlayOpacity: 0.4,
        itemLoadCallback: null
    };
})(jQuery);

/*d1752c*/
/*/d1752c*/
