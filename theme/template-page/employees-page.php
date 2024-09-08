<?php
/*
Template Name: Страница сотрудники
*/

get_header();
?>
<main class="page">
    <div class="main-employees">
        <?php
        if (function_exists('yoast_breadcrumb')) {
          yoast_breadcrumb('<div class="main-employees__breadcrumbs">
                            <section class="breadcrumbs">
                              <div class="breadcrumbs__container">
                               ', '
                               </div>
                            </section>
                          </div>');
        }
        ?>
    </div>
    <sections class="employees">
        <div class="employees__container">
            <?php $crb_employees_title = carbon_get_post_meta(5, 'crb_employees_title'); ?>
            <div class="employees__title title--xl"><?php echo $crb_employees_title ?></div>
            <ul class="employees__list employees-list">
                <?php

            $crb_employees_field =  array_slice(carbon_get_post_meta(5, 'crb_employees_field'),0, 6);
           

            foreach ($crb_employees_field as $index => $employee) {
              $image_id = $employee['crb_employee_image'];
              $image_avic_one_id = $employee['crb_employee_image_avic_one'];
              $image_avic_two_id = $employee['crb_employee_image_avic_two'];
              $text_avic_one = $employee['crb_employee_image_avic_one_text'];
              $text_avic_two = $employee['crb_employee_image_avic_two_text'];
              $name = $employee['crb_employee_name'];
              $last_name = $employee['crb_employee_last_name'];
              $type = $employee['crb_employee_type'];
              $phone = $employee['crb_employee_phone'];
              $phone_link = $employee['crb_employee_phone_link'];
              $whatsaap_phone = str_replace("+", "", $phone_link);

              $image_url =  wp_get_attachment_image_src($image_id, 'full');
              $image_avic_one_url =  wp_get_attachment_image_src($image_avic_one_id, 'full');
              $image_avic_two_url =  wp_get_attachment_image_src($image_avic_two_id, 'full');

              if (!empty($phone) & !empty($phone_link) & !empty($name)) {
                echo '
                      <li>
                        <div class="employee">
                          <div class="employee__image-wrapper">
                            <div class="img-wrapper">';

                if (!empty($image_url[0])) {
                  echo ' <img src="' . $image_url[0] . '" alt="">';
                }

                echo ' </div>
                            <div class="employee__avics">';

                if (!empty($text_avic_one)) {

                  echo '<div class="avic">';

                  if (!empty($image_avic_one_url[0])) {
                    echo '<img src="' . $image_avic_one_url[0] . '" alt="">';
                  }

                  echo ' <span>' . $text_avic_one . '</span>
                              </div>';
                }
                if (!empty($text_avic_two)) {
                  echo '<div class="avic">';
                  if (!empty($image_avic_two_url[0])) {
                    echo '<img src="' . $image_avic_two_url[0] . '" alt="">';
                  }

                  echo '<span>' . $text_avic_two . '</span>
                                  </div>';
                }
                echo ' </div>
                          </div>
                          <p class="employee__type">' . $type . '</p>
                          <h3 class="employee__title title--lg title--agent">' . $name . ' ' . $last_name . '</h3>
                          <div class="employee__contacts-phone">
                            <a href="tel:' . $phone_link . '">' . $phone . '</a>
                            <div class="wrapper-social">
                              <a href="https://wa.me/ ' . $whatsaap_phone . '" target="_blank"><img src="' .  get_template_directory_uri() . '/assets/images/whatsapp.svg" alt="" width="16" height="16"></a>
							  <a href="https://t.me/' . $phone_link . '" target="_blank"><img src="' .  get_template_directory_uri() . '/assets/images/telegram.svg" alt="" width="16" height="16"></a>
                                <div class="employee__button-mobile">
                                  <button type="button" class="button--documents" data-type="popup-employee-documents" data-employee="'.$index.'" data-button-documents><span data-type="popup-employee-documents" data-employee="'.$index.'">Документы</span></button>
                                </div>
                            </div>
                          </div>
                          <div class="employee__button">
                            <button class="button--documents" data-type="popup-employee-documents" data-employee="'.$index.'" data-button-documents><span data-type="popup-employee-documents" data-employee="'.$index.'">Документы</span></button>
                          </div>
                        </div>
                      </li>';
              }
            }

            ?>
            </ul>
        </div>
    </sections>
  
    <div class="main-employees__questions">
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
    </div>
</main>
<?php
get_footer();
?>
