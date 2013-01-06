<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
*
*/
class Usuarios extends CI_Model {
	var $EE;

	//Config
	private $_table_name = "members";

	function __construct() {
		$this->EE =& get_instance();
	}
	
	function get_grupos_usuario( $member_ids ) {
		
		if ( ! is_array($member_ids))
		{
			$member_ids = array($member_ids);
		}

		$this->EE->db->select("group_id");
		$this->EE->db->from("members");
		$this->EE->db->where_in("member_id", $member_ids);
		
		$groups = $this->db->get();
		
		if ($groups->num_rows() > 0)
		{
			foreach($groups->result() as $group)
			{
				$group_ids[] = $group->group_id;
			}
		}
		
		$group_ids = array_unique($group_ids);

		return $group_ids;
		
	}
}