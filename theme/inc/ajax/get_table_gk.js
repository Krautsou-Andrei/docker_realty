var initializeFormFilter;

jQuery(document).ready(function ($) {
  initializeFormFilter = function () {
    const SELECTORS = {
      CONTAINER_TABLE: "[data-container-table]",
      FORM_APARTAMENTS: "[data-form-table-apartamens]",
      FORM_APARTAMENTS_INPUT: "[data-form-table-apartaments-input]",
      FORM_INPUT: "[data-form-table-input]",
      FORM_LITER: "[data-form-table-liter]",
      INPUT_TABLE_PARAMS: "[data-input-table-params]",
    };

    const inputTableParams = $(SELECTORS.INPUT_TABLE_PARAMS);
    const inputTableParamsValue = $(SELECTORS.INPUT_TABLE_PARAMS).val();

    $(SELECTORS.FORM_INPUT).change(function () {
      const formTableLiter = $(SELECTORS.FORM_LITER).serializeArray();
      const formApartamens =
        $(this).attr("name") == "gk-liter"
          ? []
          : $(SELECTORS.FORM_APARTAMENTS).serializeArray();

      const currentLiter = formTableLiter[0].value;
      const container_table = $(SELECTORS.CONTAINER_TABLE);

      $.ajax({
        url: ajax_object.ajaxurl,
        type: "POST",
        data: {
          action: "get_table_gk",
          params_table: inputTableParamsValue,
          current_liter: currentLiter,
          form_apartamens: formApartamens,
        },
        success: function (response) {
          if (response.pageGkTable) {
            container_table.html(response.pageGkTable);
          }

          if (response.inputTableParams) {
            inputTableParams.val(response.inputTableParams);
          }
        },
        error: function (xhr, status, error) {
          console.error(error);
        },
        complete: function () {
          initializeFormFilter();
          new NewTooltip();
        },
      });
    });
  };
});
