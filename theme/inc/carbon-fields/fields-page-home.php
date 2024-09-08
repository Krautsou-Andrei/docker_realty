<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined("ABSPATH")) {
  exit;
}

add_action('carbon_fields_register_fields', 'page_home_fields');
function page_home_fields()
{
  Container::make('post_meta', 'Настройки страницы')
    ->where('post_type', '=', 'page')
    ->where('post_template', '=', 'template-page/home.php')
    ->add_tab('Главный слайдер', array(
      Field::make('text', 'crb_home_title_novoros', 'Заголовок Новороссийск'),
      Field::make('text', 'crb_home_title_krasnodar', 'Заголовок Краснодар'),
    //   Field::make('media_gallery', 'crb_about-gallery', 'Галерея'),
      Field::make('image', 'crb_image_about_novoros', 'Фон Новороссийска')->set_width(50),
      Field::make('image', 'crb_image_about_krasnodar', 'Фон Краснодара')->set_width(50),
      Field::make('image', 'crb_tag_about_one', 'Первый лейбл на слайдере')->set_width(50),
      Field::make('image', 'crb_tag_about_two', 'Второй лейбл на слайдере')->set_width(50),
      Field::make('text', 'crb_tag_about_one_link', 'Ссылка для первого лейбла на слайдере')->set_width(50)->set_help_text('Например https://www.instagram.com/'),
      Field::make('text', 'crb_tag_about_two_link', 'Ссылка для второго лейбла на слайдере')->set_width(50)->set_help_text('Например https://www.instagram.com/'),

    ))
    ->add_tab('Преимущества', [
      Field::make('complex', 'crb_benefits_field', 'Карточки')
        ->add_fields([
          Field::make('text', 'crb_benefits_field_title', 'Заголовок'),
          Field::make('text', 'crb_benefits_field_description', 'Описание'),
        ])
        ->set_max(4)
    ])
    ->add_tab('Сервисы', [
      Field::make('text', 'crb_services_title', 'Заголовок'),
      Field::make('complex', 'crb_services_field', 'Карточки')
        ->add_fields([
          Field::make('image', 'crb_services_field_image', 'Первый лейбл на слайдере')->set_width(20),
          Field::make('text', 'crb_services_field_title', 'Заголовок')->set_width(40),
          Field::make('text', 'crb_services_field_description', 'Описание')->set_width(40),
        ])
        ->set_max(3)
    ])
    ->add_tab('Промо', [
      Field::make('text', 'crb_promo_title', 'Заголовок'),
    ])
    ->add_tab('Выбирают нас', [
      Field::make('text', 'crb_choose_title', 'Заголовок'),
      Field::make('complex', 'crb_choose_field', 'Карточки')
        ->add_fields([
          Field::make('image', 'crb_choose_field_image', 'Первый лейбл на слайдере')->set_width(20),
          Field::make('text', 'crb_choose_field_title', 'Заголовок')->set_width(40),
          Field::make('text', 'crb_choose_field_description', 'Описание')->set_width(40),
        ])
        ->set_max(4)
    ])
    ->add_tab('Агенство', [
      Field::make('text', 'crb_agency_title', 'Заголовок'),
      Field::make('complex', 'crb_agency_field', 'Карточки')
        ->add_fields([
          Field::make('text', 'crb_agency_field_title', 'Заголовок')->set_width(40),
          Field::make('textarea', 'crb_agency_field_description', 'Описание')->set_width(60),
          Field::make('image', 'crb_agency_field_image_big', 'Большая картинка')->set_width(40),
          Field::make('image', 'crb_agency_field_image_small', 'Маленькая картинка')->set_width(40),
          Field::make('image', 'crb_agency_field_image_logo', 'Логотип на картинке')->set_width(20),
        ])
        ->set_max(2)
    ])
    ->add_tab('Партнеры', [
      Field::make('text', 'crb_partners_title', 'Заголовок'),
      Field::make('media_gallery', 'crb_partners-gallery', 'Галерея'),
    ])
    ->add_tab('Сотрудники', [
      Field::make('text', 'crb_employees_title', 'Заголовок'),
      Field::make('complex', 'crb_employees_field', 'Сотрудник')
        ->add_fields([
          Field::make('text', 'crb_employee_type', 'Должность'),
          Field::make('text', 'crb_employee_name', 'Имя')->set_width(50)->set_required(true),
          Field::make('text', 'crb_employee_last_name', 'Фамилия')->set_width(50),
          Field::make('image', 'crb_employee_image', 'Фото'),
          Field::make('image', 'crb_employee_image_avic_one', 'Авик первый')->set_width(50),
          Field::make('image', 'crb_employee_image_avic_two', 'Авик второй')->set_width(50),
          Field::make('text', 'crb_employee_image_avic_one_text', 'Текст первого авик')->set_width(50),
          Field::make('text', 'crb_employee_image_avic_two_text', 'Текст второго авик')->set_width(50),
          Field::make('text', 'crb_employee_phone', 'Телефон для сайта')->set_width(50)->set_help_text('Нужно ввести номер телефона С скобками, пробелами и тире. Т.к. он будет выглядеть на сайте')->set_required(true),
          Field::make('text', 'crb_employee_phone_link', 'Телефон для ссылок')->set_width(50)->set_help_text('Нужно ввыести номер телефона БЕЗ скобок, пробелов и тире')->set_required(true),
          Field::make('complex', 'crb_documents', 'Документы')
            ->add_fields('documents_license', 'Лицензии', [
                Field::make('media_gallery', 'crb_documents_license_gallery', 'Список лицензий (Во время загрузки картинок обязательно заполни поле "Атрибут alt". Текст указанный в этом поле будет выведен как описание документа)'),])
            ->add_fields('documents_evidence', 'Свидетельства', [
                Field::make('media_gallery', 'crb_documents_evidence_gallery', 'Список свидетельств (Во время загрузки картинок обязательно заполни поле "Атрибут alt". Текст указанный в этом поле будет выведен как описание документа)'),])
            ->add_fields('documents_certificates', 'Сертификаты', [
                Field::make('media_gallery', 'crb_documents_certificates_gallery', 'Список сертификатов (Во время загрузки картинок обязательно заполни поле "Атрибут alt". Текст указанный в этом поле будет выведен как описание документа)'),])
            ->add_fields('documents_attest', 'Аттестаты', [
                Field::make('media_gallery', 'crb_documents_attest_gallery', 'Список аттестатов (Во время загрузки картинок обязательно заполни поле "Атрибут alt". Текст указанный в этом поле будет выведен как описание документа)'),])
        ])
        ->set_layout('tabbed-vertical')
        ->set_header_template('
									<% if (crb_employee_name) { %>
										<%- crb_employee_name %> <%- crb_employee_last_name ? crb_employee_last_name : "" %>, <%- crb_employee_type ? crb_employee_type : "" %>, <%- crb_employee_phone ? crb_employee_phone : "" %>
									<% } %>
								 ')
    ])->add_tab('Блок с формой', [
      Field::make('text', 'crb_questions_title', 'Заголовок'),
      Field::make('text', 'crb_questions_subtitle', 'Подзаголовок'),
      Field::make('image', 'crb_questions_image', 'Картинка'),
    ])
    ->add_tab('Карта и контакты', [
      Field::make('text', 'crb_contact_title', 'Заголовок'),
      Field::make('text', 'crb_contact_location', 'Край'),
      Field::make('text', 'crb_contact_org', 'Организация или ИП'),
      Field::make('text', 'crb_contact_location_width', 'Ширина')->set_help_text('Например 44.689555')->set_width(50),
      Field::make('text', 'crb_contact_location_longitude', 'Долгота')->set_help_text('Например 37.759012')->set_width(50),
    ]);
}
