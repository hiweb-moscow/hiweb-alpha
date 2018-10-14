<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 11:34
	 */


	class hiweb_theme{

		/** @var \hiweb_theme\widgets\nav_menu[] */
		private static $nav_menus = [];
		/** @var array \hiweb_theme\modules\mmenu[] */
		private static $mmenus = [];
		/** @var \hiweb_theme\widgets\gallery[] */
		private static $galleries = [];
		/** @var \hiweb_theme\header[] */
		private static $headers = [];


		/**
		 * @param string $nav_location
		 * @return \hiweb_theme\widgets\nav_menu
		 */
		static function nav_menu( $nav_location = 'header' ){
			if( !array_key_exists( $nav_location, self::$nav_menus ) ){
				self::$nav_menus[ $nav_location ] = new \hiweb_theme\widgets\nav_menu( $nav_location );
			}
			return self::$nav_menus[ $nav_location ];
		}


		/**
		 * @param string $nav_location
		 * @return \hiweb_theme\widgets\mmenu
		 */
		static function mmenus( $nav_location = 'mobile-menu' ){
			if( !array_key_exists( $nav_location, self::$mmenus ) ){
				$mmenu = new \hiweb_theme\widgets\mmenu( $nav_location );
				$mmenu->init();
				self::$mmenus[ $nav_location ] = $mmenu;
			}
			return self::$mmenus[ $nav_location ];
		}


		/**
		 * @param null $id
		 * @return \hiweb_theme\widgets\gallery
		 */
		static function gallery( $id = null ){
			if( is_null( $id ) ) $id = \hiweb\strings::rand();
			if( !array_key_exists( $id, self::$galleries ) ){
				self::$galleries[ $id ] = new \hiweb_theme\widgets\gallery( $id );
			}
			return self::$galleries[ $id ];
		}


		/**
		 * @param string $id
		 * @return \hiweb_theme\header
		 */
		static function header( $id = 'header-main' ){
			if( !array_key_exists( $id, self::$headers ) ){
				self::$headers[ $id ] = new \hiweb_theme\header( $id );
			}
			return self::$headers[ $id ];
		}


		static function init_scrolltop(){
			hiweb_theme\widgets\scrolltop::init();
		}

		static function init_forms(){
			hiweb_theme\widgets\forms::init();
		}

	}