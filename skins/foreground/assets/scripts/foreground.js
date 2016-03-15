
jQuery(document).ready(function() {
  // Add the 'less than IE9' class to appropriate version of IE by checking for their support of cssFloat (true in v9)
  if (!jQuery.support.cssFloat) { jQuery('html').addClass('lt-ie9').addClass('no-js'); }


  jQuery(document).foundation(function (response) {
    // console.log(response.errors); < this line will produce error in ie9!
    if (window.console) console.log(response.errors);
  });
  
  // The Echo extension puts an item in personal tools that Foreground really should have in the top menu
  // to make this easier, we move it here and loaded earlier to speed up transform
  jQuery("#pt-notifications").prependTo("#echo-notifications");
  jQuery("#pt-notifications-alert").prependTo("#echo-notifications");
  
  // Append font-awesome icons

  // Turn categories into labels
  jQuery('#mw-normal-catlinks ul li a').addClass('label');

  // Make the Page Action button respond to hover
  jQuery('a.button.dropdown').mouseenter(function(){
    jQuery('ul#drop1').addClass('open').addClass('right').css('top', '32px').css('left', '785px');
  });
  jQuery('ul#drop1').mouseleave(function(){
    jQuery('ul#drop1').removeClass('open').css('top', '-9999px').css('left', '785px');
  });

});
