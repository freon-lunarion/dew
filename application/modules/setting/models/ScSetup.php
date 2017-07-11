<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScSetup extends CI_Model{
  private $attributes = array('ENGINE' => 'InnoDB');

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function InsertRefRecords()
  {
    $data = array(
      array(
        'code' => $this->config->item('objSC'),
        'name' => 'Score Card',
      ),
      array(
        'code' => $this->config->item('objSO'),
        'name' => 'Strategic Objective',
      ),
      array(
        'code' => $this->config->item('objKPI'),
        'name' => 'Key Performance Indicator',
      ),
      array(
        'code' => $this->config->item('objPersp'),
        'name' => 'Perspective',
      ),
      array(
        'code' => $this->config->item('objFormula'),
        'name' => 'Formula',
      ),
      array(
        'code' => $this->config->item('objMeasurement'),
        'name' => 'Measurement Unit',
      ),
    );
    $this->db->insert_batch($this->config->item('tblRefObj'), $data);
    $data = array(
      array(
        'code'        => $this->config->item('relJobSc'),
        'top'         => $this->config->item('objJob'),
        'bottom'      => $this->config->item('objSC'),
        'description' => 'ScoreCard of Job (Template)',
      ),
      array(
        'code'        => $this->config->item('relOrgSc'),
        'top'         => $this->config->item('objOrg'),
        'bottom'      => $this->config->item('objSC'),
        'description' => 'ScoreCard of Organization',
      ),
      array(
        'code'        => $this->config->item('relPosSc'),
        'top'         => $this->config->item('objPost'),
        'bottom'      => $this->config->item('objSC'),
        'description' => 'ScoreCard of Position',
      ),
      array(
        'code'        => $this->config->item('relScSo'),
        'top'         => $this->config->item('objSC'),
        'bottom'      => $this->config->item('objSO'),
        'description' => 'Strategic Objective related with ScoreCard',
      ),
      array(
        'code'        => $this->config->item('relPerSo'),
        'top'         => $this->config->item('objPersp'),
        'bottom'      => $this->config->item('objSO'),
        'description' => 'Strategic Objective related with Perspective',
      ),
      array(
        'code'        => $this->config->item('relScKpi'),
        'top'      => $this->config->item('objSC'),
        'bottom'      => $this->config->item('objKPI'),
        'description' => 'KPI related with ScoreCard',
      ),
      array(
        'code'        => $this->config->item('relSoKpi'),
        'top'      => $this->config->item('objSO'),
        'bottom'      => $this->config->item('objKPI'),
        'description' => 'KPI related with Strategic Objective',
      ),
    );
    $this->db->insert_batch($this->config->item('tblRefRel'), $data);
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
    $this->dbforge->create_table($this->config->item('tblMeasure'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblMeasure'),$measureFields);
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
    $this->dbforge->create_table($this->config->item('tblFormula'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblFormula'),$formulaFields);

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
      'color' => array(
        'type'       => 'VARCHAR',
        'constraint' => '8',
        'after'      => 'upper_bound',
        'default'    => '#000000',
      ),

    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->config->item('tblScore'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblScore'),$scoreFields);

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
    $this->dbforge->create_table($this->config->item('tblYtd'),TRUE,$this->attributes);

    // -------------------------------------------------------------------------

    $scFields = array(
      'sc_id' => array(
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => TRUE,
        'after'      => 'id'
      ),
      'status_code' => array(
        'type'       => 'VARCHAR',
        'constraint' => 10,
        'after'      => 'sc_id'
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->config->item('tblScStatus'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblScStatus'),$scFields);
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
    $this->dbforge->create_table($this->config->item('tblKpi'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblKpi'),$kpiFields);
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
    $this->dbforge->create_table($this->config->item('tblTargetS'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblTargetS'),$targetSetFields);

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
    $this->dbforge->create_table($this->config->item('tblTargetD'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblTargetD'),$targetDetailFields);

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
    $this->dbforge->create_table($this->config->item('tblTargetR'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblTargetR'),$targetRelFields);
    // -------------------------------------------------------------------------

    // score (main)
    $scoreMainFields = array(
      'value' => array(
        'type'       => 'INT',
        'constraint' => 5,
        'unsigned'   => TRUE,
        'after'      => 'id'
      ),
      'category' => array(
        'type'       => 'VARCHAR',
        'constraint' => 50,
        'unsigned'   => TRUE,
        'after'      => 'value'
      ),
      'lower_bound' => array(
        'type'       => 'DECIMAL',
        'constraint' => '8,2',
        'after'      => 'category'
      ),
      'upper_bound' => array(
        'type'       => 'DECIMAL',
        'constraint' => '8,2',
        'after'      => 'lower_bound'
      ),
      'color' => array(
        'type'       => 'VARCHAR',
        'constraint' => '8',
        'after'      => 'upper_bound',
        'default'    => '#000000',
      ),

    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->config->item('tblScoreMain'),TRUE,$this->attributes);
    $this->dbforge->add_column($this->config->item('tblScoreMain'),$scoreMainFields);
    // -------------------------------------------------------------------------

  }

  public function InsertIntialRecords()
  {
    $begin = '2000-01-01';
    $end   = '9999-12-31';
    // Perspective
    $dataSet = array(
      array(
        'name'  => 'Financial',
        'short' => 'FIN',
      ),
      array(
        'name'  => 'Business',
        'short' => 'BIZ',
      ),
      array(
        'name'  => 'Customer',
        'short' => 'CUS',
      ),
      array(
        'name'  => 'Growth',
        'short' => 'GRW',
      ),
    );
    foreach ($dataSet as $row) {
      $data = array(
        'type'        => $this->config->item('objPersp'),
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->config->item('tblObj'), $data);
      $this->db->select('MAX(id) as id');
      $objId = $this->db->get($this->config->item('tblObj'))->row()->id;

      $data = array(
        'obj_id'      => $objId,
        'name'        => $row['name'],
        'short_name'  => $row['short'],
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->config->item('tblAttr'), $data);
    }
    // -------------------------------------------------------------------------
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
    $this->db->insert_batch($this->config->item('tblYtd'), $data);
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
        'type'        => $this->config->item('objMeasurement'),
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->config->item('tblObj'), $data);
      $this->db->select('MAX(id) as id');
      $objId = $this->db->get($this->config->item('tblObj'))->row()->id;

      $data = array(
        'obj_id'      => $objId,
        'name'        => $row['name'],
        'short_name'  => $row['short'],
        'description' => $row['descr'],
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->config->item('tblAttr'), $data);

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

      $this->db->insert($this->config->item('tblMeasure'), $data);
    }
    // Formula
    $dataSet = array(
      array(
        'name'    => 'Default Max.',
        'short'   => '0MAX',
        'type'    => 'MAX',
        'score'   => array(
          array('value' => 1, 'lower'=> -999999.99, 'upper' => 69.99, 'color' => '#f56954'),
          array('value' => 2, 'lower'=> 70.00, 'upper' => 94.99, 'color' => '#f39c12'),
          array('value' => 3, 'lower'=> 95.00, 'upper' => 114.99, 'color' => '#00a65a'),
          array('value' => 4, 'lower'=> 115.00, 'upper' => 129.99, 'color' => '#00c0ef'),
          array('value' => 5, 'lower'=> 130.00, 'upper' => 999999.99, 'color' => '#3c8dbc'),
        ),
      ),
      array(
        'name'    => 'Default Min.',
        'short'   => '0MIN',
        'type'    => 'MIN',
        'score'   => array(
          array('value' => 5, 'lower'=> -999999.99, 'upper' => 69.99, 'color' => '#3c8dbc'),
          array('value' => 4, 'lower'=> 70.00, 'upper' => 94.99, 'color' => '#00c0ef'),
          array('value' => 3, 'lower'=> 95.00, 'upper' => 114.99, 'color' => '#00a65a'),
          array('value' => 2, 'lower'=> 115.00, 'upper' => 129.99, 'color' => '#f39c12'),
          array('value' => 1, 'lower'=> 130.00, 'upper' => 999999.99, 'color' => '#f56954'),
        ),
      ),
      array(
        'name'    => 'Default Stabilize',
        'short'   => '0STAB',
        'type'    => 'STA',
        'score'   => array(
          array('value' => 1, 'lower'=> 200.01, 'upper' => 999999.99, 'color' => '#f56954'),
          array('value' => 2, 'lower'=> 100.01, 'upper' => 200.00, 'color' => '#f39c12'),
          array('value' => 3, 'lower'=> -100.00, 'upper' => 100.00, 'color' => '#00a65a'),
          array('value' => 2, 'upper'=> -100.01, 'lower' => -200.00, 'color' => '#f39c12'),
          array('value' => 1, 'upper'=> -200.01, 'lower' => -999999.99, 'color' => '#f56954'),

        ),
      ),
    );
    foreach ($dataSet as $row) {
      $data = array(
        'type'        => $this->config->item('objFormula'),
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->config->item('tblObj'), $data);
      $this->db->select('MAX(id) as id');
      $objId = $this->db->get($this->config->item('tblObj'))->row()->id;

      $data = array(
        'obj_id'      => $objId,
        'name'        => $row['name'],
        'short_name'  => $row['short'],
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->config->item('tblAttr'), $data);

      $data = array(
        'formula_id'  => $objId,
        'type'        => $row['type'],
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );

      $this->db->insert($this->config->item('tblFormula'), $data);

      foreach ($row['score'] as $score) {
        $data = array(
          'formula_id'  => $objId,
          'value'       => $score['value'],
          'lower_bound' => $score['lower'],
          'upper_bound' => $score['upper'],
          'color'       => $score['color'],
          'begin_date'  => $begin,
          'end_date'    => $end,
          'create_time' => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->config->item('tblScore'), $data);
      }

    }

    // score

    $data = array(
      array(
        'value'       => 1,
        'category'    => 'Incompetent',
        'lower_bound' => 0.00,
        'upper_bound' => 1.60,
        'color'       => '#f56954',
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s')
      ),
      array(
        'value'       => 2,
        'category'    => 'Need Improvement',
        'lower_bound' => 1.61,
        'upper_bound' => 2.50,
        'color'       => '#f39c12',
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s')
      ),
      array(
        'value'       => 3,
        'category'    => 'Meet Expectation',
        'lower_bound' => 2.51,
        'upper_bound' => 3.50,
        'color'       => '#00a65a',
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s')
      ),
      array(
        'value'       => 4,
        'category'    => 'Above Expectation',
        'lower_bound' => 3.51,
        'upper_bound' => 4.50,
        'color'       => '#00c0ef',
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s')
      ),
      array(
        'value'       => 5,
        'category'    => 'Excelent',
        'lower_bound' => 4.51,
        'upper_bound' => 5.00,
        'color'       => '#3c8dbc',
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s')
      ),
    );
    $this->db->insert_batch($this->config->item('tblScoreMain'), $data);
    // -------------------------------------------------------------------------
    return ($objId - 1);
  }

  public function DropTables()
  {
    $this->dbforge->drop_table($this->config->item('tblFormula'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblScore'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblScoreMain'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblMeasure'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblYtd'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblScStatus'),TRUE);


    $this->dbforge->drop_table($this->config->item('tblKpi'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblTargetD'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblTargetS'),TRUE);
    $this->dbforge->drop_table($this->config->item('tblTargetR'),TRUE);
  }
}
