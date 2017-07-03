<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Measurement extends CI_Controller{
  private $viewDir   = 'measurement/';
  private $selfCtrl  = 'Setting/Measurement/';
  public function __construct()
  {
    parent::__construct();
    $this->load->model('MeasurementModel','MainModel');
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
    $rows = $this->MainModel->GetList($begin,$end);
  }

}
