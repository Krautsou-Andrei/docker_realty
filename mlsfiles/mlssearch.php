<div class="mh-rs-search mh-rs-search--middle mh-rs-search--type-5 mh-rs-search--size-medium mh-rs-search--bg-light-mask-">
<div id="myhome-search-form-submit" class="realty_filter rfmainp">
   <form method="get" action="https://estplanb.ru/realtyform.php">
	 <div class="row">
	 
												<div class="col-xs-12 col-lg-2 pr1 pl1 mt02">
                          
                            <select name="variant" size="1">
															<option value="0" selected>Купить</option>
															<option value="1">Снять</option>
                            </select>
                          
                        </div>														
                        <div class="col-xs-12 col-lg-2 pr1 pl1 mt02">
                          <div>
                            <select name="razdel" size="1">
															<option value="1" selected>Квартиры</option>
															<option value="2">Комнаты</option>
															<option value="3">Новостройки</option>
															<option value="4">Дома</option>
															<option value="5">Участки</option>
															<option value="6">Коммерческая</option>
                            </select>
                          </div>
                        </div>												
                        <div class="col-xs-12 col-lg-2 pr1 pl1 mt02">
                          
                            <select name="rooms" size="1">
                              <option value="">Комнат</option>
                              <option value="9999">Студия</option>
                              <option value="1">1 комната</option>
                              <option value="2">2 комнаты</option>
                              <option value="3">3 комнаты</option>
                              <option value="4">4 и более</option>                              
                            </select>
                        
                        </div>

						
							<div class="col-xs-12 col-lg-2 pr1 pl1 mt02"><input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="price_ot" placeholder="Цена от, ₽" value=""/></div>
							<div class="col-xs-12 col-lg-2 pr1 pl1 mt02"><input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="price_do" placeholder="Цена до, ₽" value=""/></div>
						
						
                        <div class="col-xs-12 col-lg-2 pr1 pl1 mt02">
                         
                            <select name="district" size="1">
															<option value="">Район</option>
															<option value="40041">Бабынинский район</option>
															<option value="40049">городской округ Калуга</option>
															<option value="40042">Дзержинский район</option>
															<option value="40025">Ленинский</option>
															<option value="40046">Ленинский район</option>
															<option value="40043">Мещовский район</option>
															<option value="40026">Московский</option>
															<option value="40027">Октябрьский</option>
															<option value="40044">Перемышльский район</option>
															<option value="40038">поселок Керамзитный</option>
															<option value="40050">Правобережье</option>
															<option value="40045">Ферзиковский район</option>
                            </select>
                          
                        </div>
	</div>	
		
										
	<div class="row text-right">
   <button type="submit" style="margin-top:10px;margin-left:5px" class="mdl-button mdl-button--lg_ mdl-js-button mdl-button--raised" onclick="$('#mls-map').val('1')"><span>Показать на карте</span></button>
	 <button type="submit" style="margin-top:10px;margin-right:5px" class="mdl-button mdl-button--lg_ mdl-js-button mdl-button--raised mdl-button--primary"><span>Найти</span></button>
	</div> 
	<input id="mls-map" name="map" value="0" type="hidden">
	</form>
</div> 
</div>		