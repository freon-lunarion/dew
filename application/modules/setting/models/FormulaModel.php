<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormulaModel extends CI_Model{

  private $objType;
  private $tbl;
  private $tblScore;

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('BaseModel');
    $this->objType  = $this->config->item('objFormula');
    $this->tbl      = $this->config->item('tblFormula');
    $this->tblScore = $this->config->item('tblScore');
  }

  // Object and Text Attribute
  public function Create($name = '',$shortName = '' , $description = '' , $type = ' ',$begin = '1990-01-01', $end = '9999-12-31')
  {
    $text = array(
      'name'        => $name,
      'short_name'  => $shortName,
      'description' => $description
    );
    $objId = $this->BaseModel->Create($this->objType,$text,$begin,$end);
    $data  = array(
      'formula_id' => $objId,
      'type'       => $type,
      'begin_date' => $begin,
      'end_date'   => $end,
    );
    $this->BaseModel->InsertOn($this->tbl,$data);
    return $objId;
  }

  public function Delete($formulaId='')
  {
    //Soft Delete
    $this->BaseModel->Delete($formulaId);
    $this->BaseModel->DeleteOn($this->tbl,$formulaId,'formula_id');
    $this->BaseModel->DeleteOn($this->tblScore,$formulaId,'formula_id');

  }

  public function ChangeValue($formulaId = 0, $type = '', $validOn = '', $endDate = '9999-12-31')
  {
    if ($validOn == '') {
      $validOn = date('Y-m-d');
    }
    $this->db->select('id');
    $this->db->where('formula_id', $formulaId);
    $this->db->order_by('end_date','desc');
    $row    = $this->db->get($this->tbl)->row();

    $attId    = $row->id;
    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')),
    );
    $this->BaseModel->ChangeOn($this->tbl,$attId,$data);

    $data = array(
      'formula_id'     => $formulaId,
      'type'           => $type,
      'begin_date'     => $validOn,
      'end_date'       => $endDate,
    );
    $this->BaseModel->InsertOn($this->tbl,$data);

  }

  public function ChangeName($formulaId = 0, $newName = '', $newShort = '', $newDesc = '', $validOn = '', $endDate = '9999-12-31')
  {
    $text = array(
      'name'        => $newName,
      'short_name'  => $newShort,
      'description' => $newDesc
    );
    $this->BaseModel->ChangeAttr($formulaId,$text,$validOn,$endDate);
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keyDate['begin'] = $beginDate;
    $keyDate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keyDate);
  }

  public function GetByIdRow($formulaId=0)
  {
    return $this->BaseModel->GetByIdRow($formulaId);
  }

  public function GetLastName($formulaId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($formulaId,$keyDate);
  }

  public function GetLastValue($formulaId = 0,$keyDate='')
  {
    return $this->BaseModel->GetLastOn($this->tbl,$formulaId,'formula_id',$keyDate);
  }

  public function GetNameHistoryList($formulaId=0,$keyDate='',$sort)
  {
    return $this->BaseModel->GetAttrList($formulaId,$keyDate,$sort);
  }

  // Formula Score

  public function CreateScore($formulaId = 0, $value = 1 , $lower = 0.00, $upper = 0.00, $begin = '1990-01-01' , $end= '9999-12-31')
  {
    $data = array(
      'formula_id'  => $formulaId,
      'value'       => $value,
      'lower_bound' => $lower,
      'upper_bound' => $upper,
      'begin_date'  => $begin,
      'end_date'    => $end,
    );
    return $this->BaseModel->InsertOn($this->tblScore,$data);
  }

  public function ChangeScore($scoreId = 0 , $value = 1 , $lower = 0.00, $upper = 0.00, $validOn = '1990-01-01' , $end = '9999-12-31')
  {
    if ($validOn == '') {
      $validOn = date('Y-m-d');
    }
    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')),
    );
    $formulaId = $this->GetScoreByIdRow($scoreId)->formula_id;
    $this->BaseModel->ChangeOn($this->tblScore,$scoreId,$data);
    $data = array(
      'formula_id'  => $formulaId,
      'value'       => $value,
      'lower_bound' => $lower,
      'upper_bound' => $upper,
      'begin_date'  => $validOn,
      'end_date'    => $end,
    );
    $this->BaseModel->InsertOn($this->tblScore,$data);
  }

  public function DeleteScore($scoreId=0)
  {
    $this->BaseModel->DeleteOn($this->tblScore,$scoreId);
  }

  public function GetScoreList($formulaId=0,$keydate = '')
  {

    if (!is_array($keydate)) {
      $this->db->where('begin_date >=', $keydate);
      $this->db->where('end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('end_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date <=', $keydate['begin']);
          $this->db->where('end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }
    $this->db->where('formula_id', $formulaId);
    $this->db->where('is_delete', FALSE);
    $this->db->order_by('value');
    $this->db->order_by('end_date','desc');
    return $this->db->get($this->tblScore)->result();
  }

  public function GetScoreByIdRow($scoreId = 0)
  {
    $this->db->where('id',$scoreId);
    return $this->db->get($this->tblScore)->row();

  }
  // KPI

}
