<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Score extends CI_Controller{
  private $viewDir   = 'score/';
  private $selfCtrl  = 'setting/Score/';
  public function __construct()
  {
    parent::__construct();
    $this->load->model('ScoreModel','MainModel');
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

  public function AjaxGetList()
  {
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $rows  = $this->MainModel->GetList($begin,$end);
    $data['rows'] = array();
    $i = 0;
    foreach ($rows as $row) {
      $temp = array(
        'id'       => $row->id,
        'begda'    => $row->begin_date,
        'endda'    => $row->end_date,
        'value'    => $row->value,
        'category' => $row->category,
        'lower'    => $row->lower_bound,
        'upper'    => $row->upper_bound,
        'color'    => $row->color,
        'viewlink' => anchor($this->selfCtrl.'View/'.$row->id,'View','class="btn btn-link" title="view"'),
      );
      $data['rows'][$i] = $temp;
      $i++;
    }
    $this->parser->parse($this->viewDir.'/obj_tbl',$data);

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

    $rec  = $this->MainModel->GetByIdRow($id);

    $data = array(
      'backLink' => $this->selfCtrl,
      'editLink' => $this->selfCtrl.'Edit/',
      'delLink'  => $this->selfCtrl.'DeleteProcess',
      'begin'    => $rec->begin_date,
      'end'      => $rec->end_date,
      'value'    => $rec->value,
      'category' => $rec->category,
      'lower'    => $rec->lower_bound,
      'upper'    => $rec->upper_bound,
      'color'    => $rec->color,
    );
    $this->parser->parse($this->viewDir.'detail_view',$data);

  }
  public function Add()
  {
    $this->load->helper('form');
    $data = array(
      'cancelLink' => $this->selfCtrl,
      'process'    => $this->selfCtrl.'AddProcess',
      'hidden'     => array(),
      'begin'      => date('Y-m-d'),
      'end'        => '9999-12-31',
      'value'      => '0',
      'category'   => '',
      'lower'      => '0.00',
      'upper'      => '0.00',
      'color'      => '#000000',
    );
    $this->load->view($this->viewDir.'form',$data);
  }

  public function AddProcess()
  {
    $begin    = $this->input->post('dt_begin');
    $end      = $this->input->post('dt_end');
    $value    = $this->input->post('nm_value');
    $category = $this->input->post('txt_category');
    $lower    = $this->input->post('nm_lower');
    $upper    = $this->input->post('nm_upper');
    $color    = $this->input->post('cp_color');

    $this->MainModel->Create($value, $category, $lower, $upper, $color, $begin, $end);
    redirect($this->selfCtrl);
  }

  public function Edit()
  {
    $this->load->helper('form');
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->selfCtrl);
    }
    $old = $this->MainModel->GetByIdRow($id);
    $data = array(
      'cancelLink' => $this->selfCtrl.'View/',
      'hidden'     => array('id' => $id),
      'process'    => $this->selfCtrl.'EditProcess',
      'begin'      => $old->begin_date,
      'end'        => $old->end_date,
      'value'      => $old->value,
      'category'   => $old->category,
      'lower'      => $old->lower_bound,
      'upper'      => $old->upper_bound,
      'color'      => $old->color,
    );
    $this->load->view($this->viewDir.'form', $data);
  }

  public function EditProcess()
  {
    $id       = $this->input->post('id');
    $begin    = $this->input->post('dt_begin');
    $end      = $this->input->post('dt_end');
    $value    = $this->input->post('nm_value');
    $category = $this->input->post('txt_category');
    $lower    = $this->input->post('nm_lower');
    $upper    = $this->input->post('nm_upper');
    $color    = $this->input->post('cp_color');
    $this->MainModel->Change($id,$value, $category, $lower, $upper, $color, $begin, $end);
    redirect($this->selfCtrl.'View/');
  }

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->MainModel->Delete($id);
    redirect($this->selfCtrl);
  }

}
