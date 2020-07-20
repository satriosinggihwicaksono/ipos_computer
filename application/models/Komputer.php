<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Komputer extends CI_Model
{
	public function insertItem($table,$item)
	{
		$ins = $this->db->insert($table,$item);
		return $ins;
	}
	
	public function deleteItem($table,$item)
	{
		$ins = $this->db->delete($table,$item);
		return $ins;
	}

	public function updateItem($table,$item,$where)
	{
		$ins = $this->db->update($table,$item,$where);
		return $ins;
	}
	
	public function namaKategori($id)
	{
		$this->db->where('id',$id);
		$ins = $this->db->get('kategori')->result_array();
		if(!empty($ins)){
			return $ins[0]['kategori'];
		} else {
			return 'unidentified';
		}	
	}
	
	public function namaSerial($id)
	{
		$this->db->where('id',$id);
		$ins = $this->db->get('serial')->result_array();
		if(!empty($ins)){
			return $ins[0]['serial'];
		} else {
			return 'unidentified';
		}	
	}
	public function namaItem($id)
	{
		$this->db->where('id',$id);
		$ins = $this->db->get('item')->result_array();
		if(!empty($ins)){
			return $ins[0]['nama'];
		} else {
			return 'unidentified';
		}	
	}
	
	public function save($id){
		$pass = password_hash($this->input->post('new_password'), PASSWORD_DEFAULT);
		$data = array (
			'password' => $pass
		);
		$this->db->where('id',$id);
		$this->db->update('user', $data);
	}
	
	public function cek($id,$ind,$tabel)
	{
		$this->db->where($ind,$id);
		$ins = $this->db->get($tabel)->result_array();
		return $ins;
	}
	
	public function check($table,$field,$item)
	{
		return $this->db->get_where($table,[$field => $item]);
	}
	
	public function getIduser(){
		$username = $this->session->userdata('username');
		$this->db->where('username',$username);
		$get = $this->db->get('user')->result_array();
		return $get[0]['id'];
	}
	
	public function namaCabang($id){
		$this->db->where('id',$id);
		$get = $this->db->get('cabang')->result_array();
		if(!empty($get)){
			return $get[0]['nama'];
		} else {
			return 'Admin';
		}	
	}
	
	public function namaSupplier($id){
		$this->db->where('id',$id);
		$get = $this->db->get('supplier')->result_array();
		if(!empty($get)){
			return $get[0]['supplier'];
		} else {
			return 'Undetified';
		}	
	}
	
	public function getIdCabang(){
		$username = $this->session->userdata('username');
		$this->db->where('username',$username);
		$get = $this->db->get('user')->result_array();
		return $get[0]['cabang'];
	}
	
	public function namaUser($id){
		$this->db->where('id',$id);
		$get = $this->db->get('user')->result_array();
		if(!empty($get)){
			return $get[0]['username'];
		} else {
			return 'Tidak Teridentifikasi';
		}
	}
	
	public function isAdmin($username){
		$this->db->where('username',$username);
		$this->db->where('hakakses',0);
		$get = $this->db->get('user')->result_array();
		return $get;
	}
	
	public function isGudang($username){
		$this->db->where('username',$username);
		$this->db->where('hakakses',1);
		$get = $this->db->get('user')->result_array();
		return $get;
	}
	
	public function format($numbers){
		$numbers = number_format($numbers);	
		$numbers = str_replace(',', '.', $numbers);	
		return $numbers;
	 }
	
	public function nota($value,$places){
		$leading = '';
		if(is_numeric($value)){ 
			for($x = 1; $x <= $places; $x++){ 
			$ceiling = pow(10, $x);
			if($value < $ceiling){ 
				$zeros = $places - $x;
			for($y = 1; $y <= $zeros; $y++){ 
				$leading .= "0"; 
			}
				$x = $places + 1; 
				}
			} 
			
			$output = $leading . $value;
		} else { 
			$output = $value; 
		}
		return $output;
	}
}