<?php

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class listing extends input{

		static $default_name = 'info_text';
		static $input_title = 'Список значений и свойств';


		static function add_repeat_field( field $parent_repeat_field ){
			$repeat = add_field_repeat( 'items' );
			$repeat->add_col_field( add_field_text( 'key' ) )->label( 'Ключ списка' );
			$repeat->add_col_field( add_field_text( 'value' ) )->label( 'Значение списка' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, $repeat )->label( 'Список значений (свойств)' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'send_enable' )->label_checkbox( 'Не отправлять данное поле по почте' ) );
		}


		public function is_required(){
			return false;
		}


		public function the(){
			$this->the_prefix();
			$items = get_array( self::get_data( 'items', false ) );
			if( !$items->is_empty() ){
				?>
				<ul>
					<?php
						foreach( $items->get() as $data ){
							$item = get_array( $data );
							?>
							<li><span class="item-key"><?= $item->get_value( 'key' ) ?>:</span> <span class="item-value"><?= $item->get_value( 'value' ) ?></span></li>
							<?php
						}
					?>
				</ul>
				<?php
			}
			$this->the_sufix();
		}


		/**
		 * @return bool
		 */
		public function is_email_submit_enable(){
			return self::get_data( 'send_enable' ) != 'on';
		}


		public function get_email_html( $value ){
			ob_start();
			$this->the();
			return ob_get_clean();
		}


	}