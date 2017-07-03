<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MeasurementModel extends CI_Model{

  private $objType;
  private $tbl;

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('BaseModel');
    $this->objType = $this->config->item('objMeasurement');
    $this->tbl     = $this->config->item('tblMeasure');
  }

  // Object and Text Attribute
  public function Create($name = '',$shortName = '' , $description = '', $hasMin = FALSE, $hasMax = FALSE, $minVal = 0, $maxVal = 0 , $begin = '1990-01-01', $end = '9999-12-31')
  {
    $text = array(
      'name'        => $name,
      'short_name'  => $shortName,
      'description' => $description
    );
    $objId = $this->BaseModel->Create($this->objType,$text,$begin,$end);

    $data = array(
      'has_min'    => $hasMin,
      'has_max'    => $hasMax,
      'min_value'  => $minVal,
      'max_value'  => $maxVal,
      'begin_date' => $begin,
      'end_date'   => $end,
    );
    $this->BaseModel->InsertOn($this->tbl,$data);
    return $objId;
  }

  public function Delete($measId=0)
  {
    //Soft Delete
    $this->BaseModel->Delete($measId);

    $this->BaseModel->DeleteOn($this->tbl,$measId,'measurement_id');
  }

  public function ChangeName($measId = 0, $newName = '', $newShort = '', $newDesc = '', $validOn = '', $endDate = '9999-12-31')
  {
    $text = array(
      'name'        => $newName,
      'short_name'  => $newShort,
      'description' => $newDesc
    );
    $this->BaseModel->ChangeAttr($measId,$text,$validOn,$endDate);
  }

  public function ChangeValue($measId = 0, $hasMin = FALSE, $hasMax = FALSE, $minVal = 0, $maxVal = 0, $validOn = '', $endDate = '9999-12-31')
  {
    if ($validOn == '') {
      $validOn = date('Y-m-d');
    }
    $this->db->select('id');
    $this->db->where('measurement_id', $measId);
    $this->db->order_by('end_date','desc');
    $row    = $this->db->get($this->tbl)->row();

    $attId    = $row->id;
    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')),
    );
    $this->BaseModel->ChangeOn($this->tbl,$attId,$data);

    $data = array(
      'measurement_id' => $measId,
      'has_min'        => $hasMin,
      'has_max'        => $hasMax,
      'min_value'      => $minVal,
      'max_value'      => $maxVal,
      'begin_date'     => $validOn,
      'end_date'       => $endDate,
    );
    $this->BaseModel->InsertOn($this->tbl,$data);

  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keyDate['begin'] = $beginDate;
    $keyDate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keyDate);
  }

  public function GetByIdRow($measId=0)
  {
    return $this->BaseModel->GetByIdRow($measId);
  }

  public function GetLastName($measId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($measId,$keyDate);
  }

  public function GetLastValue($measId = 0,$keyDate='')
  {
    return $this->BaseModel->GetLastOn($this->tbl,$measId,'measurement_id',$keyDate);
  }

  public function GetNameHistoryList($measId=0,$keyDate='',$sort)
  {
    return $this->BaseModel->GetAttrList($measId,$keyDate,$sort);
  }

}
