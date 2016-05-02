<?php

Class Migration_Add_receiving_tax extends Ci_Migration
{

	public function up()
	{
		$fields = array(
				'tax_amount' => array('type' => 'numeric'),
				
				
		);
		//Let's firt check if the field exists
		if (!$this->db->field_exists('tax_amount', 'receivings'))
		{    		// addding the field
		 $this->dbforge->add_column('receivings', $fields);
		}
		
		$fields2=array('received_amount' => array('type' => 'numeric'),);
		
		//Let's firt check if the field exists
		if (!$this->db->field_exists('received_amount', 'receivings'))
		{    		// addding the field
		      $this->dbforge->add_column('receivings', $fields2);
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('receivings', 'tax_amount');
	}
}