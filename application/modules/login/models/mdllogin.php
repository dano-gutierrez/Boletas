<?php

class MdlLogin extends CI_Model{
	
	function __construct(){
		parent::__construct();
		//$this->load->library('exceptions');
		//$this->school = $this->load->database('school', TRUE);
	}

	public function getData_School($select,$from,$where,$order,$group,$limit){
		$query="SELECT $select FROM $from ";
		if($where!=="") $query.="WHERE $where ";
		if($group!=="") $query.="GROUP BY $group ";
		if($order!=="") $query.="ORDER BY $order ";
		if($limit!=="") $query.="LIMIT $limit ";
		$result=$this->school->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function getDataFromQuery_School($query){
		$result=$this->school->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function editData($data,$table,$id,$idName,$where=";"){
		$consulta="UPDATE $table ";
		$set=FALSE;
		foreach($data AS $e=>$key){
			if($key!==FALSE && $key!=="false"){
				if(!$set){
					$consulta.=" SET ";
					$set=TRUE;
				}else{
					$consulta.=" , ";
				}
				if($key==="NULL" && !is_numeric($key) && intval($key)!==0) $consulta.="`$e` = NULL ";
				else $consulta.="`$e` = '$key' ";
			}
		}
		if($id!==FALSE) $consulta.=" WHERE $idName = $id ".$where;
		else $consulta.=$where;
		$resultado=$this->db->query($consulta);
		if(strpos((string)$this->db->conn_id->info,"Rows matched: 0")===FALSE)
			return TRUE;
		else
			return FALSE;
	}

	public function deleteData_multipleWhere($table,$where){
		foreach($where AS $e => $key){
			$this->db->where("$e",$key);
		}
		$this->db->delete($table);
		return $this->db->affected_rows()>0 ? TRUE : FALSE;
	}

	public function deleteData($table,$id,$idName){
		$this->db->where("$idName",$id);
		$this->db->delete($table);
		return $this->db->affected_rows()>0 ? TRUE : FALSE;
	}

	public function deleteDataIfExists($table,$id,$idName){
		$existence=$this->db->query("SELECT 1 FROM $table WHERE $idName = '$id' LIMIT 1;");
		if($existence->num_rows()>0){
			$this->db->where($idName,$id);
			$this->db->delete($table);
			return $this->db->affected_rows()>0 ? TRUE : FALSE;
		} else return TRUE;
	}

}

?>