<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Org extends CI_Controller{

  private $viewDir   = 'org/';
  private $selfCtrl = 'Om/Org/';

  public function __construct()
  {
    parent::__construct();
    $this->load->model('OrgModel');
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
    $data['ajaxUrl'] = $this->selfCtrl.'AJaxStruc';

    $data['begin'] = $begin;
    $data['end']   = $end;
    $data['addLink'] = $this->selfCtrl.'Add';

    $this->parser->parse($this->viewDir.'main_view',$data);
  }


  public function Add()
  {
    $begin  = $this->session->userdata('filterBegDa');
    $end    = $this->session->userdata('filterEndDa');
    if (is_null($begin) OR $begin == '') {
      $begin = date('Y-m-d');
    }
    if (is_null($end) OR $end == '') {
      $end = date('Y-m-d');
    }

    $data['process']    = $this->selfCtrl.'AddProcess';
    $data['ajaxUrl']    = site_url($this->selfCtrl.'AJaxStruc');
    $data['orgId']      = '';
    $data['orgName']    = '';
    $data['cancelLink'] = $this->selfCtrl;

    $this->load->view($this->viewDir.'add_form',$data);

  }

  public function AddProcess()
  {
    $begin  = $this->input->post('dt_begin');
    $end    = $this->input->post('dt_end');
    $name   = $this->input->post('txt_name');
    $short  = $this->input->post('txt_short');
    $parent = $this->input->post('hdn_org');
    $this->OrgModel->Create($name,$short,$begin,$end,$parent);
    redirect($this->selfCtrl);
  }


  public function EditChief()
  {
    $this->load->model('PostModel');
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $old = $this->OrgModel->GetLastChiefPost($id,$keydate);

    $data['begin']      = date('Y-m-d');
    $data['postId']     = $old->post_id;
    $data['postName']   = $old->post_name;
    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process']    = $this->selfCtrl.'EditChiefProcess';
    $this->load->view($this->viewDir.'chief_form', $data);

  }

  public function EditChiefProcess()
  {
    $validOn  = $this->input->post('dt_begin');
    $newChief = $this->input->post('hdn_post');
    $id       = $this->session->userdata('selectId');
    $this->OrgModel->ChangeChiefPost($id,$newChief,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/');
  }

  public function EditDate()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old = $this->OrgModel->GetByIdRow($id);
    $data['hidden']     = array();
    $data['end']        = $old->end_date;
    $data['begin']      = $old->begin_date;
    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process']    = $this->selfCtrl.'EditDateProcess';
    $this->load->view($this->viewDir.'date_form', $data);

  }

  public function EditDateProcess()
  {
    $id  = $this->session->userdata('selectId');
    $end = $this->input->post('dt_end');
    $this->OrgModel->Delimit($id,$end);
    redirect($this->selfCtrl.'View/');

  }

  public function EditName()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old                = $this->OrgModel->GetLastName($id);
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
    $short = $this->input->post('txt_short');
    $id      = $this->session->userdata('selectId');
    $this->OrgModel->ChangeName($id,$newName,$short,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/'.$id);
  }

  public function EditParent()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $old = $this->OrgModel->GetParentOrg($id,$keydate);

    $data['orgId']   = $old->parent_id;
    $data['orgName'] = $old->parent_name;
    $data['cancelLink'] = $this->selfCtrl.'View/';
    $data['process'] = $this->selfCtrl.'EditParentProcess/';
    $this->load->view($this->viewDir.'parent_form', $data);
  }

  public function EditParentProcess()
  {
    $id = $this->session->userdata('selectId');
    $since     = $this->input->post('dt_begin');
    $newParent = $this->input->post('hdn_org');
    $this->OrgModel->ChangeParent($id,$newParent,$since,'9999-12-31');
    redirect($this->selfCtrl.'View/'.$id);

  }

  public function EditRel($relId=0)
  {
    $data['hidden']  = array(
      'rel_id' => $relId
    );
    $old = $this->OrgModel->GetRelByIdRow($relId);
    $data['process'] = $this->selfCtrl.'EditRelProcess';
    $data['begin']   = $old->begin_date;
    $data['end']     = $old->end_date;
    $data['cancelLink'] = $this->selfCtrl.'View/';

    $this->load->view($this->viewDir.'/date_form', $data);
  }

  public function EditRelProcess()
  {
    $relId = $this->input->post('rel_id');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $this->OrgModel->ChangeRelDate($relId,$begin,$end);
    redirect($this->selfCtrl.'View/');
  }

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->OrgModel->Delete($id);
    redirect($this->selfCtrl);

  }

  public function DeleteRelProcess($relId=0)
  {
    $this->OrgModel->DeleteRel($relId);
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
    $data['ajaxUrl1'] = $this->selfCtrl.'AjaxGetDetail';
    $data['ajaxUrl2'] = $this->selfCtrl.'AjaxGetRel';
    $data['backLink'] = $this->selfCtrl;
    $data['delLink']  = $this->selfCtrl.'DeleteProcess';

    $this->parser->parse($this->viewDir.'detail_view',$data);
  }

  public function AjaxGetDetail()
  {
    $id    = $this->session->userdata('selectId');
    if (!$this->session->userdata('filterBegDa') || !$this->session->userdata('filterEndDa')) {
      $sess = array(
        'filterBegDa' => date('Y-m-d'),
        'filterEndDa' => date('Y-m-d'),
      );
      $this->session->set_userdata($sess);
    }
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $obj  = $this->OrgModel->GetByIdRow($id);
    $attr = $this->OrgModel->GetLastName($id,$keydate);
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['objBegin'] = $obj->begin_date;
    $data['objEnd']   = $obj->end_date;
    $data['objName']  = $attr->name;
    $data['objShort'] = $attr->short_name;
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $data['editDate'] = $this->selfCtrl.'EditDate/';
    $data['editName'] = $this->selfCtrl.'EditName/';
    $this->parser->parse('_element/obj_detail',$data);

    $ls = $this->OrgModel->GetNameHistoryList($id,$keydate,'desc');
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

  public function AJaxStruc($mode="")
  {
    $id    = $this->input->post('id');
    if (!$this->session->userdata('filterBegDa') || !$this->session->userdata('filterEndDa')) {
      $sess = array(
        'filterBegDa' => date('Y-m-d'),
        'filterEndDa' => date('Y-m-d'),
      );
      $this->session->set_userdata($sess);
    }
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $date['begin'] = $begin;
    $date['end']   = $end;

    $bc = $this->OrgModel->GetStruct($id,$date);
    $data['bc'][0] = array(
      'id'   => 0,
      'name' => 'ROOT',
    );

    foreach ($bc as $row) {
      $data['bc'][] = $row;
    }
    if ($id > 0 && $id != '') {
      $children   = $this->OrgModel->GetChildrenOrgList($id,$date);
      $i = 0 ;
      $data['rows'] = array();

      foreach ($children as $row) {
        $temp = array(
          'id'       => $row->child_id,
          'begda'    => $row->child_begin_date,
          'endda'    => $row->child_end_date,
          'name'     => $row->child_name,
          'name'     => $row->child_short_name,
          'viewlink' => anchor($this->selfCtrl.'View/'.$row->child_id,'View','class="btn btn-link" title="view"'),
        );
        $data['rows'][$i] = $temp;
        $i++;
      }

    } else {
      $row = $this->OrgModel->GetByIdRow(1,$date);
      $name = $this->OrgModel->GetLastName(1,$date);
      $data['rows'][0] = array(
        'id'       => $row->id,
        'begda'    => $row->begin_date,
        'endda'    => $row->end_date,
        'name'     => $name->name,
        'name'     => $name->short_name,
        'viewlink' => anchor($this->selfCtrl.'View/'.$row->id,'View','class="btn btn-link" title="view"'),
      );
    }

    $this->parser->parse('org/struct_content', $data);


  }

  public function AjaxGetRel()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    $delimit  = site_url($this->selfCtrl.'EditRel/');
    $remove   = site_url($this->selfCtrl.'DeleteRelProcess/');
    $viewOrg  = site_url('Org/View/');
    $viewPost = site_url('Post/View/');

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    if ($this->OrgModel->CountParentOrg($id,$keydate)) {
      $parent = $this->OrgModel->GetParentOrg($id,$keydate);
      $data['parentId']   = $parent->parent_id;
      $data['parentName'] = $parent->parent_name;
    } else {
      $data['parentId']   = '';
      $data['parentName'] = '';
    }

    $ls = $this->OrgModel->GetParentOrgList($id,$keydate);
    $parent = array();
    foreach ($ls as $row) {
      $parent[] = array(
        'parentBegin' => $row->parent_begin_date,
        'parentEnd'   => $row->parent_end_date,
        'parentId'    => $row->parent_id,
        'parentName'  => $row->parent_name,
        'viewOrg'     => $viewOrg.$row->parent_id,

      );

    }
    $data['parent'] = $parent;

    $children = array();
    if ($this->OrgModel->CountChildrenOrg($id,$keydate)) {
      $child = $this->OrgModel->GetChildrenOrgList($id,$keydate);
      foreach ($child as $row) {
        $children[] = array(
          'childrenBegin' => $row->child_begin_date,
          'childrenEnd'   => $row->child_end_date,
          'childrenId'    => $row->child_id,
          'childrenName'  => $row->child_name,
          'chgRel'        => $delimit.$row->child_rel_id,
          'remRel'        => $remove.$row->child_rel_id,
          'viewOrg'       => $viewOrg.$row->child_id,
        );
      }
    }
    $data['children'] = $children;
    $post = array();
    if ($this->OrgModel->CountPost($id,$keydate)) {
      $ls = $this->OrgModel->GetPostList($id,$keydate);
      foreach ($ls as $row) {
        $post[] = array(
          'postBegin' => $row->post_begin_date,
          'postEnd'   => $row->post_end_date,
          'postId'    => $row->post_id,
          'postName'  => $row->post_name,
          'chgRel'    => $delimit.$row->post_rel_id,
          'remRel'    => $remove.$row->post_rel_id,
          'viewPost'  => $viewPost.$row->post_id,

        );
      }
    }
    $data['post']     = $post;

    if ($this->OrgModel->CountChiefPost($id,$keydate)) {
      $chief = $this->OrgModel->GetLastChiefPost($id,$keydate);
      $data['chiefPostId']   = $chief->post_id;
      $data['chiefPostName'] = $chief->post_name;
    } else {
      $data['chiefPostId']   = '-';
      $data['chiefPostName'] = '-';
    }
    $ls = $this->OrgModel->GetChiefPostList($id,$keydate);
    $chief = array();
    foreach ($ls as $row) {
      $chief[] = array(
        'chiefBegin' => $row->post_begin_date,
        'chiefEnd'   => $row->post_end_date,
        'chiefId'    => $row->post_id,
        'chiefName'  => $row->post_name,
        'viewPost'   => $viewPost.$row->post_id,

      );
    }
    $data['chief'] = $chief;
    $data['editParent'] = $this->selfCtrl.'EditParent/';
    $data['editChief']  = $this->selfCtrl.'EditChief/';
    $this->parser->parse($this->viewDir . 'rel_elm',$data);

  }

}
