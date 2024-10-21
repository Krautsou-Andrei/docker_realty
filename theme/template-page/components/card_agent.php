<?php
$video_src = carbon_get_post_meta(get_the_ID(), 'crb_gk_video');
$product_agent_phone = carbon_get_theme_option('crb_phone_link');
$agent_phone = preg_replace('/[^0-9]/', '', $product_agent_phone);
$format_phone_agent = '+' . substr($agent_phone, 0, 1) . ' ' . substr($agent_phone, 1, 3) . ' ' . substr($agent_phone, 4, 3) . ' - ' . substr($agent_phone, 7, 2) . ' - ...';
?>
<div class="single-gk-card__order">
    <article class="agent-order" data-agent-order> 
        <div class="" data-container-card-agent-info></div>
        <div class="button-wrapper">
            <?php if (!empty($video_src)) { ?>
                <div class="agent-order__favorites">
                    <button class="button button--video" type="button" data-type="popup-video"><span data-type="popup-video">Просмотреть видео ролик</span></button>
                </div>
            <?php } ?>
            <div class="agent-order__button">
                <a class="button button--phone-order" href="tel:<?php echo $product_agent_phone ?>"><span><?php echo $format_phone_agent ?></span></a>
                <?php if (!empty($video_src)) { ?>
                    <button class="button--video-mobile" type="button" data-type="popup-video"><span data-type="popup-video"></span></button>
                <?php } ?>
            </div>
            <div class="agent-order__callback" onclick="setLink(event, '<?php echo esc_js(get_permalink(get_the_ID())) ?>')">
                <button class="button button--callback" type="button" data-type="popup-form-callback"><span data-type="popup-form-callback">Перезвоните мне</span></button>
            </div>
        </div>
    </article>
</div>
<script>
    function setLink(event, link) {

        if (event.target.innerText === "Перезвоните мне") {
            const formSeven = document.querySelector('[data-form-callback]');

            if (formSeven) {
                const input = formSeven.querySelector(`input[name=your-link]`);
                input.value = `${link}`;
            }
        }
    }

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