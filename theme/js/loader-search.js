jQuery(document).ready(function ($) {
  const SELECTORS = {
    BUTTON_SEARCH: "[data-button-search]",
    LOADER: "[data-loader]",
  };

  const buttonSearch = $(SELECTORS.BUTTON_SEARCH);
  const loader = $(SELECTORS.LOADER);

  function blockScroll() {
    $("body").css({
      overflow: "hidden",
      height: "100%",
    });
  }

  if (buttonSearch) {
    buttonSearch.on("click", () => {
      loader.addClass("active");
      blockScroll();
    });
  }
});
