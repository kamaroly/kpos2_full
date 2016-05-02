<?php
Class Migration_Add_tin extends Ci_Migration
{
	
	public function up()
	{
		$fields = array(
				'tin' => array('type' => 'TEXT')
		);
		
		//Let's firt check if the field exists
		if (!$this->db->field_exists('tin', 'people'))
		{    		// Adding the field
		$this->dbforge->add_column('people', $fields,TRUE);
		}
	}
	
	public function down()
	{
		$this->dbforge->drop_column('people', 'tin');
	}
}