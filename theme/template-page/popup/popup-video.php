<?php
require_once get_template_directory() . '/inc/lib/get_image_url.php';

$video_src = carbon_get_post_meta(get_the_ID(), 'crb_gk_video');
?>

<div class="popup" data-popup="popup-video" data-close-overlay>
    <div class="popup__wrapper" data-close-overlay>
        <div class="popup__content">
            <div class="popup__container">
                <div class="popup-gallery__header">
                </div>
                <button class="popup__close button-close button--close" type="button" aria-label="Закрыть"></button>
                <div class="popup__body">
                    <iframe width="100%" height="100%" class="mfp-iframe" src="<?php echo $video_src ?>" frameborder="0" allowfullscreen=""></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>