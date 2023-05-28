jQuery(document).ready(function ($) {
  // Handle the Load More button click
  $("#custom-accordion-load-more").on("click", function () {
    var $button = $(this);
    var offset = $(".et_pb_toggle").length;

    // AJAX request
    $.ajax({
      url: customAccordion.ajaxurl,
      type: "POST",
      data: {
        action: "custom_accordion_load_more",
        security: customAccordion.nonce,
        post_type: customAccordion.post_type,
        taxonomy: customAccordion.taxonomy,
        term: customAccordion.term,
        count: customAccordion.count,
        offset: offset,
      },
      beforeSend: function () {
        $button.addClass("et_pb_loading");
      },
      success: function (response) {
        var $accordion = $(".et_pb_accordion_0");

        if (response.success) {
          $accordion.append(response.data);
          $button.removeClass("et_pb_loading");

          // Check if there are more posts to load
          if (response.data.trim() === "") {
            $button.hide(); // Hide the Load More button
          }
        } else {
          console.log(response.data);
        }
      },
      error: function (xhr, status, error) {
        console.log(error);
      },
    });
  });
});
