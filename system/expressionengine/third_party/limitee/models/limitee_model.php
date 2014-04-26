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
		$results = $this->db->select('CF.field_id, CF.field_label, CF.field_maxl, CF.site_id, CF.field_type, FG.group_name, FG.group_id')
			->from('channel_fields CF')
			->join('field_groups FG', 'FG.group_id = CF.group_id')
			->where('CF.site_id', $site_id)
			->where_in('CF.field_type', array('text', 'textarea'))
			->get()
			->result();

		return $results;
	}
}