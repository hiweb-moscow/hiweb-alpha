<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-29
	 * Time: 09:42
	 */

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class postlink extends input{

		static $default_name = 'postlink';
		static $input_title = 'Сылка на страницу';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' )->VALUE( 'Форма отправлена со страницы' )->get_parent_field() )->label( 'Ссылка на страницу' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' )->VALUE( 'postlink' )->get_parent_field() )->label( 'Имя поля на латинице' )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'show' )->label_checkbox( 'Показывать в форме название страницы/записи/товара' ) );
		}

		public function the_prefix(){
			?>
			<div class="input-wrap input-wrap-<?= self::get_name() ?>">
			<?php
			if(self::get_data( 'show' ) && self::get_data( 'label' ) != '' ){
				?>
				<label class="label"><?= self::get_data( 'label' ) ?> <?= $this->is_required() ? '<span class="required">*</span>' : '' ?></label>
				<?php
			} elseif( self::is_required_empty_label() ) {
				?>
				<div class="required-empty-label">
				<?php
			}
		}


		public function the(){
			$this->the_prefix();
			if( self::get_data( 'show' ) ){
				$wp_post = get_post( get_the_ID() );
				if( $wp_post instanceof \WP_Post ){

					?>
					<div class="post-thumbnail"><?= get_image( get_post_thumbnail_id( $wp_post ) )->html( [ 200, 200 ] ) ?></div>
					<div class="post-title"><?= $wp_post->post_title ?></div>
					<?php
				}
			}
			?><input tabindex="" type="hidden" name="<?= self::get_name() ?>" value="<?= get_the_ID() ?>"/>
			<?php
			$this->the_sufix();
		}


		/**
		 * @param $value
		 * @return string
		 */
		public function get_email_value( $value ){
			if( !is_numeric( $value ) || intval( $value ) < 1 ){
				return 'Страница не указана';
			}
			$wp_post = get_post( $value );
			if( $wp_post instanceof \WP_Post ){
				return '<a href="' . get_permalink( $wp_post ) . '" target="_blank" title="Открыть траницу в новом окне">' . $wp_post->post_title . '</a>';
			}
			return 'не удалось получить сылку на страницу';
		}


	}