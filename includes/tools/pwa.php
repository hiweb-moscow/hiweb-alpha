<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05/12/2018
	 * Time: 23:35
	 */

	namespace hiweb_theme\tools;


	use hiweb\arrays;
	use hiweb\files\file;
	use hiweb\paths;
	use hiweb_theme\head;
	use hiweb_theme\includes;


	class pwa{

		static private $admin_menu_slug = 'hiweb-theme-pwa';
		static private $service_worker_filename = 'service-worker.js';
		/** @var file */
		static private $service_worker;
		/** @var arrays\array_ */
		static private $service_worker_cach_urls = [ '/' ];


		static function init(){

			self::$service_worker_cach_urls = arrays::get( self::$service_worker_cach_urls );

			require_once __DIR__ . '/pwa/-admin-menu.php';

			//add to head manifest link
			head::add_html_addition( '<link rel="manifest" href="' . get_rest_url( null, 'hiweb-theme/pwa/manifest' ) . '">' );

			if( get_field( 'head-meta-theme-color', self::$admin_menu_slug ) != '' ){
				head::add_code( '<meta name="theme-color" content="' . get_field( 'head-meta-theme-color', self::$admin_menu_slug ) . '">' );
			}
			head::add_code( '<meta name="apple-mobile-web-app-capable" content="' . ( get_field( 'apple-mobile-web-app-capable', self::$admin_menu_slug ) ? 'yes' : 'no' ) . '">' );
			head::add_code( '<meta name="apple-mobile-web-app-status-bar-style" content="' . get_field( 'apple-mobile-web-app-status-bar-style', self::$admin_menu_slug ) . '">' );

			require_once __DIR__ . '/pwa/-rest.php';

			if( get_field( 'service-worker-enable', self::$admin_menu_slug ) ){
				self::$service_worker = \hiweb\file( '/' . self::$service_worker_filename );
				$B = self::make_service_worker();
				if( $B === true || $B === - 1 ){
					includes::async_script_file( 'pwa' );
				}
			} else {
				includes::defer_script_file( 'pwa-unreg' );
			}
		}


		/**
		 * @return bool|string
		 */
		static function get_generated_service_worker_content(){
			$template = paths::get( __DIR__ . '/pwa/service-worker-template.js' );
			if( !$template->is_readable() ) return false;
			if( $template->get_content() == '' ) return false;
			$R = strtr( $template->get_content( '' ), [
				'{cache_urls:cache_urls}' => self::$service_worker_cach_urls->get_json()->get()
			] );
			return $R;
		}


		/**
		 * Make service worker js file in root, if them not exists...
		 * @return bool|int
		 */
		static function make_service_worker(){
			if( self::$service_worker->is_exists() ) return - 1;
			if( self::$service_worker->is_writable() ) return - 2;
			$new_content = self::get_generated_service_worker_content();
			if( $new_content == '' ) return - 3;
			return self::$service_worker->make_file( $new_content );
		}


		/**
		 * @param $url
		 * @return arrays\array_
		 */
		static function add_service_worker_cache_url( $url ){
			self::$service_worker_cach_urls->push( $url );
			return self::$service_worker_cach_urls;
		}

	}