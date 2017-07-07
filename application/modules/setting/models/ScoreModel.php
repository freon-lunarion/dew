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

  public function Create($value=0, $category='', $lower=0.00, $upper=0.00, $color='#000000', $begin='1990-01-01', $end='9999-12-31')
  {
    $data = array(
      'value'       => $value,
      'category'    => $category,
      'lower_bound' => $lower,
      'upper_bound' => $upper,
      'color'       => $color,
      'begin_date'  => $begin,
      'end_date'    => $end,
      'create_time' => date('Y-m-d H:i:s')
    );
    $this->db->insert($this->tbl, $data);

    return $this->db->insert_id();
  }

  public function Change($id=0,$value=0, $category='', $lower=0.00, $upper=0.00, $color='#000000', $begin='1990-01-01', $end='9999-12-31')
  {
    $data = array(
      'value'       => $value,
      'category'    => $category,
      'lower_bound' => $lower,
      'upper_bound' => $upper,
      'color'       => $color,
      'begin_date'  => $begin,
      'end_date'    => $end,
    );

    $this->db->where('id', $id);
    $this->db->update($this->tbl, $data);
  }

  public function Delete($id=0)
  {
    $data = array(
      'is_delete'  => TRUE,

    );

    $this->db->where('id', $id);
    $this->db->update($this->tbl, $data);
  }
}
