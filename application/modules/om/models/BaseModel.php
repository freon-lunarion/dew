<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model{

  public $tblObj  = 'obj'; // table for defining object
  public $tblAttr = 'obj_attribute'; // table for object name (attribute)
  public $tblRel  = 'obj_rel'; // table for relation between object(s)

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
    $objId = $this->InsertOn($this->tblObj, $data);

    $data = array(
      'obj_id'     => $objId,
      'name'       => $name,
      'begin_date' => $begin,
      'end_date'   => $end,
    );
    $this->InsertOn($this->tblAttr, $data);
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

    $this->ChangeOn($this->tblObj,$objId,$data);
    // Change date of Attribut
    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('begin_date ', $old->begin_date);
    $this->db->update($this->tblAttr,$dataBegin);

    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblAttr,$dataEnd);

    // Change date of Relation TopDown
    $this->db->where('obj_top_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('begin_date ', $old->begin_date);
    $this->db->update($this->tblRel, $dataBegin);

    $this->db->where('obj_top_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblRel, $dataEnd);

    // Change date of Relation BottomUp
    $this->db->where('obj_bottom_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('begin_date ', $old->begin_date);
    $this->db->update($this->tblRel, $dataBegin);

    $this->db->where('obj_bottom_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblRel, $dataEnd);
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
    $this->ChangeOn($this->tblObj,$objId,$data);
    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblAttr,$data);

    $this->db->where('obj_top_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblRel, $data);

    $this->db->where('obj_bottom_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblRel, $data);
  }

  /**
   * [(Soft) Delete Object/ give Deleted status to object]
   * [memberikan tanda Deleted kepada obejct]
   * @method Delete
   * @param  integer $id [description]
   */

  public function Delete($id=0)
  {
    $this->DeleteOn($this->tblObj,$id);
    $this->DeleteOn($this->tblAttr,$id,'obj_id');
    $this->DeleteOn($this->tblRel,$id,'obj_top_id');
    $this->DeleteOn($this->tblRel,$id,'obj_bottom_id');
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
    return $this->db->get($this->tblObj, 1, 0)->row();
  }

  /**
   * [Get Record(s) of Object by Type and (range) date]
   * [Mendapatkan (beberapa) record dari Object berdasarkan Type dan (range) tanggal]
   * @method GetList
   * @param  string  $type    [3 chars]
   * @param  string  $keydate [single or begin+end date]
   */

  public function GetList($type='',$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1
    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = obj.id');
    $this->db->from($this->tblAttr .' sub');
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
    $subQuery = $this->db->get_compiled_select();
    $this->db->select('obj.id');
    $this->db->select('obj.begin_date');
    $this->db->select('obj.end_date');
    $this->db->select('('.$subQuery .' ) AS name');

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

    return $this->db->get($this->tblObj)->result();
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
    $this->db->from($this->tblAttr .' attr');
    $this->db->join($this->tblObj . ' obj', 'attr.obj_id = obj.id');
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
    $this->db->order_by('end_date','desc');
    return $this->db->get($this->tblAttr)->row();
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
    return $this->db->get($this->tblAttr)->result();
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

  public function ChangeAttr($objId=0,$newName='',$validOn='',$endDate='9999-12-31')
  {
    if ($validOn == '') {
      $validOn = date('Y-m-d');
    }

    $this->db->select('id');
    $this->db->where('obj_id', $objId);
    $this->db->order_by('end_date','desc');
    $row    = $this->db->get($this->tblAttr)->row();

    $attId    = $row->id;
    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')),
    );
    $this->ChangeOn($this->tblAttr,$attId,$data);

    $data = array(
      'obj_id'     => $objId,
      'name'       => $newName,
      'begin_date' => $validOn,
      'end_date'   => $endDate,
    );
    $this->InsertOn($this->tblAttr,$data);
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
      'begin_date'    => $begin,
      'end_date'      => $end,
    );
    $objId = $this->InsertOn($this->tblRel, $data);
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

  public function ChangeRel($mode='BOTUP',$relCode='',$refId='',$newId='',$validOn='',$endDate='9999-12-31')
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
    $relId = $this->db->get($this->tblRel, 1, 0)->row()->id; // get id of relation

    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')), // set end date of old relation, 1 day before new relation begin
    );
    $this->ChangeOn($this->tblRel,$relId,$data); // change end date of relation by relation id

    if ($newId == TRUE && $newId !='' && $newId > 0) {
      switch (strtoupper($mode)) {
        case 'BOTUP':
        $data             = array(
          'obj_top_id'    => $newId,
          'obj_bottom_id' => $refId,
          'rel_code'      => $relCode,
          'begin_date'    => $validOn,
          'end_date'      => $endDate,
        );
        break;
        default:
        $data             = array(
          'obj_top_id'    => $refId,
          'obj_bottom_id' => $newId,
          'rel_code'      => $relCode,
          'begin_date'    => $validOn,
          'end_date'      => $endDate,
        );
        break;
      }
      $this->InsertOn($this->tblRel,$data); //create new relation
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

    $this->ChangeOn($this->tblRel,$relId,$data);
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
    $this->DelimitOn($this->tblRel,$relId,$endDate);
  }

  /**
   * [(Soft) Delete Relation / Give Deleted status]
   * [Memberikan Status Deleted/ Terhapus]
   * @method DeleteRel
   * @param  integer   $relId [description]
   */

  public function DeleteRel($relId=0)
  {
    $this->DeleteOn($this->tblRel,$relId);
  }

  public function GetRelById($relId=0)
  {
    $this->db->where('id', $relId);
    $this->db->from($this->tblRel);
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
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_bottom_id = rel_'.$i.'.obj_top_id');
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

  public function GetLastTopDownRel($topObjId=0,$relCode='',$keydate='',$alias='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1
    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = rel_NUM.obj_bottom_id');
    $this->db->from($this->tblAttr .' sub');
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
    $subQuery = $this->db->get_compiled_select();
    // END OF Sub Query 1
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $this->db->order_by('rel_'.$i.'.end_date','desc');

        $select = str_replace('NUM',$i,$subQuery);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS '. $alias[$i].'_id');
          $this->db->select('('.$select.') AS '. $alias[$i].'_name');
          $this->db->select('rel_0.begin_date AS '. $alias[$i].'_begin_date');
          $this->db->select('rel_0.end_date AS '. $alias[$i].'_end_date');
        } else {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS obj_'. $i.'_id');
          $this->db->select('('.$select.') AS obj_'.$i.'_name');
          $this->db->select('rel_0.begin_date AS obj'. $i.'_begin_date');
          $this->db->select('rel_0.end_date AS obj'. $i.'_end_date');

        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_bottom_id = rel_'.$i.'.obj_top_id');
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
      $select = str_replace('NUM','0',$subQuery);
      if ($alias !='') {
        $this->db->select('rel_0.obj_bottom_id AS '. $alias.'_id');
        $this->db->select('('.$select.') AS '. $alias.'_name');
        $this->db->select('rel_0.begin_date AS '. $alias.'_begin_date');
        $this->db->select('rel_0.end_date AS '. $alias.'_end_date');
      } else {
        $this->db->select('rel_0.obj_bottom_id AS obj_id');
        $this->db->select('('.$select.') AS obj_name');
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
      $this->db->order_by('rel_0.end_date','desc');
    }
    $this->db->limit(1,0);
    return $this->db->get()->row();
  }

  public function GetTopDownRelList($topObjId=0,$relCode='',$keydate='',$alias='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1
    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = rel_NUM.obj_bottom_id');
    $this->db->from($this->tblAttr .' sub');
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
    $subQuery = $this->db->get_compiled_select();
    // END OF Sub Query 1
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $select = str_replace('NUM',$i,$subQuery);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS '. $alias[$i].'_id');
          $this->db->select('rel_'.$i.'.id AS '. $alias[$i].'_rel_id');
          $this->db->select('rel_'.$i.'.begin_date AS '. $alias[$i].'_begin_date');
          $this->db->select('rel_'.$i.'.end_date AS '. $alias[$i].'_end_date');
          $this->db->select('('.$select.') AS '. $alias[$i].'_name');
        } else {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS obj_'. $i.'_id');
          $this->db->select('rel_'.$i.'.id AS obj_'. $i.'_rel_id');
          $this->db->select('('.$select.') AS obj_'.$i.'_name');
          $this->db->select('rel_'.$i.'.begin_date AS obj_'. $i.'_begin_date');
          $this->db->select('rel_'.$i.'.end_date AS obj_'. $i.'_end_date');
        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_bottom_id = rel_'.$i.'.obj_top_id');
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
      $select = str_replace('NUM','0',$subQuery);
      if ($alias !='') {
        $this->db->select('rel_0.obj_bottom_id AS '. $alias.'_id');
        $this->db->select('rel_0.id AS '. $alias.'_rel_id');
        $this->db->select('rel_0.begin_date AS '. $alias.'_begin_date');
        $this->db->select('rel_0.end_date AS '. $alias.'_end_date');
        $this->db->select('('.$select.') AS '. $alias.'_name');
      } else {
        $this->db->select('rel_0.obj_bottom_id AS obj_id');
        $this->db->select('rel_0.id AS obj_rel_id');
        $this->db->select('rel_0.begin_date AS obj_begin_date');
        $this->db->select('rel_0.end_date AS obj_end_date');
        $this->db->select('('.$select.') AS obj_name');
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
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
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

  public function GetBotUpRelList($botObjId=0,$relCode='',$keydate='',$alias='',$order='asc')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1
    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = rel_NUM.obj_top_id');
    $this->db->from($this->tblAttr .' sub');
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
    $subQuery = $this->db->get_compiled_select();
    // END OF Sub Query 1
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $select = str_replace('NUM',$i,$subQuery);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.id AS '. $alias[$i].'_rel_id');
          $this->db->select('rel_'.$i.'.obj_top_id AS '. $alias[$i].'_id');
          $this->db->select('('.$select.') AS '. $alias[$i].'_name');

          $this->db->select('rel_'.$i.'.begin_date AS '. $alias[$i].'_begin_date');
          $this->db->select('rel_'.$i.'.end_date AS '. $alias[$i].'_end_date');
        } else {
          $this->db->select('rel_'.$i.'.id AS obj_'. $i.'_rel_id');
          $this->db->select('rel_'.$i.'.obj_top_id AS obj_'. $i.'_id');
          $this->db->select('('.$select.') AS obj_'.$i.'_name');
          $this->db->select('rel_'.$i.'.begin_date AS obj_'. $i.'_begin_date');
          $this->db->select('rel_'.$i.'.end_date AS obj_'. $i.'_end_date');
        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
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
      $select = str_replace('NUM','0',$subQuery);
      if ($alias !='') {
        $this->db->select('rel_0.id AS '. $alias.'_rel_id');
        $this->db->select('rel_0.obj_top_id AS '. $alias.'_id');
        $this->db->select('rel_0.begin_date AS '. $alias.'_begin_date');
        $this->db->select('rel_0.end_date AS '. $alias.'_end_date');
        $this->db->select('('.$select.') AS '. $alias.'_name');
      } else {
        $this->db->select('rel_0.id AS obj_rel_id');
        $this->db->select('rel_0.obj_top_id AS obj_id');
        $this->db->select('('.$select.') AS obj_name');
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

  public function GetLastBotUpRel($botObjId=0,$relCode='',$keydate='',$alias='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1
    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = rel_NUM.obj_top_id');
    $this->db->from($this->tblAttr .' sub');
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
    $subQuery = $this->db->get_compiled_select();
    // END OF Sub Query 1
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $this->db->order_by('rel_'.$i.'.end_date','desc');

        $select = str_replace('NUM',$i,$subQuery);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_top_id AS '. $alias[$i].'_id');
          $this->db->select('('.$select.') AS '. $alias[$i].'_name');
          $this->db->select('rel_0.begin_date AS '. $alias[$i].'_begin_date');
          $this->db->select('rel_0.end_date AS '. $alias[$i].'_end_date');
        } else {
          $this->db->select('rel_'.$i.'.obj_top_id AS obj_'. $i.'_id');
          $this->db->select('('.$select.') AS obj_'.$i.'_name');

          $this->db->select('rel_0.begin_date AS obj_'. $i.'_begin_date');
          $this->db->select('rel_0.end_date AS obj_'. $i.'_end_date');

        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
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
      $select = str_replace('NUM','0',$subQuery);
      if ($alias !='') {
        $this->db->select('rel_0.obj_top_id AS '. $alias.'_id');
        $this->db->select('('.$select.') AS '. $alias.'_name');
        $this->db->select('rel_0.begin_date AS '. $alias.'_begin_date');
        $this->db->select('rel_0.end_date AS '. $alias.'_end_date');
      } else {
        $this->db->select('rel_0.obj_top_id AS obj_id');
        $this->db->select('('.$select.') AS obj_name');
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
      $this->db->order_by('rel_0.end_date','desc');
    }
    $this->db->limit(1,0);
    return $this->db->get()->row();
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
  // ---------------------------------------------------------------------------

}
