<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JobModel extends CI_Model{

  private $objType;
  private $relStruct;
  private $relReport;
  private $relAssign;
  private $relChief;
  private $relHold;
  private $relJob;

  public function __construct()
  {
    parent::__construct();
    $this->load->model('BaseModel');

    $this->objType   = $this->config->item('objJob');
    $this->relStruct = $this->config->item('relStruct');
    $this->relReport = $this->config->item('relReport');
    $this->relAssign = $this->config->item('relAssign');
    $this->relChief  = $this->config->item('relChief');
    $this->relHold   = $this->config->item('relHold');
    $this->relJob    = $this->config->item('relJob');
  }

  public function Create($name='',$short='',$beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $text = array(
      'name'        => $name,
      'short_name'  => $short,
    );
    return $this->BaseModel->Create($this->objType,$text,$beginDate,$endDate);
  }

  public function Delete($objId=0)
  {
    $this->BaseModel->Delete($objId);
  }

  public function Delimit($objId=0,$endDate='')
  {
    $this->BaseModel->Delimit($objId,$endDate);
  }

  public function ChangeName($objId=0,$name='',$short='',$validOn='',$endDate='9999-12-31')
  {
    $text = array(
      'name'        => $name,
      'short_name'  => $short,
    );
    $this->BaseModel->ChangeAttr($objId,$text,$validOn,$endDate);
  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
  }


  public function CountRelatedPost($objId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($objId,$this->relJob,$keyDate);
  }

  public function DeleteRel($relId=0)
  {
    $this->BaseModel->DeleteRel($relId);
  }

  public function GetByIdRow($id=0)
  {
    return $this->BaseModel->GetByIdRow($id);
  }

  public function GetLastName($objId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($objId,$keyDate);
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keydate['begin'] = $beginDate;
    $keydate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keydate);
  }

  public function GetNameHistoryList($objId=0,$keyDate='',$sort)
  {
    return $this->BaseModel->GetAttrList($objId,$keyDate,$sort);
  }

  public function GetRelByIdRow($relId=0)
  {
    return $this->BaseModel->GetRelById($relId);
  }

  public function GetRelatedPostList($objId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($objId,$this->relJob,$keyDate,'post');
  }


}
