<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('javascript');
		$this->load->library('pagination');
		$this->load->library('user_agent');
		$this->load->helper('url');
    	$this->load->library('form_validation');
		if(!$this->session->logged_in){
			redirect('auth');
		}
	}

	public function Kas()
	{	
		$username = $this->session->userdata('username');
		$id_username = $this->komputer->getIdCabang($username);
		$check_admin = $this->komputer->isAdmin($username);
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = $t[0];
			$month = $t[1];
			$year = (int)$t[2];
		}  else {
			$cabang = '';
			$month = date('m');
			$year = date('Y');
		}
		
		if($cabang != 0){
			$this->db->where('id_cabang',$cabang);
		}
		
		if(!$check_admin) {
			$this->db->where('id_cabang',$id_username);
		}
		
		$this->db->where('id_cabang !=',100);
		if(!empty($year)) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month)) $this->db->where('MONTH(waktu)', $month);
		$this->db->order_by('waktu','asc');
		$query = $this->db->get('kas')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_kas(){
		$cabang = $this->input->post('cabang');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		redirect('kas/kas/'.$cabang.'^'.$month.'^'.$year);
	}
	
	public function tambah_kas(){
		$tanggal = $this->input->post('tanggal');
		$waktu = $this->input->post('waktu');
		$id_cabang = $this->input->post('id_cabang');
		$status = $this->input->post('status');
		$deskripsi = $this->input->post('deskripsi');
		$saldo = $this->input->post('saldo');
		
		$data = array(
			'waktu' => $tanggal.' '.$waktu,
			'id_cabang' => $id_cabang,
			'status' => $status,
			'deskripsi' => $deskripsi,
			'saldo' => $saldo,
		);
		
		$ins = $this->komputer->insertItem('kas',$data);
		if($ins){
			$this->session->set_flashdata('message','Kas Berhasil disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Kas Gagal disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_kas_pusat(){
		$tanggal = $this->input->post('tanggal');
		$waktu = $this->input->post('waktu');
		$id_cabang = $this->input->post('id_cabang');
		$status = $this->input->post('status');
		$deskripsi = $this->input->post('deskripsi');
		$saldo = $this->input->post('saldo');
		
		$data = array(
			'waktu' => $tanggal.' '.$waktu,
			'id_cabang' => 100,
			'id_stor' => $id_cabang,
			'status' => $status,
			'deskripsi' => $deskripsi,
			'saldo' => $saldo,
		);
		
		$ins = $this->komputer->insertItem('kas',$data);
		if($ins){
			$this->session->set_flashdata('message','Kas Berhasil disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Kas Gagal disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_kas($id){
		$tanggal = $this->input->post('tanggal');
		$waktu = $this->input->post('waktu');
		$id_cabang = $this->input->post('id_cabang');
		$status = $this->input->post('status');
		$deskripsi = $this->input->post('deskripsi');
		$saldo = $this->input->post('saldo');
		
		$data = array(
			'waktu' => $tanggal.' '.$waktu,
			'id_cabang' => $id_cabang,
			'status' => $status,
			'deskripsi' => $deskripsi,
			'saldo' => $saldo,
		);
		
		$ins = $this->komputer->updateItem('kas',$data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Kas Berhasil diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Kas Gagal diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_stor_kas(){
		$tanggal = $this->input->post('tanggal');
		$id_cabang = $this->input->post('id_cabang');
		$pusat = $this->input->post('pusat');
		$status = 2;
		$deskripsi = $this->input->post('deskripsi');
		$saldo = $this->input->post('saldo');
		
		$data = array(
			'waktu' => $tanggal,
			'id_cabang' => $id_cabang,
			'status' => $status,
			'deskripsi' => $deskripsi,
			'saldo' => $saldo,
			'id_stor' => $pusat,
		);
		
		$ins = $this->komputer->insertItem('kas',$data);
		if($ins){
			$this->session->set_flashdata('message','Kas telah distor');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Kas belum distor');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_stor_kas($id){
		$tanggal = $this->input->post('tanggal');
		$waktu = $this->input->post('waktu');
		$id_cabang = $this->input->post('id_cabang');
		$deskripsi = $this->input->post('deskripsi');
		$saldo = $this->input->post('saldo');
		
		$data = array(
			'waktu' => $tanggal.' '.$waktu,
			'id_cabang' => $id_cabang,
			'deskripsi' => $deskripsi,
			'saldo' => $saldo,
		);
		
		$ins = $this->komputer->updateItem('kas',$data, array('id'=>$id));
		if($ins){
			$this->session->set_flashdata('message','Stor kas telah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Stor kas tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function tempat_kas(){
		$query = $this->db->get('tempat_kas')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_tempat_kas(){
		$tempat_kas = $this->input->post('tempat_kas');
		if(empty($tempat_kas)){
			$this->session->set_flashdata('message','tempat_kas belum diisi');
			redirect($this->agent->referrer());
		}
		$data = array(
			'nama' => $tempat_kas,
		);
		$ins = $this->komputer->insertItem('tempat_kas',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah tempat_kas');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah tempat_kas');
			redirect($this->agent->referrer());
		}
	}
	
	public function update_tempat_kas($id){
		
		$data = array(
			'nama' => $this->input->post('tempat_kas'),
		);
		$update = $this->komputer->updateItem('tempat_kas',$data,array('id'=> $id));
		if($update){
			$this->session->set_flashdata('message','Sukses Merubah tempat_kas');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Merubah tempat_kas');
			redirect($this->agent->referrer());
		}
	}
	
	public function kas_pusat()
	{	
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = $t[0];
			$month = $t[1];
			$year = (int)$t[2];
		}  else {
			$cabang = 0;
			$month = '';
			$year = '';
		}
		$this->db->where('id_stor !=',0);
		if(!empty($cabang))$this->db->where('id_stor',$cabang);
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		$query = $this->db->get('kas')->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_kas_pusat(){
		$cabang = $this->input->post('cabang');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		redirect('kas/kas_pusat/'.$cabang.'^'.$month.'^'.$year);
	}
}
