<?php
$video_src = carbon_get_post_meta(get_the_ID(), 'crb_gk_video');
?>
<div class="single-gk-card__order">
    <article class="agent-order" data-agent-order>
        <div data-loader class="loader agent-loader">
            <img src=" <?php bloginfo('template_url'); ?>/assets/images/loading.gif" />
        </div>

        <div class="" data-container-card-agent-info></div>

        <div class="button-wrapper">
            <?php if (!empty($video_src)) { ?>
                <div class="agent-order__favorites">
                    <button class="button button--video" type="button" data-type="popup-video"><span data-type="popup-video">Просмотреть видео ролик</span></button>
                </div>
            <?php } ?>
            <div class="agent-order__button">
                <a class="button button--phone-order" href="tel:+79104898888"><span> +7 910 489-88-...</span></a>
                <?php if (!empty($video_src)) { ?>
                    <button class="button--video-mobile" type="button" data-type="popup-video"><span data-type="popup-video"></span></button>
                <?php } ?>
            </div>
            <div class="agent-order__callback">
                <button class="button button--callback" type="button" data-type="popup-form-callback"><span data-type="popup-form-callback">Перезвоните мне</span></button>
            </div>
        </div>
    </article>
</div>
<script>
    function redirectToURL(url) {
        window.location.href = url;
    }

    const buttonsOrder = document.querySelectorAll('.button--phone-order')

    buttonsOrder.forEach((button) => {
        button.addEventListener('click', showFullNumber)
    })

    function showFullNumber(event) {
        event.preventDefault();
        event.stopPropagation();

        const phoneLink = event.currentTarget;
        const phoneSpan = phoneLink.querySelector('span');
        const numberText = phoneSpan.textContent;
        const phoneNumber = phoneLink.href;
        const formattedNumber = phoneNumber.replace(/^tel:\+(\d)(\d{3})(\d{3})(\d{2})(\d{2})$/, '+$1 $2 $3-$4-$5');

        if (numberText === formattedNumber) {
            window.location.href = phoneLink.href
        } else {
            phoneSpan.textContent = formattedNumber;
        }

    }
</script>