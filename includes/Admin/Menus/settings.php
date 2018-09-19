<?php

function options_page_html()
{
  if (!current_user_can('manage_options')) {
    return;
  }

  ?>
  <div class="wrap nf-ux-enhancements">
    <h1>Ninja Forms UX Enhancements</h1>
    <p>Minor customisations to improve your user experience with Ninja Forms.</p>

    <form action="options.php" method="post">
      <?php
      settings_fields('nf_ux_enhancements_general');
      do_settings_sections('nf-ux-enhancements');
      submit_button('Save Settings');
      ?>
    </form>
  </div>

  <?php

}

function options_page()
{
  add_submenu_page(
    'options-general.php',
    'Ninja Forms UX',
    'Ninja Forms UX',
    'manage_options',
    'nf-ux-enhancements',
    'options_page_html'
  );
}

add_action('admin_menu', 'options_page');

function settings_init()
{
  register_setting('nf_ux_enhancements_general', 'nf_ux_enhancements_sub_date_format');
  register_setting('nf_ux_enhancements_general', 'nf_ux_enhancements_subs_back');
  register_setting('nf_ux_enhancements_general', 'nf_ux_enhancements_icons_css');
  register_setting('nf_ux_enhancements_general', 'nf_ux_enhancements_scrollbar');

  add_settings_section(
    'nf_ux_enhancements_general',
    'General Settings',
    'general_settings_html',
    'nf-ux-enhancements'
  );

  add_settings_field(
    'sub_date_format',
    'Submission date display',
    'sub_date_format_html',
    'nf-ux-enhancements',
    'nf_ux_enhancements_general',
    array(
      'id' => 'nf_ux_enhancements_sub_date_format',
      'name' => 'sub_date_format'
    )
  );

  add_settings_field(
    'subs_back',
    'Return to submissions list',
    'subs_back_html',
    'nf-ux-enhancements',
    'nf_ux_enhancements_general',
    array(
      'id' => 'nf_ux_enhancements_subs_back',
      'name' => 'subs_back'
    )
  );

  add_settings_field(
    'icons_css',
    'Dashicons stylesheet',
    'block_icons_html',
    'nf-ux-enhancements',
    'nf_ux_enhancements_general',
    array(
      'id' => 'nf_ux_enhancements_icons_css',
      'name' => 'icons_css'
    )
  );

  add_settings_field(
    'scrollbar',
    'Scrollbar visibility',
    'scrollbar_html',
    'nf-ux-enhancements',
    'nf_ux_enhancements_general',
    array(
      'id' => 'nf_ux_enhancements_scrollbar',
      'name' => 'scrollbar'
    )
  );
}

function sub_date_format_html($args)
{
  $setting = get_option('nf_ux_enhancements_sub_date_format', '1');
  ?>
  <label>
    <input type="checkbox" value="1" name="<?php echo $args['id'] ?>"
      <?php checked($setting, '1') ?> >
    Use standard international date format</label>
  <?php

}

function subs_back_html($args)
{
  $setting = get_option($args['id'], '1');
  ?>
  <label>
    <input type="checkbox" value="1" name="<?php echo $args['id'] ?>"
      <?php checked($setting, '1') ?> >
    Add to submission edit page</label>
    <p class="description">
      Adds a button that takes you back to the submissions list of the current form.
    </p>
  <?php

}

function block_icons_html($args)
{
  $setting = get_option($args['id'], '0');
  ?>
  <label>
    <input type="checkbox" value="0" name="<?php echo $args['id'] ?>"
      <?php checked($setting, '1') ?> >
    Block Dashicons stylesheet on public site</label>
    <p class="description">
      Not yet implemented.
    </p>
    <p class="description">
      By default Ninja Forms always loads admin-dashicons.css, which is only used for the
      rich text editor on specific fields.
    </p>
  <?php

}

function scrollbar_html($args)
{
  $setting = get_option($args['id'], '1');
  ?>
  <label>
    <input type="checkbox" value="1" name="<?php echo $args['id'] ?>"
      <?php checked($setting, '1') ?> >
    Show scrollbar in Ninja Forms dashboard</label>
  <?php

}

add_action('admin_init', 'settings_init');

function general_settings_html($args)
{
  ?>
  <?php

}
