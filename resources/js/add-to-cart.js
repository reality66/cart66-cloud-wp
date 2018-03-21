jQuery(document).ready(function($) {

  $('.cc_product_wrapper').delegate('.cart66-button', "click", function() {
    var form = $(this).closest('form');
    var query_string = form.serialize();
    var data = 'action=cc_ajax_add_to_cart&' + query_string;
    $('.alert').hide();
    $.ajax({
      type: 'POST',
      url: cc_cart.ajax_url,
      data: data,
      dataType: 'json',
      success: function(out) {
        if(out.task == 'stay') {
          form.append('<div class="ajax_add_to_cart_message"><span class="alert alert-success ajax_button_notice"><a href="#" title="close" class="cc_close_message"><i class="icon-remove"></i></a><span class="cc_ajax_message">' + out.response + '</span></span></div>');
          $('.cart66-button').trigger('CC:item_added');
          refresh_widget();
          refresh_cart_link();
        }
        else if(out.task == 'redirect') {
          window.location.replace(out.url);
        }
      },
      error: function(response) {
        if(response.status == 500 || response.status == 503) {
          form.append('<div class="ajax_add_to_cart_message"><span class="alert alert-error ajax_button_notice"><a href="#" title="close" class="cc_close_message"><i class="icon-remove"></i></a><span class="cc_ajax_message">There was a problem adding this item to your cart. Please try again or contact the merchant.</span></span></div>');
        }
        else {
          var order_form = form.closest('.cart66');
          order_form.replaceWith(response.responseText);
        }
      }
    });

    return false;
  });

  $('.cc_product_wrapper').delegate('.cc_close_message', 'click', function() {
    $(this).parent().hide();
    return false;
  });

  function refresh_widget() {
    if($('.cc_cart_widget').length > 0) {
      $.post(cc_cart.ajax_url, {action: 'render_cart66_cart_widget'}, function(response) {
        $('.cc_cart_widget').html(response);
      });
    }
  }

  /** Dynamically Update Cart66 Cart Links **/
  refresh_cart_link();

  function refresh_cart_link() {

    var data = 'action=cc_ajax_get_cart_count';

    $.ajax({
      type: 'POST',
      url: cc_cart.ajax_url,
      data: data,
      dataType: 'html',
      success: function( result ) {
        $('.cc-cart-count').each( function( index ) {
          if ( $(this).children().length == 0 ) {
            var text = $(this).text();
            var label = text.substring(0, text.indexOf(':')+1);
            console.log("Found label: " + label);
            $(this).text(label + ' ' + result);
          }

          if ( $(this).children().length != 0 ) {
            var item = $(this).find('a').first();
            var text = item.text();
            var label = text.substring(0, text.indexOf(':')+1);
            item.text(label + ' ' + result);
            console.log("Found text: " + text);
          }

        });
      }
    });
  }

});
