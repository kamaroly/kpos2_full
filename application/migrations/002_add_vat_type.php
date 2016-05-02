<?php

Class Migration_Add_vat_type extends Ci_Migration
{

	public function up()
	{
		$fields = array(
				'vat_type' => array('type' => 'TEXT')
		);
		//Let's firt check if the field exists
		if (!$this->db->field_exists('vat_type', 'items'))
		{    		// addding the field
		 $this->dbforge->add_column('items', $fields);
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('items', 'vat_type');
	}
}