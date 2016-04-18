/**
 * @file
 * Defines Javascript behaviors for service list filter.
 */

(function () {

  var showServices = function(elements) {
    [].forEach.call(elements, function(elem) {
      elem.classList.remove('service-invisible');
    });
  };

   var hideServices = function(elements) {
    [].forEach.call(elements, function(elem) {
      elem.classList.add('service-invisible');
    });
  };

  var filter = document.querySelector('.services-filter');
  var allServices = document.querySelectorAll('.service-link');

  filter.onkeyup = function() {

    if (this.value) {

      var input = this.value;
      var links = document.querySelectorAll('.service-link a[data-service-name*='+ input +']');

      var filteredServices = [];
      [].forEach.call(links, function(elem) {
        filteredServices.push(elem.parentElement);
      });

      hideServices(allServices);
      showServices(filteredServices);
    }
    else {
      showServices(allServices);
    }
  };

  filterReset = document.querySelector('.service-filter-reset');
  filterReset.onclick = function() {
    filter.value = '';
    showServices(allServices);
  };

})();
