<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OmSetup extends CI_Model{

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
        'code' => $this->config->item('objEmployee'),
        'name' => 'Employee',
      ),
      array(
        'code' => $this->config->item('objJob'),
        'name' => 'Job',
      ),
      array(
        'code' => $this->config->item('objOrg'),
        'name' => 'Organization',
      ),
      array(
        'code' => $this->config->item('objPost'),
        'name' => 'Position',
      ),
    );
    $this->db->insert_batch($this->config->item('tblRefObj'), $data);
    $data = array(
      array(
        'code'        => $this->config->item('relStruct'),
        'top'         => $this->config->item('objOrg'),
        'bottom'      => $this->config->item('objOrg'),
        'description' => 'Organization Structure',
      ),
      array(
        'code'        => $this->config->item('relReport'),
        'top'         => $this->config->item('objPost'),
        'bottom'      => $this->config->item('objPost'),
        'description' => 'Reporting Structure',
      ),
      array(
        'code'        => $this->config->item('relAssign'),
        'top'         => $this->config->item('objOrg'),
        'bottom'      => $this->config->item('objPost'),
        'description' => 'Position Assignment to Organization',
      ),
      array(
        'code'        => $this->config->item('relChief'),
        'top'         => $this->config->item('objOrg'),
        'bottom'      => $this->config->item('objPost'),
        'description' => 'Chief of Organization',
      ),
      array(
        'code'        => $this->config->item('relHold'),
        'top'         => $this->config->item('objPost'),
        'bottom'      => $this->config->item('objEmployee'),
        'description' => 'Employee Assignment to a position',
      ),
      array(
        'code'        => $this->config->item('relJob'),
        'top'         => $this->config->item('objJob'),
        'bottom'      => $this->config->item('objPost'),
        'description' => 'Associating position with a job',
      ),
    );
    $this->db->insert_batch($this->config->item('tblRefRel'), $data);
  }

  public function InsertIntialRecords()
  {
    $hldComp =
      array(
        'type'  => $this->config->item('objOrg'),
        'name'  => 'Holding Company',
        'short' => 'CORP',
        'begin' => '2000-01-01',
        'end'   => '9999-12-31',
      );

    $data = array(
      'type'        => $hldComp['type'],
      'begin_date'  => $hldComp['begin'],
      'end_date'    => $hldComp['end'],
      'create_time' => date('Y-m-d H:i:s'),
    );
    $this->db->insert($this->config->item('tblObj'), $data);

    $this->db->select('MAX(id) as id');
    $objId = $this->db->get($this->config->item('tblObj'))->row()->id;

    $data = array(
      'obj_id'      => $objId,
      'name'        => $hldComp['name'],
      'short_name'  => $hldComp['short'],
      'begin_date'  => $hldComp['begin'],
      'end_date'    => $hldComp['end'],
      'create_time' => date('Y-m-d H:i:s'),
    );
    $this->db->insert($this->config->item('tblAttr'), $data);

    return $objId;
  }

  public function InsertDemoRecords($offset = 0,$hldComp = 1)
  {
    $begin = '2000-01-01';
    $end   = '9999-12-31';
    // Object

    $set    = array(
      array(
        'type' => $this->config->item('objOrg'),
        'name' => 'Business Division',
        'short' => 'BIZ',
      ),
      array(
        'type' => $this->config->item('objOrg'),
        'name' => 'Support Division',
        'short' => 'SUP',
      ),
      array(
        'type' => $this->config->item('objOrg'),
        'name' => 'Production Department',
        'short' => 'PD',
      ),
      array(
        'type' => $this->config->item('objOrg'),
        'name' => 'Sales & Marketing Department',
        'short' => 'SMD',
      ),
      array(
        'type' => $this->config->item('objOrg'),
        'name' => 'Finance Department',
        'short' => 'FID',
      ),
      array(
        'type' => $this->config->item('objOrg'),
        'name' => 'Human Resources Department',
        'short' => 'HRD',
      ),
      array(
        'type' => $this->config->item('objOrg'),
        'name' => 'Facility Department',
        'short' => '',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Top Managerial',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Middle Managerial',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Line Manager',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Secretary',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Designer',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Engineer',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Operator',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Clerk',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Officer',
      ),
      array(
        'type' => $this->config->item('objJob'),
        'name' => 'Analyst',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'CEO',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Business GM',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Supporting GM',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Production Manager',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Sales & Marketing Manager',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Finance Manager',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Human Resources Manager',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Facility Manager',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Secretary',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Secretary',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Secretary',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Industrial Designer',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Tech Designer',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Machine Operator',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Machine Operator',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Machine Operator',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Multimedia Designer',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Sales Executive',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Sales Executive',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Sales Executive',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Sales Executive',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Market Analyst',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Accountant',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Accountant',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Accountant',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Administrative Assistant',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Administrative Assistant',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'HR Officer',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'HR Officer',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Administrative Assistant',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'Administrative Assistant',
      ),
      array(
        'type' => $this->config->item('objPost'),
        'name' => 'IT Staff',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Myrta Deas',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Dann Bybee',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Aretha Deherrera',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Shondra Eggen',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Miranda Rodreguez',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Tobias Choice',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Bert Shear',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Raymonde Bultman',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Brianna Bissonette',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Adrianna Coy',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Jim Mento',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Alex Capote',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Marty Sowders',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Santina Ruland',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Gilberte Boedeker',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Refugio Mickel',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Chia Altamirano',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Mercedez Perrella',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Paola Acord',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Carlo Chmura',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Zulema Goldstein',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Adell Dickerson',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Thelma Boulanger',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Cherly Broman',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Delmer Segarra',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Jayna Verdejo',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Solomon Pietila',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Lala Calvert',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Eldora Mccall',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Dalila Scot',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'John Smith',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Ira Granada',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Horace Carico',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Kayla Soules',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Vince Mitcham',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Alesha Roder',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Yuri Pool',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Daphne Howes',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Cristal Sanders',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'Georgianne Caswell',
      ),
      array(
        'type' => $this->config->item('objEmployee'),
        'name' => 'India Levay',
      ),
    );

    foreach ($set as $row) {
      $data = array(
        'type'        => $row['type'],
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
        'begin_date'  => $begin,
        'end_date'    => $end,
        'create_time' => date('Y-m-d H:i:s'),
      );
      if (isset($row['short'])) {
        $data['short_name'] = $row['short'];
      }
      $this->db->insert($this->config->item('tblAttr'), $data);

    }
    $set = array(
      array($this->config->item('relStruct'), $hldComp, (2 + $offset) ),
      array($this->config->item('relStruct'), $hldComp, (3 + $offset) ),
      array($this->config->item('relStruct'), (2 + $offset), (4 + $offset) ),
      array($this->config->item('relStruct'), (2 + $offset), (5 + $offset) ),
      array($this->config->item('relStruct'), (3 + $offset), (6 + $offset) ),
      array($this->config->item('relStruct'), (3 + $offset), (7 + $offset) ),
      array($this->config->item('relStruct'), (3 + $offset), (8 + $offset) ),
      array($this->config->item('relReport'), (19 + $offset), (20 + $offset) ),
      array($this->config->item('relReport'), (19 + $offset), (21 + $offset) ),
      array($this->config->item('relReport'), (19 + $offset), (27 + $offset) ),
      array($this->config->item('relReport'), (20 + $offset), (22 + $offset) ),
      array($this->config->item('relReport'), (20 + $offset), (23 + $offset) ),
      array($this->config->item('relReport'), (20 + $offset), (28 + $offset) ),
      array($this->config->item('relReport'), (21 + $offset), (24 + $offset) ),
      array($this->config->item('relReport'), (21 + $offset), (25 + $offset) ),
      array($this->config->item('relReport'), (21 + $offset), (26 + $offset) ),
      array($this->config->item('relReport'), (21 + $offset), (29 + $offset) ),
      array($this->config->item('relReport'), (22 + $offset), (30 + $offset) ),
      array($this->config->item('relReport'), (22 + $offset), (31 + $offset) ),
      array($this->config->item('relReport'), (22 + $offset), (32 + $offset) ),
      array($this->config->item('relReport'), (22 + $offset), (33 + $offset) ),
      array($this->config->item('relReport'), (22 + $offset), (34 + $offset) ),
      array($this->config->item('relReport'), (23 + $offset), (35 + $offset) ),
      array($this->config->item('relReport'), (23 + $offset), (36 + $offset) ),
      array($this->config->item('relReport'), (23 + $offset), (37 + $offset) ),
      array($this->config->item('relReport'), (23 + $offset), (38 + $offset) ),
      array($this->config->item('relReport'), (23 + $offset), (39 + $offset) ),
      array($this->config->item('relReport'), (23 + $offset), (30 + $offset) ),
      array($this->config->item('relReport'), (24 + $offset), (41 + $offset) ),
      array($this->config->item('relReport'), (24 + $offset), (42 + $offset) ),
      array($this->config->item('relReport'), (24 + $offset), (43 + $offset) ),
      array($this->config->item('relReport'), (24 + $offset), (44 + $offset) ),
      array($this->config->item('relReport'), (24 + $offset), (45 + $offset) ),
      array($this->config->item('relReport'), (25 + $offset), (46 + $offset) ),
      array($this->config->item('relReport'), (25 + $offset), (47 + $offset) ),
      array($this->config->item('relReport'), (25 + $offset), (48 + $offset) ),
      array($this->config->item('relReport'), (26 + $offset), (49 + $offset) ),
      array($this->config->item('relReport'), (26 + $offset), (50 + $offset) ),
      array($this->config->item('relAssign'), $hldComp, (19 + $offset),60 ),
      array($this->config->item('relChief'), $hldComp, (19 + $offset), 40 ),
      array($this->config->item('relAssign'), (2 + $offset), (20 + $offset),60 ),
      array($this->config->item('relChief'), (2 + $offset), (20 + $offset), 40 ),
      array($this->config->item('relAssign'), (3 + $offset), (21 + $offset),60 ),
      array($this->config->item('relChief'), (3 + $offset), (21 + $offset), 40 ),
      array($this->config->item('relAssign'), (4 + $offset), (22 + $offset),60 ),
      array($this->config->item('relChief'), (4 + $offset), (22 + $offset), 40 ),
      array($this->config->item('relAssign'), (5 + $offset), (23 + $offset),60 ),
      array($this->config->item('relChief'), (5 + $offset), (23 + $offset), 40 ),
      array($this->config->item('relAssign'), (6 + $offset), (24 + $offset),60 ),
      array($this->config->item('relChief'), (6 + $offset), (24 + $offset), 40 ),
      array($this->config->item('relAssign'), (7 + $offset), (25 + $offset),60 ),
      array($this->config->item('relChief'), (7 + $offset), (25 + $offset), 40 ),
      array($this->config->item('relAssign'), (8 + $offset), (26 + $offset),60 ),
      array($this->config->item('relChief'), (8 + $offset), (26 + $offset), 40 ),
      array($this->config->item('relAssign'), $hldComp , (27 + $offset),100 ),
      array($this->config->item('relAssign'), (2 + $offset), (28 + $offset),100 ),
      array($this->config->item('relAssign'), (3 + $offset), (29 + $offset),100 ),
      array($this->config->item('relAssign'), (4 + $offset), (30 + $offset),100 ),
      array($this->config->item('relAssign'), (4 + $offset), (31 + $offset),100 ),
      array($this->config->item('relAssign'), (4 + $offset), (32 + $offset),100 ),
      array($this->config->item('relAssign'), (4 + $offset), (33 + $offset),100 ),
      array($this->config->item('relAssign'), (4 + $offset), (34 + $offset),100 ),
      array($this->config->item('relAssign'), (5 + $offset), (35 + $offset),100 ),
      array($this->config->item('relAssign'), (5 + $offset), (36 + $offset),100 ),
      array($this->config->item('relAssign'), (5 + $offset), (37 + $offset),100 ),
      array($this->config->item('relAssign'), (5 + $offset), (38 + $offset),100 ),
      array($this->config->item('relAssign'), (5 + $offset), (39 + $offset),100 ),
      array($this->config->item('relAssign'), (5 + $offset), (40 + $offset),100 ),
      array($this->config->item('relAssign'), (6 + $offset), (41 + $offset),100 ),
      array($this->config->item('relAssign'), (6 + $offset), (42 + $offset),100 ),
      array($this->config->item('relAssign'), (6 + $offset), (43 + $offset),100 ),
      array($this->config->item('relAssign'), (6 + $offset), (44 + $offset),100 ),
      array($this->config->item('relAssign'), (6 + $offset), (45 + $offset),100 ),
      array($this->config->item('relAssign'), (7 + $offset), (46 + $offset),100 ),
      array($this->config->item('relAssign'), (7 + $offset), (47 + $offset),100 ),
      array($this->config->item('relAssign'), (7 + $offset), (48 + $offset),100 ),
      array($this->config->item('relAssign'), (8 + $offset), (49 + $offset),100 ),
      array($this->config->item('relAssign'), (8 + $offset), (50 + $offset),100 ),
      array($this->config->item('relHold'), (19 + $offset), (51 + $offset), 100 ),
      array($this->config->item('relHold'), (20 + $offset), (52 + $offset), 100 ),
      array($this->config->item('relHold'), (21 + $offset), (53 + $offset), 100 ),
      array($this->config->item('relHold'), (22 + $offset), (54 + $offset), 100 ),
      array($this->config->item('relHold'), (23 + $offset), (55 + $offset), 100 ),
      array($this->config->item('relHold'), (24 + $offset), (56 + $offset), 100 ),
      array($this->config->item('relHold'), (25 + $offset), (57 + $offset), 100 ),
      array($this->config->item('relHold'), (26 + $offset), (58 + $offset), 100 ),
      array($this->config->item('relHold'), (27 + $offset), (59 + $offset), 100 ),
      array($this->config->item('relHold'), (28 + $offset), (60 + $offset), 100 ),
      array($this->config->item('relHold'), (29 + $offset), (61 + $offset), 100 ),
      array($this->config->item('relHold'), (30 + $offset), (62 + $offset), 100 ),
      array($this->config->item('relHold'), (31 + $offset), (63 + $offset), 100 ),
      array($this->config->item('relHold'), (32 + $offset), (64 + $offset), 100 ),
      array($this->config->item('relHold'), (33 + $offset), (65 + $offset), 100 ),
      array($this->config->item('relHold'), (34 + $offset), (66 + $offset), 100 ),
      array($this->config->item('relHold'), (35 + $offset), (67 + $offset), 100 ),
      array($this->config->item('relHold'), (36 + $offset), (68 + $offset), 100 ),
      array($this->config->item('relHold'), (37 + $offset), (69 + $offset), 100 ),
      array($this->config->item('relHold'), (38 + $offset), (70 + $offset), 100 ),
      array($this->config->item('relHold'), (39 + $offset), (71 + $offset), 100 ),
      array($this->config->item('relHold'), (40 + $offset), (72 + $offset), 100 ),
      array($this->config->item('relHold'), (41 + $offset), (73 + $offset), 100 ),
      array($this->config->item('relHold'), (42 + $offset), (74 + $offset), 100 ),
      array($this->config->item('relHold'), (43 + $offset), (75 + $offset), 100 ),
      array($this->config->item('relHold'), (44 + $offset), (76 + $offset), 100 ),
      array($this->config->item('relHold'), (45 + $offset), (77 + $offset), 100 ),
      array($this->config->item('relHold'), (46 + $offset), (78 + $offset), 100 ),
      array($this->config->item('relHold'), (47 + $offset), (79 + $offset), 100 ),
      array($this->config->item('relHold'), (48 + $offset), (80 + $offset), 100 ),
      array($this->config->item('relHold'), (49 + $offset), (81 + $offset), 100 ),
      array($this->config->item('relHold'), (50 + $offset), (82 + $offset), 100 ),
      array($this->config->item('relJob'), (9 + $offset), (19 + $offset) ),
      array($this->config->item('relJob'), (10 + $offset), (20 + $offset) ),
      array($this->config->item('relJob'), (10 + $offset), (21 + $offset) ),
      array($this->config->item('relJob'), (11 + $offset), (22 + $offset) ),
      array($this->config->item('relJob'), (11 + $offset), (23 + $offset) ),
      array($this->config->item('relJob'), (11 + $offset), (24 + $offset) ),
      array($this->config->item('relJob'), (11 + $offset), (25 + $offset) ),
      array($this->config->item('relJob'), (11 + $offset), (26 + $offset) ),
      array($this->config->item('relJob'), (12 + $offset), (27 + $offset) ),
      array($this->config->item('relJob'), (12 + $offset), (28 + $offset) ),
      array($this->config->item('relJob'), (12 + $offset), (29 + $offset) ),
      array($this->config->item('relJob'), (13 + $offset), (30 + $offset) ),
      array($this->config->item('relJob'), (13 + $offset), (35 + $offset) ),
      array($this->config->item('relJob'), (14 + $offset), (31 + $offset) ),
      array($this->config->item('relJob'), (14 + $offset), (50 + $offset) ),
      array($this->config->item('relJob'), (15 + $offset), (32 + $offset) ),
      array($this->config->item('relJob'), (15 + $offset), (33 + $offset) ),
      array($this->config->item('relJob'), (15 + $offset), (34 + $offset) ),
      array($this->config->item('relJob'), (16 + $offset), (44 + $offset) ),
      array($this->config->item('relJob'), (16 + $offset), (45 + $offset) ),
      array($this->config->item('relJob'), (16 + $offset), (48 + $offset) ),
      array($this->config->item('relJob'), (16 + $offset), (49 + $offset) ),
      array($this->config->item('relJob'), (17 + $offset), (37 + $offset) ),
      array($this->config->item('relJob'), (17 + $offset), (38 + $offset) ),
      array($this->config->item('relJob'), (17 + $offset), (39 + $offset) ),
      array($this->config->item('relJob'), (17 + $offset), (41 + $offset) ),
      array($this->config->item('relJob'), (17 + $offset), (42 + $offset) ),
      array($this->config->item('relJob'), (17 + $offset), (43 + $offset) ),
      array($this->config->item('relJob'), (17 + $offset), (46 + $offset) ),
      array($this->config->item('relJob'), (17 + $offset), (47 + $offset) ),
      array($this->config->item('relJob'), (18 + $offset), (40 + $offset) ),
    );
    foreach ($set as $key => $value) {
      if (isset($value[3])) {
        $weight = $value[3];
      } else {
        $weight = 100;
      }
      $data = array(
        'rel_code'      => $value[0],
        'obj_top_id'    => $value[1],
        'obj_bottom_id' => $value[2],
        'weight'        => $weight,
        'begin_date'    => $begin,
        'end_date'      => $end,
        'create_time'   => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->config->item('tblRel'), $data);

    }
  }

}
