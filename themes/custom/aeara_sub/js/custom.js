// Custom JS

(function ($) {
  $(document).ready(function() {
    $('.matrix__one').hover(function(){
      $('.matrix__images').toggleClass('one');
    });
    $('.matrix__two').hover(function(){
      $('.matrix__images').toggleClass('two');
    });
    $('.matrix__three').hover(function(){
      $('.matrix__images').toggleClass('three');
    });
    $('.matrix__four').hover(function(){
      $('.matrix__images').toggleClass('four');
    });
    $('.matrix__five').hover(function(){
      $('.matrix__images').toggleClass('five');
    });
    $('.matrix__six').hover(function(){
      $('.matrix__images').toggleClass('six');
    });
    $('.matrix__seven').hover(function(){
      $('.matrix__images').toggleClass('seven');
    });
    $('.matrix__eight').hover(function(){
      $('.matrix__images').toggleClass('eight');
    });

    $('.marketplace img[alt~="health"]').each(function(){
      $("<span class='ribbon'><span class='ribbon__label'>Health & Beauty</span></span>").appendTo($(this).parent());
    });
    $('.marketplace img[alt~="hair"]').each(function(){
      $("<span class='ribbon'><span class='ribbon__label'>Hair & Products</span></span>").appendTo($(this).parent());
    });
    $('.marketplace img[alt~="food"]').each(function(){
      $("<span class='ribbon'><span class='ribbon__label'>Food</span></span>").appendTo($(this).parent());
    });
    $('.marketplace img[alt~="merchandise"]').each(function(){
      $("<span class='ribbon'><span class='ribbon__label'>Merchandise</span></span>").appendTo($(this).parent());
    });
    $('.marketplace img[alt~="publications"]').each(function(){
      $("<span class='ribbon'><span class='ribbon__label'>Publications</span></span>").appendTo($(this).parent());
    });

    $('.messages--status').click(function(){
      $(this).hide();
    });

    $('.messages--error').click(function(){
      $(this).hide();
    });

    var show_password_markup = '<span class="password__reveal"><i class="fa fa-eye-slash"></i></span>';
    var generate_password_markup = '<a href="#" class="generate_strong_password">Generate password</a>';

    $('.user-register-form .js-form-item-pass-pass1').append(show_password_markup + generate_password_markup);
    $('.user-login-form .form-item-pass').append(show_password_markup);

    $('.password__reveal').on('click', function(){
      var $pwd = $(this).prev('input');
      revealPassword($pwd);
    });

    $('.generate_strong_password').on('click', function(e){
      var password = generatePassword();
      $pwd = $(this).parent().find('input');
      
      $pwd.val(password);
      revealPassword($pwd, true);
      e.preventDefault();
    });

    function revealPassword($pwd, forceOpen = false) {
      var passwordEye = $pwd.parent().find('.password__reveal');

      if ($pwd.attr('type') === 'password' || forceOpen) {
        $pwd.attr('type', 'text');
        passwordEye.html('<i class="fa fa-eye"></i>');
      } else {
        $pwd.attr('type', 'password');
        passwordEye.html('<i class="fa fa-eye-slash"></i>');
      }
    }

    function generatePassword() {
      var length = 12,
        charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()",
        retVal = "";
      for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
      }
      return retVal;
    }

    // Show the correct tab based on the hash in the URL on business profile page.
    var hash = window.location.hash;
    if (hash === '#shop') {
      $('#openShopTab').click();
    } else if (hash === '#reviews') {
      $('#openReviewsTab').click();
    }

    $("#broughtToYouBy a").attr("target","_blank");

    $('a').each(function() {
      var href = $(this).attr('href');
      var domain = new URL(href).hostname;
      var currentDomain = window.location.hostname;
  
      if (domain !== currentDomain) {
        $(this).attr('target', '_blank');
      }
    });  
  });
})(jQuery);
