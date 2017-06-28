<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JobModel extends CI_Model{

  private $objType   = 'JOB';
  // Relation Code (Ref to ref_obj_rel)
  private $relStruct = '111';
  private $relReport = '112';

  private $relAssign = '121';
  private $relChief  = '122';

  private $relHold   = '131';
  private $relJob    = '141';

  public function __construct()
  {
    parent::__construct();
    $this->load->model('BaseModel');
  }

  public function ChangeName($objId=0,$newName='',$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeAttr($objId,$newName,$validOn,$endDate);
  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
  }

  public function CountRelatedPerson($objId=0,$keyDate='')
  {
    $relCode = array($this->relJob,$this->relHold);
    return $this->BaseModel->CountTopDownRel($objId,$relCode,$keyDate);
  }

  public function CountRelatedPost($objId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($objId,$this->relJob,$keyDate);
  }

  public function Create($name='',$beginDate='1990-01-01',$endDate='9999-12-31')
  {
    return $this->BaseModel->Create($this->objType,$name,$beginDate,$endDate);
  }

  public function Delete($objId=0)
  {
    $this->BaseModel->Delete($objId);
  }

  public function DeleteRel($relId=0)
  {
    $this->BaseModel->DeleteRel($relId);
  }

  public function Delimit($objId=0,$endDate='')
  {
    $this->BaseModel->Delimit($objId,$endDate);
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

  public function GetRelatedPersonList($objId=0,$keyDate='')
  {
    $relCode = array($this->relJob,$this->relHold);
    $alias   = array('post','person');
    return $this->BaseModel->GetTopDownRelList($objId,$relCode,$keyDate,$alias);
  }

  public function GetRelatedPostList($objId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($objId,$this->relJob,$keyDate,'post');
  }


}
