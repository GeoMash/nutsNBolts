(function() {

  $(function() {
    var $window;
    $window = $(window);
    $('section [href^=#]').click(function(e) {
      return e.preventDefault();
    });
    setTimeout(function() {
      return $('.bs-docs-sidenav').affix({
        offset: {
          top: function() {
            var _ref;
            return (_ref = $window.width() <= 980) != null ? _ref : {
              290: 210
            };
          },
          bottom: 270
        }
      }, 100);
    });
    return window.prettyPrint && prettyPrint();
  });

}).call(this);
