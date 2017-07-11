<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScModel extends CI_Model{

  private $objType;
  private $relJob;
  private $relPost;
  private $relOrg;
  private $relKpi;
  private $relSo;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('BaseModel');
    $this->objType = $this->config->item('objSC');
    $this->relJob  = $this->config->item('relJobSc');
    $this->relPost = $this->config->item('relPosSc');
    $this->relOrg  = $this->config->item('relOrgSc');
    $this->relKpi  = $this->config->item('relScKpi');
  }

  public function Create($name='',$shortName='',$description='',$extId = 0, $beginDate='1990-01-10',$endDate='9999-12-31')
  {
    $text = array(
      'name'        => $name,
      'short_name'  => $shortName,
      'description' => $description
    );
    $scId =  $this->BaseModel->Create($this->objType,$text,$beginDate,$endDate);
    // TODO add relation with orgID / PostId / JobId
    $ext = $this->BaseModel->GetByIdRow($extId);
    switch ($ext->type) {
      case $this->config->item('objOrg'):
        $relCode = $this->relOrg;
        break;
      case $this->config->item('objPost'):
        $relCode = $this->relPost;
        break;
      case $this->config->item('objJob'):
        $relCode = $this->relJob;
        break;
    }
    $this->BaseModel->CreateRel($relCode,$extId,$scId,$this->config->item('relScStatusDraft'),$beginDate,$endDate);
    return $scId;
  }

  public function Delete($scId=0)
  {
    $this->BaseModel->Delete($scId);
  }

  public function ChangeName($scId = 0, $newName = '', $newShort = '', $newDesc = '', $validOn = '', $endDate = '9999-12-31')
  {
    $text = array(
      'name'        => $newName,
      'short_name'  => $newShort,
      'description' => $newDesc
    );
    $this->BaseModel->ChangeAttr($scId,$text,$validOn,$endDate);
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keyDate['begin'] = $beginDate;
    $keyDate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keyDate);
  }

  public function GetByIdRow($scId=0)
  {
    return $this->BaseModel->GetByIdRow($scId);
  }

  public function GetNameRow($scId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($scId,$keyDate);
  }

  public function GetNameList($scId=0,$keyDate='',$sort)
  {
    return $this->BaseModel->GetAttrList($scId,$keyDate,$sort);
  }
  public function CreateRelExt($scId = 0, $extId = 0)
  {
    $scId =  $this->BaseModel->Create($this->objType,$text,$beginDate,$endDate);
    // TODO add relation with orgID / PostId / JobId
    $ext = $this->BaseModel->GetByIdRow($extId);
    switch ($ext->type) {
      case $this->config->item('objOrg'):
        $relCode = $this->relOrg;
        break;
      case $this->config->item('objPost'):
        $relCode = $this->relPost;
        break;
      case $this->config->item('objJob'):
        $relCode = $this->relJob;
        break;
    }
    $this->BaseModel->CreateRel($relCode,$extId,$scId,$this->config->item('relScStatusDraft'),$beginDate,$endDate);

  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
  }

  public function CountRelJob($scId=0,$keydate='')
  {
    return  $this->BaseModel->CountBotUpRel($scId,$this->relJob,$keyDate);

  }

  public function GetRelJobList($scId=0,$keydate='')
  {
    return  $this->BaseModel->GetBotUpRelList($scId,$this->relJob,$keyDate);
  }

  public function CountRelPost($scId=0,$keydate='')
  {
    return  $this->BaseModel->CountBotUpRel($scId,$this->relPost,$keyDate);

  }

  public function GetRelPostList($scId=0,$keydate='')
  {
    return  $this->BaseModel->GetBotUpRelList($scId,$this->relPost,$keyDate);
  }

  public function CountRelOrg($scId=0,$keydate='')
  {
    return  $this->BaseModel->CountBotUpRel($scId,$this->relOrg,$keyDate);

  }

  public function GetRelOrgList($scId=0,$keydate='')
  {
    return  $this->BaseModel->GetBotUpRelList($scId,$this->relOrg,$keyDate);
  }

  public function CountRelKpi($scId=0,$keydate='')
  {
    return  $this->BaseModel->CountTopDownRel($scId,$this->relKpi,$keyDate);

  }

  public function CountKpiWeight($scId=0,$keydate='')
  {
    return  $this->BaseModel->CountTopDownRelWeight($scId,$this->relKpi,$keyDate);

  }

  public function GetRelKpiList($scId=0,$keydate='')
  {
    return  $this->BaseModel->GetTopDownRelList($scId,$this->relKpi,$keyDate);
  }
}
