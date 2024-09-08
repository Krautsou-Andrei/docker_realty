jQuery(document).ready(function($) {
    $('[data-button-filter-posts-home-page]').on('click', function(e) {
        // e.preventDefault();

        const filterType = e.currentTarget.value;
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
              action: 'filter_posts_home_page',
              filterType: filterType,
              
            },
            success: function(response) {
                if(response.success){
                    $('#put-posts-filter-single-page').html(response.data.posts);
                        
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    
//     const fetchData = async () => {
//     try {
//         const response = await fetch('https://dataout.trendagent.ru/krasnodar/buildings.json');
        
//         console.log("response", response);

//         // Проверяем, успешен ли ответ
//         if (!response.ok) {
//             throw new Error('Сетевая ошибка: ' + response.status);
//         }

//         const data = await response.json();
//         console.log(data); // Обработка полученных данных здесь
//     } catch (error) {
//         console.error('Ошибка при получении данных:', error);
//     }
// };

// fetchData();
});