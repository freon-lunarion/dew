<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PerspectiveModel extends CI_Model{

  private $objType;

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('BaseModel');
    $this->objType   = $this->config->item('objPersp');
  }

  // Object and Text Attribute
  public function Create($name = '',$shortName = '' , $description = '' , $begin = '1990-01-01', $end = '9999-12-31')
  {
    $text = array(
      'name'        => $name,
      'short_name'  => $shortName,
      'description' => $description
    );
    return $this->BaseModel->Create($this->objType,$text,$begin,$end);
  }

  public function Delete($perspId='')
  {
    //Soft Delete
    $this->BaseModel->Delete($perspId);
  }

  public function ChangeName($perspId = 0, $newName = '', $newShort = '', $newDesc = '', $validOn = '', $endDate = '9999-12-31')
  {
    $text = array(
      'name'        => $newName,
      'short_name'  => $newShort,
      'description' => $newDesc
    );
    $this->BaseModel->ChangeAttr($perspId,$text,$validOn,$endDate);
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keyDate['begin'] = $beginDate;
    $keyDate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keyDate);
  }

  public function GetByIdRow($perspId=0)
  {
    return $this->BaseModel->GetByIdRow($perspId);
  }

  public function GetLastName($perspId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($perspId,$keyDate);
  }

  public function GetNameHistoryList($perspId=0,$keyDate='',$sort)
  {
    return $this->BaseModel->GetAttrList($perspId,$keyDate,$sort);
  }


  // Relation With Strategic Objective (SO)

  public function CountSo($perspId = 0, $keyDate = '')
  {
    return $this->BaseModel->CountBotUpRel($perspId,$this->config->item('relSoPer'),$keyDate);
  }

  public function GetSoList($perspId = 0 ,$keyDate = '')
  {
    return $this->BaseModel->GetTopDownRelList($perspId,$this->config->item('relSoPer'),$keyDate,'so');
  }

}
