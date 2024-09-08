jQuery(document).ready(function($) {
    // Обработчик клика на кнопку
    $('[data-button-documents]').on('click', function(e) {
        e.preventDefault();

        const employeeId = e.currentTarget.dataset.employee
        

        // Отправка AJAX-запроса
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
              action: 'load_documents',
              employee: employeeId,
            },
            success: function(response) {
                // Вставка полученной разметки в контейнер
                $('#put-popup-employee-documents-employee').html(response.data.employee);
                $('#put-popup-employee-documents-documents').html(response.data.documents);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});