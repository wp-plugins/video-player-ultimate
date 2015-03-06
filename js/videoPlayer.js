videojs.options.flash.swf = VideoPlayerParams.video_js_swf;

jQuery(document).ready(function(){


	var videoIds = VideoPlayerParams.video_player_ids;
	for (var i = 0; i < videoIds.length; i++) {
	   videojs(videoIds[i], {"height":"auto", "width":"auto"}).ready(function(){
	    var myPlayer = this;    // Store the video object
	    var aspectRatio = 5/12; // Make up an aspect ratio
	
	    function resizeVideoJS(){
	      // Get the parent elements actual width
	      var width = document.getElementById(myPlayer.id()).parentElement.offsetWidth;
	      // Set width to fill parent element, Set height
	      myPlayer.width(width).height( width * aspectRatio );
	    }
	
	    resizeVideoJS(); // Initialize the function
	    //window.onresize = resizeVideoJS; // Call the function on resize
	  });
	
	}
	


	function windoeResizeVideoJS(){
		for (var i = 0; i < videoIds.length; i++) {
			videojs(videoIds[i], {"height":"auto", "width":"auto"}).ready(function(){
			    var myPlayer = this;    // Store the video object
			    var aspectRatio = 5/12; // Make up an aspect ratio
			
			    
			      // Get the parent elements actual width
			      var width = document.getElementById(myPlayer.id()).parentElement.offsetWidth;
			      // Set width to fill parent element, Set height
			      myPlayer.width(width).height( width * aspectRatio );
			    
			
			    //resizeVideoJS(); // Initialize the function
			    //window.onresize = resizeVideoJS; // Call the function on resize
			  });
		}
	}
	window.onresize = windoeResizeVideoJS;
});