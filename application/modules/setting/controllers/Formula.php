<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Formula extends CI_Controller{

  private $viewDir   = 'formula';
  private $selfCtrl  = 'setting/Formula';
  public function __construct()
  {
    parent::__construct();
    $this->load->model('FormulaModel');
  }

  function index()
  {
    
  }

}
