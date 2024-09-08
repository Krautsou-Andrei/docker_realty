<?php
/*
Template Name: Политика безопасности
*/
?>

<div class="popup" data-popup="personal_data" data-close-overlay>
  <div class="popup__wrapper" data-close-overlay>
    <div class="popup__content">
      <button class="popup__close button-close button--close" type="button"></button>
      <div class="popup__body">
				<?php
				$post_id = get_post(3);
				setup_postdata($post_id)
				?>
        <h2 class="title--popup"><?php echo get_the_title(3)?></h2>
        <div class="text-wrapper">
          <p><?php echo get_the_content()?></p>
        </div>
      </div>
    </div>
  </div>
</div>
