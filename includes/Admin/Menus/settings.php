<?php
class NF_UXEnhancements_Settings
{
	private $options;

	public function __construct()
	{
		$this->options = get_option('nf_ux_enhancements_admin');
	}

	public function options_page_html()
	{
		if (!current_user_can('manage_options')) {
			return;
		}

		?>
  <div class="wrap nf-ux-enhancements">
    <h1>
			<?php echo __('UX Enhancements for Ninja Forms', 'nf-ux-enhancements') ?>
		</h1>
    <p>
			<?php echo __('Useful tweaks to improve you and your visitors\' user experience with Ninja Forms.', 'nf-ux-enhancements') ?>
		</p>

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

	public function options_page()
	{
		add_submenu_page(
			'options-general.php',
			'Ninja Forms UX',
			'Ninja Forms UX',
			'manage_options',
			'nf-ux-enhancements',
			array($this, 'options_page_html')
		);
	}

	public function sanitize($option)
	{
		$sanitized = array();

		$field_names = array(
			'browser_save_data',
			'sub_date_format',
			'subs_back',
			'scrollbar'
		);

		foreach ($field_names as $field_id) {
			if (isset($option[$field_id])) {
				$sanitized[$field_id] = $option[$field_id];
			} else {
				$sanitized[$field_id] = '';
			}
		}

		return $sanitized;
	}

	public function settings_init()
	{
		register_setting(
			'nf_ux_enhancements_general',
			'nf_ux_enhancements_admin',
			array(
				'sanitize_callback' => array($this, 'sanitize'),
			)
		);

		add_settings_section(
			'nf_ux_enhancements_general',
			'General Settings',
			array($this, 'general_settings_html'),
			'nf-ux-enhancements'
		);

		add_settings_field(
			'browser_save_data',
			'Browser auto-fill',
			array($this, 'browser_save_data_html'),
			'nf-ux-enhancements',
			'nf_ux_enhancements_general',
			array(
				'id' => 'browser_save_data'
			)
		);

		add_settings_field(
			'sub_date_format',
			'Submission date display',
			array($this, 'sub_date_format_html'),
			'nf-ux-enhancements',
			'nf_ux_enhancements_general',
			array(
				'id' => 'sub_date_format'
			)
		);

		add_settings_field(
			'subs_back',
			'Return to submissions list',
			array($this, 'subs_back_html'),
			'nf-ux-enhancements',
			'nf_ux_enhancements_general',
			array(
				'id' => 'subs_back'
			)
		);

		add_settings_field(
			'scrollbar',
			'Scrollbar visibility',
			array($this, 'scrollbar_html'),
			'nf-ux-enhancements',
			'nf_ux_enhancements_general',
			array(
				'id' => 'scrollbar'
			)
		);
	}

	public function browser_save_data_html($args)
	{
		$options = $this->options;
		$setting = isset($options[$args['id']]) ? $options[$args['id']] : '1';
		?>
		<label>
			<input type="checkbox" value="1" name="nf_ux_enhancements_admin[<?php echo $args['id'] ?>]"
				<?php checked($setting, '1') ?> >
			Let web browsers save the user's submitted data</label>
			<p class="description">
				Note: this feature hasn't been tested with any other Ninja Forms
				extensions, like multi-part forms. If you experience any issues where
				forms don't submit properly, try disabling this option.
			</p>
		<?php

	}

	public function sub_date_format_html($args)
	{
		$options = $this->options;
		$setting = isset($options[$args['id']]) ? $options[$args['id']] : '1';
		?>
		<label>
			<input type="checkbox" value="1" name="nf_ux_enhancements_admin[<?php echo $args['id'] ?>]"
				<?php checked($setting, '1') ?> >
			Use international standard date format</label>
		<?php

	}

	public function subs_back_html($args)
	{
		$options = $this->options;
		$setting = isset($options[$args['id']]) ? $options[$args['id']] : '1';
		?>
		<label>
			<input type="checkbox" value="1" name="nf_ux_enhancements_admin[<?php echo $args['id'] ?>]"
				<?php checked($setting, '1') ?> >
			Add 'All Submissions' button to submission edit page</label>
		<?php

	}

	public function scrollbar_html($args)
	{
		$options = $this->options;
		$setting = isset($options[$args['id']]) ? $options[$args['id']] : '1';
		?>
		<label>
			<input type="checkbox" value="1" name="nf_ux_enhancements_admin[<?php echo $args['id'] ?>]"
				<?php checked($setting, '1') ?> >
			Show scrollbar in Ninja Forms dashboard</label>
		<?php

	}

	function general_settings_html($args)
	{
	}
}