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
        <div class="wp-block-contact-form-7-contact-form-selector">
          <div class="wpcf7 js" id="wpcf7-f525-p41-o1" lang="ru-RU" dir="ltr">
            <div class="screen-reader-response">
              <p role="status" aria-live="polite" aria-atomic="true">При отправке сообщения произошла ошибка. Пожалуйста, попробуйте ещё раз позже.</p>
              <ul></ul>
            </div>

            <form action="/#wpcf7-f525-p41-o1" id="form-questions" method="post" class="questions__form questions-form" aria-label="Контактная форма" novalidate="novalidate" data-status="failed">

              <?php
              echo do_shortcode('[contact-form-7 id="010e767" title="Контактная форма"]')
              ?>

            </form>
          </div>
        </div>
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
