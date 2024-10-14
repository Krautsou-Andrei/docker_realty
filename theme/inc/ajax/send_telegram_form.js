var send_telegram_form;

jQuery(document).ready(function ($) {
  var isFetch = true;

  send_telegram_form = function (data) {
    if (isFetch) {
      isFetch = false;
      $.ajax({
        url: ajax_object.ajaxurl,
        type: "POST",
        data: data,
        success: function (response) {},
        error: function (xhr, status, error) {
          console.error(error);
        },
        complete: function () {
          isFetch = true;
        },
      });
    }
  };
});
