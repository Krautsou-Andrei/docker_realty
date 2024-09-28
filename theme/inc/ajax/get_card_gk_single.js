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
        updateMainScript();
        initializeFormFilter();
      } else {
        content.html("Ничего не найдено");
      }
    },
    error: function (xhr, status, error) {
      loader.hide();
      console.error(error);
    },
  });

  function updateMainScript() {
    const SELECTORS = {
      RETURN_FIRST_SLIDE: "[data-return-first-slide]",
    };

    const sliderSinglePagePreviewMobile = new Swiper(".product-single-slider", {
      preloadImages: false,
      slidesPerView: "auto",
      observer: true,
      observerParents: true,
      observerSlideChildren: true,
      spaceBetween: 7,
      scrollbar: {
        el: ".custom-scrollbar",
      },
      thumbs: {
        swiper: {
          el: ".product-single-slide-gallery",
          slidesPerView: "auto",
          observer: true,
          observerParents: true,
          observerSlideChildren: true,
          spaceBetween: 10,
        },
      },
      lazy: {
        loadOnTransitionStart: true,
        loadPrevNext: true,
      },
      watchSlidesProgress: true,
      watchSlidesVisibility: true,
    });

    function returnFirstSlide() {
      const returnButton = document.querySelector(SELECTORS.RETURN_FIRST_SLIDE);
      if (returnButton) {
        returnButton.addEventListener("click", () => {
          sliderSinglePagePreviewMobile.slideTo(0);
        });
      }
    }
  }
});
