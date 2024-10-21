var GlobalFilterSlider;
var GlobalSetSliderByFilter;

jQuery(document).ready(function ($) {
  const SELECTORS = {
    FILTER_FROM: "[data-filter-from]",
    FILTER_FROM_VIEW: "[data-filter-from-view]",
    FILTER_TO: "[data-filter-to]",
    FILTER_TO_VIEW: "[data-filter-to-view]",
    INPUT: "[data-input-visible]",
    RANGE_FILTER: "[ data-range-progress-filter]",

    AREA_FROM: "#area-from",
    AREA_TO: "#area-to",
    PRICE_FROM: "#price-from",
    PRICE_TO: "#price-to",
    RANGE_AREA: "[data-range-progress-area]",
    RANGE_PRICE: "[data-range-progress-price]",
  };

  class FilterSlider {
    constructor(element) {
      this.elements = element;
      this.filterFrom = this.elements.querySelectorAll(SELECTORS.FILTER_FROM);
      this.filterFromView = this.elements.querySelectorAll(
        SELECTORS.FILTER_FROM_VIEW
      );
      this.filterGap = 1;
      this.filterTo = this.elements.querySelectorAll(SELECTORS.FILTER_TO);
      this.filterToView = this.elements.querySelectorAll(
        SELECTORS.FILTER_TO_VIEW
      );
      this.inputs = this.elements.querySelectorAll(SELECTORS.INPUT);
      this.rangesFilter = this.elements.querySelectorAll(
        SELECTORS.RANGE_FILTER
      );

      this.addListeners(this.inputs);
    }

    addListeners(array) {
      array.forEach((element) => {
        element.addEventListener("input", this.openFilterInput.bind(this));
      });
    }

    openFilterInput(event) {
      if (event.target.closest(SELECTORS.INPUT)) {
        const input = event.target;
        this.slideInput(input);
      }
    }

    slideInput(input, minValView, maxValView) {
      const range_inputs = input.parentNode.children;

      let minVal = minValView ? minValView : parseInt(range_inputs[0].value);
      let maxVal = maxValView ? maxValView : parseInt(range_inputs[1].value);

      if (maxVal && minVal && maxVal - minVal < this.filterGap) {
        if ("filterFrom" in input.dataset) {
          this.filterFrom.forEach((inputfilterFrom) => {
            maxVal = maxVal - this.filterGap;
            inputfilterFrom.value = maxVal;
          });
        } else {
          this.filterTo.forEach((inputfilterTo) => {
            minVal = minVal + this.filterGap;
            inputfilterTo.value = minVal;
          });
        }
      }
      this.setSliderProgress(
        this.rangesFilter,
        this.filterFrom,
        this.filterFromView,
        this.filterTo,
        this.filterToView,
        minVal,
        maxVal
      );
    }

    setSliderProgress(
      ranges,
      inputsFrom,
      inputViewFrom,
      inputsTo,
      inputViewTo,
      minVal,
      maxVal
    ) {
      if (maxVal && minVal && maxVal - minVal >= this.filterGap) {
        inputsFrom.forEach((inputfilterFrom) => {
          inputfilterFrom.value = minVal;
        });
        inputsTo.forEach((inputfilterTo) => {
          inputfilterTo.value = maxVal;
        });

        inputViewFrom.forEach((viewfilterFrom) => {
          viewfilterFrom.textContent = `${minVal}`;
        });
        inputViewTo.forEach((viewcfilterTo) => {
          viewcfilterTo.textContent = `${maxVal}`;
        });

        ranges.forEach((range) => {
          range.style.left =
            ((minVal - inputsFrom[0].min) /
              (inputsFrom[0].max - inputsFrom[0].min)) *
              100 +
            "%";
          range.style.right =
            100 -
            (((maxVal == "" ? 1 : maxVal) -
              (maxVal == "" ? 1 : inputsTo[0].min)) /
              (inputsTo[0].max - inputsTo[0].min)) *
              100 +
            "%";
        });
      }
    }
  }

  function setSliderByFilter() {
    // Такой же код есть в popup-filter
    const areaFrom = document.querySelector(SELECTORS.AREA_FROM);
    const areaTo = document.querySelector(SELECTORS.AREA_TO);
    const rangesArea = document.querySelectorAll(SELECTORS.RANGE_AREA);
    const priceFrom = document.querySelector(SELECTORS.PRICE_FROM);
    const priceTo = document.querySelector(SELECTORS.PRICE_TO);
    const rangesPrice = document.querySelectorAll(SELECTORS.RANGE_PRICE);

    const minValArea = areaFrom?.value;
    const maxValArea = areaTo?.value;
    const minValPrice = priceFrom?.value;
    const maxValPrice = priceTo?.value;

    if (minValArea && maxValArea) {
      rangesArea.forEach((range) => {
        range.style.left =
          ((minValArea - areaFrom.min) / (areaFrom.max - areaFrom.min)) * 100 +
          "%";
        range.style.right =
          100 -
          ((maxValArea - areaTo.min) / (areaTo.max - areaTo.min)) * 100 +
          "%";
      });
    }
    if (minValPrice && maxValPrice) {
      rangesPrice.forEach((range) => {
        range.style.left = (minValPrice / priceFrom.max) * 100 + "%";
        range.style.right = 100 - (maxValPrice / priceTo.max) * 100 + "%";
      });
    }
  }

  GlobalSetSliderByFilter = setSliderByFilter;
  GlobalFilterSlider = FilterSlider;
});
