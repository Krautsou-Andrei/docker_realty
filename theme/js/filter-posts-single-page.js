jQuery(document).ready(function ($) {
  let isFetch = true;

  $("[data-button-filter-posts-single-page]").on("click", function (e) {
    const filterType = e.currentTarget.value;

    if (isFetch) {
      isFetch = false;
      $.ajax({
        url: ajax_object.ajaxurl,
        type: "POST",
        data: {
          action: "filter_posts_single_page",
          filterType: filterType,
        },
        success: function (response) {
          if (response.success) {
            $("#put-posts-filter-single-page").html(response.data.posts);
          }
        },
        error: function (xhr, status, error) {
          console.error(error);
        },
        complete: function () {
          isFetch = true;
        },
      });
    }
  });
});
