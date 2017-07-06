<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Formula extends CI_Controller{

  private $viewDir   = 'formula/';
  private $selfCtrl  = 'setting/Formula/';
  public function __construct()
  {
    parent::__construct();
    $this->load->model('FormulaModel','MainModel');
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

    $data = array(
      'ajaxUrl' => $this->selfCtrl.'AjaxGetList',
      'begin'   => $begin,
      'end'     => $end,
      'addLink' => $this->selfCtrl.'Add'
    );
    $this->parser->parse($this->viewDir.'main_view',$data);
  }

  public function AjaxGetList($value='')
  {
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $rows = $this->MainModel->GetList($begin,$end);

    $data['rows'] = array();
    $i = 0;
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
    $data = array(
      'cancelLink' => $this->selfCtrl,
      'process'    => $this->selfCtrl.'AddProcess',
    );
    $this->load->view($this->viewDir.'add_form',$data);

  }

  public function AddProcess()
  {
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $name  = $this->input->post('txt_name');
    $short = $this->input->post('txt_short');
    $descr = $this->input->post('txt_description');
    $type  = $this->input->post('rd_type');
    $this->MainModel->Create($name,$short,$descr,$type,$begin,$end);
    redirect($this->selfCtrl);
  }

  public function EditDate()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old = $this->MainModel->GetByIdRow($id);
    $data = array(
      'begin'      => $old->begin_date,
      'end'        => $old->end_date,
      'cancelLink' => $this->selfCtrl.'View/',
      'hidden'     => array(),
      'process'    => $this->selfCtrl.'EditDateProcess',
    );
    $this->load->view($this->viewDir.'date_form', $data);
  }

  public function EditDateProcess()
  {
    $id  = $this->session->userdata('selectId');
    $end = $this->input->post('dt_end');
    $this->MainModel->Delimit($id,$end);
    redirect($this->selfCtrl.'View/');
  }

  public function EditName()
  {
    $id = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old = $this->MainModel->GetLastName($id);
    $data = array(
      'begin'      => date('Y-m-d'),
      'name'       => $old->name,
      'short'      => $old->short_name,
      'descr'      => $old->description,
      'cancelLink' => $this->selfCtrl.'View/',
      'process'    => $this->selfCtrl.'EditNameProcess',
    );
    $this->load->view($this->viewDir.'name_form', $data);

  }

  public function EditNameProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $name    = $this->input->post('txt_name');
    $short   = $this->input->post('txt_short');
    $descr   = $this->input->post('txt_descr');
    $id      = $this->session->userdata('selectId');
    $this->MainModel->ChangeName($id,$name,$short,$descr,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/'.$id);

  }

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->MainModel->Delete($id);
    redirect($this->selfCtrl);
  }

  public function EditRel($relId=0)
  {
    $old = $this->MainModel->GetRelByIdRow($relId);
    $data = array(
      'hidden'     => array('rel_id' => $relId),
      'process'    => $this->selfCtrl.'EditRelProcess',
      'cancelLink' => $this->selfCtrl.'View/',
      'begin'      => $old->begin_date,
      'end'        => $old->end_date,
    );

    $this->load->view($this->viewDir.'date_form', $data);
  }

  public function EditRelProcess()
  {
    $relId = $this->input->post('rel_id');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $this->MainModel->ChangeRelDate($relId,$begin,$end);
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

    $data = array(
      'begin'    => $begin,
      'end'      => $end,
      'backLink' => $this->selfCtrl,
      'delLink'  => $this->selfCtrl.'DeleteProcess',
      'ajaxUrl1' => $this->selfCtrl.'AjaxGetDetail',
      'ajaxUrl2' => $this->selfCtrl.'AjaxScore',
    );
    $this->parser->parse($this->viewDir.'detail_view',$data);

  }

  public function AjaxGetDetail()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $keydate = array(
      'begin' => $begin,
      'end'   => $end,
    );

    $data = array(
      'objBegin' => '',
      'objEnd'   => '',
      'objName'  => '',
      'begin'    => $begin,
      'end'      => $end,
      'editDate' => $this->selfCtrl.'EditDate/',
      'editName' => $this->selfCtrl.'EditName/',
      'editValue' => $this->selfCtrl.'EditValue/',
    );

    $obj  = $this->MainModel->GetByIdRow($id);
    if ($obj) {
      $attr = $this->MainModel->GetLastName($id,$keydate);
      $data['objBegin'] = $obj->begin_date;
      $data['objEnd']   = $obj->end_date;
      $data['objName']  = $attr->name;
      $data['objShort'] = $attr->short_name;
      $data['objDescr'] = $attr->description;
    }
    $this->parser->parse('_element/obj_detail',$data);

    $val = $this->MainModel->GetLastValue($id,$keydate);
    switch ($val->type) {
      case 'MAX':
        $data['type'] = 'Maximize';
        break;
      case 'MIN':
        $data['type'] = 'Minimize';
        break;
      case 'STA':
        $data['type'] = 'Stablize';
        break;
    }
    $this->parser->parse($this->viewDir.'detail_value_view',$data);

    $ls = $this->MainModel->GetNameHistoryList($id,$keydate,'desc');
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
    $data['history'] = $history;
    $this->parser->parse('_element/hisname_tbl',$data);

  }


  public function EditValue()
  {
    $this->load->helper('form');
    $id = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old = $this->MainModel->GetLastValue($id);
    $data = array(
      'begin'      => date('Y-m-d'),
      'cancelLink' => $this->selfCtrl.'View/',
      'process'    => $this->selfCtrl.'EditValueProcess',
      'typeMax'    => '',
      'typeMin'    => '',
      'typeSta'    => '',
    );
    switch ($old->type) {
      case 'MAX':
        $data['typeMax'] = 'checked';
        break;
      case 'MIN':
        $data['typeMin'] = 'checked';
        break;
      case 'STA':
        $data['typeSta'] = 'checked';
        break;
    }

    $this->load->view($this->viewDir.'value_form', $data);
  }

  public function EditValueProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $id      = $this->session->userdata('selectId');
    $type  = $this->input->post('rd_type');

    $this->MainModel->ChangeValue($id,$types ,$validOn,'9999-12-31');
    redirect($this->selfCtrl.'View/'.$id);
  }

  public function AjaxScore()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $keydate = array(
      'begin' => $begin,
      'end'   => $end,
    );
    $ls = $this->MainModel->GetScoreList($id,$keydate);
    $rows = array();
    foreach ($ls as $row) {
      $rows[] = array(
        'scoreId'     => $row->id,
        'scoreValue'  => $row->value,
        'scoreLower'  => $row->lower_bound,
        'scoreUpper'  => $row->upper_bound,
        'scoreBegin'  => $row->begin_date,
        'scoreEnd'    => $row->end_date,
        'scoreEdit'   => site_url($this->selfCtrl.'EditScore/'.$row->id),
        'scoreDelete' => site_url($this->selfCtrl.'DeleteScoreProcess/'.$row->id)
      );
    }
    $data = array(
      'addLink' => site_url($this->selfCtrl.'AddScore'),
      'score'   => $rows,
    );

    $this->parser->parse($this->viewDir.'score_view',$data);
  }

  public function AddScore()
  {
    $this->load->helper('form');
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    $data = array(
      'process'    => $this->selfCtrl.'AddScoreProcess',
      'cancelLink' => $this->selfCtrl.'View',
      'hidden'  => array(
        'formulaId' => $id,
      ),
      'scoreValue' => 0,
      'scoreLower' => 0,
      'scoreUpper' => 0,
      'begin'      => $begin,
      'end'        => date('Y-m-d'),
    );

    $this->load->view($this->viewDir.'score_form', $data);
  }

  public function AddScoreProcess()
  {
    $id    = $this->input->post('formulaId');
    $score = $this->input->post('nm_value');
    $lower = $this->input->post('nm_lower');
    $upper = $this->input->post('nm_upper');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $this->MainModel->CreateScore($id,$score,$lower,$upper,$begin,$end);
    redirect($this->selfCtrl.'View/');
  }

  public function EditScore($id=0)
  {
    $this->load->helper('form');
    $old = $this->MainModel->GetScoreByIdRow($id);
    $data = array(
      'process'    => $this->selfCtrl.'EditScoreProcess',
      'cancelLink' => $this->selfCtrl.'View',
      'hidden'  => array(
        'scoreId' => $id,
      ),
      'scoreValue' => $old->value,
      'scoreLower' => $old->lower_bound,
      'scoreUpper' => $old->upper_bound,
      'begin'      => $old->begin_date,
      'end'        => $old->end_date,
    );

    $this->load->view($this->viewDir.'score_form', $data);
  }

  public function EditScoreProcess()
  {
    $id    = $this->input->post('scoreId');
    $score = $this->input->post('nm_value');
    $lower = $this->input->post('nm_lower');
    $upper = $this->input->post('nm_upper');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $this->MainModel->ChangeScore($id,$score,$lower,$upper,$begin,$end);
    redirect($this->selfCtrl.'View/');
  }

  public function DeleteScoreProcess($id)
  {
    $this->MainModel->DeleteScore($id);
    redirect($this->selfCtrl.'View');

  }

  public function AjaxGetRel()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    $delimit = site_url($this->selfCtrl.'EditRel/');
    $remove  = site_url($this->selfCtrl.'DeleteRelProcess/');

    $keydate = array(
      'begin' => $begin,
      'end'   => $end,
    );

  }
}
