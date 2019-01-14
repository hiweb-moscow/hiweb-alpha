<?php

	namespace {


		use hiweb\fields\types\info\field;


		if( !function_exists( 'add_field_info' ) ){
			/**
			 * @param $id
			 * @return field
			 */
			function add_field_info( $id ){
				$new_field = new field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\info {


		use function hiweb\css;


		class field extends \hiweb\fields\field{

			public function __construct( $id = null ){
				parent::__construct( $id );
				$this->INPUT()->attributes['type'] = 'text';
				$this->INPUT()->attributes['class'] = 'hiweb-field-info';
			}


			/**
			 * @param string $placeholder
			 * @return $this
			 */
			public function placeholder( $placeholder ){
				$this->INPUT()->attributes['placeholder'] = $placeholder;
				return $this;
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public function html(){
				css( HIWEB_URL_CSS . '/field-text.css' );
				$this->attributes['value'] = $this->VALUE()->get_sanitized();
				$this->attributes['disabled'] = '';
				return '<div class="ui fluid input"><input ' . $this->sanitize_attributes( $this->attributes ) . '></div>';
			}

		}
	}