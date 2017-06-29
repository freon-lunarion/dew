<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmpModel extends CI_Model{

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
    //Codeigniter : Write Less Do More
    $this->load->model('BaseModel');
    $this->objType   = $this->config->item('objEmployee');

    $this->relStruct = $this->config->item('relStruct');
    $this->relReport = $this->config->item('relReport');
    $this->relAssign = $this->config->item('relAssign');
    $this->relChief  = $this->config->item('relChief');
    $this->relHold   = $this->config->item('relHold');
    $this->relJob    = $this->config->item('relJob');
  }

  public function AddPost($persId=0,$postId=0,$beginDate='',$endDate='9999-12-31')
  {
    if ($beginDate == '') {
      $beginDate = date('Y-m-d');
    }
    $this->BaseModel->CreateRel($this->$relHold,$postId,$persId,$beginDate,$endDate);
  }

  public function ChangePost($relId=0,$beginDate='',$endDate='9999-12-31')
  {
    if ($beginDate == '') {
      $beginDate = date('Y-m-d');
    }
    $this->BaseModel->CreateRel($this->$relHold,$postId,$persId,$beginDate,$endDate,$order);
  }

  public function ChangeName($persId=0,$newName='',$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeAttr($persId,$newName,$validOn,$endDate);
  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
  }

  public function CountPost($persId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($persId,$this->$relHold,$keyDate);
  }

  public function Create($name='',$postId=FALSE,$beginDate='',$endDate='9999-12-31')
  {
    if ($beginDate == '') {
      $beginDate = date('Y-m-d');
    }
    $persId = $this->BaseModel->Create($this->objType,$name,$beginDate,$endDate);
    if ($postId) {
      $this->BaseModel->CreateRel($this->$relHold,$postId,$persId,$beginDate,$endDate);
    }
  }

  public function Delete($persId=0)
  {
    $this->BaseModel->Delete($persId);
  }

  public function Delimit($persId=0,$endDate='')
  {
    $this->BaseModel->Delimit($persId,$endDate);
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

  public function GetByNameList($name='',$keydate='')
  {
    return $this->BaseModel->GetByNameList($name,$keydate,$this->objType);
  }

  public function GetNameHistoryList($objId=0,$keyDate='',$sort)
  {
    return $this->BaseModel->GetAttrList($objId,$keyDate,$sort);
  }

  public function GetPostList($persId=0,$keyDate='',$order = 'asc')
  {
    return $this->BaseModel->GetBotUpRelList($persId,$this->relHold,$keyDate,'post' ,$order);
  }

  public function GetRelByIdRow($relId=0)
  {
    return $this->BaseModel->GetRelById($relId);
  }
}
