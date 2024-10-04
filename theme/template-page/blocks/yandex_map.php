<?php
$city = isset($args['city']) ? json_encode($args['city']) : json_encode('Новороссийск');
$coordinates = isset($args['coordinates']) ? (json_encode($args['coordinates'])) : '[]';
$coordinates_center = isset($args['coordinates_center']) ? (json_encode($args['coordinates_center'])) : '[]';
$title = isset($args['title']) ? $args['title'] : '';
$locations = isset($args['locations']) ? json_encode($args['locations']) : json_encode('[]');

?>

<div id="single-map" class="product__map">
    <div class="single-page-map-title"><?php echo $title ?></div>
    <div class="container">
        <div class="map__select">
            <div>
                <input id="map_select_1" class="map_select_checkbox" type="checkbox" value="market" name="show[]">
                <label for="map_select_1" class="map_select_checkbox_label">Продуктовые магазины</label>
            </div>
            <div>
                <input id="map_select_2" class="map_select_checkbox" type="checkbox" value="hospital" name="show[]">
                <label for="map_select_2" class="map_select_checkbox_label">Медицинские учреждения</label>
            </div>
            <div>
                <input id="map_select_3" class="map_select_checkbox" type="checkbox" value="school" name="show[]" checked="true">
                <label for="map_select_3" class="map_select_checkbox_label">Школы</label>
            </div>
            <div>
                <input id="map_select_4" class="map_select_checkbox" type="checkbox" value="kindergarten" name="show[]" checked="true">
                <label for="map_select_4" class="map_select_checkbox_label">Детские сады</label>
            </div>
        </div>
        <div id="map" class="map-wrapper"></div>
    </div>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=d62977c1-6b54-4445-9e4f-920e2ef797de"
        type="text/javascript">
    </script>
    <script>
        ymaps.ready(init);

        function init() {
            var myMap = new ymaps.Map("map", {
                    center: <?php echo $coordinates_center ?>,
                    zoom: 16
                }, {
                    searchControlProvider: "yandex#search"
                }),
                objectManager = new ymaps.ObjectManager({
                    clusterize: false,
                    gridSize: 32,
                    clusterDisableClickZoom: false,
                });
            const coordinates = <?php echo $coordinates ?>;

            if (coordinates.length > 0) {
                var myPlacemark = new ymaps.Placemark(coordinates, {
                    balloonContent: ""
                }, {
                    iconLayout: "default#imageWithContent",
                    iconImageHref: "/wp-content/themes/realty/assets/images/yamap/ico_adres.svg",
                    iconImageSize: [26, 30],
                    iconImageOffset: [-13, -30],
                    balloonOffset: [17, 0],
                    balloonPanelMaxMapArea: 0,
                    hideIconOnBalloonOpen: false
                });

                myMap.geoObjects.add(myPlacemark);
            } else {
                var locations = <?php echo $locations; ?>;

                locations.forEach(function(location) {
                    var myPlacemarkCustom = new ymaps.Placemark(location.coordinates, {
                        balloonContent: location.balloonContent
                    }, {
                        iconLayout: "default#imageWithContent",
                        iconImageHref: "/wp-content/themes/realty/assets/images/yamap/ico_adres.svg",
                        iconImageSize: [26, 30],
                        iconImageOffset: [-13, -30],
                        balloonOffset: [17, 0],
                        balloonPanelMaxMapArea: 0,
                        hideIconOnBalloonOpen: false
                    });

                    myMap.geoObjects.add(myPlacemarkCustom);
                });

            }

            objectManager.objects.options.set("iconLayout", "default#imageWithContent");
            objectManager.objects.options.set("iconImageSize", [20, 20]);
            objectManager.objects.options.set("iconImageOffset", [-10, -20]);
            objectManager.objects.options.set("balloonOffset", [0, -10]);
            objectManager.objects.options.set("balloonPanelMaxMapArea", 0);
            objectManager.objects.options.set("hideIconOnBalloonOpen", false);
            objectManager.clusters.options.set("iconColor', '#4C4DA2");

            myMap.geoObjects.add(objectManager);

            jQuery(document).ready(function($) {
                function handleCheckboxChange() {
                    $(".map_select_checkbox").each(function() {
                        objectManager.removeAll();
                        if ($(this).is(":checked")) {
                            var city = <?php echo $city ?>;
                            var citySlug = (city === "Новороссийск") ? "novoross" : "krasnodar";

                            if (citySlug !== "") {
                                var url = "/map/" + citySlug + "/" + $(this).val() + ".json";
                                $.get(url).done(function(data) {
                                    objectManager.add(data);
                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                    console.error("Ошибка при загрузке данных: " + errorThrown);
                                });
                            } else {
                                console.error("Город не определен");
                            }
                        }
                    });
                }

                $(".map_select_checkbox").on("change", handleCheckboxChange);
                handleCheckboxChange(); // Вызов функции для обработки состояния чекбоксов при загрузке страницы
            });

        }
    </script>



</div>