<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:52
	 */

	add_action( 'wp_loaded', function(){


		$post_type_taxonomies = [];
		foreach( get_taxonomies() as $taxonomy ){
			$taxonomy = get_taxonomy( $taxonomy );
			if( $taxonomy->public && $taxonomy->publicly_queryable ){
				$post_type_taxonomies = array_merge( $post_type_taxonomies, $taxonomy->object_type );
				add_field_separator( 'SEO настройки категории' )->LOCATION()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-custom-h1' )->label( 'Индивидуальный заголовок H1' )->description( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->LOCATION()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-meta-title' )->label( 'Заголовок (title)' )->LOCATION()->TAXONOMIES( $taxonomy->name );
				add_field_textarea( 'seo-meta-description' )->label( 'Описание страницы (description)' )->LOCATION()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-meta-keywords' )->label( 'Ключевые слова (keywords)' )->LOCATION()->TAXONOMIES( $taxonomy->name );
			}
		}
		$post_type_taxonomies = array_unique( $post_type_taxonomies );

		foreach( get_post_types() as $post_type ){
			/** @var WP_Post_Type $post_type */
			$post_type = get_post_type_object( $post_type );
			if( $post_type->public ){
				if( ( $post_type->publicly_queryable || $post_type->hierarchical ) && get_field( 'enable-custom-h1-' . $post_type->name, \theme\seo::$admin_menu_main ) ){
					add_field_text( 'seo-custom-h1' )->label( 'Индивидуальный заголовок H1' )->description( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->LOCATION()->POST_TYPES( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
				}
				if( ( $post_type->has_archive || get_array( $post_type_taxonomies, false )->in( $post_type->name ) ) && get_field( 'enable-custom-loop-title-' . $post_type->name, \theme\seo::$admin_menu_main ) ){
					add_field_text( 'seo-custom-loop-title' )->label( 'Индивидуальный заголовок на архивной странице' )->description( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->LOCATION()->POST_TYPES( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
				}
				if( ( $post_type->publicly_queryable || $post_type->hierarchical ) ){
					if( get_field( 'enable-' . $post_type->name, \theme\seo::$admin_menu_main ) ){
						add_field_text( 'seo-meta-title' )->label( 'Заголовок (title)' )->LOCATION()->POST_TYPES( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
						add_field_textarea( 'seo-meta-description' )->label( 'Описание страницы (description)' )->LOCATION()->POST_TYPES( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
						add_field_text( 'seo-meta-keywords' )->label( 'Ключевые слова (keywords)' )->LOCATION()->POST_TYPES( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
					}
				}
			}
		}
	}, 20 );