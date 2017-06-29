<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perspective extends CI_Controller{
  private $viewDir   = 'perspective';
  private $selfCtrl  = 'setting/Perspective';
  public function __construct()
  {
    parent::__construct();
    $this->load->model('PerspectiveModel');

  }

  function index()
  {

  }

}
