<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Limitee Model
 *
 * @package Limitee
 * @author  Caddis
 * @link    https://www.caddis.co
 */

class Limitee_model extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get field settings from database
	 *
	 * @param int $siteId
	 * @return array
	 */
	public function getFields($siteId) {
		$results = $this->db->select('
				channels.channel_id,
				fields.field_id,
				fields.field_label,
				fields.field_maxl,
				fields.site_id,
				fields.field_type,
				groups.group_name,
				groups.group_id
			')
			->from('channels')
			->join(
				'field_groups AS groups',
				'groups.group_id = channels.field_group'
			)
			->join(
				'channel_fields AS fields',
				'groups.group_id = fields.group_id',
				'right'
			)
			->where('channels.site_id', $siteId)
			->group_by('fields.field_id')
			->order_by('
				groups.group_name ASC,
				fields.field_label ASC
			')
			->get()
			->result();

		return $results;
	}

	/**
	 * Get field group ID from channel ID
	 *
	 * @param int $channelId
	 * @return string
	 */
	public function getFieldGroupId($channelId) {
		return $this->db->select('field_group')
			->from('channels')
			->where('channels.channel_id', $channelId)
			->get()
			->row('field_group');
	}
}