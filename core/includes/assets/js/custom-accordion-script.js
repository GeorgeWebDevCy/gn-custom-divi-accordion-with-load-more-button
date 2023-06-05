jQuery(document).ready(function ($) {
  $('[id^="custom-accordion-load-more-"]').on("click", function () {
    var $button = $(this);
    var accordion_id = $button.data("accordion-id");
    var accordionContainer = $(
      ".et_pb_module.et_pb_accordion.et_pb_accordion_" + accordion_id
    );
    var offset = accordionContainer.find(".et_pb_toggle").length;

    console.log("Load More button clicked");
    console.log("Button ID:", $button.attr("id"));
    console.log("Accordion Unique ID:", accordion_id);

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
        accordion_unique_id: "custom-accordion-load-more-" + accordion_id,
      },
      beforeSend: function () {
        $button.addClass("et_pb_loading");
      },
      success: function (response) {
        if (response.success) {
          console.log("URL:", customAccordion.ajaxurl);
          console.log("Type:", "POST");
          console.log("Data:");
          console.log("  action:", "custom_accordion_load_more");
          console.log("  security:", customAccordion.nonce);
          console.log("  post_type:", customAccordion.post_type);
          console.log("  taxonomy:", customAccordion.taxonomy);
          console.log("  term:", customAccordion.term);
          console.log("  count:", customAccordion.count);
          console.log("  offset:", offset);
          console.log("  accordion_id:", accordion_id);

          console.log("item = " + accordion_id);
          console.log("Response " + JSON.stringify(response));
          console.log("Response Data " + JSON.stringify(response.data));
          accordionContainer.append(response.data);
          $button.removeClass("et_pb_loading");

          if (response.data.trim() === "") {
            $button.hide();
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
