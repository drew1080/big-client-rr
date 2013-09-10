// DOM Ready
$(function() {
	
	// SVG fallback
	// toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script#update
	if (!Modernizr.svg) {
		var imgs = document.getElementsByTagName('img');
		var dotSVG = /.*\.svg$/;
		for (var i = 0; i != imgs.length; ++i) {
			if(imgs[i].src.match(dotSVG)) {
				imgs[i].src = imgs[i].src.slice(0, -3) + "png";
			}
		}
	}
  
  $(".marketo-insight").each(function() {
    $(this).load(function (){
        // do something once the iframe is loaded
        var script=document.createElement('script');

        // script=document.standardCreateElement('script');
        // script.src = 'http://localhost:8888/rr/wp-content/themes/rr/js/marketo.js';
        // script.type = 'text/javascript';
        
        var $head = $(this).contents().find("head");      
        
        $head.append(script);
        
        $head.append($("<link/>", 
            { rel: "stylesheet", href: "http://fonts.googleapis.com/css?family=Roboto:400,300", type: "text/css" }));
                      
        $head.append($("<link/>", 
            { rel: "stylesheet", href: "http://localhost:8888/rr/wp-content/themes/rr/css/marketo.css", type: "text/css" }));
    });
  });
  
  
	
  var $container = $('#insights');
  var filters = {};
  //var $master_selector = '';
  
    $container.isotope({
      // options
      itemSelector : '.item',
      layoutMode : 'fitRows'
    });

  $('#format').change(function(){
    var selector = $(this).attr('value');
    
    if (selector == "0") {
      selector = '';
    } else {
      selector = '.format-' + selector;
    }
    
    filters['format'] = selector;
    
    //convert object into array
    var isoFilters = [];
    for ( var prop in filters ) {
      isoFilters.push( filters[ prop ] )
    }
    
    selector = isoFilters.join('');
    $container.isotope({ filter: selector });
    
    return false;
  });
  
  $('#topic').change(function(){
    var selector = $(this).attr('value');
    
    if (selector == "0") {
      selector = '';
    } else {
      selector = '.topic-' + selector;
    }
    
    filters['topic'] = selector;
    
    //convert object into array
    var isoFilters = [];
    for ( var prop in filters ) {
      isoFilters.push( filters[ prop ] )
    }
    
    selector = isoFilters.join('');
    
    $container.isotope({ filter: selector });
    return false;
  });
  
  $('#region').change(function(){
    var selector = $(this).attr('value');
    
    if (selector == "0") {
      selector = '';
    } else {
      selector = '.region-' + selector;
    }
    
    filters['region'] = selector;
    
    //convert object into array
    var isoFilters = [];
    for ( var prop in filters ) {
      isoFilters.push( filters[ prop ] )
    }
    
    selector = isoFilters.join('');
    
    $container.isotope({ filter: selector });
    return false;
  });
  
});