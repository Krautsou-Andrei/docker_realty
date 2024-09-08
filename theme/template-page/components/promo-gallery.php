<?php 
include "wp-content/mlsfiles/exclusiv.php";
        
    $args = array(
              'post_type' => 'post',
              'posts_per_page' => -1,
              'posts_per_page' => 5,
    );
    
        $query = new WP_Query($args);

          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();

              $product_gallery = carbon_get_post_meta(get_the_ID(), 'product-gallery');
              $product_region = carbon_get_post_meta(get_the_ID(), 'product-region');
              $product_city = carbon_get_post_meta(get_the_ID(), 'product-city');
              $product_address = carbon_get_post_meta(get_the_ID(), 'product-address');
              $product_description = carbon_get_post_meta(get_the_ID(), 'product-description');
              $product_price =  carbon_get_post_meta(get_the_ID(), 'product-price');
              $product_area = carbon_get_post_meta(get_the_ID(), 'product-area');
              $product_filter_compare = carbon_get_post_meta(get_the_ID(), 'product_compare_content');
              $product_filter_more = carbon_get_post_meta(get_the_ID(), 'product_more_content');

              if ($product_area > 0) {
                $product_price_meter = number_format(round(floatval($product_price) / floatval($product_area), 2), 0, '.', ' ');
              }
              $product_price_format = number_format(round(floatval(carbon_get_post_meta(get_the_ID(), 'product-price'))), 0, '.', ' ');


              echo '  <li class="promo-slider__slide swiper-slide slide" data-new-buildings '.(!empty($product_more_content) ? 'data-filter-more' : '').'  '.($product_filter_compare ? 'data-filter-compare' : '').'>';
              if (!empty($product_gallery[0])) {
                $image_url = wp_get_attachment_image_src($product_gallery[0], 'full');
                $post_permalink = get_permalink(get_the_ID());
                echo '<a href="' . esc_url($post_permalink) . '">
                            <div class="slide-image">
                              <img src="' . $image_url[0] . '" alt="" width="380" height="195">
                            </div>';
              }
              echo '
                            <div class="slide__info info">
                              <h3 class="info__title title--lg title--promo-slide">' . $product_city . '</h3>
                              <p class="info__description">' . $product_description . '</p>';

              if (!empty($product_price_meter)) {
                echo '<p class="info__price">от ' . $product_price_meter . ' ₽ за м²</p>';
              } else {
                echo '<p class="info__price">' . $product_price_format . ' ₽</p>';
              }
              echo  '<p class="info__location"><span>' . $product_address . ' ' . $product_city . '</span><span>, ' . $product_region . '</span></p>
                          </div>
                          </a>
                          </li>';
            }
          }
        
        ?>