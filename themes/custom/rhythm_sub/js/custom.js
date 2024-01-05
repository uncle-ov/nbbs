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

    $('.user-login-form .form-item-pass').append('<span class="password__reveal"><i class="fa fa-eye-slash"></i></span>');

    $('.password__reveal').on('click', function(){
      var $pwd = $(this).prev('input');
      if ($pwd.attr('type') === 'password') {
        $pwd.attr('type', 'text');
        $(this).html('<i class="fa fa-eye"></i>');
      } else {
        $pwd.attr('type', 'password');
        $(this).html('<i class="fa fa-eye-slash"></i>');
      }
    });
  });
})(jQuery);
