<?php
if (!defined("ABSPATH")) {
    exit;
}

// Функция для загрузки постов
function load_documents_handler()
{
    $employeeId = $_POST['employee'];

    $crb_employees_field = carbon_get_post_meta(5, 'crb_employees_field');

    $employee = $crb_employees_field[$employeeId];

    $image_id = $employee['crb_employee_image'];
    $name = $employee['crb_employee_name'];
    $last_name = $employee['crb_employee_last_name'];
    $type = $employee['crb_employee_type'];
    $all_documents =  $employee['crb_documents'];

    $image_url =  wp_get_attachment_image_src($image_id, 'full');



    ob_start();

    $employee_markup = '<div class="popup-employee__image">
                          <img src="' . $image_url[0] . '" alt="" width="58" height="58"/>
                        </div>
                        <div class="popup-employee__info-wrapper">
                          <h2 class="popup-employee__title title--lg">' . $name . ' ' . $last_name . '</h2>
                          <p class="popup-employee__subtitle">' . $type . '</p>
                        </div>';

    $documents_markup = '';

    foreach ($all_documents as $type_documents) {

        $type = $type_documents["_type"];
        $documents = $type_documents["crb_{$type}_gallery"];

        foreach ($documents as $document_id) {

            $document_url = wp_get_attachment_image_src($document_id, 'full');
            $document_alt = get_post_meta($document_id, '_wp_attachment_image_alt', true);

            $documents_markup .= '<li class="slider-documents-gallery-popup__slide swiper-slide document" data-' . $type . '>
                                    <figure>
                                    <div class="image-wrapper">
                                        <img src="' . $document_url[0] . '" alt="' . $document_alt . '" class="document__image" />
                                    </div>
                                    <figcaption>' . $document_alt . '</figcaption>
                                    </figure>
                                </li>
                                ';
        }
    }
    
    if($documents_markup === ""){
        $documents_markup = '<li class="slider-documents-gallery-popup__slide swiper-slide document"  style="width: 100%; display: flex; aling-items: center; justify-content: center">
                              <figure>
                                <div class="image-wrapper">
                                 <span>Документов не найдено</span>
                                </div>
                                <figcaption></figcaption>
                              </figure>
                            </li>';
    }

    $response = array(
        'employee' => $employee_markup,
        'documents' => $documents_markup
    );


    // wp_reset_postdata();

    // $response = ob_get_clean();

    wp_send_json_success($response);
    wp_die();
}

add_action('wp_ajax_load_documents', 'load_documents_handler');
add_action('wp_ajax_nopriv_load_documents', 'load_documents_handler');
