<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:06
	 */

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class text extends input{

		static $default_name = 'text';
		static $input_title = 'Текстовое поле';

		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'Текстовое поле' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'placeholder' )->placeholder( 'Плейсхолдер в поле' ) )->label( 'Плейсхолдер в поле' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) );
		}




	}