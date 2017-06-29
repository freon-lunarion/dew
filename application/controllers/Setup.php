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
    $this->load->model('SetupModel','Setup');
    $this->load->model('om/OmSetup','OmSetup');
    $this->load->model('setting/ScSetup','ScSetup');
    $this->Setup->DropTables();
    $this->ScSetup->DropTables();

    $this->Setup->CreateRefTables();
    $this->Setup->CreateTables();
    $this->OmSetup->InsertRefRecords();
    $this->ScSetup->InsertRefRecords();
    $this->ScSetup->CreateTables();

    $compId = $this->OmSetup->InsertIntialRecords();
    $offset = $this->ScSetup->InsertIntialRecords();

    if ($type == 'demo') {
      $this->OmSetup->InsertDemoRecords($offset,$compId);

    }
    redirect('Om');
  }

}
