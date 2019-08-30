jQuery(document).ready(function($) {
  $("#cc-product-review-form").on("submit", function(e) {
    e.preventDefault();
    $("#cc-review-processing").show();
    var data = $(this).serialize();

    $.ajax({
      type: "POST",
      url: cc_review.ajax_url,
      data: data,
      dataType: "html",
      success: function(result) {
        $("#cc-product-review-failed").hide();
        $("#cc-product-review-received").show("slow");
        $("#cc-review-processing").hide();
        $("#cc-product-review-form")[0].reset();
      },
      error: function() {
        $("#cc-product-review-failed").show("slow");
        $("#cc-review-processing").hide();
      }
    });
  });
});
