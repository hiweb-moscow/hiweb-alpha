<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 23:18
	 */

	namespace hiweb_theme;


	use hiweb\context;
	use hiweb\files;


	class includes{

		/**
		 * @param        $filePathOrUrl
		 * @param array  $deeps
		 * @param string $media
		 * @return bool
		 */
		static function css( $filePathOrUrl, $deeps = [], $media = 'all' ){
			if( !\hiweb\context::is_frontend_page() ) return null;
			//
			$search_paths = [
				$filePathOrUrl,
				get_stylesheet_directory() . '/' . $filePathOrUrl,
				get_stylesheet_directory() . '/' . $filePathOrUrl . '.min.css',
				get_stylesheet_directory() . '/' . $filePathOrUrl . '.css',
				get_template_directory() . '/' . $filePathOrUrl,
				get_template_directory() . '/' . $filePathOrUrl . '.min.css',
				get_template_directory() . '/' . $filePathOrUrl . '.css',
				HIWEB_THEME_VENDORS_DIR . '/' . $filePathOrUrl,
				HIWEB_THEME_VENDORS_DIR . '/' . $filePathOrUrl . '.min.css',
				HIWEB_THEME_VENDORS_DIR . '/' . $filePathOrUrl . '.css',
				HIWEB_DIR_VENDORS . '/' . $filePathOrUrl,
				HIWEB_DIR_VENDORS . '/' . $filePathOrUrl . '.min.css',
				HIWEB_DIR_VENDORS . '/' . $filePathOrUrl . '.css'
			];
			foreach( $search_paths as $path ){
				if( file_exists( $path ) && is_file( $path ) ){
					return include_css( $path, $deeps, $media );
				}
			}
			console_error( 'Не удалось подключить CSS файл [' . $filePathOrUrl . ']' );
			return false;
		}


		/**
		 * @param       $jsPathOrURL
		 * @param array $deeps
		 * @param bool  $inFooter
		 * @return bool
		 */
		static function js( $jsPathOrURL, $deeps = [], $inFooter = true ){
			if( !\hiweb\context::is_frontend_page() ) return null;
			//
			$search_paths = [
				$jsPathOrURL,
				get_stylesheet_directory() . '/' . $jsPathOrURL,
				get_stylesheet_directory() . '/' . $jsPathOrURL . '.min.js',
				get_stylesheet_directory() . '/' . $jsPathOrURL . '.js',
				get_template_directory() . '/' . $jsPathOrURL,
				get_template_directory() . '/' . $jsPathOrURL . '.min.js',
				get_template_directory() . '/' . $jsPathOrURL . '.js',
				HIWEB_THEME_VENDORS_DIR . '/' . $jsPathOrURL,
				HIWEB_THEME_VENDORS_DIR . '/' . $jsPathOrURL . '.min.js',
				HIWEB_THEME_VENDORS_DIR . '/' . $jsPathOrURL . '.js',
				HIWEB_DIR_VENDORS . '/' . $jsPathOrURL,
				HIWEB_DIR_VENDORS . '/' . $jsPathOrURL . '.min.js',
				HIWEB_DIR_VENDORS . '/' . $jsPathOrURL . '.js'
			];
			foreach( $search_paths as $path ){
				if( file_exists( $path ) && is_file( $path ) ){
					return include_js( $path, $deeps, $inFooter );
				}
			}
			console_error( 'Не удалось подключить JS файл [' . $jsPathOrURL . ']' );
			return false;
		}


		/**
		 * @param bool $include_migrate_js
		 * @return bool
		 */
		static function jquery( $include_migrate_js = false ){
			$R = self::js( HIWEB_THEME_VENDORS_DIR . '/jquery3/jquery-3.3.1.min.js' );
			if( $include_migrate_js ){
				self::js( HIWEB_THEME_VENDORS_DIR . '/jquery3/jquery-migrate-1.4.1.min.js' );
			}
			return $R;
		}


		static function bootstrap( $include_js = false, $include_reboot_css = false ){
			self::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap-grid.min.css' );
			self::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap.min.css' );
			if( $include_reboot_css ){
				self::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap-reboot.min.css' );
			}
			if( $include_js ){
				self::js( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/js/bootstrap.min.js', [ self::jquery() ] );
			}
		}


		static function hamburgers(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/hamburgers/hamburders.min.js', [ 'jquery' ] );
			self::css( HIWEB_THEME_VENDORS_DIR . '/hamburgers/hamburgers.min.css' );
		}


		static function fancybox(){
			self::css( HIWEB_THEME_VENDORS_DIR . '/fancybox3/jquery.fancybox.min.css' );
			self::js( HIWEB_THEME_VENDORS_DIR . '/fancybox3/jquery.fancybox.min.js', [ self::jquery() ] );
		}


		static function mmenu(){
			self::css( HIWEB_THEME_VENDORS_DIR . '/jquery.mmenu/jquery.mmenu.all.css' );
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.mmenu/jquery.mmenu.all.min.js', [ self::jquery() ] );
		}


		static function jquery_touchswipe(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.touchSwipe/jquery.touchSwipe.min.js', [ self::jquery() ] );
		}


		static function fontawesome( $use_js = true ){
			if( $use_js ){
				self::js( HIWEB_DIR_VENDORS . '/font-awesome-5/js/all.min.js' );
			} else {
				self::css( HIWEB_DIR_VENDORS . '/font-awesome-5/css/all.min.css' );
			}
		}

	}