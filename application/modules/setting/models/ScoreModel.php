<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScoreModel extends CI_Model{
  private $tbl;
  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->tbl = $this->config->item('tblScoreMain');

  }

  public function GetList($begin='1990-01-01',$end='9999-12-31')
  {
    $this->db->where('is_delete', FALSE);
    $this->db->group_start();
      $this->db->group_start();
        $this->db->where('begin_date >=', $begin);
        $this->db->where('end_date <=', $end);
      $this->db->group_end();
      $this->db->or_group_start();
        $this->db->where('end_date >=', $begin);
        $this->db->where('end_date <=', $end);
      $this->db->group_end();
      $this->db->or_group_start();
        $this->db->where('begin_date >=', $begin);
        $this->db->where('begin_date <=', $end);
      $this->db->group_end();
      $this->db->or_group_start();
        $this->db->where('begin_date <=', $begin);
        $this->db->where('end_date >=', $end);
      $this->db->group_end();
    $this->db->group_end();
    return $this->db->get($this->tbl)->result();

  }

  public function GetByIdRow($id=0)
  {
    $this->db->where('id', $id);
    return $this->db->get($this->tbl)->row();
  }

  public function GetByRangeValueRow($value=0.00,$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }

    $this->db->where('lower_bound >=', $value);
    $this->db->where('upper_bound <=', $value);
    $this->db->where('is_delete', FALSE);

    if (!is_array($keydate)) {
      $this->db->where('begin_date >=', $keydate);
      $this->db->where('end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('end_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date <=', $keydate['begin']);
          $this->db->where('end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }

    return $this->db->get($this->tbl)->row();
  }

  public function GetByScoreValueRow($value=0.00,$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $this->db->where('value', $value);
    $this->db->where('is_delete', FALSE);

    if (!is_array($keydate)) {
      $this->db->where('begin_date >=', $keydate);
      $this->db->where('end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('end_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date <=', $keydate['begin']);
          $this->db->where('end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }

    return $this->db->get($this->tbl)->row();
  }

}
