<div class="popup" data-popup="popup-employee-documents" data-close-overlay>
  <div class="popup__wrapper" data-close-overlay>
    <div class="popup__content">
        <div class="visually-hidden" aria-hidden="true" data-employee-id>0</div>
      <div class="popup__container">
        <button class="popup__close button-close button--close" type="button" aria-label="Закрыть"></button>
        <div class="popup__body">
            
          <div class="popup-employee-documents__employee popup-employee" id="put-popup-employee-documents-employee">
          </div>
          
          <div class="filter-wrapper">
            <div class="documents__filter">
               <ul class="radio-documents-popup">
                  <li>
                    <label class="radio">
                      <input type="radio" name="type-documents" value="Все" checked data-filter-documents="all" data-employee="" data-button-filter-employee-documents data-employee-id/>
                      <span>Все</span>
                    </label>
                  </li>
                  <li>
                    <label class="radio">
                      <input type="radio" name="type-documents" value="Лицензии" data-filter-documents="documents_license" data-employee="" data-button-filter-employee-documents data-employee-id/>
                      <span>Лицензии</span>
                    </label>
                  </li>
                  <li>
                    <label class="radio">
                      <input type="radio" name="type-documents" value="Свидетельства" data-filter-documents="documents_evidence" data-employee="" data-button-filter-employee-documents data-employee-id/>
                      <span>Свидетельства</span>
                    </label>
                  </li>
                  <li>
                    <label class="radio">
                      <input type="radio" name="type-documents" value="Сертификаты" data-filter-documents="documents_certificates" data-employee=""  data-button-filter-employee-documents data-employee-id/>
                      <span>Сертификаты</span>
                    </label>
                  </li>
                  <li>
                    <label class="radio">
                      <input type="radio" name="type-documents" value="Аттестаты" data-filter-documents="documents_attest" data-employee="" data-button-filter-employee-documents data-employee-id/>
                      <span>Аттестаты</span>
                    </label>
                  </li>
                </ul>
            </div>
            <div class="documents__filter-mobile slider-documents-radio-popup swiper">
              <ul class="swiper-wrapper">
                  <li class="swiper-slide" data-filter-documents="all" data-employee="" data-button-filter-employee-documents data-employee-id>
                    <label class="radio">
                      <input type="radio" name="type-documents-popup-mobile" value="Квартиры" checked />
                      <span>Все</span>
                    </label>
                  </li>
                  <li class="swiper-slide" data-filter-documents="documents_license" data-employee="" data-button-filter-employee-documents data-employee-id>
                    <label class="radio">
                      <input type="radio" name="type-documents-popup-mobile" value="Лицензии"  />
                      <span>Лицензии</span>
                    </label>
                  </li>
                  <li class="swiper-slide" data-filter-documents="documents_evidence" data-employee="" data-button-filter-employee-documents data-employee-id>
                    <label class="radio">
                      <input type="radio" name="type-documents-popup-mobile" value="Свидетельства" />
                      <span>Свидетельства</span>
                    </label>
                  </li>
                  <li class="swiper-slide" data-filter-documents="documents_certificates" data-employee=""  data-button-filter-employee-documents data-employee-id>
                    <label class="radio">
                      <input type="radio" name="type-documents-popup-mobile" value="Сертификаты" />
                      <span>Сертификаты</span>
                    </label>
                  </li>
                  <li class="swiper-slide" data-filter-documents="documents_attest" data-employee="" data-button-filter-employee-documents data-employee-id>
                    <label class="radio">
                      <input type="radio" name="type-documents-popup-mobile" value="Аттестаты"  />
                      <span>Аттестаты</span>
                    </label>
                  </li>
              </ul>
            </div>
          </div>
          <div class="popup-employee-documents__documents popup-documents">
                <div class="slider-documents-gallery-popup swiper">
                  <ul class="slider-documents-gallery-popup__list swiper-wrapper" id="put-popup-employee-documents-documents">
                    <li class="slider-documents-gallery-popup__slide swiper-slide document">
                      <figure>
                        <div class="image-wrapper">
                        </div>
                        <figcaption></figcaption>
                      </figure>
                    </li>
                  </ul>
                </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
