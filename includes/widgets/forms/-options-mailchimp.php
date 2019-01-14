<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 17/11/2018
	 * Time: 18:52
	 */


	///Options MailChimp
	use hiweb_theme\widgets\forms;

	/**
	 * @var forms self::class
	 */


	self::$options_object_mailchimp = add_admin_menu_page( self::$options_name_mailchimp, '<i class="fab fa-mailchimp"></i> MailChimp', 'edit.php?post_type=' . self::$post_type_name );
	self::$options_object_mailchimp->page_title( '<i class="fab fa-mailchimp"></i> Интеграция с сервисом MailChimp' );
	add_field_text( 'api-key' )->label( 'Ключ API приложения' )->description( '<p><a href="https://us19.admin.mailchimp.com/account/api/" target="_blank"><i class="fas fa-key"></i> Страница получения ключа</a></p><a href="https://mailchimp.com/help/about-api-keys/" target="_blank"><i class="fas fa-question-circle"></i> Инструкция для получения ключа</a>' )->LOCATION()->ADMIN_MENUS( self::$options_name_mailchimp );
	///
	if( forms::mailchimp()->is_api_key_exists() ){
		if(is_admin()){
			if( forms::mailchimp()->is_connected() ){
				add_field_checkbox( 'enable-default-list-id' )->label_checkbox( 'Если для формы список не указан или не существует (его удалили), то использовать указанный список получателей рассылки ниже:' )->VALUE( 'on' )->get_parent_field()->LOCATION()->ADMIN_MENUS( self::$options_name_mailchimp );
				add_field_select( 'list-id' )->options( array_merge( [ '' => '--список для подписчиков не выбран--' ], forms::mailchimp()->get_list_ids_names() ) )->label( 'Выберите список адресов, в который по умолчанию добавлять адреса из форм' )->description( '<p><a href="https://admin.mailchimp.com/lists/" target="_blank"><i class="fas fa-clipboard-list"></i> Создание списков адресов для рассылки</a></p>' )->LOCATION()->ADMIN_MENUS( self::$options_name_mailchimp );
			} else {
				add_field_separator( 'Ключ введен не верно! Подключение по API не удалось.' )->LOCATION()->ADMIN_MENUS( self::$options_name_mailchimp );
			}
		}
	} else {
		add_field_separator( 'Вы не указали API ключ. <a href="https://admin.mailchimp.com/account/api/" target="_blank">Получите ключ</a> и введите его в строке выше.' )->LOCATION()->ADMIN_MENUS( self::$options_name_mailchimp );
	}


	///Forms Meta Fields
	if( forms::mailchimp()->is_api_key_exists() ){
		$mail_chimp_options_select = ['' => '--выберите список для добавления адресов--'];
		if(is_admin()) {
			$selected_id = get_field('list-id',self::$options_name_mailchimp);
			if( $selected_id != '' && array_key_exists($selected_id, forms::mailchimp()->get_list_ids_names())) {
				$mail_chimp_options_select = ['' => 'Выбранный список в настройках: '.forms::mailchimp()->get_list_ids_names()[$selected_id]];
			}
			add_field_select( 'mailchimp-list-id' )->options( array_merge($mail_chimp_options_select,forms::mailchimp()->get_list_ids_names()) )->label( 'Выберите список адресов, в который по умолчанию добавлять адреса из форм' )->description('<p>Если Вы еще не создали списки, перейдите на страницу <a href="https://admin.mailchimp.com/lists/" target="_blank"><i class="fas fa-clipboard-list"></i> Создание списков для рассылки</a></p> В данный список будут добавляться адреса в момент отправки формы, который был указан заполнителем.')->LOCATION()->POST_TYPES(forms::$post_type_name)->META_BOX()->title('Установки MailChimp для данной формы')->context()->side();
		}

	} else {
		add_field_separator('<span style="display: block; text-align: center;"><i class="fab fa-mailchimp fa-5x"></i><br>Ошибка подключения к MailChimp.</span><p style="text-align: center"><a href="'.get_admin_url(null, 'edit.php?post_type='.forms::$options_name.'&page='.forms::$options_name_mailchimp).'" class="button button-primary button-large">Перейти к настройкам</a></p>')->LOCATION()->POST_TYPES(forms::$post_type_name)->META_BOX()->title('Установки MailChimp для данной формы')->context()->side();
	}