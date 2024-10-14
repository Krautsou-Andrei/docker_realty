jQuery(document).ready(function ($) {
  let isFetch = true;

  function handleLoadDocuments(e) {
    const button = e.currentTarget;
    const page = e.currentTarget.dataset.page;
    const limit = 10;
    const isNextPage = e.currentTarget.dataset.nextPage;

    if (isFetch) {
      $.ajax({
        url: ajax_object.ajaxurl,
        type: "POST",
        data: {
          action: "pagination_documents",
          page: isNextPage ? page + 1 : page,
          limit: limit,
        },
        success: function (response) {
          if (response.success) {
            if (response.data.isNextPage) {
              $("#next-page-pagination-documents").show();
              button.dataset.page = page + 1;
              button.dataset.nextPage = response.data.isNextPage;
            } else {
              $("#next-page-pagination-documents").hide();
              button.dataset.nextPage = response.data.isNextPage;
            }

            $("#show-pagination-documents").html(response.data.documents);
            $("[data-button-open-gallery-documents]").on(
              "click",
              handleClickOpenPopupDocuments
            );
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
  }

  $("[data-button-pagination-documents]").on("click", handleLoadDocuments);

  if ($(window).width() < 768) {
    handleLoadDocuments({ currentTarget: { dataset: { page: "all" } } });
  }

  function handleClickOpenPopupDocuments(e) {
    const indexSlider = e.currentTarget.dataset.indexSlider;

    let filterType = "all";
    const type = "documents-popup";

    if ($(window).width() > 1080) {
      const buttons = $(".radio-documents [data-button-filter-documents]")
        .toArray()
        .map(function (element) {
          return $(element);
        });

      buttons.forEach(function (button) {
        if (button.is(":checked")) {
          filterType = button.data("filterDocuments");
        }
      });
    } else {
    }

    if (isFetch) {
      isFetch = false;

      $.ajax({
        url: ajax_object.ajaxurl,
        type: "POST",
        data: {
          action: "filter_documents",
          filterType: filterType,
          typeSearch: type,
        },
        success: function (response) {
          if (response.success) {
            $("[data-put-documents-popup-preview]").html(
              response.data.documents
            );
            $("[data-put-documents-popup-gallery]").html(
              response.data.documentsGallery
            );

            window.sliderDocuments.update();
            window.sliderDocuments.slideTo(indexSlider);

            $("[data-button-open-gallery-documents]").on(
              "click",
              handleClickOpenPopupDocuments
            );
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
  }
});
