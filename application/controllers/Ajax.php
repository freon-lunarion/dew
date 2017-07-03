<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {

  }

  public function SetFilterDate()
  {
    $begin = $this->input->post('begDa');
    if ($this->input->post('begDa') == '') {
      $begin = date('Y-m-d');
    }
    $end   = $this->input->post('endDa');
    if ($this->input->post('endDa') == '') {
      $end = date('Y-m-d');
    }
    $this->session->set_userdata('filterBegDa',$begin);
    $this->session->set_userdata('filterEndDa',$end);
  }

}
