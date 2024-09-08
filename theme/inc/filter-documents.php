<?php
if (!defined("ABSPATH")) {
    exit;
}

// Функция для загрузки постов
function filter_documents_handler()
{
    $search_type = $_POST['typeSearch'];
    $filter_type = $_POST['filterType'];
    $employeeId = $_POST['employee'];
    $min_show_documents = $_POST['limit'];
    $page = $_POST['page'];;
    
    
    // $is_show_button_next_page = false;
    $all_documents = [];
    $filter_documents = [];
    
    $crb_employees_field = carbon_get_post_meta(5, 'crb_employees_field');
    
    
    if($employeeId){
        $employee = $crb_employees_field[$employeeId];
        $all_documents =  $employee['crb_documents'];
    } else {
        foreach($crb_employees_field as $employee){
            array_push($all_documents, ...$employee['crb_documents']);
        };
        
    }
    
    
    
    ob_start();

    $documents_markup = '';

    foreach ($all_documents as $type_documents) {

        $type = $type_documents["_type"];
        $documents = $type_documents["crb_{$type}_gallery"];

        if($filter_type === $type){
            array_push($filter_documents, ...$documents);   
        } 
        
        if($filter_type === 'all'){
            array_push($filter_documents, ...$documents); 
            
            // if(count($filter_documents) > $min_show_documents){
            //     $is_show_button_next_page = true;
                
            // }
        } 
    }
    
    if($search_type == 'employee'){
        foreach ($filter_documents as $document_id) {
            $document_url = wp_get_attachment_image_src($document_id, 'full');
            $document_alt = get_post_meta($document_id, '_wp_attachment_image_alt', true);
    
             $documents_markup .= '
                                  <li class="slider-documents-gallery-popup__slide swiper-slide document" >
                                      <figure>
                                        <div class="image-wrapper">
                                          <img src="' . $document_url[0] . '"  alt="' . $document_alt . '" class="document__image" />
                                        </div>
                                        <figcaption>' . $document_alt . '</figcaption>
                                      </figure>
                                    </li>
                                  ';
        }
        
    } 
    
    if($search_type == "documents"){
        foreach ($filter_documents as $index => $document_id) {

            $document_url = wp_get_attachment_image_src($document_id, 'full');
            $document_alt = get_post_meta($document_id, '_wp_attachment_image_alt', true);
            
            if($index < $min_show_documents * $page || $page === 'all'){
        
                $documents_markup .= '  <li class="slider-documents-gallery__slide swiper-slide document" data-type="popup-gallery-documents">
                                          <figure data-type="popup-gallery-documents">
                                                <div class="image-wrapper" data-type="popup-gallery-documents">
                                                    <img src="' . $document_url[0] . '" alt="' . $document_alt . '" class="document__image" data-index-slider="'.$index.'" data-type="popup-gallery-documents" data-button-open-gallery-documents/>
                                                
                                                </div>
                                                <figcaption data-index-slider="'.$index.'" data-type="popup-gallery-documents" data-button-open-gallery-documents>' . $document_alt . '</figcaption>
                                          </figure>
                                        </li>';
            }
        };
         
        // if(count($filter_documents) > $min_show_documents * $page ){
        //     $page ++;
               
        // }
        
    }
    
    $documents_gallery_markup = "";
    
    if($search_type == "documents-popup"){
        foreach ($filter_documents as $index => $document_id) {

            $document_url = wp_get_attachment_image_src($document_id, 'full');
            $document_alt = get_post_meta($document_id, '_wp_attachment_image_alt', true);
            
            
        
                $documents_markup .= '      <li class="slider-documents-preview__slide swiper-slide" data-swiper-slide-index="'.$index.'" role="group" aria-label="'.($index + 1).' / '.count($filter_documents).'">
                                                <div class="slide-wrapper">
                                                  <img src="' . $document_url[0] . '" alt="' . $document_alt . '" />
                                                </div>
                                            </li>';
                                                        
                $documents_gallery_markup .= '  <li class="slider-popup-documents-gallery__slide swiper-slide" role="group" aria-label="'.($index + 1).' / '.count($filter_documents).'">
                                                 <img src="' . $document_url[0] . '" alt="' . $document_alt . ' width="82" height="64" />
                                                </li>';                                        
            
        };
         
        // if(count($filter_documents) > $min_show_documents * $page ){
        //     $page ++;
               
        // }
        
    }
    
    if($documents_markup === ""){
            $documents_markup = '<li class="slider-documents-gallery-popup__slide swiper-slide document" style="width: 100%; display: flex; aling-items: center; justify-content: center">
                                  <figure>
                                    <div class="image-wrapper">
                                     <span>Данного вида документов не найдено</span>
                                    </div>
                                    <figcaption></figcaption>
                                  </figure>
                                </li>';
        }
    
    $response = array(
        // 'type' => $search_type,
        // 'isShowButton' => $is_show_button_next_page,
        // 'page' => $page,
        'documents' => $documents_markup,
        'documentsGallery' => $documents_gallery_markup,
    );

    // wp_reset_postdata();

    // $response = ob_get_clean();

    wp_send_json_success($response);
    wp_die();
}

add_action('wp_ajax_filter_documents', 'filter_documents_handler');
add_action('wp_ajax_nopriv_filter_documents', 'filter_documents_handler');
