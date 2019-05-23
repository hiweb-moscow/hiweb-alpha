<?php

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class info_text extends input{

		static $default_name = 'info_text';
		static $input_title = 'Информационно-текстовая вставка';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_textarea( 'text' ) )->label( 'Информационно-текстовая вставка' )->compact( 1 );
		}


		public function is_required(){
			return false;
		}


		public function the(){
			$this->the_prefix();
			echo self::get_data( 'text' );
			$this->the_sufix();
		}


		public function get_email_html( $value ){
			return '';
		}


	}