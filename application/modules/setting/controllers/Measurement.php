<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Measurement extends CI_Controller{
  private $viewDir   = 'measurement';
  private $selfCtrl  = 'setting/Measurement';
  public function __construct()
  {
    parent::__construct();
    $this->load->model('MeasureModel');

  }

  function index()
  {

  }

}
