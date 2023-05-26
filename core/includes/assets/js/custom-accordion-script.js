jQuery(document).ready(function ($) {
  $("#custom-accordion-load-more").on("click", function () {
    var button = $(this),
      data = {
        action: "custom_accordion_load_more",
        security: customAccordion.nonce,
        post_type: customAccordion.post_type,
        taxonomy: customAccordion.taxonomy,
        term: customAccordion.term,
        count: customAccordion.count,
        offset: $(".et_pb_toggle").length, // Pass the number of existing posts
      };

    $.post(customAccordion.ajaxurl, data, function (response) {
      if (response.success) {
        var posts = $(response.data);

        if (posts.length > 0) {
          $(".et_pb_toggle:last").after(posts); // Append the loaded posts
        } else {
          button.hide(); // Hide the "Load More" button if no more posts
          // You can add additional logic here to hide other elements
        }
      } else {
        console.log("Error: " + response.data);
      }
    });
  });
});
