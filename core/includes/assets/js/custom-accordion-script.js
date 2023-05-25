jQuery(document).ready(function($) {
    var loadMoreButton = $('#custom-accordion-load-more');
    var accordionContainer = $('#custom-accordion');

    loadMoreButton.on('click', function(e) {
        e.preventDefault();
        var offset = accordionContainer.children('.et_pb_toggle_content').length;

        $.ajax({
            url: customAccordion.ajaxurl,
            type: 'POST',
            data: {
                action: 'custom_accordion_load_more',
                security: customAccordion.nonce,
                post_type: customAccordion.post_type,
                taxonomy: customAccordion.taxonomy,
                term: customAccordion.term,
                count: customAccordion.count,
                offset: offset,
            },
            beforeSend: function() {
                loadMoreButton.addClass('loading');
            },
            success: function(response) {
                if (response.success) {
                    accordionContainer.append(response.data);
                    loadMoreButton.removeClass('loading');
                }
            },
        });
    });
});
