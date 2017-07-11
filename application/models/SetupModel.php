<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetupModel extends CI_Model{

  private $attributes = array('ENGINE' => 'InnoDB');

  private $offset = 0;

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->dbforge();

  }

  public function CreateRefTables()
  {
    // Table Ref Object Type

    $fields = array(
      'code' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => '',
      ),
      'name' => array(
        'type'       =>'VARCHAR',
        'constraint' => '15',
        'default'    => '',
      ),
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('code', TRUE);
    $this->dbforge->create_table($this->config->item('tblRefObj'),TRUE,$this->attributes);
    // -------------------------------------------
    // Table Ref Relation Type
    $fields = array(
      'code' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => '',
      ),
      'top' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => '',
      ),
      'bottom' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => '',
      ),
      'description' => array(
        'type'       =>'VARCHAR',
        'constraint' => '120',
        'default'    => '',
      ),
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('code', TRUE);
    $this->dbforge->create_table($this->config->item('tblRefRel'),TRUE,$this->attributes);

    // -------------------------------------------


    // Table Ref Status
    $fields = array(
      'code' => array(
        'type'       =>'VARCHAR',
        'constraint' => '5',
        'default'    => '',
      ),
      'name' => array(
        'type'       =>'VARCHAR',
        'constraint' => '30',
        'default'    => '',
      ),
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('code', TRUE);
    $this->dbforge->create_table($this->config->item('tblRefStat'),TRUE,$this->attributes);
    $data = array(
      array(
        'code' => $this->config->item('statDraf'),
        'name' => 'Draf',
      ),
      array(
        'code' => $this->config->item('statPending'),
        'name' => 'Pending',
      ),
      array(
        'code' => $this->config->item('statReject'),
        'name' => 'Rejecte',
      ),
      array(
        'code' => $this->config->item('statApprove'),
        'name' => 'Approve',
      ),
    );
    $this->db->insert_batch($this->config->item('tblRefStat'), $data);
  }

  public function CreateTables()
  {
    $genField = array(
      'id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE
      ),
      'begin_date' => array(
        'type'    => 'DATE',
        'default' => '2000-01-01',
      ),
      'end_date' => array(
        'type'    => 'DATE',
        'default' => '9999-01-01',
      ),
      'is_delete' => array(
        'type'       => 'TINYINT',
        'constraint' => 1,
        'default'    => '0',
      ),
      'create_by' => array(
        'type'    =>'INT',
        'default' => '0',
      ),
      'create_time' => array(
        'type'    =>'DATETIME',
      ),
      'update_by' => array(
        'type'    =>'INT',
        'default' => '0',
      ),
      'timestamp' => array(
        'type'    =>'timestamp',
      ),
    );

    // Table Object
    $fields = array(
      'type' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => $this->config->item('objPost'),
        'after'      => 'id',

      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->config->item('tblObj'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblObj'),$fields);

    // -------------------------------------------
    // Table attributes
    $fields = array(
      'obj_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
        'after'      => 'id',
        'unsigned'   => TRUE,
      ),
      'name' => array(
        'type'       =>'VARCHAR',
        'constraint' => '150',
        'default'    => '',
        'after'      => 'obj_id',
      ),
      'short_name' => array(
        'type'       =>'VARCHAR',
        'constraint' => '15',
        'default'    => NULL,
        'after'      => 'name',
      ),
      'description' => array(
        'type'       =>'TEXT',
        'default'    => '',
        'after'      => 'short_name',
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->config->item('tblAttr'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblAttr'),$fields);

    // -------------------------------------------
    // Table Relation
    $fields = array(
      'rel_code' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'after'      => 'id',
      ),
      'obj_top_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
        'after'      => 'rel_code',
        'unsigned'   => TRUE,
      ),
      'obj_bottom_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
        'after'      => 'obj_top_id',
        'unsigned'   => TRUE,
      ),
      'weight' => array(
        'type'       =>'DECIMAL',
        'constraint' => '8,2',
        'after'      => 'obj_bottom_id',
        'default'    => 100,
      ),
    );

    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->config->item('tblRel'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblRel'),$fields);
    // -------------------------------------------

  }

  public function DropTables()
  {
    $this->dbforge->drop_table($this->config->item('tblRel'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblAttr'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblObj'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblRefObj'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblRefRel'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblRefStat'),TRUE);

  }

}
