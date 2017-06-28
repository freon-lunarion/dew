<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetupModel extends CI_Model{

  private $tblObj     = 'obj';
  private $tblAttr    = 'obj_attribute';
  private $tblRel     = 'obj_rel';

  private $tblRefObj  = 'ref_obj_type';
  private $tblRefRel  = 'ref_obj_rel';

  private $tblYTD     = 'sc_ytd';
  private $tblKpi     = 'sc_kpi_attr';

  private $tblMeasure = 'sc_measurement_attr';
  private $tblFormula = 'sc_formula_attr';
  private $tblScore   = 'sc_formula_score';

  private $tblTargetS = 'sc_target_set';
  private $tblTargetD = 'sc_target_detail';
  private $tblTargetR = 'sc_target_rel';

  private $attributes = array('ENGINE' => 'InnoDB');

  private $objOrg      = 'ORG';
  private $objPost     = 'POS';
  private $objJob      = 'JOB';
  private $objEmployee = 'EMP';

  private $objSC     = 'SC';
  private $objSO     = 'SO';
  private $objKPI    = 'KPI';
  private $objPersp  = 'PRS';

  private $objFormula     = 'FRM';
  private $objMeasurement = 'MEA';

  // Relation Code (Ref to ref_obj_rel)
  private $relStruct = '111';
  private $relReport = '112';

  private $relAssign = '121';
  private $relChief  = '122';

  private $relHold   = '131';
  private $relJob    = '141';

  private $relScOrg  = '201';
  private $relScJob  = '202';
  private $relScPos  = '203';

  private $relSoSc   = '211';
  private $relSoPer  = '212';

  private $relKpiSc  = '221';
  private $relKpiSo  = '222';


  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->dbforge();

  }

  public function CreateRefTable()
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
    $this->dbforge->create_table($this->tblRefObj,TRUE,$this->attributes);
    $data = array(
      array(
        'code' => $this->objEmployee,
        'name' => 'Employee',
      ),
      array(
        'code' => $this->objJob,
        'name' => 'Job',
      ),
      array(
        'code' => $this->objOrg,
        'name' => 'Organization',
      ),
      array(
        'code' => $this->objPost,
        'name' => 'Position',
      ),
      array(
        'code' => $this->objSC,
        'name' => 'Score Card',
      ),
      array(
        'code' => $this->objSO,
        'name' => 'Strategic Objective',
      ),
      array(
        'code' => $this->objKPI,
        'name' => 'Key Performance Indicator',
      ),
      array(
        'code' => $this->objPersp,
        'name' => 'Perspective',
      ),
      array(
        'code' => $this->objFormula,
        'name' => 'Formula',
      ),
      array(
        'code' => $this->objMeasurement,
        'name' => 'Measurement Unit',
      ),
    );
    $this->db->insert_batch($this->tblRefObj, $data);
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
    $this->dbforge->create_table($this->tblRefRel,TRUE,$this->attributes);

    $data = array(
      array(
        'code'        => $this->relStruct,
        'top'         => $this->objOrg,
        'bottom'      => $this->objOrg,
        'description' => 'Organization Structure',
      ),
      array(
        'code'        => $this->relReport,
        'top'         => $this->objPost,
        'bottom'      => $this->objPost,
        'description' => 'Reporting Structure',
      ),
      array(
        'code'        => $this->relAssign,
        'top'         => $this->objOrg,
        'bottom'      => $this->objPost,
        'description' => 'Position Assignment to Organization',
      ),
      array(
        'code'        => $this->relChief,
        'top'         => $this->objOrg,
        'bottom'      => $this->objPost,
        'description' => 'Chief of Organization',
      ),
      array(
        'code'        => $this->relHold,
        'top'         => $this->objPost,
        'bottom'      => $this->objEmployee,
        'description' => 'Employee Assignment to a position',
      ),
      array(
        'code'        => $this->relJob,
        'top'         => $this->objJob,
        'bottom'      => $this->objPost,
        'description' => 'Associating position with a job',
      ),
      array(
        'code'        => $this->relScJob,
        'top'         => $this->objSC,
        'bottom'      => $this->objJob,
        'description' => 'ScoreCard of Job (Template)',
      ),
      array(
        'code'        => $this->relScOrg,
        'top'         => $this->objSC,
        'bottom'      => $this->objOrg,
        'description' => 'ScoreCard of Organization',
      ),
      array(
        'code'        => $this->relScPos,
        'top'         => $this->objSC,
        'bottom'      => $this->objPost,
        'description' => 'ScoreCard of Position',
      ),
      array(
        'code'        => $this->relSoSc,
        'top'         => $this->objSO,
        'bottom'      => $this->objSC,
        'description' => 'Strategic Objective related with ScoreCard',
      ),
      array(
        'code'        => $this->relSoPer,
        'top'         => $this->objSO,
        'bottom'      => $this->objPersp,
        'description' => 'Strategic Objective related with Perspective',
      ),
      array(
        'code'        => $this->relKpiSc,
        'top'         => $this->objKPI,
        'bottom'      => $this->objSC,
        'description' => 'KPI related with ScoreCard',
      ),
      array(
        'code'        => $this->relKpiSo,
        'top'         => $this->objKPI,
        'bottom'      => $this->objSO,
        'description' => 'KPI related with Strategic Objective',
      ),
    );
    $this->db->insert_batch($this->tblRefRel, $data);
    // -------------------------------------------
  }

  public function CreateTable()
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
        'default'    => $this->objPost,
        'after'      => 'id',

      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblObj,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblObj,$fields);

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
    $this->dbforge->create_table($this->tblAttr,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblAttr,$fields);

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
    $this->dbforge->create_table($this->tblRel,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblRel,$fields);
    // -------------------------------------------

    $data = array(
      'type'        => $this->objOrg,
      'begin_date'  => '2000-01-01',
      'end_date'    => '9999-12-31',
      'create_time' => date('Y-m-d H:i:s'),
      'timestamp'   => date('Y-m-d H:i:s'),
    );
    $this->db->insert($this->tblObj, $data);
    $data = array(
      'obj_id'      => 1,
      'name'        => 'Holding Company',
      'begin_date'  => '2000-01-01',
      'end_date'    => '9999-12-31',
      'create_time' => date('Y-m-d H:i:s'),
      // 'timestamp'   => date('Y-m-d H:i:s'),
    );
    $this->db->insert($this->tblAttr, $data);
  }

  public function CreateScTable()
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
    // Measurement unit
    $measureFields = array(
      'measurement_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => TRUE,
        'after'      => 'id'
      ),
      'has_min' => array(
        'type'       => 'BOOL',
        'default'    => 0,
        'after'      => 'measurement_id'
      ),
      'has_max' => array(
        'type'       => 'BOOL',
        'default'    => 0,
        'after'      => 'has_min'
      ),
      'min_value' => array(
        'type'       => 'DECIMAL',
        'constraint' => '8,2',
        'default'    => 0,
        'after'      => 'has_max'
      ),
      'max_value' => array(
        'type'       => 'DECIMAL',
        'constraint' => '8,2',
        'default'    => 0,
        'after'      => 'min_value'
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblMeasure,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblMeasure,$measureFields);
    // -------------------------------------------------------------------------
    // Formula
    $formulaFields = array(
      'formula_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => TRUE,
        'after'      => 'id'
      ),
      'type' => array(
        'type' => 'VARCHAR',
        'constraint' => 3,
        'after'      => 'formula_id'
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblFormula,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblFormula,$formulaFields);



    // Formula Score
    $scoreFields = array(
      'formula_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => TRUE,
        'after'      => 'id'
      ),
      'value' => array(
        'type'       => 'INT',
        'constraint' => 5,
        'unsigned'   => TRUE,
        'after'      => 'formula_id'
      ),
      'lower_bound' => array(
        'type'       => 'DECIMAL',
        'constraint' => '8,2',
        'after'      => 'value'
      ),
      'upper_bound' => array(
        'type'       => 'DECIMAL',
        'constraint' => '8,2',
        'after'      => 'lower_bound'
      ),

    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblScore,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblScore,$scoreFields);

    // -------------------------------------------------------------------------
    // YTD
    $ytdFields = array(
      'code' => array(
        'type' => 'VARCHAR',
        'constraint' => 3,
      ),
      'name' => array(
        'type' => 'VARCHAR',
        'constraint' => 150,
      ),
    );
    $this->dbforge->add_field($ytdFields);
    $this->dbforge->add_key('code', TRUE);
    $this->dbforge->create_table($this->tblYTD,TRUE,$this->attributes);

    // -------------------------------------------------------------------------
    // KPI Attr
    $kpiFields = array(
      'kpi_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => TRUE,
        'after'      => 'id'
      ),
      'formula_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => TRUE,
        'after'      => 'kpi_id'

      ),
      'measurement_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => TRUE,
        'after'      => 'formula_id'

      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblKpi,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblKpi,$kpiFields);
    // -------------------------------------------------------------------------
    // TARGET SET
    $targetSetFields = array(
      'type' => array(
        'type'       => 'VARCHAR',
        'after'      => 'id',
        'constraint' => 1,
      ),
      'ytd_code' => array(
        'type'       => 'VARCHAR',
        'constraint' => 3,
        'after'      => 'type'
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblTargetS,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblTargetS,$targetSetFields);

    // TARGET DETAIL
    $targetDetailFields = array(
      'target_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'after'      => 'id',
        'unsigned'   => TRUE
      ),
      'period' => array(
        'type'       => 'VARCHAR',
        'after'      => 'target_id',
        'constraint' => 3,
      ),
      'value' => array(
        'type'       => 'DECIMAL',
        'after'      => 'period',
        'constraint' => '8,2',
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblTargetD,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblTargetD,$targetDetailFields);

    // TARGET Relation
    $targetRelFields = array(
      'target_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'after'      => 'id',
        'unsigned'   => TRUE
      ),
      'kpi_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'after'      => 'target_id',
        'unsigned'   => TRUE
      ),
      'ext_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'after'      => 'kpi_id',
        'unsigned'   => TRUE
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblTargetR,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblTargetR,$targetRelFields);
    // -------------------------------------------

  }

  public function SCRecords()
  {
    $begin = '2000-01-01';
    $end   = '9999-12-31';
    // ytd
    $data = array(
      array(
        'code' => 'SUM',
        'name' => 'Total',
      ),
      array(
        'code' => 'AVG',
        'name' => 'Average',
      ),
      array(
        'code' => 'LST',
        'name' => 'Last',
      ),
      array(
        'code' => 'MIN',
        'name' => 'Minimum',
      ),
      array(
        'code' => 'MAX',
        'name' => 'Maximum',
      ),
    );
    $this->db->insert_batch($this->tblYTD, $data);
    // Measurement unit
    $dataSet = array(
      array(
        'name'    => 'Rupiah',
        'short'   => 'IDR',
        'descr'   => 'Indonesian Rupiah',
        'has_min' => 1,
        'min_val' => 0,
        'has_max' => 0,
        'max_val' => 0,
      ),
      array(
        'name'    => 'Dollar',
        'short'   => 'USD',
        'descr'   => 'United State Dollar',
        'has_min' => 1,
        'has_max' => 0,
        'min_val' => 0,
        'max_val' => 0,
      ),
      array(
        'name'    => 'Progress',
        'short'   => '%',
        'descr'   => 'Percentage of Progress',
        'has_min' => 1,
        'has_max' => 1,
        'min_val' => 0,
        'max_val' => 100,
      ),
      array(
        'name'    => 'Percentage',
        'short'   => '%',
        'descr'   => 'Percentage',
        'has_min' => 0,
        'has_max' => 0,
        'min_val' => 0,
        'max_val' => 0,
      ),
      array(
        'name'    => 'Quantity',
        'short'   => 'Qty',
        'descr'   => 'Quantity',
        'has_min' => 0,
        'has_max' => 0,
        'min_val' => 0,
        'max_val' => 0,
      ),
      array(
        'name'    => 'Scale',
        'short'   => '1-5',
        'descr'   => 'Scale from 1 to 5',
        'has_min' => 0,
        'has_max' => 0,
        'min_val' => 0,
        'max_val' => 0,
      ),
    );
    foreach ($dataSet as $row) {
      $data = array(
        'type'        => $this->objFormula,
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->tblObj, $data);
      $this->db->select('MAX(id) as id');
      $objId = $this->db->get($this->tblObj)->row()->id;

      $data = array(
        'obj_id'      => $objId,
        'name'        => $row['name'],
        'short_name'  => $row['short'],
        'description' => $row['descr'],
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->tblAttr, $data);

      $data = array(
        'measurement_id' => $objId,
        'has_min'        => $row['has_min'],
        'has_max'        => $row['has_max'],
        'min_value'      => $row['min_val'],
        'max_value'      => $row['max_val'],
        'begin_date'     => $begin,
        'end_date'       => $end,
        'create_time'    => date('Y-m-d H:i:s'),
      );

      $this->db->insert($this->tblMeasure, $data);
    }
    // Formula
    $dataSet = array(
      array(
        'name'    => 'Default Max.',
        'short'   => '0MAX',
        'type'    => 'MAX',
        'score'   => array(
          array('value' => 1, 'lower'=> -999999.99, 'upper' => 69.99),
          array('value' => 2, 'lower'=> 70.00, 'upper' => 94.99),
          array('value' => 3, 'lower'=> 95.00, 'upper' => 114.99),
          array('value' => 4, 'lower'=> 115.00, 'upper' => 129.99),
          array('value' => 5, 'lower'=> 130.00, 'upper' => 999999.99),
        ),
      ),
      array(
        'name'    => 'Default Min.',
        'short'   => '0MIN',
        'type'    => 'MIN',
        'score'   => array(
          array('value' => 5, 'lower'=> -999999.99, 'upper' => 69.99),
          array('value' => 4, 'lower'=> 70.00, 'upper' => 94.99),
          array('value' => 3, 'lower'=> 95.00, 'upper' => 114.99),
          array('value' => 2, 'lower'=> 115.00, 'upper' => 129.99),
          array('value' => 1, 'lower'=> 130.00, 'upper' => 999999.99),
        ),
      ),
      array(
        'name'    => 'Default Stabilize',
        'short'   => '0STAB',
        'type'    => 'STA',
        'score'   => array(
          array('value' => 1, 'lower'=> 200.01, 'upper' => 999999.99),
          array('value' => 2, 'lower'=> 100.01, 'upper' => 200.00),
          array('value' => 3, 'lower'=> -100.00, 'upper' => 100.00),
          array('value' => 2, 'upper'=> -100.01, 'lower' => -200.00),
          array('value' => 1, 'upper'=> -200.01, 'lower' => -999999.99),

        ),
      ),
    );
    foreach ($dataSet as $row) {
      $data = array(
        'type'        => $this->objFormula,
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->tblObj, $data);
      $this->db->select('MAX(id) as id');
      $objId = $this->db->get($this->tblObj)->row()->id;

      $data = array(
        'obj_id'      => $objId,
        'name'        => $row['name'],
        'short_name'  => $row['short'],
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->tblAttr, $data);

      $data = array(
        'formula_id'  => $objId,
        'type'        => $row['type'],
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );

      $this->db->insert($this->tblFormula, $data);

      foreach ($row['score'] as $score) {
        $data = array(
          'formula_id'  => $objId,
          'value'       => $score['value'],
          'lower_bound' => $score['lower'],
          'upper_bound' => $score['upper'],
          'begin_date'  => $begin,
          'end_date'    => $end,
          'create_time' => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->tblScore, $data);
      }

    }

  }
  public function InsertDemoRecords()
  {
    $begin = '2000-01-01';
    $end   = '9999-12-31';
    // Object
    $data = array(
      $this->objOrg => array('min' => 2, 'max' => 8),
      $this->objJob => array('min' => 9, 'max' => 18),
      $this->objPost => array('min' => 19, 'max' => 50),
      $this->objEmployee => array('min' => 51, 'max' => 91),
    );
    $offset = $data[$this->objOrg]['min'];
    $set    = array(
      'Business Division',
      'Support Division',
      'Production Department',
      'Sales & Marketing Department',
      'Finance Department',
      'Human Resources Department',
      'Facility Department',
      'Top Managerial',
      'Middle Managerial',
      'Line Manager',
      'Secretary',
      'Designer',
      'Engineer',
      'Operator',
      'Clerk',
      'Officer',
      'Analyst',
      'CEO',
      'Business GM',
      'Supporting GM',
      'Production Manager',
      'Sales & Marketing Manager',
      'Finance Manager',
      'Human Resources Manager',
      'Facility Manager',
      'Secretary',
      'Secretary',
      'Secretary',
      'Industrial Designer',
      'Tech Designer',
      'Machine Operator',
      'Machine Operator',
      'Machine Operator',
      'Multimedia Designer',
      'Sales Executive',
      'Sales Executive',
      'Sales Executive',
      'Sales Executive',
      'Market Analyst',
      'Accountant',
      'Accountant',
      'Accountant',
      'Administrative Assistant',
      'Administrative Assistant',
      'HR Officer',
      'HR Officer',
      'Administrative Assistant',
      'Administrative Assistant',
      'IT Staff',
      'Myrta Deas',
      'Dann Bybee',
      'Aretha Deherrera',
      'Shondra Eggen',
      'Miranda Rodreguez',
      'Tobias Choice',
      'Bert Shear',
      'Raymonde Bultman',
      'Brianna Bissonette',
      'Adrianna Coy',
      'Jim Mento',
      'Alex Capote',
      'Marty Sowders',
      'Santina Ruland',
      'Gilberte Boedeker',
      'Refugio Mickel',
      'Chia Altamirano',
      'Mercedez Perrella',
      'Paola Acord',
      'Carlo Chmura',
      'Zulema Goldstein',
      'Adell Dickerson',
      'Thelma Boulanger',
      'Cherly Broman',
      'Delmer Segarra',
      'Jayna Verdejo',
      'Solomon Pietila',
      'Lala Calvert',
      'Eldora Mccall',
      'Dalila Scot',
      'John Smith',
      'Ira Granada',
      'Horace Carico',
      'Kayla Soules',
      'Vince Mitcham',
      'Alesha Roder',
      'Yuri Pool',
      'Daphne Howes',
      'Cristal Sanders',
      'Georgianne Caswell',
      'India Levay',
    );
    foreach ($data as $objType => $value) {
      $min = $value['min'];
      $max = $value['max'];
      for ($j=$min; $j <= $max ; $j++) {
        $data = array(
          'type'        => $objType,
          'begin_date'  => $begin,
          'end_date'    => $end,
          'create_time' => date('Y-m-d H:i:s'),
          // 'timestamp'   => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->tblObj, $data);
        $data = array(
          'obj_id'      => $j,
          'name'        => $set[$j-$offset],
          'begin_date'  => $begin,
          'end_date'    => $end,
          'create_time' => date('Y-m-d H:i:s'),
          // 'timestamp'   => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->tblAttr, $data);
      }
    }

    $set = array(
      array($this->relStruct, 1, 2 ),
      array($this->relStruct, 1, 3 ),
      array($this->relStruct, 2, 4 ),
      array($this->relStruct, 2, 5 ),
      array($this->relStruct, 3, 6 ),
      array($this->relStruct, 3, 7 ),
      array($this->relStruct, 3, 8 ),
      array($this->relReport, 19, 20 ),
      array($this->relReport, 19, 21 ),
      array($this->relReport, 19, 27 ),
      array($this->relReport, 20, 22 ),
      array($this->relReport, 20, 23 ),
      array($this->relReport, 20, 28 ),
      array($this->relReport, 21, 24 ),
      array($this->relReport, 21, 25 ),
      array($this->relReport, 21, 26 ),
      array($this->relReport, 21, 29 ),
      array($this->relReport, 22, 30 ),
      array($this->relReport, 22, 31 ),
      array($this->relReport, 22, 32 ),
      array($this->relReport, 22, 33 ),
      array($this->relReport, 22, 34 ),
      array($this->relReport, 23, 35 ),
      array($this->relReport, 23, 36 ),
      array($this->relReport, 23, 37 ),
      array($this->relReport, 23, 38 ),
      array($this->relReport, 23, 39 ),
      array($this->relReport, 23, 30 ),
      array($this->relReport, 24, 41 ),
      array($this->relReport, 24, 42 ),
      array($this->relReport, 24, 43 ),
      array($this->relReport, 24, 44 ),
      array($this->relReport, 24, 45 ),
      array($this->relReport, 25, 46 ),
      array($this->relReport, 25, 47 ),
      array($this->relReport, 25, 48 ),
      array($this->relReport, 26, 49 ),
      array($this->relReport, 26, 50 ),
      array($this->relAssign, 1, 19 ),
      array($this->relChief, 1, 19 ),
      array($this->relAssign, 2, 20 ),
      array($this->relChief, 2, 20 ),
      array($this->relAssign, 3, 21 ),
      array($this->relChief, 3, 21 ),
      array($this->relAssign, 4, 22 ),
      array($this->relChief, 4, 22 ),
      array($this->relAssign, 5, 23 ),
      array($this->relChief, 5, 23 ),
      array($this->relAssign, 6, 24 ),
      array($this->relChief, 6, 24 ),
      array($this->relAssign, 7, 25 ),
      array($this->relChief, 7, 25 ),
      array($this->relAssign, 8, 26 ),
      array($this->relChief, 8, 26 ),
      array($this->relAssign, 1, 27 ),
      array($this->relAssign, 2, 28 ),
      array($this->relAssign, 3, 29 ),
      array($this->relAssign, 4, 30 ),
      array($this->relAssign, 4, 31 ),
      array($this->relAssign, 4, 32 ),
      array($this->relAssign, 4, 33 ),
      array($this->relAssign, 4, 34 ),
      array($this->relAssign, 5, 35 ),
      array($this->relAssign, 5, 36 ),
      array($this->relAssign, 5, 37 ),
      array($this->relAssign, 5, 38 ),
      array($this->relAssign, 5, 39 ),
      array($this->relAssign, 5, 40 ),
      array($this->relAssign, 6, 41 ),
      array($this->relAssign, 6, 42 ),
      array($this->relAssign, 6, 43 ),
      array($this->relAssign, 6, 44 ),
      array($this->relAssign, 6, 45 ),
      array($this->relAssign, 7, 46 ),
      array($this->relAssign, 7, 47 ),
      array($this->relAssign, 7, 48 ),
      array($this->relAssign, 8, 49 ),
      array($this->relAssign, 8, 50 ),
      array($this->relHold, 19, 51 ),
      array($this->relHold, 20, 52 ),
      array($this->relHold, 21, 53 ),
      array($this->relHold, 22, 54 ),
      array($this->relHold, 23, 55 ),
      array($this->relHold, 24, 56 ),
      array($this->relHold, 25, 57 ),
      array($this->relHold, 26, 58 ),
      array($this->relHold, 27, 59 ),
      array($this->relHold, 28, 60 ),
      array($this->relHold, 29, 61 ),
      array($this->relHold, 30, 62 ),
      array($this->relHold, 31, 63 ),
      array($this->relHold, 32, 64 ),
      array($this->relHold, 33, 65 ),
      array($this->relHold, 34, 66 ),
      array($this->relHold, 35, 67 ),
      array($this->relHold, 36, 68 ),
      array($this->relHold, 37, 69 ),
      array($this->relHold, 38, 70 ),
      array($this->relHold, 39, 71 ),
      array($this->relHold, 40, 72 ),
      array($this->relHold, 41, 73 ),
      array($this->relHold, 42, 74 ),
      array($this->relHold, 43, 75 ),
      array($this->relHold, 44, 76 ),
      array($this->relHold, 45, 77 ),
      array($this->relHold, 46, 78 ),
      array($this->relHold, 47, 79 ),
      array($this->relHold, 48, 80 ),
      array($this->relHold, 49, 81 ),
      array($this->relHold, 50, 82 ),
      array($this->relJob, 9, 19 ),
      array($this->relJob, 10, 20 ),
      array($this->relJob, 10, 21 ),
      array($this->relJob, 11, 22 ),
      array($this->relJob, 11, 23 ),
      array($this->relJob, 11, 24 ),
      array($this->relJob, 11, 25 ),
      array($this->relJob, 11, 26 ),
      array($this->relJob, 12, 27 ),
      array($this->relJob, 12, 28 ),
      array($this->relJob, 12, 29 ),
      array($this->relJob, 13, 30 ),
      array($this->relJob, 13, 35 ),
      array($this->relJob, 14, 31 ),
      array($this->relJob, 14, 50 ),
      array($this->relJob, 15, 32 ),
      array($this->relJob, 15, 33 ),
      array($this->relJob, 15, 34 ),
      array($this->relJob, 16, 44 ),
      array($this->relJob, 16, 45 ),
      array($this->relJob, 16, 48 ),
      array($this->relJob, 16, 49 ),
      array($this->relJob, 17, 37 ),
      array($this->relJob, 17, 38 ),
      array($this->relJob, 17, 39 ),
      array($this->relJob, 17, 41 ),
      array($this->relJob, 17, 42 ),
      array($this->relJob, 17, 43 ),
      array($this->relJob, 17, 46 ),
      array($this->relJob, 17, 47 ),
      array($this->relJob, 18, 40 ),
    );
    foreach ($set as $key => $value) {
      $data = array(
        'rel_code'      => $value[0],
        'obj_top_id'    => $value[1],
        'obj_bottom_id' => $value[2],
        'begin_date'    => $begin,
        'end_date'      => $end,
        'create_time'   => date('Y-m-d H:i:s'),
        // 'timestamp'     => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->tblRel, $data);

    }
  }

  public function DropTable()
  {
    $this->dbforge->drop_table($this->tblRel,TRUE);
    $this->dbforge->drop_table($this->tblAttr,TRUE);
    $this->dbforge->drop_table($this->tblObj,TRUE);
    $this->dbforge->drop_table($this->tblRefObj,TRUE);
    $this->dbforge->drop_table($this->tblRefRel,TRUE);

    $this->dbforge->drop_table($this->tblFormula,TRUE);
    $this->dbforge->drop_table($this->tblScore,TRUE);
    $this->dbforge->drop_table($this->tblMeasure,TRUE);
    $this->dbforge->drop_table($this->tblYTD,TRUE);

    $this->dbforge->drop_table($this->tblKpi,TRUE);
    $this->dbforge->drop_table($this->tblTargetD,TRUE);
    $this->dbforge->drop_table($this->tblTargetS,TRUE);
    $this->dbforge->drop_table($this->tblTargetR,TRUE);
  }

}
