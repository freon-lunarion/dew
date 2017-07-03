<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller{

  private $viewDir   = 'post/';
  private $selfCtrl = 'Om/Post/';

  public function __construct()
  {
    parent::__construct();
    $this->load->model('PostModel');
    $this->load->library('parser');
    $this->load->helper(array('html','url','security'));

  }

  function index()
  {
    $this->session->unset_userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    if ($begin == '') {
      $begin = date('Y-m-d');
    }

    if ($end == '') {
      $end = date('Y-m-d');
    }

    $data['ajaxUrl'] = $this->selfCtrl.'AjaxGetList';
    $data['begin'] = $begin;
    $data['end']   = $end;
    $data['addLink'] = $this->selfCtrl.'Add';

    $this->parser->parse($this->viewDir.'main_view',$data);
  }

  public function AjaxGetList()
  {
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $rows = $this->PostModel->GetList($begin,$end);

    $data['rows'] = array();
    $i = 0 ;
    foreach ($rows as $row) {
      $temp = array(
        'id'       => $row->id,
        'begda'    => $row->begin_date,
        'endda'    => $row->end_date,
        'name'     => $row->name,
        'short'    => $row->short_name,
        'viewlink' => anchor($this->selfCtrl.'View/'.$row->id,'View','class="btn btn-link" title="view"'),
      );
      $data['rows'][$i] = $temp;
      $i++;
    }
    $this->parser->parse('_element/obj_tbl',$data);

  }

  public function Add()
  {
    $this->load->helper('form');
    $this->load->model(array('OrgModel','JobModel','EmpModel'));
    $begin  = $this->session->userdata('filterBegDa');
    $end    = $this->session->userdata('filterEndDa');
    if (is_null($begin) OR $begin == '') {
      $begin = date('Y-m-d');
    }
    if (is_null($end) OR $end == '') {
      $end = date('Y-m-d');
    }

    $ls     = $this->JobModel->GetList($begin,$end);
    $job = array();
    foreach ($ls as $row) {
      $job[$row->id] = $row->id.' - '.$row->name;
    }

    $ls  = $this->EmpModel->GetList($begin,$end);
    $emp = array(''=>'');
    foreach ($ls as $row) {
      $emp[$row->id] = $row->id.' - '.$row->name;
    }
    $data = array(
      'orgId' => '',
      'orgName' => '',
      'postId' => '',
      'postName' => '',
      'process' => $this->selfCtrl.'AddProcess',
      'jobOpt' => $job,
      'empOpt' => $emp,
      'cancelLink' => $this->selfCtrl,
    );
    $this->load->view($this->viewDir.'add_form',$data);

  }

  public function AddProcess()
  {
    $begin   = $this->input->post('dt_begin');
    $end     = $this->input->post('dt_end');
    $name    = $this->input->post('txt_name');
    $spr     = $this->input->post('hdn_post');
    $parent  = $this->input->post('hdn_org');
    $job     = $this->input->post('slc_job');
    $isChief = $this->input->post('chk_chief');
    $emp     = $this->input->post('slc_emp');
    $this->PostModel->Create($name,$begin,$end,$parent,$spr,$isChief,$job,$emp);
    redirect($this->selfCtrl);
  }

  public function DeleteRelProcess($relId=0)
  {
    $this->OrgModel->DeleteRel($relId);
    redirect($this->selfCtrl.'View/');
  }

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->PostModel->Delete($id);
    redirect($this->selfCtrl);

  }
  public function EditAssignment()
  {
    $this->load->helper('form');

    $this->load->model(array('OrgModel'));
    $id     = $this->session->userdata('selectId');
    $begin  = $this->session->userdata('filterBegDa');
    $end    = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $old = $this->PostModel->GetLastAssignmentOrg($id,$keydate);
    $data['orgId']   = $old->org_id;
    $data['orgName'] = $old->org_name;

    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process']    = $this->selfCtrl.'EditAssignmentProcess/';
    $this->load->view($this->viewDir.'assignment_form', $data);

  }

  public function EditAssignmentProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newOrg  = $this->input->post('hdn_org');
    $id      = $this->session->userdata('selectId');
    $this->PostModel->ChangeAssigmentOrg($id,$newOrg,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/');
  }

  public function EditDate()
  {
    $this->load->helper('form');

    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old = $this->PostModel->GetByIdRow($id);
    $data['end']    = $old->end_date;
    $data['begin']  = $old->begin_date;
    $data['hidden'] = array();
    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process'] = $this->selfCtrl.'EditDateProcess';
    $this->load->view($this->viewDir.'date_form', $data);

  }

  public function EditDateProcess()
  {
    $id  = $this->session->userdata('selectId');
    $end = $this->input->post('dt_end');
    $this->PostModel->Delimit($id,$end);
    redirect($this->selfCtrl.'View/');

  }

  public function EditHolder()
  {
    $this->load->helper('form');

    $this->load->model('PersModel');
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == '') {
      redirect($this->selfCtrl);
    }

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $ls = $this->PersModel->GetList($begin,$end);
    $empOpt = array(''=>'');
    foreach ($ls as $row) {
      $empOpt[$row->id] = $row->id .' - '.$row->name;
    }

    if ($this->PostModel->CountHolder($id,$keydate)) {
      $emp = $this->PostModel->GetLastHolder($id,$keydate)->person_id;
    } else {
      $emp = '';
    }

    $data['empOpt']   = $empOpt;
    $data['empSlc']   = $emp;
    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process']  = $this->selfCtrl.'EditHolderProcess/';
    $this->load->view($this->viewDir.'holder_form', $data);

  }

  public function EditHolderProcess()
  {
    $validOn   = $this->input->post('dt_begin');
    $newHolder = $this->input->post('rd_emp');
    $id        = $this->session->userdata('selectId');
    $this->PostModel->ChangeHolder($id,$newHolder,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/');
  }

  public function EditJob()
  {
    $this->load->helper('form');

    $this->load->model('JobModel');
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == '') {
      redirect($this->selfCtrl);
    }

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $ls = $this->JobModel->GetList($begin,$end);
    $jobOpt = array();
    foreach ($ls as $row) {
      $jobOpt[$row->id] = $row->id .' - '.$row->name;
    }
    $job = $this->PostModel->GetLastJob($id,$keydate);
    $data = array(
      'jobOpt'     => $jobOpt,
      'jobSlc'     => $job->job_id,
      'cancelLink' => $this->selfCtrl.'View/',
      'process'    => $this->selfCtrl.'EditJobProcess/',
    );

    $this->load->view($this->viewDir.'job_form', $data);

  }

  public function EditJobProcess()
  {
    $validOn   = $this->input->post('dt_begin');
    $newJob    = $this->input->post('slc_job');
    $id        = $this->session->userdata('selectId');
    $this->PostModel->ChangeJob($id,$newJob,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/');
  }

  public function EditManaging()
  {
    $this->load->helper('form');

    $this->load->model(array('OrgModel'));
    $id     = $this->session->userdata('selectId');
    $begin  = $this->session->userdata('filterBegDa');
    $end    = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    if ($this->PostModel->CountManagingOrg($id,$keydate)) {
      $old = $this->PostModel->GetLastManagingOrg($id,$keydate);
      $data['orgId']   = $old->org_id;
      $data['orgName'] = $old->org_name;
    } else {
      $data['orgId']   = '';
      $data['orgName'] = '';
    }

    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process']    = $this->selfCtrl.'EditManagingProcess';
    $this->load->view($this->viewDir.'managing_form', $data);

  }

  public function EditManagingProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newOrg  = $this->input->post('hdn_org');
    $id      = $this->session->userdata('selectId');
    $this->PostModel->ChangeManagingOrg($id,$newOrg,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/');
  }

  public function EditName()
  {
    $this->load->helper('form');

    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old                = $this->PostModel->GetLastName($id);
    $data['begin']      = date('Y-m-d');
    $data['name']       = $old->name;
    $data['short']      = $old->short_name;
    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process']    = $this->selfCtrl.'EditNameProcess';
    $this->load->view($this->viewDir.'name_form', $data);

  }

  public function EditNameProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newName = $this->input->post('txt_name');
    $id      = $this->session->userdata('selectId');
    $this->PostModel->ChangeName($id,$newName,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/'.$id.'/'.$validOn.'/9999-12-31');
  }

  public function EditRel($relId=0)
  {
    $this->load->helper('form');

    $data['hidden']  = array(
      'rel_id' => $relId
    );
    $old = $this->PostModel->GetRelByIdRow($relId);
    $data['process'] = $this->selfCtrl.'EditRelProcess';
    $data['begin']   = $old->begin_date;
    $data['end']     = $old->end_date;
    $data['cancelLink'] = $this->selfCtrl.'View/';

    $this->load->view($this->viewDir.'date_form', $data);
  }

  public function EditRelProcess()
  {
    $relId = $this->input->post('rel_id');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $this->PostModel->ChangeRelDate($relId,$begin,$end);
    redirect($this->selfCtrl.'View/');
  }

  public function EditSupervisor()
  {
    $this->load->helper('form');

    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == '') {
      redirect($this->selfCtrl);
    }

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $post = $this->PostModel->GetLastSupervisor($id,$keydate);
    $data['postId']     = $post->post_id;
    $data['postName']   = $post->post_name;
    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process']    = $this->selfCtrl.'EditSupervisorProcess/';
    $this->load->view($this->viewDir.'supervisor_form', $data);

  }

  public function EditSupervisorProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newPost = $this->input->post('hdn_post');
    $id      = $this->session->userdata('selectId');
    $this->PostModel->ChangeSupervisor($id,$newPost,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/');
  }

  public function View($id=0)
  {
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == 0 ) {
      $id    = $this->session->userdata('selectId');
    } else {
      $array = array(
        'selectId' => $id,
      );
      $this->session->set_userdata($array);
    }
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['backLink'] = $this->selfCtrl;
    $data['delLink']  = $this->selfCtrl.'DeleteProcess';
    $data['ajaxUrl1'] = $this->selfCtrl.'AjaxGetDetail';
    $data['ajaxUrl2'] = $this->selfCtrl.'AjaxGetRel';
    $this->parser->parse($this->viewDir.'detail_view',$data);
  }

  public function AjaxGetDetail()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $obj  = $this->PostModel->GetByIdRow($id);
    $attr = $this->PostModel->GetLastName($id,$keydate);
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['objBegin'] = $obj->begin_date;
    $data['objEnd']   = $obj->end_date;
    $data['objName']  = $attr->name;

    $data['editDate'] = $this->selfCtrl.'EditDate/';
    $data['editName'] = $this->selfCtrl.'EditName/';
    $this->parser->parse('_element/obj_detail',$data);


    $ls = $this->PostModel->GetNameHistoryList($id,$keydate,'desc');
    $history = array();
    foreach ($ls as $row) {
      if ($attr->id == $row->id) {
        $class = 'info';
      } else {
        $class = '';
      }
      $history[] = array(
        'historyRow'   => $class,
        'historyBegin' => $row->begin_date,
        'historyEnd'   => $row->end_date,
        'historyName'  => $row->name,
      );
    }
    $data['history']  = $history;
    $this->parser->parse('_element/hisname_tbl',$data);

  }

  public function AjaxGetRel()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    $delimit = site_url($this->selfCtrl.'EditRel/');
    $remove  = site_url($this->selfCtrl.'DeleteRelProcess/');

    $viewOrg  = site_url('Org/View/');
    $viewPost = site_url('Post/View/');
    $viewPers = site_url('Pers/View/');
    $viewJob  = site_url('Job/View/');

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $data['editAss']    = $this->selfCtrl.'EditAssignment/';
    $data['editHolder'] = $this->selfCtrl.'EditHolder/';
    $data['editJob']    = $this->selfCtrl.'EditJob/';
    $data['editMan']    = $this->selfCtrl.'EditManaging/';
    $data['editSpr']    = $this->selfCtrl.'EditSupervisor/';
    if ($this->PostModel->CountSupervisor($id,$keydate)) {
      $spr = $this->PostModel->GetLastSupervisor($id,$keydate);
      $data['sprPostId']   = $spr->post_id;
      $data['sprPostName'] = $spr->post_name;
    } else {
      $data['sprPostId']   = '-';
      $data['sprPostName'] = '-';
    }

    $ls = $this->PostModel->GetSupervisorList($id,$keydate);
    $spr = array();
    foreach ($ls as $row) {
      $spr[] = array(
        'sprBegin' => $row->post_begin_date,
        'sprEnd'   => $row->post_end_date,
        'sprId'    => $row->post_id,
        'sprName'  => $row->post_name,
        'viewPost' => $viewPost.$row->post_id,

      );
    }
    $data['spr'] = $spr;
    if ($this->PostModel->CountHolder($id,$keydate)) {
      $holder = $this->PostModel->GetLastHolder($id,$keydate);
      $data['holderBegin'] = $holder->person_begin_date;
      $data['holderEnd']   = $holder->person_end_date;
      $data['holderId']    = $holder->person_id;
      $data['holderName']  = $holder->person_name;
    } else {
      $data['holderBegin'] = '';
      $data['holderEnd']   = '';
      $data['holderId']    = '';
      $data['holderName']  = '';
    }

    $ls = $this->PostModel->GetHolderHistoryList($id,$keydate);
    $holder = array();
    foreach ($ls as $row) {
      $holder[] = array(
        'holderBegin' => $row->person_begin_date,
        'holderEnd'   => $row->person_end_date,
        'holderId'    => $row->person_id,
        'holderName'  => $row->person_name,
        'viewPers'    => $viewPers.$row->person_id,

      );
    }
    $data['holder'] = $holder;

    $job = $this->PostModel->GetLastJob($id,$keydate);
    $data['jobBegin'] = $job->job_begin_date;
    $data['jobEnd']   = $job->job_end_date;
    $data['jobId']    = $job->job_id;
    $data['jobName']  = $job->job_name;
    $ls = $this->PostModel->GetJobList($id,$keydate);

    $job = array();
    foreach ($ls as $row) {
      $job[] = array(
        'jobBegin' => $row->job_begin_date,
        'jobEnd'   => $row->job_end_date,
        'jobId'    => $row->job_id,
        'jobName'  => $row->job_name,
        'viewJob'  => $viewJob.$row->job_id,

      );
    }
    $data['job'] = $job;

    $sub = array();
    $ls  = $this->PostModel->GetSubordinatePostList($id,$keydate);
    foreach ($ls as $row) {
      $sub[] = array(
        'subBegin'    => $row->post_begin_date,
        'subEnd'      => $row->post_end_date,
        'subPostId'   => $row->post_id,
        'subPostName' => $row->post_name,
        'chgRel'      => $delimit.$row->post_rel_id,
        'remRel'      => $remove.$row->post_rel_id,
        'viewPost'    => $viewPost.$row->post_id,
      );
    }
    $data['sub'] = $sub;

    $peer = array();
    if ($this->PostModel->CountPeerPerson($id,$keydate)) {
      $ls = $this->PostModel->GetPeerPostList($id,$keydate);
      foreach ($ls as $row) {
        $peer[] = array(
          'peerBegin'    => $row->post_begin_date,
          'peerEnd'      => $row->post_end_date,
          'peerPostId'   => $row->post_id,
          'peerPostName' => $row->post_name,
          'viewPost'     => $viewPost.$row->post_id,
        );
      }
    }
    $data['peer'] = $peer;

    $data['man']     = array();
    $data['manId']   = '';
    $data['manName'] = '';
    if ($this->PostModel->CountManagingOrg($id,$keydate)) {
      $man = $this->PostModel->GetLastManagingOrg($id,$keydate);
      $data['manId']   = $man->org_id;
      $data['manName'] = $man->org_name;

      $ls = $this->PostModel->GetManagingOrgList($id,$keydate);
      $man = array();
      foreach ($ls as $row) {
        $man[] = array(
          'manBegin' => $row->org_begin_date,
          'manEnd'   => $row->org_end_date,
          'manId'    => $row->org_id,
          'manName'  => $row->org_name,
          'viewOrg'  => $viewOrg.$row->org_id,

        );
      }
      $data['man']     = $man;
    }

    $data['ass']     = array();
    $data['assId']   = '';
    $data['assName'] = '';
    $ass = $this->PostModel->GetLastAssignmentOrg($id,$keydate);
    $data['assId']   = $ass->org_id;
    $data['assName'] = $ass->org_name;

    $ls = $this->PostModel->GetAssignmentOrgList($id,$keydate);
    $ass = array();
    foreach ($ls as $row) {
      $ass[] = array(
        'assBegin' => $row->org_begin_date,
        'assEnd'   => $row->org_end_date,
        'assId'    => $row->org_id,
        'assName'  => $row->org_name,
        'viewOrg'  => $viewOrg.$row->org_id,
      );
    }
    $data['ass']     = $ass;
    $this->parser->parse($this->viewDir . 'rel_elm',$data);

  }

}
