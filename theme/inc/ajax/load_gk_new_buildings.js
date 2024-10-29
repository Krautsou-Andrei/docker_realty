jQuery(document).ready(function ($) {
  let container = $("#content-container-new-gk-buildings");

  let paged = 2; // Начинаем с загрузки со второй страницы

  function loadMorePosts() {
    $.ajax({
      url: ajax_object.ajaxurl,
      type: "POST",
      data: {
        action: "load_gk_new_buildings",
        paged: paged,
      },
      success: function (response) {
        if (response.success) {
          container.append(response.data);
          favorites();

          paged++;
        }
      },
    });
  }

  function checkScroll() {
    if (container.length > 0) {
      let windowHeight = $(window).height();
      let scrollTop = $(window).scrollTop();
      let documentHeight = $(document).height();
      let contentBottomOffset =
        container.offset().top + container.outerHeight();

      if (
        window.innerWidth < 768 &&
        scrollTop + windowHeight >= contentBottomOffset - 100
      ) {
        loadMorePosts();
      }
    }
  }

  $(window).on("scroll", checkScroll);
});
