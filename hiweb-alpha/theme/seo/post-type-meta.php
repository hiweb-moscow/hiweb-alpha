<?php
/**
 * Created by PhpStorm.
 * User: denisivanin
 * Date: 2019-01-21
 * Time: 11:52
 */

add_action('wp_loaded', function () {


    $post_type_taxonomies = [];
    foreach (get_taxonomies() as $taxonomy) {
        $taxonomy = get_taxonomy($taxonomy);
        if ($taxonomy->public && $taxonomy->publicly_queryable) {
            $post_type_taxonomies = array_merge($post_type_taxonomies, $taxonomy->object_type);
            add_field_separator('SEO настройки категории')->LOCATION()->TAXONOMIES($taxonomy->name);
            add_field_text('seo-custom-h1')->label('Индивидуальный заголовок H1')->description('Оставьте поле пустым для использования основного заголовка текущей страницы')->LOCATION()->TAXONOMIES($taxonomy->name);
	        add_field_text('seo-custom-loop-title')->label('Индивидуальный заголовок на архивной странице')->description('Оставьте поле пустым для использования основного заголовка текущей страницы')->LOCATION()->TAXONOMIES($taxonomy->name);
            add_field_text('seo-meta-title')->label('Мета-Заголовок <code>&lt;title&gt;...&lt;/title&gt;</code>')->LOCATION()->TAXONOMIES($taxonomy->name);
            add_field_textarea('seo-meta-description')->label('Мета-Описание страницы <code>&lt;meta name=&quot;description&quot; content=&quot;...&quot; /&gt;</code>')->LOCATION()->TAXONOMIES($taxonomy->name);
            add_field_text('seo-meta-keywords')->label('Мета-Ключевые слова <code>&lt;meta name=&quot;keywords&quot; content=&quot;...&quot; /&gt;</code>')->LOCATION()->TAXONOMIES($taxonomy->name);
            add_field_select('seo-meta-robots-mode')->options([
                'default' => '--выберите вариант--',
                'noindex' => 'noindex - Не индексировать текст страницы. Страница не будет участвовать в результатах поиска',
                'nofollow' => 'nofollow - Не переходить по ссылкам на странице	',
                'none' => 'none - Соответствует директивам noindex, nofollow',
                'noarchive' => 'noarchive - Не показывать ссылку на сохраненную копию в результатах поиска',
                'noyaca' => 'noyaca - Не использовать сформированное автоматически описание	',
                'index' => 'index - Отмена дерективы "noindex"',
                'follow' => 'follow - Отмена дерективы "nofollow"',
                'archive' => 'archive - Отмена дерективы "noarchive"',
                'all' => 'all - Соответствует директивам index и follow — разрешено индексировать текст и ссылки на странице'])
                ->label('Режим индексации страницы и ссылок <code>&lt;meta name=&quot;robots&quot; content=&quot;...&quot; /&gt;</code>')
                ->description('Данный режим добавить в шапку страницы таксономии мета тег <code>robots</code> внутри тега <code>head</code>.<br><a href="https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html" target="_blank">Документация на Яндекс.Вебмастер</a>')
                ->LOCATION()->TAXONOMIES($taxonomy->name);
	        add_field_separator('Шаблон SEO настроек для всех вложенных записей и страниц данной категории','Оставьте поля пустыми, чтобы не использовать шаблон в том или ином месте.<br><i class="far fa-exclamation-circle"></i> Внимание! Если одна запись, страница или товар находиться сразу в несколких категориях, то возможен конфдик со случайным исходом конфликтующих данных.')->LOCATION()->TAXONOMIES($taxonomy->name);
	        add_field_text('seo-sub-custom-h1')->label('Шаблон индивидуального заголовока H1')->LOCATION()->TAXONOMIES($taxonomy->name);
	        add_field_text('seo-sub-custom-loop-title')->label('Шаблон индивидуального заголовока на архивной странице')->LOCATION()->TAXONOMIES($taxonomy->name);
	        add_field_text('seo-sub-meta-title')->label('Шаблон заголовока (title)')->LOCATION()->TAXONOMIES($taxonomy->name);
	        add_field_textarea('seo-sub-meta-description')->label('Шаблон описания страницы (description)')->LOCATION()->TAXONOMIES($taxonomy->name);
	        add_field_text('seo-sub-meta-keywords')->label('Шаблон ключевых слов (keywords)')->LOCATION()->TAXONOMIES($taxonomy->name);
	        add_field_select('seo-sub-meta-robots-mode')->options([
		        'default' => '--выберите вариант--',
		        'noindex' => 'noindex - Не индексировать текст страницы. Страница не будет участвовать в результатах поиска',
		        'nofollow' => 'nofollow - Не переходить по ссылкам на странице	',
		        'none' => 'none - Соответствует директивам noindex, nofollow',
		        'noarchive' => 'noarchive - Не показывать ссылку на сохраненную копию в результатах поиска',
		        'noyaca' => 'noyaca - Не использовать сформированное автоматически описание	',
		        'index' => 'index - Отмена дерективы "noindex"',
		        'follow' => 'follow - Отмена дерективы "nofollow"',
		        'archive' => 'archive - Отмена дерективы "noarchive"',
		        'all' => 'all - Соответствует директивам index и follow — разрешено индексировать текст и ссылки на странице'])
		        ->label('Режим индексации для всех страницы и ссылок, если иного не задано самой страницей')
		        ->description('Данный режим добавить в шапку страницы таксономии мета тег <code>robots</code> внутри тега <code>head</code>.<br><a href="https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html" target="_blank">Документация на Яндекс.Вебмастер</a>')
		        ->LOCATION()->TAXONOMIES($taxonomy->name);
        }
    }
    $post_type_taxonomies = array_unique($post_type_taxonomies);

    foreach (get_post_types() as $post_type) {
        /** @var WP_Post_Type $post_type */
        $post_type = get_post_type_object($post_type);
        if ($post_type->public) {
            if (($post_type->publicly_queryable || $post_type->hierarchical) && get_field('enable-custom-h1-' . $post_type->name, \theme\seo::$admin_menu_main)) {
                add_field_text('seo-custom-h1')->label('Индивидуальный заголовок H1')->description('Оставьте поле пустым для использования основного заголовка текущей страницы')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
            }
            if (($post_type->has_archive || get_array($post_type_taxonomies, false)->in($post_type->name)) && get_field('enable-custom-loop-title-' . $post_type->name, \theme\seo::$admin_menu_main)) {
                add_field_text('seo-custom-loop-title')->label('Индивидуальный заголовок на архивной странице')->description('Оставьте поле пустым для использования основного заголовка текущей страницы')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
            }
            if (($post_type->publicly_queryable || $post_type->hierarchical)) {
                if (get_field('enable-' . $post_type->name, \theme\seo::$admin_menu_main)) {
                    add_field_text('seo-meta-title')->label('Заголовок (title)')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
                    add_field_textarea('seo-meta-description')->label('Описание страницы (description)')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
                    add_field_text('seo-meta-keywords')->label('Ключевые слова (keywords)')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
                    add_field_select('seo-meta-robots-mode')->options([
                        'default' => '--выберите вариант--',
                        'noindex' => 'noindex - Не индексировать текст страницы. Страница не будет участвовать в результатах поиска',
                        'nofollow' => 'nofollow - Не переходить по ссылкам на странице	',
                        'none' => 'none - Соответствует директивам noindex, nofollow',
                        'noarchive' => 'noarchive - Не показывать ссылку на сохраненную копию в результатах поиска',
                        'noyaca' => 'noyaca - Не использовать сформированное автоматически описание	',
                        'index' => 'index - Отмена дерективы "noindex"',
                        'follow' => 'follow - Отмена дерективы "nofollow"',
                        'archive' => 'archive - Отмена дерективы "noarchive"',
                        'all' => 'all - Соответствует директивам index и follow — разрешено индексировать текст и ссылки на странице'])
                        ->label('Режим индексации страницы и ссылок')
                        ->description('Данный режим добавить в шапку страницы таксономии мета тег <code>robots</code> внутри тега <code>head</code>.<br><a href="https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html" target="_blank">Документация на Яндекс.Вебмастер</a>')
                        ->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
                }
            }
        }
    }
}, 20);