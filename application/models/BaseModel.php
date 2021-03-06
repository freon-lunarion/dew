<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  // Object

  /**
   * [Create Object with name attribute]
   * [Membuat Object dengan nama]
   * @method Create
   * @param  string $type  [Object Type refer to ref_obj_type table]
   * @param  string $name  [max:150 char(s)]
   * @param  string $begin [yyyy-mm-dd]
   * @param  string $end   [yyyy-mm-dd]
   */

  public function Create($type='',$name='',$begin='1990-01-01',$end='9999-12-31')
  {
    $data = array(
      'type'       => strtoupper($type),
      'begin_date' => $begin,
      'end_date'   => $end,
    );
    $objId = $this->InsertOn($this->config->item('tblObj'), $data);

    $data = array(
      'obj_id'     => $objId,
      'begin_date' => $begin,
      'end_date'   => $end,
    );

    if (is_array($name)) {
      $data['name'] = $name['name'];

      if (isset($name['short'])) {
        $data['short_name'] = $name['short'];
      } else if (isset($name['short_name'])) {
        $data['short_name'] = $name['short_name'];
      }

      if (isset($name['desc'])) {
        $data['description'] = $name['desc'];
      } else if (isset($name['description'])) {
        $data['description'] = $name['description'];
      }

    } else {
      $data['name'] = $name;
    }

    $this->InsertOn($this->config->item('tblAttr'), $data);
    return $objId;
  }

  /**
   * [Change Object's Date]
   * [Mengubah Tanggal (Begin & End) Berlaku Object]
   * @method ChangeDate
   * @param  integer    $objId     [description]
   * @param  string     $beginDate [description]
   * @param  string     $endDate   [description]
   */

  public function ChangeDate($objId=0,$beginDate='',$endDate='')
  {
    $old = $this->GetByIdRow($objId);
    if ($beginDate == '') {
      $beginDate = date('Y-m-d');
    }
    if ($endDate == '') {
      $endDate = date('Y-m-d');
    }

    $data = array(
      'begin_date' => $beginDate,
      'end_date'   => $endDate,
      'timestamp'  => date('Y-m-d H:i:s'),
    );

    $dataBegin = array(
      'begin_date' => $beginDate,
      'timestamp'  => date('Y-m-d H:i:s'),
    );

    $dataEnd = array(
      'end_date'   => $endDate,
      'timestamp'  => date('Y-m-d H:i:s'),
    );

    $this->ChangeOn($this->config->item('tblObj'),$objId,$data);
    // Change date of Attribut
    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('begin_date ', $old->begin_date);
    $this->db->update($this->config->item('tblAttr'),$dataBegin);

    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->config->item('tblAttr'),$dataEnd);

    // Change date of Relation TopDown
    $this->db->where('obj_top_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('begin_date ', $old->begin_date);
    $this->db->update($this->config->item('tblRel'), $dataBegin);

    $this->db->where('obj_top_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->config->item('tblRel'), $dataEnd);

    // Change date of Relation BottomUp
    $this->db->where('obj_bottom_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('begin_date ', $old->begin_date);
    $this->db->update($this->config->item('tblRel'), $dataBegin);

    $this->db->where('obj_bottom_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->config->item('tblRel'), $dataEnd);
  }

  /**
   * [Change End Date of Object]
   * [Mengubah End Date dari Object]
   * @method Delimit
   * @param  integer $objId   [description]
   * @param  string  $endDate [yyyy-mm-dd]
   */

  public function Delimit($objId=0,$endDate='')
  {
    $old = $this->GetByIdRow($objId);
    if ($endDate == '') {
      $endDate = date('Y-m-d');
    }
    $data = array(
      'end_date' => $endDate,
      'timestamp' => date('Y-m-d H:i:s'),
    );

    // Delimit Object
    $this->ChangeOn($this->config->item('tblObj'),$objId,$data);
    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->config->item('tblAttr'),$data);

    $this->db->where('obj_top_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->config->item('tblRel'), $data);

    $this->db->where('obj_bottom_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->config->item('tblRel'), $data);
  }

  /**
   * [(Soft) Delete Object/ give Deleted status to object]
   * [memberikan tanda Deleted kepada obejct]
   * @method Delete
   * @param  integer $id [description]
   */

  public function Delete($id=0)
  {
    $this->DeleteOn($this->config->item('tblObj'),$id);
    $this->DeleteOn($this->config->item('tblAttr'),$id,'obj_id');
    $this->DeleteOn($this->config->item('tblRel'),$id,'obj_top_id');
    $this->DeleteOn($this->config->item('tblRel'),$id,'obj_bottom_id');
  }

  /**
   * [Get a record of Object by ID]
   * [Mendapat (satu) record dari Object berdasarkan ID]
   * @method GetByIdRow
   * @param  integer    $id [description]
   */

  public function GetByIdRow($id=0)
  {
    $this->db->where('is_delete', 0);
    $this->db->where('id', $id);
    return $this->db->get($this->config->item('tblObj'), 1, 0)->row();
  }

  /**
   * [Get Record(s) of Object by Type and (range) date]
   * [Mendapatkan (beberapa) record dari Object berdasarkan Type dan (range) tanggal]
   * @method GetList
   * @param  string  $type    [3 chars]
   * @param  string  $keydate [single or begin+end date]
   */

  public function GetList($type='',$keydate='',$order='asc')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $selectName = $this->_subqueryTextAttr('name',$keydate,0,'');
    $selectShort = $this->_subqueryTextAttr('short_name',$keydate,0,'');

    $this->db->select('obj.id');
    $this->db->select('obj.begin_date');
    $this->db->select('obj.end_date');
    $this->db->select('('.$selectName .' ) AS name');
    $this->db->select('('.$selectShort .' ) AS short_name');


    $this->db->where('obj.type', $type);
    $this->db->where('obj.is_delete', FALSE);

    if (!is_array($keydate)) {
      $this->db->where('obj.begin_date >=', $keydate);
      $this->db->where('obj.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('obj.begin_date >=', $keydate['begin']);
          $this->db->where('obj.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.end_date >=', $keydate['begin']);
          $this->db->where('obj.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.begin_date >=', $keydate['begin']);
          $this->db->where('obj.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.begin_date <=', $keydate['begin']);
          $this->db->where('obj.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }
    $this->db->where('obj.end_date', $order);
    return $this->db->get($this->config->item('tblObj'))->result();
  }

  /**
   * [Get Record(s) of Object by their name]
   * [Medapatkan (beberapa) Record dari Object berdasarkan namanya]
   * @method GetByNameList
   * @param  string        $name    [name of object]
   * @param  string        $keydate [single or range date]
   * @param  [type]        $type    [object type, 3 char]
   */

  public function GetByNameList($name='',$keydate='',$type=NULL)
  {
    $this->db->select('obj.id');
    $this->db->select('obj.type');
    $this->db->select('obj.begin_date');
    $this->db->select('obj.end_date');
    $this->db->select('attr.name');
    $this->db->from($this->config->item('tblAttr') .' attr');
    $this->db->join($this->config->item('tblObj') . ' obj', 'attr.obj_id = obj.id');
    $this->db->where('attr.is_delete', 0);
    $this->db->where('obj.is_delete', 0);
    $this->db->like('LOWER(attr.name)', $name);

    if (!is_null($type)) {
      if (!is_array($type)) {
        $this->db->where('obj.type', $type);
      } else {
        $this->db->where_in('obj.type', $type);
      }
    }

    if (!is_array($keydate)) {
      $this->db->where('attr.begin_date >=', $keydate);
      $this->db->where('attr.end_date <=', $keydate);
      $this->db->where('obj.begin_date >=', $keydate);
      $this->db->where('obj.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('attr.begin_date >=', $keydate['begin']);
          $this->db->where('attr.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('attr.end_date >=', $keydate['begin']);
          $this->db->where('attr.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('attr.begin_date >=', $keydate['begin']);
          $this->db->where('attr.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('attr.begin_date <=', $keydate['begin']);
          $this->db->where('attr.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('obj.begin_date >=', $keydate['begin']);
          $this->db->where('obj.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.end_date >=', $keydate['begin']);
          $this->db->where('obj.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.begin_date >=', $keydate['begin']);
          $this->db->where('obj.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.begin_date <=', $keydate['begin']);
          $this->db->where('obj.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }
    return $this->db->get()->result();
  }
  // ---------------------------------------------------------------------------

  // Name / Attribute
  /**
   * [Get the latest Atrribute (name) of Object]
   * [Medapatkan nama terakhir dari Object]
   * @method GetLastAttr
   * @param  integer     $objId   [description]
   * @param  string      $keydate [single or range date]
   */

  public function GetLastAttr($objId=0,$keydate='')
  {
    return $this->GetLastOn($this->config->item('tblAttr'),$objId,'obj_id',$keydate);
  }

  /**
   * [Get List of Atrribute (name) of object]
   * [Mendapatkan Dafatra nama dari object]
   * @method GetAttrList
   * @param  integer     $objId   [description]
   * @param  string      $keydate [single pr range date]
   * @param  string      $sort    ["asc" or "desc"]
   */

  public function GetAttrList($objId=0,$keydate='',$sort='asc')
  {
    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);
    if (!is_array($keydate)) {
      if ($keydate == '') {
        $keydate = date('Y-m-d');
      }
      $this->db->where('begin_date <=', $keydate);
      $this->db->where('end_date >=', $keydate);
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
    $this->db->order_by('end_date',$sort);
    return $this->db->get($this->config->item('tblAttr'))->result();
  }

  /**
   * [Change Object's attribute (name)]
   * [Mengganti Atribut Object (nama)]
   * @method ChangeAttr
   * @param  integer    $objId   [ID Object]
   * @param  string     $newName [nama baru]
   * @param  string     $validOn [tanggal mulai]
   * @param  string     $endDate [yyyy-mm-dd]
   */

  public function ChangeAttr($objId=0,$text='',$validOn='',$endDate='9999-12-31')
  {
    if ($validOn == '') {
      $validOn = date('Y-m-d');
    }

    $this->db->select('id');
    $this->db->where('obj_id', $objId);
    $this->db->order_by('end_date','desc');
    $row    = $this->db->get($this->config->item('tblAttr'))->row();

    $attId    = $row->id;
    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')),
    );
    $this->ChangeOn($this->config->item('tblAttr'),$attId,$data);

    $data = array(
      'obj_id'     => $objId,
      'begin_date' => $validOn,
      'end_date'   => $endDate,
    );

    if (is_array($text)) {
      $data['name']       = $text['name'];

      if (isset($text['short'])) {
        $data['short_name'] = $text['short'];
      } else if (isset($text['short_name'])) {
        $data['short_name'] = $text['short_name'];
      }

      if (isset($text['desc'])) {
        $data['description'] = $text['desc'];
      } else if (isset($text['description'])) {
        $data['description'] = $text['description'];

      }

    } else {
      $data['name'] = $text;
    }

    $this->InsertOn($this->config->item('tblAttr'),$data);
  }
  // ---------------------------------------------------------------------------

  // Relation
  /**
   * [Create Relation between Objects]
   * [Membuat Relasi antar-Object]
   * @method CreateRel
   * @param  string    $relCode  [description]
   * @param  integer   $topObjId [description]
   * @param  integer   $botObjId [description]
   * @param  string    $begin    [description]
   * @param  string    $end      [description]
   */

  public function CreateRel($relCode='',$topObjId=0,$botObjId=0,$begin='1990-01-01',$end='9999-12-31')
  {
    $data = array(
      'rel_code'      => $relCode,
      'obj_top_id'    => $topObjId,
      'obj_bottom_id' => $botObjId,
      'weight'        => $weight,
      'begin_date'    => $begin,
      'end_date'      => $end,
    );
    $objId = $this->InsertOn($this->config->item('tblRel'), $data);
  }

  /**
   * [Change a Relation of Object with other object]
   * [Mengubah relasi sebuah object dengan object lainnya]
   * @method ChangeRel
   * @param  string    $mode    ["BOTUP" or "TOPDOWN"]
   * @param  string    $relCode [reference to ref_obj_rel]
   * @param  string    $refId   [Object ID ]
   * @param  string    $newId   [Other Object (Id)]
   * @param  string    $validOn [new Begin Date]
   * @param  string    $endDate [yyyy-mm-dd]
   */

  public function ChangeRel($mode='BOTUP',$relCode='',$refId='',$newId='', $weight= 100,$validOn='',$endDate='9999-12-31')
  {
    if ($validOn == '') {
      $validOn = date('Y-m-d');
    }

    $this->db->select('id');
    $this->db->where('rel_code', $relCode);
    switch (strtoupper($mode)) {
      case 'BOTUP':
        $this->db->where('obj_bottom_id', $refId);
        break;
      default:
        $this->db->where('obj_top_id', $refId);
        break;
    }
    $this->db->where('is_delete', FALSE);
    $this->db->order_by('end_date');
    $relId = $this->db->get($this->config->item('tblRel'), 1, 0)->row()->id; // get id of relation

    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')), // set end date of old relation, 1 day before new relation begin
    );
    $this->ChangeOn($this->config->item('tblRel'),$relId,$data); // change end date of relation by relation id

    if ($newId == TRUE && $newId !='' && $newId > 0) {
      switch (strtoupper($mode)) {
        case 'BOTUP':
        $data             = array(
          'obj_top_id'    => $newId,
          'obj_bottom_id' => $refId,
          'rel_code'      => $relCode,
          'weight'        => $weight,
          'begin_date'    => $validOn,
          'end_date'      => $endDate,
        );
        break;
        default:
        $data             = array(
          'obj_top_id'    => $refId,
          'obj_bottom_id' => $newId,
          'rel_code'      => $relCode,
          'weight'        => $weight,
          'begin_date'    => $validOn,
          'end_date'      => $endDate,
        );
        break;
      }
      $this->InsertOn($this->config->item('tblRel'),$data); //create new relation
    }
  }

  /**
   * [Change Begin & End Date  of Relation ]
   * [Mengubah Tanggal Mulai dan Selesai dari Relation ]
   * @method ChangeRelDate
   * @param  integer       $relId     [description]
   * @param  string        $beginDate [description]
   * @param  string        $endDate   [description]
   */

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $data = array(
      'begin_date' => $beginDate,
      'end_date'   => $endDate,
    );

    $this->ChangeOn($this->config->item('tblRel'),$relId,$data);
  }

  /**
   * [Change End Date  of Relation]
   * [Mengubah Tanggal Selesai dari Relation ]
   * @method DelimitRel
   * @param  integer    $relId   [description]
   * @param  string     $endDate [description]
   */

  public function DelimitRel($relId=0,$endDate='')
  {
    $this->DelimitOn($this->config->item('tblRel'),$relId,$endDate);
  }

  /**
   * [(Soft) Delete Relation / Give Deleted status]
   * [Memberikan Status Deleted/ Terhapus]
   * @method DeleteRel
   * @param  integer   $relId [description]
   */

  public function DeleteRel($relId=0)
  {
    $this->DeleteOn($this->config->item('tblRel'),$relId);
  }

  public function GetRelById($relId=0)
  {
    $this->db->where('id', $relId);
    $this->db->from($this->config->item('tblRel'));
    return $this->db->get()->row();
  }
  // ---------------------------------------------------------------------------

  // Relation - Top Down
  public function CountTopDownRel($topObjId=0,$relCode=array(),$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $this->db->select('COUNT(rel_0.id) as val');
    $this->db->from($this->config->item('tblRel') .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->config->item('tblRel') .' AS rel_'.$i,'rel_'.$j.'.obj_bottom_id = rel_'.$i.'.obj_top_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }

      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();

          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      }
    } else {

      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();

        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      }
    }
    return $this->db->get()->row()->val;
  }

  public function CountTopDownRelWeight($topObjId=0,$relCode='',$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $this->db->select('SUM(rel_0.weight) AS weight');
    $this->db->from($this->config->item('tblRel') .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (!is_array($keydate)) {
      $this->db->where('rel_0.begin_date >=', $keydate);
      $this->db->where('rel_0.end_date <=', $keydate);
      if ($relCode != '') {
        $this->db->where('rel_0.rel_code', $relCode);
      }
      $this->db->where('rel_0.is_delete', FALSE);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('rel_0.begin_date >=', $keydate['begin']);
          $this->db->where('rel_0.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('rel_0.end_date >=', $keydate['begin']);
          $this->db->where('rel_0.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('rel_0.begin_date >=', $keydate['begin']);
          $this->db->where('rel_0.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('rel_0.begin_date <=', $keydate['begin']);
          $this->db->where('rel_0.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
      if ($relCode != '') {
        $this->db->where('rel_0.rel_code', $relCode);
      }
      $this->db->where('rel_0.is_delete', FALSE);
    }

    return $this->db->get()->row()->weight;
  }

  public function GetLastTopDownRel($topObjId=0,$relCode='',$keydate='',$alias='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $subName  = $this->_subqueryTextAttr('name',$keydate,'NUM','TOPDOWN');
    $subShort = $this->_subqueryTextAttr('short_name',$keydate,'NUM','TOPDOWN');

    $this->db->from($this->config->item('tblRel') .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $this->db->order_by('rel_'.$i.'.end_date','desc');

        $selectName  = str_replace('NUM',$i,$subName);
        $selectShort = str_replace('NUM',$i,$subShort);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS '. $alias[$i].'_id');
          $this->db->select('('.$selectName.') AS '. $alias[$i].'_name');
          $this->db->select('('.$selectShort.') AS '. $alias[$i].'_short_name');
          $this->db->select('rel_0.begin_date AS '. $alias[$i].'_begin_date');
          $this->db->select('rel_0.end_date AS '. $alias[$i].'_end_date');
        } else {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS obj_'. $i.'_id');
          $this->db->select('('.$selectName.') AS obj_'.$i.'_name');
          $this->db->select('('.$selectShort.') AS obj_'.$i.'_short_name');
          $this->db->select('rel_0.begin_date AS obj'. $i.'_begin_date');
          $this->db->select('rel_0.end_date AS obj'. $i.'_end_date');

        }
      }

      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->config->item('tblRel') .' AS rel_'.$i,'rel_'.$j.'.obj_bottom_id = rel_'.$i.'.obj_top_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();

          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);

        }
      }
    } else {

      $selectName  = str_replace('NUM',0,$subName);
      $selectShort = str_replace('NUM',0,$subShort);
      if ($alias !='') {
        $this->db->select('rel_0.obj_bottom_id AS '. $alias.'_id');
        $this->db->select('('.$selectName.') AS '. $alias.'_name');
        $this->db->select('('.$selectShort.') AS '. $alias.'_short_name');
        $this->db->select('rel_0.begin_date AS '. $alias.'_begin_date');
        $this->db->select('rel_0.end_date AS '. $alias.'_end_date');
      } else {
        $this->db->select('rel_0.obj_bottom_id AS obj_id');
        $this->db->select('('.$selectName.') AS obj_name');
        $this->db->select('('.$selectShort.') AS obj_short_name');
        $this->db->select('rel_0.begin_date AS obj_begin_date');
        $this->db->select('rel_0.end_date AS obj_end_date');

      }


      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);

      }
      $this->db->order_by('rel_0.end_date','desc');
    }
    $this->db->limit(1,0);
    return $this->db->get()->row();
  }

  public function GetTopDownRelList($topObjId=0,$relCode='',$keydate='',$alias='',$order='asc')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $subName  = $this->_subqueryTextAttr('name',$keydate,'NUM','TOPDOWN');
    $subShort = $this->_subqueryTextAttr('short_name',$keydate,'NUM','TOPDOWN');

    $this->db->from($this->config->item('tblRel') .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $selectName  = str_replace('NUM',$i,$subName);
        $selectShort = str_replace('NUM',$i,$subShort);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS '. $alias[$i].'_id');
          $this->db->select('rel_'.$i.'.id AS '. $alias[$i].'_rel_id');
          $this->db->select('rel_'.$i.'.begin_date AS '. $alias[$i].'_begin_date');
          $this->db->select('rel_'.$i.'.end_date AS '. $alias[$i].'_end_date');
          $this->db->select('('.$selectName.') AS '. $alias[$i].'_name');
          $this->db->select('('.$selectShort.') AS '. $alias[$i].'_short_name');
        } else {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS obj_'. $i.'_id');
          $this->db->select('rel_'.$i.'.id AS obj_'. $i.'_rel_id');
          $this->db->select('('.$selectName.') AS obj_'.$i.'_name');
          $this->db->select('('.$selectShort.') AS obj_'.$i.'_short_name');
          $this->db->select('rel_'.$i.'.begin_date AS obj_'. $i.'_begin_date');
          $this->db->select('rel_'.$i.'.end_date AS obj_'. $i.'_end_date');
        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->config->item('tblRel') .' AS rel_'.$i,'rel_'.$j.'.obj_bottom_id = rel_'.$i.'.obj_top_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      }
    } else {

      $selectName  = str_replace('NUM',0,$subName);
      $selectShort = str_replace('NUM',0,$subShort);
      if ($alias !='') {
        $this->db->select('rel_0.obj_bottom_id AS '. $alias.'_id');
        $this->db->select('rel_0.id AS '. $alias.'_rel_id');
        $this->db->select('rel_0.begin_date AS '. $alias.'_begin_date');
        $this->db->select('rel_0.end_date AS '. $alias.'_end_date');
        $this->db->select('('.$selectName.') AS '. $alias.'_name');
        $this->db->select('('.$selectShort.') AS '. $alias.'_short_name');
      } else {
        $this->db->select('rel_0.obj_bottom_id AS obj_id');
        $this->db->select('rel_0.id AS obj_rel_id');
        $this->db->select('rel_0.begin_date AS obj_begin_date');
        $this->db->select('rel_0.end_date AS obj_end_date');
        $this->db->select('('.$selectName.') AS obj_name');
        $this->db->select('('.$selectShort.') AS obj_short_name');
      }


      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      }
    }
    $this->db->order_by('rel_0.end_date',$order);
    $this->db->order_by('rel_0.begin_date',$order);
    return $this->db->get()->result();
  }

  // ---------------------------------------------------------------------------

  // Relation - Bottom Up
  public function CountBotUpRel($botObjId=0,$relCode='',$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $this->db->select('COUNT(rel_0.id) as val');
    $this->db->from($this->config->item('tblRel') .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->config->item('tblRel') .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();

          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      }
    } else {
      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      }
    }
    return $this->db->get()->row()->val;
  }

  public function CountBotUpRelWeight($botObjId=0,$relCode='',$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $this->db->select('SUM(rel_0.weight) AS weight');
    $this->db->from($this->config->item('tblRel') .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $topObjId);
    $this->db->where('rel_0.is_delete', FALSE);
    if ($relCode != '') {
      $this->db->where('rel_0.rel_code', $relCode);
    }
    if (!is_array($keydate)) {
      $this->db->where('rel_0.begin_date >=', $keydate);
      $this->db->where('rel_0.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('rel_0.begin_date >=', $keydate['begin']);
          $this->db->where('rel_0.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('rel_0.end_date >=', $keydate['begin']);
          $this->db->where('rel_0.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('rel_0.begin_date >=', $keydate['begin']);
          $this->db->where('rel_0.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('rel_0.begin_date <=', $keydate['begin']);
          $this->db->where('rel_0.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();

    }

    return $this->db->get()->row()->weight;
  }

  public function GetLastBotUpRel($botObjId=0,$relCode='',$keydate='',$alias='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $subName = $this->_subqueryTextAttr('name',$keydate,'NUM','BOTUP');
    $subShort = $this->_subqueryTextAttr('short_name',$keydate,'NUM','BOTUP');

    $this->db->from($this->config->item('tblRel') .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $this->db->order_by('rel_'.$i.'.end_date','desc');

        $selectName  = str_replace('NUM',$i,$subName);
        $selectShort = str_replace('NUM',$i,$subShort);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_top_id AS '. $alias[$i].'_id');
          $this->db->select('('.$selectName.') AS '. $alias[$i].'_name');
          $this->db->select('('.$selectShort.') AS '. $alias[$i].'_short_name');
          $this->db->select('rel_0.begin_date AS '. $alias[$i].'_begin_date');
          $this->db->select('rel_0.end_date AS '. $alias[$i].'_end_date');
        } else {
          $this->db->select('rel_'.$i.'.obj_top_id AS obj_'. $i.'_id');
          $this->db->select('('.$selectName.') AS obj_'.$i.'_name');
          $this->db->select('('.$selectShort.') AS obj_'.$i.'_short_name');

          $this->db->select('rel_0.begin_date AS obj_'. $i.'_begin_date');
          $this->db->select('rel_0.end_date AS obj_'. $i.'_end_date');

        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->config->item('tblRel') .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
          $this->db->group_start();
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
          $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
          $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
          $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
          $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
          $this->db->group_end();
          $this->db->group_end();

          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);

        }
      }
    } else {

      $selectName  = str_replace('NUM',0,$subName);
      $selectShort = str_replace('NUM',0,$subShort);
      if ($alias !='') {
        $this->db->select('rel_0.obj_top_id AS '. $alias.'_id');
        $this->db->select('('.$selectName.') AS '. $alias.'_name');
        $this->db->select('('.$selectShort.') AS '. $alias.'_short_name');
        $this->db->select('rel_0.begin_date AS '. $alias.'_begin_date');
        $this->db->select('rel_0.end_date AS '. $alias.'_end_date');
      } else {
        $this->db->select('rel_0.obj_top_id AS obj_id');
        $this->db->select('('.$selectName.') AS obj_name');
        $this->db->select('('.$selectShort.') AS obj_short_name');
        $this->db->select('rel_0.begin_date AS obj_begin_date');
        $this->db->select('rel_0.end_date AS obj_end_date');

      }


      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('rel_0.begin_date >=', $keydate['begin']);
        $this->db->where('rel_0.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('rel_0.end_date >=', $keydate['begin']);
        $this->db->where('rel_0.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('rel_0.begin_date >=', $keydate['begin']);
        $this->db->where('rel_0.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('rel_0.begin_date <=', $keydate['begin']);
        $this->db->where('rel_0.end_date >=', $keydate['end']);
        $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);

      }
      $this->db->order_by('rel_0.end_date','desc');
    }
    $this->db->limit(1,0);
    return $this->db->get()->row();
  }

  public function GetBotUpRelList($botObjId=0,$relCode='',$keydate='',$alias='',$order='asc')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $subName = $this->_subqueryTextAttr('name',$keydate,'NUM','BOTUP');
    $subShort = $this->_subqueryTextAttr('short_name',$keydate,'NUM','BOTUP');

    $this->db->from($this->config->item('tblRel') .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $selectName  = str_replace('NUM',$i,$subName);
        $selectShort = str_replace('NUM',$i,$subShort);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.id AS '. $alias[$i].'_rel_id');
          $this->db->select('rel_'.$i.'.obj_top_id AS '. $alias[$i].'_id');
          $this->db->select('rel_'.$i.'.weight AS '. $alias[$i].'_weight');
          $this->db->select('('.$selectName.') AS '. $alias[$i].'_name');
          $this->db->select('('.$selectShort.') AS '. $alias[$i].'_short_name');

          $this->db->select('rel_'.$i.'.begin_date AS '. $alias[$i].'_begin_date');
          $this->db->select('rel_'.$i.'.end_date AS '. $alias[$i].'_end_date');
        } else {
          $this->db->select('rel_'.$i.'.id AS obj_'. $i.'_rel_id');
          $this->db->select('rel_'.$i.'.obj_top_id AS obj_'. $i.'_id');
          $this->db->select('rel_'.$i.'.weight AS obj_'. $i.'weight');
          $this->db->select('('.$selectName.') AS obj_'.$i.'_name');
          $this->db->select('('.$selectShort.') AS obj_'.$i.'_short_name');
          $this->db->select('rel_'.$i.'.begin_date AS obj_'. $i.'_begin_date');
          $this->db->select('rel_'.$i.'.end_date AS obj_'. $i.'_end_date');
        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->config->item('tblRel') .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }

      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();

          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      }
    } else {
      // Sub query 1
      $selectName  = str_replace('NUM',0,$subName);
      $selectShort = str_replace('NUM',0,$subShort);
      if ($alias !='') {
        $this->db->select('rel_0.id AS '. $alias.'_rel_id');
        $this->db->select('rel_0.obj_top_id AS '. $alias.'_id');
        $this->db->select('rel_0.weight AS '. $alias.'_weight');
        $this->db->select('rel_0.begin_date AS '. $alias.'_begin_date');
        $this->db->select('rel_0.end_date AS '. $alias.'_end_date');
        $this->db->select('('.$selectName.') AS '. $alias.'_name');
        $this->db->select('('.$selectShort.') AS '. $alias.'_short_name');
      } else {
        $this->db->select('rel_0.id AS obj_rel_id');
        $this->db->select('rel_0.obj_top_id AS obj_id');
        $this->db->select('rel_0.weight AS obj_weight');
        $this->db->select('('.$selectName.') AS obj_name');
        $this->db->select('('.$selectShort.') AS obj_short_name');
        $this->db->select('rel_0.begin_date AS obj_begin_date');
        $this->db->select('rel_0.end_date AS obj_end_date');
      }
      //  end of Sub query 1
      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);

      }
    }
    $this->db->order_by('rel_0.end_date',$order);
    $this->db->order_by('rel_0.begin_date',$order);
    return $this->db->get()->result();
  }

  // ---------------------------------------------------------------------------

  // Basic
  /*
   * Basic query functions to manipulate data on table.
   * Set some field(s) data as meta data
   */
 /*
  * fungsi - fungsi dasar untuk memanipulasi data pada table
  * data beberapa kolom telah ditentukan, sebagai meta data
  */

 /**
  * [Memasukan data pada table]
  * @method InsertOn
  * @param  string   $tbl  [table's name]
  * @param  array    $data [description]
  */

  public function InsertOn($tbl='',$data=array())
  {
    $data['create_time'] = date('Y-m-d H:i:s');
    $data['timestamp']   = date('Y-m-d H:i:s');
    $this->db->insert($tbl, $data);

    return $this->db->insert_id();
  }

  /**
   * [Mengubah data pada table]
   * @method ChangeOn
   * @param  string   $tbl  [table's name]
   * @param  integer  $id   [record's id]
   * @param  array    $data [description]
   */

  public function ChangeOn($tbl='',$id=0,$data=array())
  {
    $data['timestamp']   = date('Y-m-d H:i:s');
    if (is_array($id)) {
      $this->db->where_in('id', $id);
    } else {
      $this->db->where('id', $id);
    }
    $this->db->update($tbl, $data);
  }

  /**
   * [Mengubah data tanggal berlaku pada table]
   * @method DelimitOn
   * @param  string    $tbl     [table's name]
   * @param  integer   $id      [record's id]
   * @param  string    $endDate [description]
   */

  public function DelimitOn($tbl='',$id=0,$endDate='')
  {
    $data  = array(
      'end_date'  => $endDate,
      'timestamp' => date('Y-m-d H:i:s')
    );
    if (is_array($id)) {
      $this->db->where_in('id', $id);
    } else {
      $this->db->where('id', $id);
    }
    $this->db->update($tbl, $data);
  }

  /**
   * [Give Deleted Status]
   * @method DeleteOn
   * @param  string   $tbl   [table's name]
   * @param  integer  $id    [description]
   * @param  string   $field [description]
   */

  public function DeleteOn($tbl='',$id=0,$field='id')
  {
    $data  = array(
      'is_delete' => 1,
      'timestamp' => date('Y-m-d H:i:s')
    );
    if (is_array($id)) {
      $this->db->where_in($field, $id);
    } else {
      $this->db->where($field, $id);
    }
    $this->db->update($tbl, $data);
  }

  public function GetLastOn($tbl='',$id=0,$field='obj_id',$keydate='')
  {
    $this->db->where($field, $id);
    $this->db->where('is_delete', FALSE);

    if (!is_array($keydate)) {
      if ($keydate == '') {
        $keydate = date('Y-m-d');
      }
      $this->db->where('begin_date <=', $keydate);
      $this->db->where('end_date >=', $keydate);
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
    $this->db->order_by('end_date','desc');
    return $this->db->get($tbl)->row();
  }

  private function _subqueryTextAttr($selectField='',$keydate='',$index = 'NUM',$whereField='')
  {
    $this->db->select('sub.'.$selectField);
    $this->db->where('sub.is_delete', FALSE);
    switch (strtoupper($whereField)) {
      case 'TOPDOWN':
        $this->db->where('sub.obj_id = rel_'.$index.'.obj_bottom_id');

        break;
      case 'BOTUP':
        $this->db->where('sub.obj_id = rel_'.$index.'.obj_top_id');

        break;
      default:
        $this->db->where('sub.obj_id = obj.id');
        break;
    }
    $this->db->from($this->config->item('tblAttr') .' sub');
    $this->db->order_by('end_date','desc');
    $this->db->limit(1,0);
    if (!is_array($keydate)) {
      $this->db->where('sub.begin_date >=', $keydate);
      $this->db->where('sub.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.end_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date <=', $keydate['begin']);
          $this->db->where('sub.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }
    return $this->db->get_compiled_select();
  }
  // ---------------------------------------------------------------------------

}
