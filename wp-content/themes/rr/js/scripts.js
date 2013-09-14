function responsiveCategoryHeader() {
  if ($('body').hasClass('category') || $('body').hasClass('date')) {        
    // Longest word is 561px
    var window_width = $(window).width();
    var category_title = $(".entry-title")[0];
    var category_name_width = $(category_title).width();
    var category_name_height = $(category_title).height();
    var word_count = $(category_title).text().replace( /[^\w ]/g, "" ).split( /\s+/ ).length;
    var check_category_header_class = false;
    var max_acceptable_header_width = 0;
    var max_acceptable_header_height = 0;
    var min_acceptable_header_width = 0;
    var min_acceptable_header_height = 0;

    if ( window_width <= 320) {
      check_category_header_class = true;
      max_acceptable_header_width = 290;
      min_acceptable_header_width = 175;
      max_acceptable_header_height = 80;//77 is default
    } else if ( window_width <= 400 ) {
      check_category_header_class = true;
      max_acceptable_header_width = 370;
      min_acceptable_header_width = 178;
      max_acceptable_header_height = 80;//77 is default
    } else if ( window_width <= 650 ) {
      check_category_header_class = true;
      max_acceptable_header_width = 620;
      min_acceptable_header_width = 490;
      max_acceptable_header_height = 80;//77 is default
    } else if ( window_width <= 930 ) {
      check_category_header_class = true;
      max_acceptable_header_width = 900;
      min_acceptable_header_width = 770;
      max_acceptable_header_height = 110;//106 is default
    }

    if ( check_category_header_class ) {
      var category_header_class = checkHeaderLength(word_count, category_name_width, 
          category_name_height, max_acceptable_header_width, max_acceptable_header_height); 
      $(category_title).addClass(category_header_class);
      
      var category_name_width = $(category_title).width();
      var category_name_height = $(category_title).height();
      
      if (category_header_class != '') {
        category_header_class = recheckHeaderLength(word_count, category_name_width, 
              category_name_height, min_acceptable_header_width, min_acceptable_header_height); 
        $(category_title).addClass(category_header_class);
      }
    }

    $(".entry-title").css('visibility', 'visible');

    function checkHeaderLength(word_count, title_width, title_height, max_acceptable_header_width, max_acceptable_header_height) {
      var category_header_class = '';

       if ( title_height > max_acceptable_header_height ) {
          category_header_class = 'category-long category-long-wrap';
        } else if ( title_width >= max_acceptable_header_width)  {
          category_header_class = 'category-long';
        } 

      return category_header_class;
    }
    
    function recheckHeaderLength(word_count, title_width, title_height, min_acceptable_header_width, min_acceptable_header_height) {
       var category_header_class = '';

        if ( title_height <= min_acceptable_header_height ) {
           category_header_class = 'category-medium category-medium-wrap';
         } else if ( title_width <= min_acceptable_header_width)  {
           category_header_class = 'category-medium';
         } 

       return category_header_class;
     }
  }
}

// $(window).resize(function() {
//   responsiveCategoryHeader();
// });

// DOM Ready
$(function() {
  responsiveCategoryHeader();
  
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
  
  // if ($('body').hasClass('home')) {
  //   $("#mycarousel").jcarousel({
  //     scroll: 6,
  //     initCallback: mycarousel_initCallback,
  //     buttonNextHTML: null,
  //     buttonPrevHTML: null,
  //     auto: 2,
  //     wrap: "circular",
  //     animation: 2000
  //  });
  // }
  
  /*	CarouFredSel: a circular, responsive jQuery carousel.
  	Configuration created by the "Configuration Robot"
  	at caroufredsel.dev7studios.com
  */
  // $("#mycarousel").carouFredSel({
  //  width: "100%",
  //  height: "auto",
  //  items: {
  //    visible: 6,
  //    width: "variable",
  //    height: "variable"
  //  },
  //  scroll: {
  //    items: 6,
  //    duration: 2000,
  //    pauseOnHover: true
  //  }
  // });
  
  $("#mycarousel ul").carouFredSel({
    width   : "100%",
  	responsive	: true,
  	scroll		:  6,
  	items		: {
  		visible		: {
  		  min : 1,
  		  max : 6
  		},
  		width		: 200,
  		duration: 2000,
  		minimum: 1
  	}
  });
  
  
  
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