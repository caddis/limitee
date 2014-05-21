<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Limitee Model
 *
 * @package Limitee
 * @author  Caddis
 * @link    http://www.caddis.co
 */

class Limitee_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_fields($site_id)
	{	
		$results = $this->db->select('C.channel_id, CF.field_id, CF.field_label, CF.field_maxl, CF.site_id, CF.field_type, FG.group_name, FG.group_id')
			->from('channels C')
			->join('field_groups FG', 'FG.group_id = C.field_group')
			->join('channel_fields CF', 'FG.group_id = CF.group_id', 'right')
			->where('C.site_id', $site_id)
			->get();

		return $results->result();
	}
}