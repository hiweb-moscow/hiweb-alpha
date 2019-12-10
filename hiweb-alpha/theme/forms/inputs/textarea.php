<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:15
	 */

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class textarea extends input{

		static $default_name = 'comment';
		static $input_title = 'Текстовой блок (несколько строк)';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'Текстовой блок' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'rows' )->placeholder( 'Количество строк' )->VALUE( 4 )->get_parent_field() )->label( 'Количество строк' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'placeholder' )->placeholder( 'Плейсхолдер в поле' ) )->label( 'Плейсхолдер в поле' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'require-message' )->label( 'Сообщение под полем при неверно заполненом поле' )->VALUE('Вы не заполнили поле')->get_parent_field() )->compact(1);
		}


		public function the(){
			$this->the_prefix();
			?>
			<div class="input">
				<textarea name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?> <?= self::get_tag_pair( 'rows' ) ?> <?=self::is_required() ? 'data-required' : ''?>></textarea>
			</div>
			<?php
			$this->the_sufix();
		}


	}