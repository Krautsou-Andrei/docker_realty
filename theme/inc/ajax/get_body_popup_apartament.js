var NewTooltip;

jQuery(document).ready(function ($) {
  const SELECTORS = {
    APARTAMENT: "[data-info-apartament]",
    APARTAMNET_CONTAINER: "[data-apartament-container]",
    CONTAINER: "[data-container]",
    CORNER: "[data-corner]",
    LOADER: "[data-loader-apartament]",
    POPUP_APARTAMENT: "[date-popup-apartament]",
  };

  const apartamentContainer = document.querySelector(
    SELECTORS.APARTAMNET_CONTAINER
  );

  function get_body(id_apartament) {
    const loader = $(SELECTORS.LOADER);
    loader.show();
    apartamentContainer.innerHTML = "";

    $.ajax({
      url: ajax_object.ajaxurl,
      type: "POST",
      data: {
        action: "get_body_popup_apartament",
        id_apartament: id_apartament,
      },
      success: function (response) {
        loader.hide();

        if (response.body_popup_apartament) {
          apartamentContainer.innerHTML = response.body_popup_apartament;
        }
      },
      error: function (xhr, status, error) {
        loader.hide();
        console.error(error);
      },
    });
  }

  class Tooltip {
    constructor() {
      this.apartaments = document.querySelectorAll(SELECTORS.APARTAMENT);
      this.container = document.querySelector(SELECTORS.CONTAINER);
      this.corner = document.querySelector(SELECTORS.CORNER);
      this.popup = document.querySelector(SELECTORS.POPUP_APARTAMENT);

      this.init();
    }

    init() {
      if (this.apartaments) {
        this.apartaments.forEach((apartament) => {
          apartament.addEventListener(
            "mouseenter",
            this.handlerOver.bind(this)
          );
          apartament.addEventListener("mouseleave", this.handlerOut.bind(this));
        });
      }
    }

    handlerOver(event) {
      const element = event.currentTarget;
      const elementRect = element.getBoundingClientRect();
      const elementLeft = elementRect.left;
      const elementRight = elementRect.right;
      const elementBottom = elementRect.bottom;

      const apartamentId = element.dataset?.infoApartamentId;

      this.popup.classList.remove("visually-hidden");

      const widthContainer = this.container.getBoundingClientRect().width;
      const widthPopup = this.popup.getBoundingClientRect().width;

      const dinstantionRight = widthContainer - elementLeft;

      this.popup.style.top = `${elementBottom + 10}px`;
      this.popup.style.width = "fit-content";

      if (dinstantionRight > widthPopup) {
        this.popup.style.right = "unset";
        this.corner.classList.add("left");
        this.popup.style.left = `${elementLeft}px`;
      } else {
        this.popup.style.left = "unset";
        this.corner.classList.add("right");
        this.popup.style.right = `${widthContainer - elementRight}px`;
      }

      if (apartamentId) {
        get_body(apartamentId);
      }
    }

    handlerOut() {
      this.popup.classList.add("visually-hidden");
      this.corner.classList.remove("left");
      this.corner.classList.remove("right");
    }
  }

  NewTooltip = Tooltip;
});
