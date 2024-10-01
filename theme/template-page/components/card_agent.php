<?php ?>
<div class="single-gk-card__order">
    <article class="agent-order" data-agent-order>
        <div data-loader class="loader">
            <img src=" <?php bloginfo('template_url'); ?>/assets/images/loading.gif" />
        </div>

        <div class="" data-container-card-agent-info></div>

        <div class="button-wrapper">
            <div class="agent-order__button">
                <a class="button button--phone-order" href="tel:+79104898888"><span> +7 910 489-88-...</span></a>
                <button class="button--favorites-mobile" type="button" data-favorite-cookies="'64" data-button-favorite-mobile data-delete-favorite="1"><span></span></button>
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