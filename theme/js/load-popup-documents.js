jQuery(document).ready(function ($) {
  let isFetch = true;

  $("[data-button-documents]").on("click", function (e) {
    e.preventDefault();

    const employeeId = e.currentTarget.dataset.employee;

    if (isFetch) {
      isFetch = false;

      $.ajax({
        url: ajax_object.ajaxurl,
        type: "POST",
        data: {
          action: "load_documents",
          employee: employeeId,
        },
        success: function (response) {
          $("#put-popup-employee-documents-employee").html(
            response.data.employee
          );
          $("#put-popup-employee-documents-documents").html(
            response.data.documents
          );
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
