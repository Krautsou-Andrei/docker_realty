<?php
/*
Template Name: Страница ипотека
*/

get_header();
?>
<main class="page">
  <div class="main-ipoteca">
    <?php
    if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<div class="main-ipoteca__breadcrumbs">
                        <section class="breadcrumbs">
                          <div class="breadcrumbs__container">
                           ', '
                           </div>
                        </section>
                      </div>');
    }
    ?>
    <div class="main-ipoteca__ipoteca-calc">
      <section class="ipoteca">
        <div class="ipoteca__container">
          <section class="ipoteca__calc">
            <div class="ipoteca-calc">
              <div class="ipoteca-calc__title">
                <?php $referer = wp_get_referer() ?>
                <a class="button-back" href="<?php echo esc_url($referer) ?>" aria-label="Назад"></a>
                <?php
                $crb_ipoteca_title = carbon_get_post_meta(get_the_ID(), 'crb_ipoteca_title');
                ?>
                <h1 class="title--xl title--catalog title--ipoteca-page"><?php echo $crb_ipoteca_title ?></h1>
              </div>
              <div class="ipoteca-calc-wrapper">
                <div class="ipoteca-calc__calc">
                  <form class="calc" id="form" method="post">
                    <div class="calc__top">
                      <label class="ipoteca-label" for="">
                        <span>Стоимость недвижимости</span>
                        <input type="number" name="sum" placeholder="Стоимость">
                        <span>₽</span>
                      </label>
                      <label class="ipoteca-label" for="">
                        <span>Первоначальный взнос</span>
                        <input type="number" name="perv" placeholder="Взнос">
                        <span>₽</span>
                      </label>
                    </div>
                    <div class="calc__footer">
                      <div class="calc-footer-row">
                        <label class="ipoteca-label" for="">
                          <span>Процентная ставка</span>
                          <input type="number" name="proc" placeholder="0">
                          <span>%</span>
                        </label>
                        <label class="ipoteca-label" for="">
                          <span>Срок кредита</span>
                          <input type="number" name="srok" placeholder="0">
                          <span>Лет</span>
                        </label>
                      </div>
                    </div>
                  </form>
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                <script>
                  $('input').change(function() {

                    const msg = $('#form').serialize();
                    const name = $(this).attr('name');

                    const formatter = new Intl.NumberFormat('ru-RU', {
                      notation: 'standard',
                      compactDisplay: 'short'
                    });

                    if (name === 'sum') {

                      $('#sum-credit').html(`${formatter.format(this.value) } ₽`);
                    }

                    if (name === 'perv') {
                      $('#sum-prev').html(`${formatter.format(this.value) } ₽`);
                    }

                    if (name === 'proc') {
                      $('#stavka').html(`${formatter.format(this.value) } %`);
                    }

                    if (name === 'srok') {
                      $('#credit-srok').html(`${formatter.format(this.value) } лет`);
                    }              

                    $.ajax({
                      url: '/wp-content/themes/realty/inc/ajax-ipoteca.php',
                      method: 'POST',
                      data: msg,
                      success: function(data) {                       
                        $('#result').html(data);
                      }
                    });

                  });
                </script>
                <div class="ipoteca-calc__order">
                  <div class="ipoteca-order">
                    <div class="ipoteca-calc-title-wrapper">
                      <p class="ipoteca-order__title">Ежемесячный платеж</p>
                      <h2 class="ipoteca-order__price title--xl title--ipoteca-order" id="result">0 ₽</h2>
                    </div>
                    <p class="ipoteca-order-stavka"><span>Ставка</span><span></span><span id='stavka'>0 %</span></p>
                    <p class="ipoteca-order-sum-credit"><span>Сумма кредита</span><span></span><span id='sum-credit'>0 ₽</span></p>
                    <p class="ipoteca-order-tax"><span>Первоначальный взнос</span><span></span><span id='sum-prev'>0 ₽</span></p>
                    <p class="ipoteca-order-date"><span>Срок кредита</span><span></span><span id='credit-srok'>0 лет</span></p>

                    <div class="ipoteca-order__button">
                      <button class="button" data-type="popup-form-ipoteca"><span data-type="popup-form-ipoteca">Отправить заявку</span></button>
                    </div>
                    <p class="ipoteca-order__info">
                      Расчет параметров кредита является
                      предварительным
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </section>

    </div>
    <div class="main-ipoteca__easy">
      <section class="ipoteca-easy">
        <div class="ipoteca-easy__container">
          <?php
          $crb_ipoteca_easy_title = carbon_get_post_meta(get_the_ID(), 'crb_ipoteca_easy_title');

          ?>
          <h2 class="ipoteca-easy__title title--xl"><?php echo $crb_ipoteca_easy_title ?></h2>
          <div class="ipoteca-easy__slider ipoteca-easy-slider swiper">
            <ul class="ipoteca-easy-slider__list swiper-wrapper">
              <?php
              $crb_ipoteca_easy_field = carbon_get_post_meta(get_the_ID(), 'crb_ipoteca_easy_field');

              if (!empty($crb_ipoteca_easy_field)) {

                foreach ($crb_ipoteca_easy_field as $card) {
                  $crb_ipoteca_easy_image = $card['crb_ipoteca_easy_image'];
                  $crb_ipoteca_easy_card_title = $card['crb_ipoteca_easy_card_title'];
                  $crb_ipoteca_easy_description = $card['crb_ipoteca_easy_description'];

                  echo '<li class="swiper-slide">';
                  if (!empty($crb_ipoteca_easy_image)) {
                    $image_url = wp_get_attachment_image_src($crb_ipoteca_easy_image, 'full');
                    echo '<img src="' . $image_url[0] . '" alt="">';
                  }
                  echo ' <h3 class="title--lg">' . $crb_ipoteca_easy_card_title . '</h3>
                         <p>' . $crb_ipoteca_easy_description . '</p>
                        </li>';
                }
              }
              ?>
            </ul>
          </div>
        </div>
      </section>

    </div>
    <div class="main-ipoteca__more-questions">
      <section class="more-questions">
        <div class="more-questions__container">
          <?php
          $crb_ipoteca_questions_title = carbon_get_post_meta(get_the_ID(), 'crb_ipoteca_questions_title');
          ?>
          <h2 class="more-questions__title title--xl"><?php echo $crb_ipoteca_questions_title ?></h2>
          <div class="more-questions__accordion">
            <div class="accordion">

              <?php
              $crb_ipoteca_questions_field = carbon_get_post_meta(get_the_ID(), 'crb_ipoteca_questions_field');

              if (!empty($crb_ipoteca_questions_field)) {

                foreach ($crb_ipoteca_questions_field as $card) {
                  $crb_ipoteca_questions_card_title = $card['crb_ipoteca_questions_card_title'];
                  $crb_ipoteca_questions_answer = $card['crb_ipoteca_questions_answer'];

                  echo '<div class="accordion__item">
                          <div class="accordion__header"><span>' . $crb_ipoteca_questions_card_title . '</span></div>
                          <div class="accordion__content">
                            <p>' . nl2br(esc_html($crb_ipoteca_questions_answer)) . '</p>
                          </div>
                        </div>';
                }
              }
              ?>
            </div>
          </div>
        </div>
      </section>

    </div>
  </div>
  <section class="main-ipoteca__questions">
    <section class="questions">
      <div class="questions__container">
        <div class="questions-wrapper">
          <div class="questions__info">
            <?php
            $crb_questions_title = carbon_get_post_meta(5, 'crb_questions_title');
            $crb_questions_subtitle = carbon_get_post_meta(5, 'crb_questions_subtitle');
            $crb_questions_image_id = carbon_get_post_meta(5, 'crb_questions_image');

            if (!empty($crb_questions_image_id)) {
              $crb_questions_image_url = wp_get_attachment_image_src($crb_questions_image_id, 'full');
            }
            ?>
            <h2 class="questions__title title--xl title--questions"><?php echo $crb_questions_title ?></h2>
            <p class="questions__subtitle title--lg subtitle--questions"><?php echo $crb_questions_subtitle ?></p>
            <form action="" class="questions__form questions-form">

              <?php
              echo do_shortcode('[contact-form-7 id="010e767" title="Контактная форма"]')
              ?>

            </form>
            <p class="questions__oferts">Нажимая на кнопку «Отправить», вы соглашаетесь на обработку <strong data-type="personal_data">персональных данных</strong></p>
          </div>
          <div class="questions__image">
            <?php
            if (!empty($crb_questions_image_url[0])) {
              echo '<img src="' . $crb_questions_image_url[0] . '" alt="" width="537" height="461">';
            }
            ?>

          </div>
        </div>
      </div>
    </section>
  </section>
</main>
<?php
get_footer();
?>
