jQuery(document).ready(function ($) {
  const loader = $("[data-loader]");
  const content = $("#content-container-page-gk");

  $.ajax({
    url: ajax_object.ajaxurl,
    type: "POST",
    data: {
      action: "get_card_gk_single",
      id_page_gk: params.id_page_gk,
      slug_page: params.slug_page,
    },
    success: function (response) {
      loader.hide();      
      if (response.pageGk) {
        content.html(response.pageGk);
      } else {
        content.html("Ничего не найдено");
      }
    },
    error: function (xhr, status, error) {
      loader.hide();
      console.error(error);
    },
  });
});
