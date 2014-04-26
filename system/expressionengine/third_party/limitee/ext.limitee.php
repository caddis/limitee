<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Limitee Extension
 *
 * @package Limitee
 * @author  Caddis
 * @link    http://www.caddis.co
 */

include(PATH_THIRD . 'limitee/config.php');

class Limitee_ext {  

	public $name = LIMITEE_NAME;
	public $version = LIMITEE_VER;
	public $description = LIMITEE_DESC;
	public $docs_url = '';
	public $settings_exist	= 'y';
	public $settings = array();

	private $site_id;
	
	/**
	 * Constructor
	 *
	 * @param  mixed Settings array or empty string if none exist
	 * @return void
	 */
	public function __construct($settings = array())
	{
		$this->settings = $settings;
		$this->site_id = ee()->config->item('site_id');
	}

	/**
	 * Settings form
	 *
	 * @param  array Settings
	 * @return void
	 */
	public function settings_form($current)
	{
		ee()->load->helper(array('html', 'form'));
		ee()->load->model('limitee_model');

		$fields = ee()->limitee_model->get_fields($this->site_id);

		$values = array(
			'settings' => isset($current[$this->site_id]) ? $current[$this->site_id] : array(),
			'channel_fields' => $fields
		);

		return ee()->load->view('limitee_settings', $values, true);
	}

	/**
	 * Save Settings
	 *
	 * @return void
	 */
	public function save_settings()
	{
		unset($_POST['submit']);

		// Associate the posted settings with the current site ID
		$this->settings[$this->site_id] = $_POST;

		// Update the settings
		if (ee()->db->update('extensions', array('settings' => serialize($this->settings)), "class = 'Limitee_ext'")) {
			ee()->session->set_flashdata('message_success', ee()->lang->line('preferences_updated'));
		} else {
			ee()->session->set_flashdata('message_error', ee()->lang->line('update_failed'));
		}
	}

	/**
	 * Activate Extension
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		ee()->db->insert('extensions', array(
			'class' => __CLASS__,
			'method' => 'publish_form_entry_data',
			'hook' => 'publish_form_entry_data',
			'settings' => '',
			'priority' => 10,
			'version' => $this->version,
			'enabled' => 'y'
		));
	}

	/**
	 * Update Extension
	 *
	 * @return mixed void on update / false if none
	 */
	public function update_extension($current = '')
	{
		if ($current == $this->version)
		{
			return false;
		}

		return true;
	}

	/**
	 * Disable Extension
	 *
	 * @return void
	 */
	public function disable_extension()
	{
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	/**
	* Bind Links
	*
	* @param array $data
	* @return array $data
	*/
	function publish_form_entry_data($data)
	{
		ee()->load->helper('array');
		ee()->cp->load_package_js('base');

		ee()->session->cache['limitee']['require_scripts'] = true;

		if (isset(ee()->session->cache['limitee']['require_scripts']) === true) {
			$wrapper = '';
			$js = '';

			$count_settings = $this->settings[$this->site_id]['rules'];

			foreach ($count_settings as $key => $val) {
				$max = $val['max'];
				$type = $val['type'];

				if ($max !== '') {
					$selector = (substr($key, 0, 12) == 'group_title_') ? '"title"' : '"field_id_' . $key . '"';

					$js .= '$(\'[name=' . $selector . ']\').limiter(' . $max . ',' . $type . ')' . "\n";
				}
			}

			if ($js != '') {
				$wrapper .= '$(function() {' . "\n\n" . $js . "\n" . '});' . "\n";
			}

			ee()->cp->add_to_foot('<script>' . $wrapper . '</script>');
		}

		ee()->cp->add_to_head('
			<style type="text/css">
			.limitee {
				font-size: 11px;
				float: left;
				clear: left;
				padding: 6px 0 0 2px;
			}
			.limitee-over {
				color: #E11842;
			}
			</style>
		');

		return $data;
	}
}