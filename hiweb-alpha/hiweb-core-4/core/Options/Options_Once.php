<?php

	namespace hiweb\core\Options;


	use hiweb\core\Options\Options;


	/**
	 * Используется для суб-опции, которая имеет тольуко одно значение
	 * Class Options_Once
	 * @package hiweb\core\Options
	 */
	abstract class Options_Once extends Options{

		/**
		 * @param null $option_key
		 * @param null $default
		 * @return array|mixed|null
		 */
		protected function get( $option_key = null, $default = null ){
			$R = $this->options_ArrayObject()->get_value( '', $default );
			if( is_callable( $R ) && get_class($R) == 'Closure' ) $R = $R(func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			return $R;
		}


		/**
		 * @param      $value
		 * @param null $null
		 * @return Options|mixed
		 */
		protected function set( $null, $value ){
			parent::set( '', $value );
			return $this->getParent_OptionsObject();
		}


		/**
		 * Set or get once option value
		 * @param null|mixed $value
		 * @param null|mixed $default
		 * @param null       $null
		 * @return array|\hiweb\core\Options\Options|mixed|null
		 */
		public function _( $value = null, $default = null, $null = null ){
			return parent::_( '', $value, $default );
		}
		
		public function _get_optionsCollect(){
			return $this->_();
		}
		
		
	}