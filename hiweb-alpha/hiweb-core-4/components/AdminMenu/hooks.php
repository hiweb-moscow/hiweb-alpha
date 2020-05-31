<?php
	
	use hiweb\components\Includes\IncludesFactory_AdminPage;
	
	
	add_action( 'init', function(){
		IncludesFactory_AdminPage::css( __DIR__ . '/AdminMenu.css' );
	} );
	
	//Show Admin menu PAGES
	add_action( 'admin_menu', '\hiweb\components\AdminMenu\AdminMenuFactory::_register_admin_menus' );