<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrgModel extends CI_Model{

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

    $this->objType   = $this->config->item('objOrg');
    $this->relStruct = $this->config->item('relStruct');
    $this->relReport = $this->config->item('relReport');
    $this->relAssign = $this->config->item('relAssign');
    $this->relChief  = $this->config->item('relChief');
    $this->relHold   = $this->config->item('relHold');
    $this->relJob    = $this->config->item('relJob');
  }

  public function ChangeChiefPost($orgId,$newPost=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('TOPDOWN',$this->relChief,$orgId,$newPost,$validOn,$endDate);
  }

  public function ChangeName($orgId=0,$name='',$short,$validOn='',$endDate='9999-12-31')
  {
    $text = array(
      'name'       => $name,
      'short_name' => $short,
    );
    $this->BaseModel->ChangeAttr($orgId,$text,$validOn,$endDate);
  }

  public function ChangeParent($orgId=0,$newParent=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BOTUP',$this->relStruct,$orgId,$newParent,$validOn,$endDate);
  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
  }

  public function CountChiefPost($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($orgId,$this->relAssign,$keyDate);
  }

  public function CountChiefPerson($orgId=0,$keyDate='')
  {
    $relCode = array($this->relChief,$this->relHold);
    return $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);

  }

  public function CountChildrenOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($orgId,$this->relStruct,$keyDate);
  }

  public function CountParentOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($orgId,$this->relStruct,$keyDate);
  }

  public function CountPerson($orgId=0,$keyDate='')
  {
    $relCode = array($this->relAssign,$this->relHold);
    return $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);
  }

  public function CountPost($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($orgId,$this->relAssign,$keyDate);
  }

  public function Create($name='',$short,$beginDate='1990-01-01',$endDate='9999-12-31',$parentOrg=0)
  {
    $text = array(
      'name'       => $name,
      'short_name' => $short,
    );
    $orgId = $this->BaseModel->Create($this->objType,$text,$beginDate,$endDate);

    $this->BaseModel->CreateRel($this->relStruct,$parentOrg,$orgId,$beginDate,$endDate);

    return $orgId;
  }

  public function Delete($orgId=0)
  {
    $this->BaseModel->Delete($orgId);
  }

  public function DeleteRel($relId=0)
  {
    $this->BaseModel->DeleteRel($relId);
  }

  public function Delimit($orgId=0,$endDate='')
  {
    $this->BaseModel->Delimit($orgId,$endDate);
  }

  public function GetByIdRow($id=0)
  {
    return $this->BaseModel->GetByIdRow($id);
  }

  public function GetChiefPersonList($orgId=0,$keyDate='')
  {
    $relCode = array($this->relChief,$this->relHold);
    $alias   = array('post','person');
    $count   = $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);
    while ($count == 0) {
      $parent = $this->GetParentOrg($orgId,$keyDate,'parent');
      $orgId  = $parent->parent_id;
      $count  = $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);

    }
    return $this->BaseModel->GetTopDownRelList($orgId,$relCode,$keyDate,$alias);

  }

  public function GetChiefPostList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,$this->relChief,$keyDate,'post');
  }

  public function GetChildrenOrgList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,$this->relStruct,$keyDate,'child');
  }
  public function GetLastChiefPerson($orgId=0,$keyDate='')
  {
    $relCode = array($this->relChief,$this->relHold);
    $alias   = array('post','person');
    $count   = $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);
    while ($count == 0) {
      $parent = $this->GetParentOrg($orgId,$keyDate,'parent');
      $orgId  = $parent->parent_id;
      $count  = $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);

    }
    return $this->BaseModel->GetLastTopDownRel($orgId,$relCode,$keyDate,$alias);

  }

  public function GetLastChiefPost($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastTopDownRel($orgId,$this->relChief,$keyDate,'post');
  }

  public function GetLastName($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($orgId,$keyDate);
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keydate['begin'] = $beginDate;
    $keydate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keydate);
  }

  public function GetNameHistoryList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetAttrList($orgId,$keyDate);
  }

  public function GetParentOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($orgId,$this->relStruct,$keyDate,'parent');
  }

  public function GetParentOrgList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($orgId,$this->relStruct,$keyDate,'parent');
  }

  public function GetPersonList($orgId=0,$keyDate='')
  {
    $relCode = array($this->relAssign,$this->relHold);
    $alias   = array('post','person');
    return $this->BaseModel->GetTopDownRelList($orgId,$relCode,$keyDate,$alias);

  }

  public function GetPostList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,$this->relAssign,$keyDate,'post');

  }

  public function GetRelByIdRow($relId=0)
  {
    return $this->BaseModel->GetRelById($relId);
  }

  public function GetStruct($objId=0,$keydate=array())
  {
    $obj  = $this->GetByIdRow($objId,$keydate);
    $attr = $this->GetLastName($objId,$keydate);
    $result = array();
    if ($objId > 0) {
      $result[0] = array(
        'id'    => $obj->id,
        'name'  => $attr->name,
      );
      while ($this->CountParentOrg($objId,$keydate)) {
        $parent = $this->GetParentOrg($objId,$keydate);
        $result[] = array(
          'id'    => $parent->parent_id,
          'name'  => $parent->parent_name,
        );
        $objId  = $parent->parent_id;
      }
    }

    return array_reverse($result);
  }
}
