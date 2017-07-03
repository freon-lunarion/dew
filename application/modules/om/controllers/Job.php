<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends CI_Controller{

  private $viewDir  = 'job/';
  private $selfCtrl = 'Om/Job/';
  public function __construct()
  {
    parent::__construct();
    $this->load->model('JobModel'); // BaseModel is included
    $this->load->library('parser');
    
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
    $rows = $this->JobModel->GetList($begin,$end);
    $data['rows'] = array();
    $i = 0 ;
    foreach ($rows as $row) {
      $temp = array(
        'id'       => $row->id,
        'begda'    => $row->begin_date,
        'endda'    => $row->end_date,
        'name'     => $row->name,
        'short'    => $row->short_name,
        'viewlink' => anchor($this->selfCtrl.'View/'.$row->id,'View','class ="btn btn-link" title ="view"'),
      );
      $data['rows'][$i] = $temp;
      $i++;
    }
    $this->parser->parse('_element/obj_tbl',$data);
  }

  public function Add()
  {
    $this->load->helper('form');

    $data['cancelLink'] = $this->selfCtrl;
    $data['process'] = $this->selfCtrl.'AddProcess';
    $this->load->view($this->viewDir.'add_form',$data);
  }

  public function AddProcess()
  {
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $name  = $this->input->post('txt_name');
    $short = $this->input->post('txt_short');

    $this->JobModel->Create($name,$short,$begin,$end);
    redirect($this->selfCtrl);
  }

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->BaseModel->Delete($id);
    redirect($this->selfCtrl);

  }

  public function DeleteRelProcess($relId=0)
  {
    $this->JobModel->DeleteRel($relId);
    redirect($this->selfCtrl.'View/');
  }

  public function EditDate()
  {
    $this->load->helper('form');

    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old = $this->JobModel->GetByIdRow($id);
    $data['begin'] = $old->begin_date;
    $data['end']   = $old->end_date;

    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['hidden']  = array();
    $data['process'] = $this->selfCtrl.'EditDateProcess';
    $this->load->view($this->viewDir.'date_form', $data);

  }

  public function EditDateProcess()
  {
    $id  = $this->session->userdata('selectId');
    $end = $this->input->post('dt_end');
    $this->JobModel->Delimit($id,$end);
    redirect($this->selfCtrl.'View/');
  }

  public function EditName()
  {
    $this->load->helper('form');

    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old                = $this->JobModel->GetLastName($id);
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
    $short   = $this->input->post('txt_short');
    $id      = $this->session->userdata('selectId');
    $this->JobModel->ChangeName($id,$newName,$short,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/'.$id);
  }

  public function EditRel($relId=0)
  {
    $this->load->helper('form');

    $data['hidden']  = array(
      'rel_id' => $relId
    );
    $old = $this->JobModel->GetRelByIdRow($relId);
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
    $this->JobModel->ChangeRelDate($relId,$begin,$end);
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

    $data = array(
      'objBegin' => '',
      'objEnd'   => '',
      'objName'  => '',
    );
    $obj  = $this->JobModel->GetByIdRow($id);
    if ($obj) {
      $attr = $this->JobModel->GetLastName($id,$keydate);
      $data = array(
        'objBegin' => $obj->begin_date,
        'objEnd'   => $obj->end_date,
        'objName'  => $attr->name,
        'objShort' => $attr->short_name,
        'objDescr' => $attr->description,
      );
    }
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['editDate'] = $this->selfCtrl.'EditDate/';
    $data['editName'] = $this->selfCtrl.'EditName/';
    $this->parser->parse('_element/obj_detail',$data);

    $ls =  $this->JobModel->GetNameHistoryList($id,$keydate,'desc');
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
        'historyShort' => $row->short_name,
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

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $ls = $this->JobModel->GetRelatedPostList($id,$keydate);
    $post = array();

    foreach ($ls as $row) {
      $post[] = array(
        'postRelId' => $row->post_rel_id,
        'postBegin' => $row->post_begin_date,
        'postEnd'   => $row->post_end_date,
        'postId'    => $row->post_id,
        'postName'  => $row->post_name,
        'chgRel'    => $delimit.$row->post_rel_id,
        'remRel'    => $remove.$row->post_rel_id,
        'viewPost'  => site_url('Post/View/'.$row->post_id),
      );
    }
    $data['post']     = $post;

    $this->parser->parse($this->viewDir . 'rel_elm',$data);

  }

}
