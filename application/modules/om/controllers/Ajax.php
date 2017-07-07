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

  /**
   * [Show Organization Structure Selection on Dialog/ Modal Box]
   * [Menampilkan Pilihan Structur Organisasi Pada Kotak Dialog/ Modal]
   * @method ShowOrgStrucSelection
   */

  public function ShowOrgStrucSelection()
  {
    $this->load->model('OrgModel');
    $this->load->library('parser');

    $orgId = $this->input->post('id');
    $mode  = strtolower($this->input->post('mode')); // [org,post]
    if (!$this->session->userdata('filterBegDa') || !$this->session->userdata('filterEndDa')) {
      $sess = array(
        'filterBegDa' => date('Y-m-d'),
        'filterEndDa' => date('Y-m-d'),
      );
      $this->session->set_userdata($sess);
    }
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $date  = array(
      'begin' => $begin,
      'end'   => $end,
    );

    // Breadcrumb Navigation
    $bc = $this->OrgModel->GetStruct($orgId,$date);
    switch ($mode) {
      case 'org':
        $data['bc'][0] = array(
          'id'   => 0,
          'name' => 'ROOT',
        );
        break;
    }
    foreach ($bc as $row) {
      $data['bc'][] = $row;
    }
    // --------------------------------------------

    // Children of Organization
    if ($orgId ==0 ) {
      $row = $this->OrgModel->GetByIdRow(1,$date);
      $name = $this->OrgModel->GetLastName(1,$date);
      $data['org'][0] = array(
        'id'    => $row->id,
        'begda' => $row->begin_date,
        'endda' => $row->end_date,
        'name'  => $name->name,
        'short' => $name->short_name,
      );


    } else {
      $child = $this->OrgModel->GetChildrenOrgList($orgId,$date);
      $i     = 0;
      $data['org'] = array();
      foreach ($child as $row) {
        $data['org'][$i] = array(
          'id'    => $row->child_id,
          'begda' => $row->child_begin_date,
          'endda' => $row->child_end_date,
          'name'  => $row->child_name,
          'short' => $row->child_short_name,

        );
        $i++;
      }

    }

    // --------------------------------------------

    switch ($mode) {
      case 'org':
        $this->parser->parse('_element/orgStruct_content', $data);

        break;
      case 'post':
        // Position in Organization
        $i  = 0;
        $ls = $this->OrgModel->GetPostList($orgId,$date);
        foreach ($ls as $row) {
          $data['post'][$i] = array(
            'id'    => $row->post_id,
            'name'  => $row->post_name,
            'short' => $row->post_short_name,
            'begda' => $row->post_begin_date,
            'endda' => $row->post_end_date,
          );
          $i++;
        }
        $this->parser->parse('_element/orgPostStruct_content', $data);

        break;
    }
  }

  public function ShowEmployeeSelection()
  {
    $this->load->model(array('EmpModel'));
    $this->load->library('parser');

    $query = $this->input->post('query');

    $mode  = strtolower($this->input->post('mode')); // [org,post]
    if (!$this->session->userdata('filterBegDa') || !$this->session->userdata('filterEndDa')) {
      $sess = array(
        'filterBegDa' => date('Y-m-d'),
        'filterEndDa' => date('Y-m-d'),
      );
      $this->session->set_userdata($sess);
    }
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $date  = array(
      'begin' => $begin,
      'end'   => $end,
    );

    $ls   = $this->EmpModel->GetByNameList($query,$date);
    $data['emp'] = array();
    foreach ($ls as $row) {
      $data['emp'][] = array(
        'id'   => $row->id,
        'name' => $row->name,
      );
    }
    $this->parser->parse('_element/empSelection_content', $data);

  }
}
