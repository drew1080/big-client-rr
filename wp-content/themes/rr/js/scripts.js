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
    
    // var $this = $(this);
    //     // don't proceed if already selected
    //     if ( $this.hasClass('selected') ) {
    //       return;
    //     }
    //     
    //     var $optionSet = $this.parents('.option-set');
    //     // change selected class
    //     $optionSet.find('.selected').removeClass('selected');
    //     $this.addClass('selected');
    //     
    //     // store filter value in object
    //     // i.e. filters.color = 'red'
    //     var group = $optionSet.attr('data-filter-group');
    //     filters[ group ] = $this.attr('data-filter-value');
    //     // convert object into array
    //     var isoFilters = [];
    //     for ( var prop in filters ) {
    //       isoFilters.push( filters[ prop ] )
    //     }
    //     var selector = isoFilters.join('');
    //     $container.isotope({ filter: selector });
    
    
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