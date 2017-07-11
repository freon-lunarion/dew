<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SoModel extends CI_Model{

  private $objType;
  private $relSc;
  private $relKpi;
  private $relPerp;

  public function __construct()
  {
    parent::__construct();
    $this->load->model('BaseModel');
    $this->objType = $this->config->item('objSO');
    $this->relSc   = $this->config->item('relScSo');
    $this->relKpi  = $this->config->item('relSoKpi');
    $this->relPerp = $this->config->item('relPerSo');

  }

  public function Create($name='',$shortName='',$description='', $beginDate='1990-01-10',$endDate='9999-12-31')
  {
    $text = array(
      'name'        => $name,
      'short_name'  => $shortName,
      'description' => $description
    );
    $soId =  $this->BaseModel->Create($this->objType,$text,$beginDate,$endDate);

    return $soId;
  }

  public function Delete($soId=0)
  {
    $this->BaseModel->Delete($soId);

  }

  public function ChangeName($soId = 0, $newName = '', $newShort = '', $newDesc = '', $validOn = '', $endDate = '9999-12-31')
  {
    $text = array(
      'name'        => $newName,
      'short_name'  => $newShort,
      'description' => $newDesc
    );
    $this->BaseModel->ChangeAttr($soId,$text,$validOn,$endDate);
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keyDate['begin'] = $beginDate;
    $keyDate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keyDate);
  }

  public function GetByIdRow($soId=0)
  {
    return $this->BaseModel->GetByIdRow($soId);
  }

  public function GetNameRow($soId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($soId,$keyDate);
  }

  public function GetNameList($soId=0,$keyDate='',$sort)
  {
    return $this->BaseModel->GetAttrList($soId,$keyDate,$sort);
  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
  }

  public function CreateRelSc($scId,$soId,$beginDate='',$endDate='')
  {
    
  }
}
