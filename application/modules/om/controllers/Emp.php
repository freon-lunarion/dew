<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emp extends CI_Controller{

  private $viewDir   = 'emp/';
  private $selfCtrl = 'Om/Emp/';
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


    $data['ajaxUrl'] = $this->selfCtrl.'AjaxGetList';
    $data['begin']   = $begin;
    $data['end']     = $end;

    $data['addLink'] = $this->selfCtrl.'Add';
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

    $this->load->model('PostModel');
    $ls    = $this->PostModel->GetList(date('Y-m-d'),date('Y-m-d'));
    $post  = array(''=>'');
    foreach ($ls as $row) {
      $post[$row->id] = $row->id.' - '.$row->name;
    }
    $data['postOpt']    = $post;
    $data['postSlc']    = '';
    $data['cancelLink'] = $this->selfCtrl;

    $data['process'] = $this->selfCtrl.'AddProcess';
    $this->load->view($this->viewDir.'add_form',$data);
  }

  public function AddPost()
  {
    $this->load->helper('form');

    $this->load->model('PostModel');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    $data['postId']     = '';
    $data['postName']   = '';
    $data['begin']      = date('Y-m-d');
    $data['end']        = '9999-12-31';
    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process']    = $this->selfCtrl.'AddPostProcess';
    $this->load->view($this->viewDir.'post_form',$data);

  }

  public function AddPostProcess()
  {
    $persId = $this->session->userdata('selectId');;
    $begin  = $this->input->post('dt_begin');
    $end    = $this->input->post('dt_end');
    $postId = $this->input->post('hdn_post');
    $weight = $this->input->post('nm_weight');
    $this->EmpModel->AddPost($persId,$postId,$begin,$end);
    redirect($this->selfCtrl);
  }

  public function AddProcess()
  {
    $begin  = $this->input->post('dt_begin');
    $end    = $this->input->post('dt_end');
    $name   = $this->input->post('txt_name');
    $short  = $this->input->post('txt_short');
    $weight = $this->input->post('nm_weight');
    $postId = $this->input->post('hdn_post');
    $this->EmpModel->Create($name,$short,$postId,$weight,$begin,$end);
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
    $this->EmpModel->DeleteRel($relId);
    redirect($this->selfCtrl.'View/');
  }

  public function EditDate()
  {
    $this->load->helper('form');

    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old = $this->EmpModel->GetByIdRow($id);
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
    $this->EmpModel->Delimit($id,$end);
    redirect($this->selfCtrl.'View/');
  }

  public function EditName()
  {
    $this->load->helper('form');

    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old                = $this->EmpModel->GetNameRow($id);
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
    $this->EmpModel->ChangeName($id,$newName,$short,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/'.$id.'/'.$validOn.'/9999-12-31');
  }

  public function EditRel($relId=0)
  {
    $this->load->helper('form');

    $data['hidden']  = array(
      'rel_id' => $relId
    );
    $old = $this->EmpModel->GetRelByIdRow($relId);
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
    $this->EmpModel->ChangeRelDate($relId,$begin,$end);
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

  public function ViewSpr($relId=0)
  {
    $this->load->model(array('PostModel'));
    $rel = $this->EmpModel->GetRelByIdRow($relId);
    $keydate['begin'] = $this->session->userdata('filterBegDa');
    $keydate['end']   = $this->session->userdata('filterEndDa');
    $persObj = $this->EmpModel->GetByIdRow($rel->obj_bottom_id,$keydate);
    $persAtr = $this->EmpModel->GetNameRow($rel->obj_bottom_id,$keydate);
    $postAtr = $this->PostModel->GetNameRow($rel->obj_top_id,$keydate);

    $sprPost = $this->PostModel->GetReportTo($rel->obj_top_id,$keydate);
    $sprPers = $this->PostModel->GetLastHolder($sprPost->post_id,$keydate);
    $data['backLink']    = $this->selfCtrl.'View/';
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
    $attr = $this->EmpModel->GetNameRow($id,$keydate);

    $data['objBegin'] = $obj->begin_date;
    $data['objEnd']   = $obj->end_date;
    $data['objName']  = $attr->name;
    $data['objShort'] = $attr->short_name;
    $data['editDate'] = $this->selfCtrl.'EditDate/';
    $data['editName'] = $this->selfCtrl.'EditName/';
    $this->parser->parse('_element/obj_detail',$data);

    $ls =  $this->EmpModel->GetNameList($id,$keydate,'desc');
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
    $delimit  = site_url($this->selfCtrl.'EditRel/');
    $remove   = site_url($this->selfCtrl.'DeleteRelProcess/');
    $sprLk    = site_url($this->selfCtrl.'ViewSpr/');
    $viewPost = site_url('Post/View/');

    $ls = $this->EmpModel->GetPostList($id,$keydate,'desc');
    $post = array();
    foreach ($ls as $row) {
      $post[] = array(
        'postRelId' => $row->post_rel_id,
        'postWeight' => $row->post_weight,
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
    $data['addPost']  = $this->selfCtrl.'AddPost/';

    $data['post']     = $post;
    $this->parser->parse($this->viewDir . 'rel_elm',$data);

  }

}
