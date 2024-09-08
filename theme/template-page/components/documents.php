<section class="documents">
  <div class="documents__container">
    <h2 class="documents__title title--xl">Документы</h2>
    <div class="filter-wrapper">
      <div class="documents__filter">
        <ul class="radio-documents">
          <li>
            <label class="radio">
              <input type="radio" name="type-documents" data-filter-documents="all" value="Все" checked  data-button-filter-documents/>
              <span>Все</span>
            </label>
          </li>
          <li>
            <label class="radio">
              <input type="radio" name="type-documents" data-filter-documents="documents_license" value="Лицензии" data-button-filter-documents/>
              <span>Лицензии</span>
            </label>
          </li>
          <li>
            <label class="radio">
              <input type="radio" name="type-documents" data-filter-documents="documents_evidence" value="Свидетельства" data-button-filter-documents/>
              <span>Свидетельства</span>
            </label>
          </li>
          <li>
            <label class="radio">
              <input type="radio" name="type-documents" data-filter-documents="documents_certificates" value="Сертификаты" data-button-filter-documents/>
              <span>Сертификаты</span>
            </label>
          </li>
          <li>
            <label class="radio">
              <input type="radio" name="type-documents" data-filter-documents="documents_attest" value="Аттестаты" data-button-filter-documents/>
              <span>Аттестаты</span>
            </label>
          </li>
        </ul>
      </div>
      <div class="documents__filter-mobile slider-documents-radio swiper">
        <ul class="swiper-wrapper">
            <li class="swiper-slide">
                <label class="radio">
                  <input type="radio" name="type-documents-mobile" data-filter-documents="all" value="Все" checked  data-button-filter-documents-mobile />
                  <span>Все</span>
                </label>
            </li>
            <li class="swiper-slide">
                <label class="radio">
                  <input type="radio" name="type-documents-mobile" data-filter-documents="documents_license" value="Лицензии"  data-button-filter-documents-mobile />
                  <span>Лицензии</span>
                </label>
            </li>
            <li class="swiper-slide">
                <label class="radio">
                  <input type="radio" name="type-documents-mobile" data-filter-documents="documents_evidence" value="Свидетельства" data-button-filter-documents-mobile />
                  <span>Свидетельства</span>
                </label>
            </li>
            <li class="swiper-slide">
                <label class="radio">
                  <input type="radio" name="type-documents-mobile" data-filter-documents="documents_certificates" value="Сертификаты"  data-button-filter-documents-mobile />
                  <span>Сертификаты</span>
                </label>
            </li>
            <li class="swiper-slide">
                <label class="radio">
                  <input type="radio" name="type-documents-mobile" data-filter-documents="documents_attest" value="Аттестаты"  data-button-filter-documents-mobile />
                  <span>Аттестаты</span>
                </label>
            </li>
        </ul>
      </div>
    </div>
    <div class="slider-documents-gallery">
      <ul class="slider-documents-gallery__list documents__list" id="show-pagination-documents">
          <?php
            $min_show_documents = 10;
            $count_documents = 0;
            $crb_employees_field = carbon_get_post_meta(5, 'crb_employees_field');
            $employees_documents = [];
            $all_documents = [];
            
            foreach($crb_employees_field as $employee){
                array_push($employees_documents, ...$employee['crb_documents']);
            };
            
            foreach($employees_documents as $type_documents){
                $type = $type_documents["_type"];
                $documents = $type_documents["crb_{$type}_gallery"];
                array_push($all_documents, ...$documents);
            };
            
            foreach($all_documents as $index => $document_id){
                // $type = $type_documents["_type"];
                // $documents = $type_documents["crb_{$type}_gallery"];
                
                // foreach ($documents as $index => $document_id) {
                    $count_documents ++;
                    
                    if($count_documents <= $min_show_documents){
                        
    
                        $document_url = wp_get_attachment_image_src($document_id, 'full');
                        $document_alt = get_post_meta($document_id, '_wp_attachment_image_alt', true);
                  
                
                        echo '
                            <li class="slider-documents-gallery__slide document" data-type="popup-gallery-documents">
                              <figure data-type="popup-gallery-documents">
                                    <div class="image-wrapper" data-type="popup-gallery-documents">
                                        <img class="document__image" loading="lazy"   src="' . $document_url[0] . '" alt="' . $document_alt . '" class="document__image" data-index-slider="'.$index.'" data-type="popup-gallery-documents" data-button-open-gallery-documents/>
                                        
                                    </div>
                                    <figcaption data-index-slider="'.$index.'" data-type="popup-gallery-documents" data-button-open-gallery-documents>' . $document_alt . '</figcaption>
                              </figure>
                            </li>';
                    };
                    
                // }
             };
            
            
          ?>
          
        
      </ul>
    </div>
   
    <?php
        if($count_documents > $min_show_documents){
            echo '  <div class="documents__button" id="next-page-pagination-documents" >
                      <button class="button button--all-documents" data-button-pagination-documents data-page="1" data-next-page="true" ><span>Показать еще</span></button>
                    </div>';
            
        }
    
    ?>
    
    
   
  </div>
</section>
