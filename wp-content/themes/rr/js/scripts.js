// DOM Ready
$(function() {
  
  // Longest work is 561px
  var category_title = $(".entry-title")[0];
  var category_name_width = $(category_title).width();
  var wordCount = $(category_title).text().replace( /[^\w ]/g, "" ).split( /\s+/ ).length
  
  if ( category_name_width >= 480 ) {
    $(category_title).addClass('category-long');
  }
    
  // } else if (longest word count >= whatever)  {
  //   
  // }
  
  function mycarousel_initCallback(carousel) {
  	$('#mycarousel-next').bind('click', function() {
        carousel.next();
        return false;
    });

    $('#mycarousel-prev').bind('click', function() {
        carousel.prev();
        return false;
    });
  }
  
  if ($('body').hasClass('home')) {
    $("#mycarousel").jcarousel({
      scroll: 6,
      initCallback: mycarousel_initCallback,
      buttonNextHTML: null,
      buttonPrevHTML: null,
      auto: 2,
      wrap: "circular",
      animation: 2000
  	});
  }
  
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
  
  // If you need to add styles to Marketo, uncomment this
  // $(".marketo-insight").each(function() {
  //   $(this).load(function (){
  //       // do something once the iframe is loaded
  //       var script=document.createElement('script');
  // 
  //       // script=document.standardCreateElement('script');
  //       // script.src = 'http://localhost:8888/rr/wp-content/themes/rr/js/marketo.js';
  //       // script.type = 'text/javascript';
  //       
  //       var $head = $(this).contents().find("head");      
  //       
  //       $head.append(script);
  //       
  //       $head.append($("<link/>", 
  //           { rel: "stylesheet", href: "http://fonts.googleapis.com/css?family=Roboto:400,300", type: "text/css" }));
  //                     
  //       $head.append($("<link/>", 
  //           { rel: "stylesheet", href: "http://localhost:8888/rr/wp-content/themes/rr/css/marketo.css", type: "text/css" }));
  //   });
  // });
  
  $('body').bind('click', function(e) {
    if($(e.target).closest('#format-select').length == 0
    && $(e.target).closest('#topic-select').length == 0
    && $(e.target).closest('#region-select').length == 0) {
      // click happened outside of menu, hide any visible menu items
      hideFilterDropdowns(e.target);  
    } else {
      //showFilters();
    }
  });
  
  $('#format-select').click(function(){
    hideFilterDropdowns(this);
    $('#format').show();        
  });
  
  $('#topic-select').click(function(){
    hideFilterDropdowns(this);
    $('#topic').show();        
  });
  
  $('#region-select').click(function(){
    hideFilterDropdowns(this);
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
    
    $('#' + type + '-select').show();
    
    return false;
  }
  
  function hideFilterDropdowns(current_filterbox) {
    showFilters();
    
    $('#format').hide(); 
    $('#topic').hide(); 
    $('#region').hide(); 
    
    if ($(current_filterbox).attr('id') == 'format-select'
    || $(current_filterbox).attr('id') == 'topic-select'
    || $(current_filterbox).attr('id') == 'region-select') {
      $(current_filterbox).hide();
    }
  }
  
  function showFilters() {
    $('#format-select').show();
    $('#topic-select').show();
    $('#region-select').show();
  }
  
});