jQuery(document).ready(function($) {
    // Обработчик клика на кнопку
    $('[data-button-filter-employee-documents]').on('click', function(e) {
        // e.preventDefault();

        const filterType = e.currentTarget.dataset.filterDocuments
        const employeeId = e.currentTarget.dataset.employee
        const type = 'employee';
        
        // Отправка AJAX-запроса
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
              action: 'filter_documents',
              filterType: filterType,
              employee: employeeId,
              typeSearch: type,
              page: 'all',
              
            },
            success: function(response) {
                if(response.success){
                    // Вставка полученной разметки в контейнер
                    $('#put-popup-employee-documents-documents').html(response.data.documents);
                        
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    
    function handleFilterButtons(e){
        // e.preventDefault();

        const filterType = e.currentTarget.dataset.filterDocuments
        const type = "documents";
        

        // Отправка AJAX-запроса
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
              action: 'filter_documents',
              filterType: filterType,
              typeSearch: type,
              page: 'all',
              
            },
            success: function(response) {
                if(response.success){
                    
                    $('#next-page-pagination-documents').hide();
                    // $('#next-page-pagination-documents').data('page', response.data.page);
                
                    // $('#next-page-pagination-documents').hide();
                    
                    // Вставка полученной разметки в контейнер
                    $('#show-pagination-documents').html(response.data.documents);
                    $('[data-button-open-gallery-documents]').on('click', handleClickOpenPopupDocuments);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
    
   
    
    function handleClickOpenPopupDocuments(e) {
        // e.preventDefault();
        const indexSlider = e.currentTarget.dataset.indexSlider;
        
        let filterType = 'all';
        const type = "documents-popup";
        
        if($(window).width() > 1080){
            const buttons = $('.radio-documents [data-button-filter-documents]').toArray().map(function(element) {
              return $(element);
            });
            
            buttons.forEach(function(button) {
              if (button.is(':checked')) {
                filterType = button.data('filterDocuments')
              }
            });
        } else {
            const buttons = $('.slider-documents-radio [data-button-filter-documents-mobile]').toArray().map(function(element) {
              return $(element);
            });
            
            buttons.forEach(function(button) {
              if (button.is(':checked')) {
                filterType = button.data('filterDocuments')
              }
            });
        }
    
        // Отправка AJAX-запроса
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_documents',
                filterType: filterType,
                typeSearch: type,
                page: 'all',
            },
            success: function(response) {
                if (response.success) {
                    $('[data-put-documents-popup-preview]').html(response.data.documents);
                    $('[data-put-documents-popup-gallery]').html(response.data.documentsGallery);
          
                    window.sliderDocuments.update();
                    window.sliderDocuments.slideTo(indexSlider);
                    
                    // Подписка на событие click после успешного ответа AJAX
                    $('[data-button-open-gallery-documents]').off('click').on('click', handleClickOpenPopupDocuments);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    $('[data-button-open-gallery-documents]').on('click', handleClickOpenPopupDocuments);
    $('[data-button-filter-documents-mobile]').on('click', handleFilterButtons);
    $('[data-button-filter-documents]').on('click', handleFilterButtons);
    
});