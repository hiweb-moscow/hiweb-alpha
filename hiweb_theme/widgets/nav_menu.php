<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 0:05
	 */

	namespace hiweb_theme\widgets {


		use hiweb\arrays;
		use hiweb\path;
		use hiweb\themes;
		use hiweb_theme\includes;


		class nav_menu{

			public $id = 'mobile-menu';
			protected $root_classes = [];
			protected $root_tags = [];
			//public $use_ul_li = true;
			public $depth = 2;
			public $nav_location = 'mobile-menu';
			public $ul_classes = [];
			public $item_classes = [];
			public $link_classes = [];
			public $item_class_active = 'active';
			public $items;
			public $items_by_parent;
			public $use_stellarnav = true;
			public $use_stellarnav_showArrows = true;


			public function __construct( $nav_location ){
				$this->nav_location = $nav_location;
				$this->add_tag( 'data-arrows', (bool)$this->use_stellarnav_showArrows );
			}


			/**
			 * @param $class
			 * @return $this
			 */
			public function add_class( $class ){
				$this->root_classes[] = $class;
				return $this;
			}


			/**
			 * @return string
			 */
			public function get_classes(){
				return implode( ' ', $this->root_classes );
			}


			/**
			 * @param string $name
			 * @param string $value
			 * @return $this
			 */
			public function add_tag( $name, $value = '' ){
				$this->root_tags[ $name ] = $value;
				return $this;
			}


			/**
			 * @return string
			 */
			public function get_tags(){
				$R = [];
				foreach( $this->root_tags as $name => $value ){
					$R[] = $name . '="' . htmlentities( $value ) . '"';
				}
				return implode( ' ', $R );
			}


			/**
			 * @param bool $by_parent
			 * @return array
			 */
			public function get_items( $by_parent = false ){
				if( !is_array( $this->items ) ){
					$this->items = [];
					foreach( themes::get()->menu_items( $this->nav_location ) as $item ){
						$this->items[ (int)$item->ID ] = $item;
						$this->items_by_parent[ (int)$item->menu_item_parent ][ (int)$item->ID ] = $item;
					}
				}
				if( is_bool( $by_parent ) ){
					return $by_parent ? $this->items_by_parent : $this->items;
				} elseif( is_numeric( $by_parent ) ) {
					return array_key_exists( (int)$by_parent, $this->items_by_parent ) ? $this->items_by_parent[ (int)$by_parent ] : [];
				}
			}


			/**
			 * @param int $ID
			 * @return bool
			 */
			public function has_subitems( $ID = 0 ){
				return array_key_exists( $ID, $this->get_items( true ) );
			}


			/**
			 * @param int $parent_id
			 * @param int $depth
			 */
			public function the_list( $parent_id = 0, $depth = 0 ){
				if( $depth <= $this->depth && $this->has_subitems( $parent_id ) ){
					?>
					<ul class="<?= implode( ' ', $this->ul_classes ) ?> nav-level-<?= $depth ?>" <?= $depth > 0 ? 'style="visibility: hidden;"' : '' ?>>
						<?php
							foreach( $this->get_items( $parent_id ) as $item ){
								$active = path::is_page( $item->url );
								?>
								<li class="<?= implode( ' ', $this->item_classes ) ?>">
									<a class="<?= implode( ' ', $this->link_classes ) ?><?= $active ? ' ' . $this->item_class_active : '' ?>" href="<?= $item->url ?>"><?= $item->title ?></a>
									<?php $this->the_list( $item->ID, $depth + 1 ); ?>
								</li>
								<?php
							}
						?>
					</ul>
					<?php
				}
			}


			public function the(){
				if( $this->use_stellarnav ){
					includes::fontawesome( false );
					includes::css( HIWEB_THEME_VENDORS_DIR . '/jquery.stellarnav/stellarnav.min.css' );
					includes::js( HIWEB_THEME_VENDORS_DIR . '/jquery.stellarnav/stellarnav.min.js', [ includes::jquery() ] );
					$this->root_classes[] = 'stellarnav';
					?>
					<div id="<?= $this->id ?>" <?= $this->get_tags() ?> <?= $this->get_classes() == '' ?: 'class="' . $this->get_classes() . '"' ?>>
					<nav>
					<?php
				} else {
					?>
					<nav id="<?= $this->id ?>" <?= $this->get_tags() ?> <?= arrays::is_empty( $this->root_classes ) ? '' : 'class="' . implode( ' ', $this->root_classes ) . '"' ?>>
					<?php
				}
				$this->the_list( 0 );
				if( !$this->use_stellarnav ){ ?></nav> <?php } else { ?>
					</nav>
					</div>
					<?php
					includes::defer_script_file( 'nav_menu' );
				}
			}


			/**
			 * @return string
			 */
			public function get(){
				ob_start();
				$this->the();
				return ob_get_clean();
			}


			/**
			 * @return string
			 */
			public function __toString(){
				return $this->get();
			}

		}
	}