//JQuery Module Pattern

// An object literal
var app = {
  init: function() {
    app.functionOne();
  },
  functionOne: function () {
  }
};
$("document").ready(function () {
  app.init();

  var menubuttons = $('.menubuttons');

  menubuttons.on('click', function (e) {
      e.preventDefault();
      var $this = $(this);
      // Display active tab
      let currentTab = $(this).attr('href');
      $('.sectionuser').hide();
      $(currentTab).show();
      menubuttons.removeClass('active-tab');
      $this.toggleClass('active-tab').next('div').toggleClass('active');
  });



    // navbar toggler
           $('.topnav-user li .dropdown_toggle').on('click', function () {

                $('.profile-dropdown').toggleClass('show');
               return false;

            });

            $(window).on('click', function () {

                $('.profile-dropdown').removeClass('show');

           });

});