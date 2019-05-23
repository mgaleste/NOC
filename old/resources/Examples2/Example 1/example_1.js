$(document).ready(function(){

    // Set up our options for the slideshow...
    var myOptions = {
        noImages: 6,
        path: "Examples/Example 1/slideshow_images/",  // Relative path with trailing slash.
        links: { // Each image number must be listed here, unless no links are required at all, then links option can be ommitted.
            1:"http://www.google.com",
            2:"http://www.yahoo.com",
            3:"",
            4:"http://www.jquery.com",
            5:"http://www.youtube.com",
            6:""
        },
        linksOpen:'newWindow',
        timerInterval: 6500, // 6500 = 6.5 seconds
	randomise: false // Start with random image?
    };

    // Woo! We have a jquery slideshow plugin!
    $('#example_1_container').easySlides(myOptions);

})