(function() {
  var $ = jQuery;

  Drupal.behaviors.shop_remove_lightbox = {
    attach: function (context, settings) {
       $('.product-teaser').each(function() {
        var href = $(this).find('.post-prev-title a').attr('href');
        $(this).find('.lightbox-gallery-3').attr('href', href).removeClass('lightbox-gallery-3').removeClass('mfp-image');
       });
    }
  };


  $(window).resize(function(){
    $('.fullwidth-slider.owl-carousel').css({'max-height': $(window).height()});
  });

  $('li.comment-delete a').html('<i class="fa fa-times"></i> ' + Drupal.t('Delete'));
  $('li.comment-edit a').html('<i class="fa fa-pencil"></i> ' + Drupal.t('Edit'));
  $('li.comment-reply a').html('<i class="fa fa-comment"></i> ' + Drupal.t('Reply'));

  Drupal.behaviors.blog_timeline = {
    attach: function (context, settings) {
       $('.timeline > ul > li:odd > article', context).addClass('fadeInLeft wow');
       $('.timeline > ul > li:even > article', context).addClass('fadeInRight wow');
       if($('.timeline').lenght) {
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 90,
            mobile: false, 
            live: true 
        });
        wow.init(); 
      }
    }
  };

  Drupal.behaviors.button_js = {
    attach: function (context, settings) {
       $('.button-js', context).click(function() {
        $(this).closest('form').submit();
       });
    }
  };

/*  Drupal.behaviors.href_anchor_smooth = {
    attach: function (context, settings) {
      var $root = $('html, body');
      $('a[href^="#"]', context).click(function() {
          var href = $.attr(this, 'href');
          if (href != '#') {
            $root.animate({
                scrollTop: $(href).offset().top
            }, 500, function () {
                window.location.hash = href;
            });
          }
      });
    }
  };*/

  Drupal.behaviors.href_click = {
    attach: function (context, settings) {
       $('a[href="#"]', context).click(function() {
        return false;
       });
    }
  };

  Drupal.behaviors.cart_remove_wrap = {
    attach: function (context, settings) {
      $('.cart-remove-wrap a', context).click(function() {
        $(this).parent().find('input').click();
        return false;
      });
    }
  };

  Drupal.behaviors.view_sort = {
    attach: function (context, settings) {
      $('.views-exposed-form #edit-sort-order', context).change(function() {
        $(this).parent().parent().find('input').click();
        return false;
      });
    }
  };

  Drupal.behaviors.products_filter = {
    attach: function (context, settings) {
      if($('#block-rhythmproductsfilter').length > 0 && $('.products-filter-from').length > 0) {
        $('.form-item-price__number-min, .form-item-price__number-max').hide();
        $('.products-filter-from input').val($('.form-item-price__number-min input').val());
        $('.products-filter-to input').val($('.form-item-price__number-max input').val());
        $('#block-rhythmproductsfilter button').click(function() {
          $('.form-item-price__number-min input').val($('.products-filter-from input').val());
          var to = $('.products-filter-to input').val();
          $('.form-item-price__number-max input').val(to ? to : 100000);
          $('.form-item-price__number-min input').closest('form').submit();
          return false;
        });
      }
    }
  };

  Drupal.behaviors.product_zoom = {
    attach: function (context, settings) {
      $(".lightbox-gallery-3", context).magnificPopup({
        gallery: {
          enabled: true,
          tCounter: '<span class="mfp-counter">%curr% ' + Drupal.t('of') + ' %total%</span>' // markup of counter
        }
      });
    }
  };

  Drupal.behaviors.tb_megamenu_align = {
    attach: function (context, settings) {
      $('.mega-align-right .mn-sub', context).addClass('to-left');
    }
  };

  $(document).ready(function() {
    Pizza.init();
  });

}());
