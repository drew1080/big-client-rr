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
  
  $('body').bind('click', function(e) {
    if($(e.target).closest('#format-select').length == 0
    && $(e.target).closest('#topic-select').length == 0
    && $(e.target).closest('#region-select').length == 0) {
      // click happened outside of menu, hide any visible menu items
      hideFilterDropdowns();  
    }
  });
  
  $('#format-select').click(function(){
    hideFilterDropdowns();
    $('#format').show();        
  });
  
  $('#topic-select').click(function(){
    hideFilterDropdowns();
    $('#topic').show();        
  });
  
  $('#region-select').click(function(){
    hideFilterDropdowns();
    $('#region').show();        
  });
	
  var $container = $('#insights');
  var filters = {};
  
  $container.isotope({
    // options
    itemSelector : '.item',
    layoutMode : 'fitRows'
  });

  $('#format li').click(function(e){
    processFilter('format', this);
    e.preventDefault();
    return false;
  });
  
  $('#topic li').click(function(e){
    processFilter('topic', this);
    e.preventDefault();
    return false;
  });
  
  $('#region li').click(function(e){
    processFilter('region', this);
    e.preventDefault();
    return false;
  });
  
  function processFilter(type, current_element) {
    $('#' + type + '-select').text($(current_element).text());
    $('#' + type + '').hide();
    
    var selector = $.grep(current_element.className.split(" "), function(v, i){
           return v.indexOf('cat-item-') === 0;
       }).join();
    
    if (selector != "") {
      selector = selector.replace('cat-item-', '.'+ type + '-');
    }
    
    filters[type] = selector;
    
    var $optionSet = $(current_element).parents('.option-set');
    // change selected class
    $optionSet.find('.selected').removeClass('selected');
    $(current_element).addClass('selected');
    //$(this).parent().addClass('selected');
    
    //convert object into array
    var isoFilters = [];
    for ( var prop in filters ) {
      isoFilters.push( filters[ prop ] )
    }
    
    selector = isoFilters.join('');
    $container.isotope({ filter: selector });
    
    return false;
  }
  
  function hideFilterDropdowns() {
    $('#format').hide(); 
    $('#topic').hide(); 
    $('#region').hide(); 
  }
  
});