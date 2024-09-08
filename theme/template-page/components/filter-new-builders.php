<div class="mb1 main-catalog__filter">
    <section class="filter-catalog">
        <div class="filter-catalog__container">

            <div class="filter-catalog-mobile mb1">
                <div class="filter-catalog-mobile__button">
                    <a class="button-catalog-filter" onclick="window.history.go(-1); return false;">
                        <img src="/wp-content/themes/realty/assets/images/back.svg" alt="">
                        <span>Назад </span>
                    </a>
                </div>
                <div class="filter-catalog-mobile__button">
                    <button class="button-catalog-filter">
                        <img src="/wp-content/themes/realty/assets/images/filter.svg">
                        <span data-type="popup-filter">Фильтры </span>
                    </button>
                </div>
            </div>


            <form method="post" id="realty_filter_form" class="hidden-xs">
                <div class="row realty_filter search_filter">
                    <div class="col-xs-12">

                        <div class="op10 col-lg-2 col-md-4 col-sm-6 col-xs-12">

                            <select class="form-control" name="razdel" onchange="javascript:location=this.value;">
                                <option value="' . $this->pagelink1 . '" selected>Квартиры</option>
                                <option value="' . $this->pagelink1r . '">Комнаты</option>
                                <option value="' . $this->pagelink1nb . '">Новостройки</option>
                                <option value="' . $this->pagelink2 . '">Дома</option>
                                <option value="' . $this->pagelink2_1 . '">Участки</option>
                                <option value="' . $this->pagelink4 . '">Коммерческая</option>
                            </select>
                        </div>
                        <div class="op10 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <select class="form-control" name="city">
                                <option> Любой город</option>
                            </select>
                        </div>
                        <div class="op10 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <select multiple id="raions" class="form-control" name="raions[]" class="form-control" tabindex="4" data-placeholder="Район">
                            </select>
                        </div>
                        <div class="op10 col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <select id="mater" multiple name="mater[]" class="form-control" data-placeholder="Материал стен"></select>
                        </div>


                        <div class="op10 col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <select name="stage" class="form-control">
                                <option value="" disabled selected>Этаж</option>
                                <option value="">Любой этаж</option>
                            </select>
                        </div>



                        <!-- <link rel="stylesheet" href="' . $this->scriptpath . 'css/jquery-ui.min.css">
                        <script src="' . $this->scriptpath . 'js/jquery-ui.min.js"></script>
                        <script src="' . $this->scriptpath . 'js/jquery.ui.touch-punch.min.js"></script> -->

                        <div class="ui-slider-title op10 col-lg-3 col-md-4 col-sm-6 col-xs-12 pl1 pr1 mt7">
                            <strong>Площадь, м²</strong><span id="mlsArea"> от до </span><br />
                            <input type="hidden" id="area_ot" name="area_ot" value="10" />
                            <input type="hidden" id="area_do" name="area_do" value="220" />
                            <div class="col-xs-12 mt1" id="slider-mlsArea"></div>
                        </div>

                        <div class="ui-slider-title op10 rooms_f col-lg-3 col-md-4 col-sm-6 col-xs-12 mt7">
                            <b>Комнат</b>
                            <div class="rooms-wrapper">
                                <input type="checkbox" name="rooms[]" id="rooms9999" value="9999"><label for="rooms9999"><span class="btn btn-default">Студия</span></label>
                                <input type="checkbox" name="rooms[]" id="rooms1" value="1"><label for="rooms1"><span class="btn btn-default">1</span></label>
                                <input type="checkbox" name="rooms[]" id="rooms2" value="2"><label for="rooms2"><span class="btn btn-default">2</span></label>
                                <input type="checkbox" name="rooms[]" id="rooms3" value="3"><label for="rooms3"><span class="btn btn-default">3</span></label>
                                <input type="checkbox" name="rooms[]" id="rooms4" value="4"><label for="rooms4"><span class="btn btn-default">4+</span></label>
                            </div>
                        </div>

                        <div class="ui-slider-title op10 col-lg-3 col-md-4 col-sm-6 col-xs-12 mt7">
                            <strong>Цена, ₽</strong><span id="mlsPrice"> от 10 до 20 </span><br />
                            <input type="hidden" id="price_ot" name="price_ot" value="1">
                            <input type="hidden" id="price_do" name="price_do" value="2">
                            <div class="col-xs-12 mt1" id="slider-mlsPrice"></div>
                        </div>
                        <script>
                            function numberWithSpaces(x) {
                                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                            }

                            function clearslide() {
                                $("#price_ot").val(0);
                                $("#price_do").val(0);
                                $("#area_ot").val(0);
                                $("#area_do").val(0);
                            }
                            $(function() {
                                $("#slider-mlsPrice").slider({
                                    range: true,
                                    min: 10,
                                    max: 20,
                                    step: 1,
                                    values: [10, 20],
                                    slide: function(event, ui) {
                                        $("#price_ot").val(ui.values[0]);
                                        $("#price_do").val(ui.values[1]);
                                        $("#mlsPrice").html(" от " + numberWithSpaces(ui.values[0]) + " до " + numberWithSpaces(ui.values[1]));
                                    }
                                });

                            });

                            $(function() {
                                $("#slider-mlsArea").slider({
                                    range: true,
                                    min: 20,
                                    max: 30,
                                    step: 1,
                                    values: [20, 30],
                                    slide: function(event, ui) {
                                        $("#area_ot").val(ui.values[0]);
                                        $("#area_do").val(ui.values[1]);
                                        $("#mlsArea").html(" от " + ui.values[0] + " до " + ui.values[1]);
                                    }
                                });

                            });
                        </script>
                        <div class="op10 col-lg-push-1 col-lg-2 col-md-8 col-sm-6 col-xs-12 text-right pt25">

                            <div class="form-filter-catalog__button">
                                <button name="UPD" id="UPDFilter" class="button" type="submit">
                                    <img src="/wp-content/themes/realty/assets/images/search_outline.svg" width="16" height="16" alt=""><span>Найти</span>
                                </button>
                            </div>
                            <input name="mlspage" id="mlspage" type="hidden" value="1" />
                            <input name="mlsorder" id="mlsorder" type="hidden" value="' . (isset($this->mlsorder) ? $this->mlsorder : '1') . '" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>