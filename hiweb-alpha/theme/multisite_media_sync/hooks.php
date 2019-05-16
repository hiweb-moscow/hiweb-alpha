<?php

	if( defined( 'WP_ALLOW_MULTISITE' ) && WP_ALLOW_MULTISITE && defined( 'BLOG_ID_CURRENT_SITE' ) && BLOG_ID_CURRENT_SITE != get_current_blog_id() ){

		add_filter( 'query', function( $query ){
			global $wpdb;
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				if( preg_match( "/(SELECT \* FROM\s+{$wpdb->posts}\s+WHERE ID = (?<post_id>[\d]+)\s+LIMIT 1)/im", $query, $matches ) > 0 && isset( $matches['post_id'] ) ){
					$old_post_table = $wpdb->posts;
					switch_to_blog( BLOG_ID_CURRENT_SITE );
					$id = intval( trim( $matches['post_id'] ) );
					if( $id > 0 ){
						$post = get_post( $id );
						if( $post instanceof WP_Post && $post->post_type == 'attachment' ){
							$query = str_replace( $old_post_table, $wpdb->posts, $query );
						}
					}
					restore_current_blog();
				} elseif( preg_match( "/(SELECT\s+SQL_CALC_FOUND_ROWS\s+{$wpdb->posts}.ID\s+FROM\s+{$wpdb->posts}\s+WHERE[\s\S]+{$wpdb->posts}.post_type = ['\"]attachment['\"][\s\S]+)/im", $query ) > 0 ) {
					$old_post_table = $wpdb->posts;
					switch_to_blog( BLOG_ID_CURRENT_SITE );
					$query = str_replace( $old_post_table, $wpdb->posts, $query );
					restore_current_blog();
				}
			}
			return $query;
		} );

		add_filter( 'posts_pre_query', function( $null, $wp_query ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				if( $wp_query instanceof WP_Query ){
					if( isset( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] == 'attachment' ){
						global $wpdb;
						$old_post_table = $wpdb->posts;
						switch_to_blog( BLOG_ID_CURRENT_SITE );
						$wp_query->request = str_replace( $old_post_table, $wpdb->posts, $wp_query->request );
						restore_current_blog();
					}
				}
			}
			return $null;
		}, 10, 2 );

		add_filter( 'get_attached_file', function( $file, $attachment_id ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = get_attached_file( $attachment_id );
				restore_current_blog();
				return $R;
			}
			return $file;
		}, 10, 2 );

		add_filter( 'wp_get_attachment_image_src', function( $image, $attachment_id, $size, $icon ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = wp_get_attachment_image_src( $attachment_id, $size, $icon );
				restore_current_blog();
				return $R;
			}
			return $image;
		}, 10, 4 );

		add_filter( 'wp_get_attachment_url', function( $url, $attachment_id ){ //?
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = wp_get_attachment_url( $attachment_id );
				restore_current_blog();
				return $R;
			}
			return $url;
		}, 10, 2 );

		add_filter( 'wp_get_attachment_metadata', function( $data, $attachment_id ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = wp_get_attachment_metadata( $attachment_id );
				restore_current_blog();
				return $R;
			}
			return $data;
		}, 10, 2 );

		add_filter( 'wp_get_attachment_caption', function( $caption, $attachment_id ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = wp_get_attachment_caption( $attachment_id );
				restore_current_blog();
				return $R;
			}
			return $caption;
		}, 10, 2 );

		add_filter( 'wp_get_attachment_thumb_file', function( $thumbfile, $attachment_id ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = wp_get_attachment_thumb_file( $attachment_id );
				restore_current_blog();
				return $R;
			}
			return $thumbfile;
		}, 10, 2 );

		add_filter( 'wp_get_attachment_thumb_url', function( $url, $attachment_id ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = wp_get_attachment_thumb_url( $attachment_id );
				restore_current_blog();
				return $R;
			}
			return $url;
		}, 10, 2 );

		add_filter( 'upload_dir', function( $cache ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = wp_upload_dir();
				restore_current_blog();
				return $R;
			}
			return $cache;
		}, 10, 2 );

		add_filter( 'wp_update_attachment_metadata', function( $data, $attachment_id ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				$R = wp_update_attachment_metadata( $attachment_id, $data );
				restore_current_blog();
				return $R;
			}
			return $data;
		}, 10, 2 );

		add_filter( 'wp_insert_attachment_data', function( $data, $postarr ){
			if( BLOG_ID_CURRENT_SITE != get_current_blog_id() ){
				switch_to_blog( BLOG_ID_CURRENT_SITE );
				add_action( 'wp_insert_post', function(){
					if( BLOG_ID_CURRENT_SITE == get_current_blog_id() ){
						restore_current_blog();
					}
				} );
			}
			return $data;
		} );

		///META BOX
		add_action( 'add_meta_boxes', function(){
			$screens = get_post_types( [ 'public' => true, 'publicly_queryable' => true ] );
			add_meta_box( 'hiweb_multisite_duplicate_post', 'Дублировать страницу', function( $post, $meta ){
				// Поля формы для введения данных
				$other_blog_id = 1;
				foreach( get_sites( [ 'site__not_in' => get_current_blog_id() ] ) as $site ){
					$other_blog_id = $site->blog_id;
				}
				$url = get_admin_url( $other_blog_id, 'admin-ajax.php?action=hiweb_multisite_duplicate&source_post_id=' . $post->ID . '&source_blog_id=' . get_current_blog_id() );
				?>
				<a href="<?= $url ?>" class="button button-primary" target="_blank">Дублировать страницу</a>
				<p class="descending">Создать копию данной странице на другом сайте. После создания копии, выбудете автоматически переключены на новую страницу в другой вкладке. Новая страница создаеться в статусе "Черновик"</p>
				<?php
			}, $screens, 'side', 'high' );
		} );

		function wp_ajax_hiweb_multisite_duplicate(){
			if( !is_user_logged_in() ){
				?>
				<h1>Вы не авторизированны.</h1><p>Дублирование не удалось. Пройдите авторизацию, после чего попытка дублирование произойдет повторно.</p>
				<?php
				wp_login_form();
			} else {
				switch_to_blog( $_GET['source_blog_id'] );
				$wp_post = get_post( $_GET['source_post_id'] );
				if( !$wp_post instanceof WP_Post ){
					?><h1>не удалось продублировать страницу</h1><p>Указанный индификатор записи не верный</p><?php
					die;
				}
				$metas_source = get_post_meta( $wp_post->ID );
				$metas = [];
				foreach( $metas_source as $key => $meta ){
					$metas[ $key ] = $meta[0];
				}
				restore_current_blog();
				///
				$new_post_id = wp_insert_post( [
					'post_author' => $wp_post->post_author,
					'post_date' => $wp_post->post_date,
					'post_date_gmt' => $wp_post->post_date_gmt,
					'post_content' => $wp_post->post_content,
					'post_title' => $wp_post->post_title,
					'post_excerpt' => $wp_post->post_excerpt,
					'post_status' => 'draft',
					'comment_status' => $wp_post->comment_status,
					'ping_status' => $wp_post->ping_status,
					'post_password' => $wp_post->post_password,
					'post_name' => $wp_post->post_name,
					'to_ping' => $wp_post->to_ping,
					'pinged' => $wp_post->pinged,
					'post_modified' => $wp_post->post_modified,
					'post_modified_gmt' => $wp_post->post_modified_gmt,
					'post_content_filtered' => $wp_post->post_content_filtered,
					'post_parent' => $wp_post->post_parent,
					'post_type' => $wp_post->post_type,
					'post_mime_type' => $wp_post->post_mime_type
				] );
				if( is_int( $new_post_id ) ){
					foreach( $metas as $key => $val ){
						update_post_meta( $new_post_id, $key, $val );
					}
					?>
					<meta http-equiv="refresh" content="3;<?=get_edit_post_link( $new_post_id )?>">
					<h1>Страница создана, подождите пару секунд...</h1>
					<?php
				} else {
					?><h1>не удалось продублировать страницу</h1><p>Ошибка во время создания новой страницы</p><?php
				}
			}
			die;
		}

		add_action( 'wp_ajax_hiweb_multisite_duplicate', 'wp_ajax_hiweb_multisite_duplicate' );
		add_action( 'wp_ajax_nopriv_hiweb_multisite_duplicate', 'wp_ajax_hiweb_multisite_duplicate' );
	}