<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Limitee Extension
 *
 * @package Limitee
 * @author  Caddis
 * @link    https://www.caddis.co
 */

include(PATH_THIRD . 'limitee/config.php');

class Limitee_ext
{
	public $name = LIMITEE_NAME;
	public $version = LIMITEE_VER;
	public $description = LIMITEE_DESC;
	public $docs_url = LIMITEE_DOCS;
	public $settings_exist	= 'y';
	public $settings = array();

	private $site_id;

	/**
	 * Constructor
	 *
	 * @param  mixed $settings
	 */
	public function __construct($settings = array()) {
		$this->settings = $settings;
		$this->site_id = ee()->config->item('site_id');
	}

	/**
	 * Settings form
	 *
	 * @param  array $current settings
	 */
	public function settings_form($current) {
		ee()->load->helper(array('html', 'form'));
		ee()->load->model('limitee_model');

		$fields = ee()->limitee_model->getFields($this->site_id);

		$values = array(
			'settings' => isset($current[$this->site_id]) ?
				$current[$this->site_id] :
				array(),
			'channelFields' => $fields
		);

		return ee()->load->view('limitee_settings', $values, true);
	}

	/**
	 * Save Settings
	 */
	public function save_settings() {
		unset($_POST['submit']);

		// Associate the posted settings with the current site ID
		$this->settings[$this->site_id] = $_POST;

		// Update the settings
		if (ee()->db->update('extensions', array(
			'settings' => serialize($this->settings)), "class = 'Limitee_ext'")
		) {
			ee()->session->set_flashdata(
				'message_success',
				ee()->lang->line('preferences_updated')
			);
		} else {
			ee()->session->set_flashdata(
				'message_error',
				ee()->lang->line('update_failed')
			);
		}
	}

	/**
	 * Activate Extension
	 */
	public function activate_extension() {
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
	 * @param string $current
	 * @return mixed void on update / false if none
	 */
	public function update_extension($current = '') {
		if ($current === $this->version) {
			return false;
		}

		return true;
	}

	/**
	 * Disable Extension
	 */
	public function disable_extension() {
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	/**
	* Bind Links
	*
	* @param array $data
	* @return array
	*/
	function publish_form_entry_data($data) {
		if (! isset($this->settings[$this->site_id])) {
			return $data;
		}

		ee()->cp->load_package_js('base');

		ee()->session->cache['limitee']['require_scripts'] = true;

		if (isset(ee()->session->cache['limitee']['require_scripts']) === true) {
			ee()->load->model('limitee_model');

			$wrapper = '';
			$js = '';

			$countSettings = $this->settings[$this->site_id]['rules'];

			$channelId = isset($data['channel_id']) ?
				$data['channel_id'] :
				$_GET['channel_id'];

			// Get field group ID
			$fieldGroupId = ee()->limitee_model->getFieldGroupId($channelId);

			foreach ($countSettings as $key => $val) {
				$max = $val['max'];

				if ($max !== '') {
					$type = $val['type'];

					$selector = $key === 'title_' . $fieldGroupId || $key === 'group_title_' . $channelId ?
						'"title"' :
						'"field_id_' . $key . '"';

					$js .= '$(\'[name=' . $selector . ']\')
						.limitee(' . $max . ',' . $type . ');';
				}
			}

			if ($js !== '') {
				$wrapper .= '$(function() {' . $js . '});';
			}

			ee()->cp->add_to_foot('<script>' . $wrapper . '</script>');
		}

		ee()->cp->add_to_head('
			<style>
			.limitee {
				clear: left;
				float: left;
				font-size: 11px;
				padding: 6px 0 0 2px;
			}
			.limitee.-is-over {
				color: #f00;
			}
			</style>
		');

		return $data;
	}
}