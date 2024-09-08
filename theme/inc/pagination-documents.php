<?php
if (!defined("ABSPATH")) {
    exit;
}

// Функция для загрузки постов
function pagination_documents_handler()
{
    $page = $_POST['page'];
    $min_show_documents = $_POST['limit'];
    $isNextPage = false;
    
    $crb_employees_field = carbon_get_post_meta(5, 'crb_employees_field');
    $all_documents = [];
    
    foreach($crb_employees_field as $employee){
        
        foreach($employee['crb_documents'] as $type_documents){
            $type = $type_documents["_type"];
            array_push($all_documents, ...$type_documents["crb_{$type}_gallery"]);
        }
    };
    
    $documents_markup = '';
   
    
    foreach ($all_documents as $index => $document_id) {

            $document_url = wp_get_attachment_image_src($document_id, 'full');
            $document_alt = get_post_meta($document_id, '_wp_attachment_image_alt', true);
            
            if($page === 'all' || $index < $min_show_documents * $page){
        
                $documents_markup .= '  <li class="slider-documents-gallery__slide swiper-slide document" data-type="popup-gallery-documents">
                                          <figure data-type="popup-gallery-documents">
                                                <div class="image-wrapper" data-type="popup-gallery-documents">
                                                    <img src="' . $document_url[0] . '" alt="' . $document_alt . '" class="document__image" data-index-slider="'.$index.'" data-type="popup-gallery-documents" data-filter-documents = "' . $type . '" data-button-open-gallery-documents/>
                                                </div>
                                                <figcaption data-index-slider="'.$index.'" data-type="popup-gallery-documents" data-button-open-gallery-documents data-filter-documents = "' . $type . '">' . $document_alt . '</figcaption>
                                          </figure>
                                        </li>';
            }
    };
        
    $button_markup = '';
     
    if($page !== 'all' && count($all_documents) > $min_show_documents * $page ){
        $isNextPage = true;
            
    }
    
    
    $response = array(
        'isNextPage' => $isNextPage,
        'documents' => $documents_markup
    );


    wp_send_json_success($response);
    wp_die();
}

add_action('wp_ajax_pagination_documents', 'pagination_documents_handler');
add_action('wp_ajax_nopriv_pagination_documents', 'pagination_documents_handler');
