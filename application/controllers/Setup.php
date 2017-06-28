<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {

  }

  public function Database($type='')
  {
    $this->load->model(array('SetupModel'));
    $this->SetupModel->DropTable();
    $this->SetupModel->CreateRefTable();
    $this->SetupModel->CreateTable();
    $this->SetupModel->CreateScTable();
    if ($type == 'demo') {
      $this->SetupModel->InsertDemoRecords();
      $this->SetupModel->SCRecords();

    }
    redirect('Om');
  }

}
