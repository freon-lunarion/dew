<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostModel extends CI_Model{

  private $objType   = 'POS';
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
    //Codeigniter : Write Less Do More
    $this->load->model('BaseModel');
  }

  public function ChangeAssigmentOrg($postId=0,$newOrg=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp',$this->relAssign,$postId,$newOrg,$validOn,$endDate);
  }

  public function ChangeHolder($postId=0,$newPersId=FALSE,$validOn='',$endDate='9999-12-31')
  {
    if ($newPersId) {
      $this->BaseModel->ChangeRel('TopDown',$this->relHold,$postId,$newPersId,$validOn,$endDate);
    }
  }

  public function ChangeJob($postId=0,$newJobId=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp',$this->relJob,$postId,$newPersId,$validOn,$endDate);
  }
  public function ChangeName($postId = 0, $newName='',$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeAttr($postId,$newName,$validOn,$endDate);
  }

  public function ChangeManagingOrg($postId=0,$newOrg=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp',$this->relChief,$postId,$newOrg,$validOn,$endDate);
  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
  }

  public function ChangeSupervisor($postId=0,$newPost=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp',$this->relReport,$postId,$newOrg,$validOn,$endDate);
  }

  public function CountAssigmentOrg($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,$this->relChief,$keyDate);
  }

  public function CountHolder($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($postId,$this->relHold,$keyDate);
  }

  public function CountJob($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,$this->relJob,$keyDate);
  }

  public function CountPeerPerson($postId=0,$keyDate='')
  {
    $chief = $this->GetLastSupervisor($postId,$keyDate);
    if ($chief) {
      $relCode = array($this->relReport,$this->relHold);
      return ($this->BaseModel->CountTopDownRel($chief->post_id,$relCode,$keyDate) - 1);
    } else {
      return false;
    }

  }

  public function CountPeerPost($postId=0,$keyDate='')
  {
    $chiefId = $this->GetSupervisor($postId,$keyDate)->post_id;
    return   ($this->BaseModel->CountTopDownRel($chiefId,$this->relReport,$keyDate) - 1);

  }

  public function CountManagingOrg($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,$this->relChief,$keyDate);
  }

  public function CountSubordinatePerson($postId=0,$keyDate='')
  {
    $relCode = array($this->relReport,$this->relHold);
    return $this->BaseModel->CountTopDownRel($postId,$relCode,$keyDate);

  }

  public function CountSubordinatePost($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($postId,$this->relReport,$keyDate);
  }

  public function CountSupervisor($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,$this->relReport,$keyDate);
  }

  public function Create($name='',$beginDate='1990-01-01',$endDate='9999-12-31-31',$orgId=0,$reportTo=0,$isChief=FALSE,$jobId=0,$empId=false)
  {
    $postId = $this->BaseModel->Create($this->objType,$name,$beginDate,$endDate);

    $this->BaseModel->CreateRel($this->relReport,$reportTo,$postId,$beginDate,$endDate);
    $this->BaseModel->CreateRel($this->relAssign,$orgId,$postId,$beginDate,$endDate);
    if ($isChief) {
      $this->BaseModel->CreateRel($this->relChief,$orgId,$postId,$beginDate,$endDate);
    }
    $this->BaseModel->CreateRel($this->relJob,$jobId,$postId,$beginDate,$endDate);
    if ($empId) {
      $this->BaseModel->CreateRel($this->relHold,$postId,$empId,$beginDate,$endDate);
    }
    return $postId;
  }

  public function Delete($postId=0)
  {
    $this->BaseModel->Delete($postId);
  }

  public function DeleteRel($relId=0)
  {
    $this->BaseModel->DeleteRel($relId);
  }

  public function Delimit($postId=0,$endDate='')
  {
    $this->BaseModel->Delimit($postId,$endDate);
  }

  public function GetAssignmentOrgList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,$this->relAssign,$keyDate,'org');
  }

  public function GetByIdRow($id=0)
  {
    return $this->BaseModel->GetByIdRow($id);
  }

  public function GetHolderHistoryList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($postId,$this->relHold,$keyDate,'person');
  }

  public function GetJobList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,$this->relJob,$keyDate,'job');
  }

  public function GetLastAssignmentOrg($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,$this->relAssign,$keyDate,'org');

  }

  public function GetLastManagingOrg($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,$this->relChief,$keyDate,'org');

  }

  public function GetLastName($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($postId,$keyDate);
  }

  public function GetLastHolder($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastTopDownRel($postId,$this->relHold,$keyDate,'person');

  }

  public function GetLastJob($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,$this->relJob,$keyDate,'job');

  }

  public function GetReportTo($postId=0,$keyDate='')
  {
    // TODO check "Reporting To" / "Supervisor" relation
    if ($this->BaseModel->CountBotUpRel($postId,$this->relReport,$keyDate) == TRUE) {
      $spr = $this->BaseModel->GetLastBotUpRel($postId,$this->relReport,$keyDate,'post');
    } else {
      // TODO Check status "Chief"
      $orgId = $this->BaseModel->GetLastBotUpRel($postId,$this->relAssign,$keyDate,'org')->org_id;
      if ($this->BaseModel->CountBotUpRel($postId,$this->relChief,$keyDate) == TRUE) {
        if ($this->BaseModel->CountBotUpRel($orgId,$this->relStruct,$keyDate)) {
          $orgId = $this->BaseModel->GetLastBotUpRel($orgId,$this->relStruct,$keyDate,'org')->org_id;
        }
      }
      // TODO Search chief post of organization
      $spr = $this->BaseModel->GetLastTopDownRel($orgId,$this->relChief,$keyDate,'post');

    }

    // Check Holder of position
    if ($this->BaseModel->CountTopDownRel($spr->post_id,$this->relHold,$keyDate) == TRUE) {
      return $spr;
    } else {
      return $this->GetReportTo($spr->post_id,$keyDate);
    }
  }

  public function GetLastSupervisor($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,$this->relReport,$keyDate,'post');
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keydate['begin'] = $beginDate;
    $keydate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keydate);
  }

  public function GetManagingOrgList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,$this->relChief,$keyDate,'org');
  }

  public function GetNameHistoryList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetAttrList($postId,$keyDate);
  }

  public function GetPeerPostList($postId=0,$keyDate='')
  {
    $chiefId = $this->GetLastSupervisor($postId,$keyDate)->post_id;
    $list = $this->BaseModel->GetTopDownRelList($chiefId,$this->relReport,$keyDate,'post');

    $result = array();
    foreach ($list as $row) {
      if ($row->post_id != $postId) {
        $result[] = $row;
      }
    }
    return $result;

  }

  public function GetPeerPersonList($postId=0,$keyDate='')
  {
    $chiefId = $this->GetSupervisor($postId,$keyDate)->post_id;
    $relCode = array($this->relReport,$this->relHold);
    $alias   = array('post','person');

    $list = $this->BaseModel->GetTopDownRelList($chiefId,$relCode,$keyDate,$alias);

    $result = array();
    foreach ($list as $row) {
      if ($row->post_id != $postId) {
        $result[] = $row;
      }
    }
    return $result;

  }

  public function GetRelByIdRow($relId=0)
  {
    return $this->BaseModel->GetRelById($relId);
  }

  public function GetSubordinatePersonList($postId=0,$keyDate='')
  {
    $relCode = array($this->relReport,$this->relHold);
    $alias   = array('post','person');
    return $this->BaseModel->GetTopDownRelList($postId,$relCode,$keyDate,$alias);

  }

  public function GetSubordinatePostList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($postId,$this->relReport,$keyDate,'post');
  }

  public function GetSupervisorList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,$this->relReport,$keyDate,'post');
  }

}
