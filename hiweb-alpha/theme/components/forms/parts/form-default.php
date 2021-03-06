<?php use theme\recaptcha; ?>
<form id="hiweb-theme-widgets-form-<?= get_the_form()->get_object_id() ?>" class="hiweb-theme-widget-form hiweb-theme-widget-form-id-<?=get_the_form()->get_id()?> hiweb-theme-widget-form-<?=get_the_form()->get_wp_post()->post_name?>" data-form-id="<?= get_the_form_id() ?>" data-form-object-id="<?= get_the_form()->get_object_id() ?>" action="<?= get_the_form()->get_action_url() ?>" method="<?= get_the_form()->get_method() ?>">
	<div class="hiweb-theme-widget-form-inside">
        <?php if(get_the_form()->is_show_title()) {
            ?><div class="hiweb-theme-widget-form-title"><?=get_the_form()->get_wp_post()->post_title?></div><?php
        } ?>
		<input type="hidden" name="hiweb-theme-widget-form-id" value="<?= get_the_form_id() ?>">
		<?php
			if( have_form_inputs() ){
				while( have_form_inputs() ){
					the_form_input();
					the_form_input_html();
				}
			}
		?>
		<?php if(class_exists('\theme\recaptcha')) recaptcha::the_input() ?>
	</div>
	<?php hw_template_part( HIWEB_THEME_PARTS . '/widgets/forms/status' ); ?>
</form>