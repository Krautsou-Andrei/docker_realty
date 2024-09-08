jQuery(document).ready(function($) {
    $('[data-button-filter-posts-single-page]').on('click', function(e) {
        // e.preventDefault();

        const filterType = e.currentTarget.value;
       
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
              action: 'filter_posts_single_page',
              filterType: filterType,
              
            },
            success: function(response) {
                if(response.success){
                    $('#put-posts-filter-single-page').html(response.data.posts);
                        
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});