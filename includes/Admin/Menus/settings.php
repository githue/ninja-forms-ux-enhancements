<?php

function options_page_html()
{
  if (!current_user_can('manage_options')) {
    return;
  }

  ?>
  <div class="wrap nf-ux-enhancements">
    <h1>Ninja Forms UX Enhancements</h1>
    <p>Useful tweaks to improve your and your visitors' user experience with Ninja Forms.</p>

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
  register_setting('nf_ux_enhancements_general', 'nf_ux_enhancements_scrollbar');
  register_setting('nf_ux_enhancements_general', 'nf_ux_enhancements_browser_save');

  add_settings_section(
    'nf_ux_enhancements_general',
    'General Settings',
    'general_settings_html',
    'nf-ux-enhancements'
  );

  add_settings_field(
    'browser_save_data',
    'Browser auto-fill',
    'browser_save_data_html',
    'nf-ux-enhancements',
    'nf_ux_enhancements_general',
    array(
      'id' => 'nf_ux_enhancements_browser_save'
    )
  );

  add_settings_field(
    'sub_date_format',
    'Submission date display',
    'sub_date_format_html',
    'nf-ux-enhancements',
    'nf_ux_enhancements_general',
    array(
      'id' => 'nf_ux_enhancements_sub_date_format'
    )
  );

  add_settings_field(
    'subs_back',
    'Return to submissions list',
    'subs_back_html',
    'nf-ux-enhancements',
    'nf_ux_enhancements_general',
    array(
      'id' => 'nf_ux_enhancements_subs_back'
    )
  );

  add_settings_field(
    'scrollbar',
    'Scrollbar visibility',
    'scrollbar_html',
    'nf-ux-enhancements',
    'nf_ux_enhancements_general',
    array(
      'id' => 'nf_ux_enhancements_scrollbar'
    )
  );
}

function browser_save_data_html($args)
{
  $setting = get_option($args['id'], '1');
  ?>
  <label>
    <input type="checkbox" value="1" name="<?php echo $args['id'] ?>"
      <?php checked($setting, '1') ?> >
    Let web browsers save the user's submitted data.</label>
    <p class="description">
      Warning: this feature hasn't been tested with any other Ninja Forms
      extensions, like multi-part forms. If you experience any issues where
      forms don't submit properly, try disabling this option.
    </p>
  <?php

}

function sub_date_format_html($args)
{
  $setting = get_option('nf_ux_enhancements_sub_date_format', '1');
  ?>
  <label>
    <input type="checkbox" value="1" name="<?php echo $args['id'] ?>"
      <?php checked($setting, '1') ?> >
    Use international standard date format</label>
  <?php

}

function subs_back_html($args)
{
  $setting = get_option($args['id'], '1');
  ?>
  <label>
    <input type="checkbox" value="1" name="<?php echo $args['id'] ?>"
      <?php checked($setting, '1') ?> >
    Add 'All Submissions' button to submission edit page</label>
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
