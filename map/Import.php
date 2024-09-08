<?php

class Import {
    
    public static function get($link, $type = 'market')
    {
        $items = json_decode(file_get_contents($link), true);
        $data = [];
        $data['type'] = 'FeatureCollection';
        if(array_key_exists('data', $items) and array_key_exists('features', $items['data']) and count($items['data']['features']) > 0)
        {
            foreach ($items['data']['features'] as $key => $item) {
                $data['features'][$key]['type'] = $item['type'];
                $data['features'][$key]['id'] = $item['id'];
                $data['features'][$key]['geometry'] = $item['geometry'];
                $data['features'][$key]['options']['iconImageHref'] = '/wp-content/themes/realty/assets/images/yamap/'.$type.'.svg';
                $data['features'][$key]['geometry']['coordinates'] = [ $data['features'][$key]['geometry']['coordinates'][1] , $data['features'][$key]['geometry']['coordinates'][0] ];
                $data['features'][$key]['properties']['balloonContent'] = '<div><h3 class="common-selection__title">'.$item['properties']['clusterCaption'].'</h3></div>';
                $data['features'][$key]['properties']['clusterCaption'] = $item['properties']['clusterCaption'];
            }
            return $data;
        }else{
            return false;
        }

    }

    public static function save($folder, $name, $data){

        $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE);

        $filePath = $folder . '/' .$name. '.json';
        $fp = fopen($filePath, 'w');
        
        fwrite($fp, $jsonString);
        fclose($fp);
    } 
}