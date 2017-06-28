<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exp extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {

  }

  public function Search()
  {
    $this->load->model(array('BaseModel'));
    $this->load->view('search_form');
  }

  public function AjaxSearchResult()
  {
    $this->load->model(array('BaseModel'));
    $query = $this->input->post('query');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    if ($begin == '') {
      $begin = date('Y-m-d');
    }

    if ($end == '') {
      $end = date('Y-m-d');
    }
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $ls = $this->BaseModel->GetByNameList($query,$keydate);

    $result = array();
    foreach ($ls as $row) {
      switch ($row->type) {
        case 'ORG':
          $link = anchor('Org/View/'.$row->id,'View','title="View Detail" class="btn btn-link"');
          break;
        case 'JOB':
          $link = anchor('Job/View/'.$row->id,'View','title="View Detail" class="btn btn-link"');
          break;
        case 'EMP':
          $link = anchor('Pers/View/'.$row->id,'View','title="View Detail" class="btn btn-link"');
          break;
        case 'POS':
          $link = anchor('Post/View/'.$row->id,'View','title="View Detail" class="btn btn-link"');
          break;
        default:
          $link = '';
          break;
      }
      $result[] = array(
        'id' => $row->id,
        'name' => $row->name,
        'type' => $row->type,
        'link' => $link
      );
    }
    echo json_encode($result);

  }

}
