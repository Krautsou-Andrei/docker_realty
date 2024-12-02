jQuery(document).ready(function ($) {
  let container = $("#content-container-new-gk-buildings");
  let isLoading = false;
  let paged = 2;

  const currentUrl = window.location.href;
  const url = new URL(currentUrl);
  const queryParams = url.searchParams;

  let region = "Краснодарский край";
  let city = "Новороссийск";
  let typeBuild = "Квартиры";
  let rooms = "";
  let selectPrice = "";
  let selectArea = "";

  queryParams.forEach((value, key) => {
    if (key === "city") {
      city = value;
    }
    if (key === "region") {
      region = value;
    }
    if (key === "type-build") {
      typeBuild = value;
    }
    if (key === "rooms") {
      rooms = value;
    }
    if (key === "select_price") {
      selectPrice = value;
    }
    if (key === "select_area") {
      selectArea = value;
    }
  });

  function loadMorePosts() {
    if (isLoading) {
      return;
    }

    isLoading = true;

    $.ajax({
      url: ajax_object.ajaxurl,
      type: "POST",
      data: {
        action: "load_gk_new_buildings",
        paged: paged,
        region: region,
        city: city,
        typeBuild: typeBuild,
        rooms: rooms,
        selectPrice: selectPrice,
        selectArea: selectArea,
      },
      success: function (response) {
        if (response.success) {
          if (!response.data.end) {
            container.append(response.data.gk);
            favorites();

            paged++;
            isLoading = false;
          } else {
            isLoading = false;
          }
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

function updateMainScript() {
  const sliderCatalogPreviewMobile = new Swiper(".preview-gallery-mobile", {
    loop: true,
    slidesPerView: 1,
    centeredSlides: true,
    observer: true,
    observerParents: true,
    observerSlideChildren: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });
}

function myFunctionSent() {
  const button = document.querySelector("#success");

  button.setAttribute("data-type", "popup-success");
  button.click();
  button.removeAttribute("data-type");
}

document.addEventListener(
  "wpcf7mailsent",
  function (event) {
    myFunctionSent();
  },
  false
);

function favorites() {
  const buttonFavorites = document.querySelectorAll("[data-button-favorite]");
  const buttonFavoritesMobile = document.querySelectorAll(
    "[data-button-favorite-mobile]"
  );
  var expiration = new Date();
  expiration.setTime(expiration.getTime() + 30 * 24 * 60 * 60 * 1000); // Срок действия: 30 дней

  if (buttonFavorites) {
    buttonFavorites.forEach((button) => {
      button.addEventListener("click", setCookie);
    });
  }

  if (buttonFavoritesMobile) {
    buttonFavoritesMobile.forEach((button) => {
      button.addEventListener("click", setCookie);
    });
  }

  function setCookie(event) {
    event.stopPropagation();

    const cardId = event.currentTarget.dataset.favoriteCookies;
    const category = event.currentTarget.dataset.categoryCookie;

    const isFavorites = event.currentTarget.dataset.deleteFavorite;

    const favorites = getNameFromCookie("favorites"); // Получаем текущие избранные значения из cookies
    const categories = getNameFromCookie("categories");

    const currentUrl = window.location.href;

    const objectSaveCategoty = `${category},${cardId}`;

    if (isFavorites) {
      if (category !== undefined) {
        categories.delete(objectSaveCategoty);
      } else {
        favorites.delete(cardId);
      }

      const attributesDeleteFavorite =
        event.currentTarget.parentElement.parentElement.querySelectorAll(
          "[data-delete-favorite]"
        );

      attributesDeleteFavorite.forEach((attribute) => {
        if (attribute.dataset.buttonFavoriteMobile !== undefined) {
          attribute.classList.remove("delete");
        }

        if (attribute.dataset.buttonFavorite !== undefined) {
          attribute.innerHTML = "<span>В избранное</span>";
        }

        attribute.dataset.deleteFavorite = "";
      });

      if (currentUrl.includes("favorites")) {
        window.location.href = `${window.location.origin}/favorites'`;
      }
    } else {
      if (category !== undefined) {
        categories.add(objectSaveCategoty);
      } else {
        favorites.add(cardId);
      }

      const attributesDeleteFavorite =
        event.currentTarget.parentElement.parentElement.querySelectorAll(
          "[data-delete-favorite]"
        );
      attributesDeleteFavorite.forEach((attribute) => {
        if (attribute.dataset.buttonFavoriteMobile !== undefined) {
          attribute.classList.add("delete");
        }

        if (attribute.dataset.buttonFavorite !== undefined) {
          attribute.innerHTML = "<span>Удалить</span>";
        }

        attribute.dataset.deleteFavorite = "1";
      });
    }

    saveFavoritesToCookie(favorites); // Сохраняем обновленный массив в cookies
    if (category !== undefined) {
      saveCategoriesToCookie(categories);
    }
  }

  function saveCategoriesToCookie(categories) {
    const categoriesArray = Array.from(categories); // Преобразуем Set в массив
    const categoriesString = JSON.stringify(categoriesArray); // Преобразуем массив в строку JSON
    document.cookie = `categories=${encodeURIComponent(
      categoriesString
    )}; expires=${expiration.toUTCString()}; path=/`;
  }

  function saveFavoritesToCookie(favorites) {
    const favoritesArray = Array.from(favorites); // Преобразуем Set в массив
    const favoritesString = JSON.stringify(favoritesArray); // Преобразуем массив в строку JSON
    document.cookie = `favorites=${encodeURIComponent(
      favoritesString
    )}; expires=${expiration.toUTCString()}; path=/`;
  }

  function getNameFromCookie(name) {
    const favoritesString = getCookie(name);
    if (favoritesString) {
      const favoritesArray = JSON.parse(decodeURIComponent(favoritesString)); // Преобразуем строку JSON в массив
      return new Set(favoritesArray); // Преобразуем массив в Set
    }
    return new Set();
  }

  function getCookie(name) {
    let matches = document.cookie.match(
      new RegExp(
        "(?:^|; )" +
          name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
          "=([^;]*)"
      )
    );
    return matches ? decodeURIComponent(matches[1]) : undefined;
  }
}
