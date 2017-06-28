<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emp extends CI_Controller{

  private $viewDir   = 'emp/';
  private $ctrlClass = 'Om/Emp/';
  public function __construct()
  {
    parent::__construct();
    $this->load->model('EmpModel'); // BaseModel is included
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


    $data['ajaxUrl'] = $this->ctrlClass.'AjaxGetList';
    $data['begin']   = $begin;
    $data['end']     = $end;

    $data['addLink'] = $this->ctrlClass.'Add';
    $this->parser->parse($this->viewDir.'main_view',$data);
  }

  public function AjaxGetList()
  {
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $rows  = $this->EmpModel->GetList($begin,$end);
    $data['rows'] = array();
    $i = 0 ;
    foreach ($rows as $row) {
      $temp = array(
        'id'       => $row->id,
        'begda'    => $row->begin_date,
        'endda'    => $row->end_date,
        'name'     => $row->name,
        'viewlink' => anchor($this->ctrlClass.'View/'.$row->id,'View','class="btn btn-link" title="view"'),
      );
      $data['rows'][$i] = $temp;
      $i++;
    }
    $this->parser->parse('_element/obj_tbl',$data);
  }

  public function Add()
  {
    $this->load->model('PostModel');
    $ls    = $this->PostModel->GetList(date('Y-m-d'),date('Y-m-d'));
    $post  = array(''=>'');
    foreach ($ls as $row) {
      $post[$row->id] = $row->id.' - '.$row->name;
    }
    $data['postOpt']    = $post;
    $data['postSlc']    = '';
    $data['cancelLink'] = $this->ctrlClass;

    $data['process'] = $this->ctrlClass.'AddProcess';
    $this->load->view($this->viewDir.'add_form',$data);
  }

  public function AddPost()
  {
    $this->load->model('PostModel');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    $data['postId']     = '';
    $data['postName']   = '';
    $data['begin']      = date('Y-m-d');
    $data['end']        = '9999-12-31';
    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']    = $this->ctrlClass.'AddPostProcess';
    $this->load->view($this->viewDir.'post_form',$data);

  }

  public function AddPostProcess()
  {
    $persId = $this->session->userdata('selectId');;
    $begin  = $this->input->post('dt_begin');
    $end    = $this->input->post('dt_end');
    $postId = $this->input->post('slc_post');
    $this->EmpModel->AddPost($persId,$postId,$begin,$end);
    redirect($this->ctrlClass);
  }

  public function AddProcess()
  {
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $name  = $this->input->post('txt_name');
    $this->EmpModel->Create($name,$begin,$end);
    redirect($this->ctrlClass);
  }

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->BaseModel->Delete($id);
    redirect($this->ctrlClass);

  }

  public function DeleteRelProcess($relId=0)
  {
    $this->EmpModel->DeleteRel($relId);
    redirect($this->ctrlClass.'View/');
  }

  public function EditDate()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->ctrlClass);
    }
    $old = $this->EmpModel->GetByIdRow($id);
    $data['begin'] = $old->begin_date;
    $data['end']   = $old->end_date;

    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['hidden']  = array();
    $data['process'] = $this->ctrlClass.'EditDateProcess';
    $this->load->view($this->viewDir.'date_form', $data);

  }

  public function EditDateProcess()
  {
    $id  = $this->session->userdata('selectId');
    $end = $this->input->post('dt_end');
    $this->EmpModel->Delimit($id,$end);
    redirect($this->ctrlClass.'View/');
  }

  public function EditName()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->ctrlClass);
    }
    $old                = $this->EmpModel->GetLastName($id);
    $data['begin']      = date('Y-m-d');
    $data['name']       = $old->name;
    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']    = $this->ctrlClass.'EditNameProcess';
    $this->load->view($this->viewDir.'name_form', $data);

  }

  public function EditNameProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newName = $this->input->post('txt_name');
    $id      = $this->session->userdata('selectId');
    $this->EmpModel->ChangeName($id,$newName,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/'.$id.'/'.$validOn.'/9999-12-31');
  }

  public function EditRel($relId=0)
  {
    $data['hidden']  = array(
      'rel_id' => $relId
    );
    $old = $this->EmpModel->GetRelByIdRow($relId);
    $data['process'] = $this->ctrlClass.'EditRelProcess';
    $data['begin']   = $old->begin_date;
    $data['end']     = $old->end_date;
    $data['cancelLink'] = $this->ctrlClass.'View/';

    $this->load->view($this->viewDir.'date_form', $data);
  }

  public function EditRelProcess()
  {
    $relId = $this->input->post('rel_id');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $this->EmpModel->ChangeRelDate($relId,$begin,$end);
    redirect($this->ctrlClass.'View/');
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

    $data['backLink'] = $this->ctrlClass;
    $data['delLink']  = $this->ctrlClass.'DeleteProcess';
    $data['ajaxUrl1'] = $this->ctrlClass.'AjaxGetDetail';
    $data['ajaxUrl2'] = $this->ctrlClass.'AjaxGetRel';
    $this->parser->parse($this->viewDir.'detail_view',$data);
  }

  public function ViewSpr($relId=0)
  {
    $this->load->model(array('PostModel'));
    $rel = $this->EmpModel->GetRelByIdRow($relId);
    $keydate['begin'] = $this->session->userdata('filterBegDa');
    $keydate['end']   = $this->session->userdata('filterEndDa');
    $persObj = $this->EmpModel->GetByIdRow($rel->obj_bottom_id,$keydate);
    $persAtr = $this->EmpModel->GetLastName($rel->obj_bottom_id,$keydate);
    $postAtr = $this->PostModel->GetLastName($rel->obj_top_id,$keydate);

    $sprPost = $this->PostModel->GetReportTo($rel->obj_top_id,$keydate);
    $sprPers = $this->PostModel->GetLastHolder($sprPost->post_id,$keydate);
    $data['backLink']    = $this->ctrlClass.'View/';
    $data['persId']      = $rel->obj_bottom_id;

    $data['persName']    = $persAtr->name;
    $data['postId']      = $rel->obj_top_id;
    $data['postName']    = $postAtr->name;
    $data['sprPersId']   = $sprPers->person_id;
    $data['sprPersName'] = $sprPers->person_name;
    $data['sprPostId']   = $sprPost->post_id;
    $data['sprPostName'] = $sprPost->post_name;
    $data['viewPost']    = site_url('Post/View/'.$sprPost->post_id);
    $data['viewPers']    = site_url('Pers/View/'.$sprPers->person_id);

    $this->parser->parse($this->viewDir.'supervisor_view',$data);
  }

  public function AjaxGetDetail()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $obj  = $this->EmpModel->GetByIdRow($id);
    $attr = $this->EmpModel->GetLastName($id,$keydate);

    $data['objBegin'] = $obj->begin_date;
    $data['objEnd']   = $obj->end_date;
    $data['objName']  = $attr->name;
    $data['editDate'] = $this->ctrlClass.'EditDate/';
    $data['editName'] = $this->ctrlClass.'EditName/';
    $this->parser->parse('_element/obj_detail',$data);

    $ls =  $this->EmpModel->GetNameHistoryList($id,$keydate,'desc');
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
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $delimit  = site_url($this->ctrlClass.'EditRel/');
    $remove   = site_url($this->ctrlClass.'DeleteRelProcess/');
    $sprLk    = site_url($this->ctrlClass.'ViewSpr/');
    $viewPost = site_url('Post/View/');

    $ls = $this->EmpModel->GetPostList($id,$keydate,'desc');
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
        'sprLink'   => $sprLk.$row->post_rel_id,
        'viewPost'  => $viewPost.$row->post_rel_id,
      );
    }
    $data['addPost']  = $this->ctrlClass.'AddPost/';

    $data['post']     = $post;
    $this->parser->parse($this->viewDir . 'rel_elm',$data);

  }

}
