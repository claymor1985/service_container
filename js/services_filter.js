/**
 * @file
 * Defines Javascript behaviors for service list filter.
 */

(function ($) {

  $filter = $('.services-filter');

  $filter.on("keyup", function() {
    if ($(this).val()) {
      var input = $(this).val();
      $filteredServices = $('.service-link a[data-service-name*='+ input +']').parent();
      $('.service-link').addClass('service-invisible');
      $filteredServices.removeClass('service-invisible');
    }
    else {
      $('.service-link').removeClass('service-invisible');
    }
  });

  $('.service-filter-reset').on("click", function(){
    $filter.val('');
    $('.service-link').removeClass('service-invisible');
  });


})(jQuery);