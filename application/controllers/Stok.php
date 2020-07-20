<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {
	
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

	public function index()
	{	
		$this->load->view('index');
	}
	
	public function tambah_stok($id)
	{	
		
		$link = $this->uri->segment(3);
		
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		
		if($posisi > 0){
			$t = explode("%5E",$link);
			$id = $t[0];
			$id_cabang = $t[1];
			$search = $t[2];
			$kondisi = $t[3];
		}
		
		$this->db->where('status',0);
		$this->db->where('id_item',$id);
		if(!empty($id_cabang)) $this->db->where('cabang',$id_cabang);
		if(!empty($search)) $this->db->like('serial',$search);
		if(!empty($kondisi)) $this->db->where('kondisi',$kondisi);
		$data_result = $this->db->get('serial')->num_rows();
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/stok/tambah_stok/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/stok/tambah_stok/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);	
		
		$this->db->where('status',0);
		$this->db->where('id_item',$id);
		if(!empty($id_cabang)) $this->db->where('cabang',$id_cabang);
		if(!empty($search)) $this->db->like('serial',$search);
		if(!empty($kondisi)) $this->db->where('kondisi',$kondisi);
		$query = $this->db->get('serial',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_tambah_stok(){
		$id = $this->input->post('id');
		$id_cabang = $this->input->post('id_cabang');
		$serial = $this->input->post('serial');
		$kondisi = $this->input->post('kondisi');
		redirect('stok/tambah_stok/'.$id.'^'.$id_cabang.'^'.$serial.'^'.$kondisi);
	}
	
	public function add_stok(){
		$id_item = $this->input->post('id_item');
		$id_cabang = $this->input->post('id_cabang');
		$serial = $this->input->post('serial');
		
		$data = array(
			'id_item' => $id_item,
			'cabang' => $id_cabang,
			'serial' => $serial,
			'status' => 0,
		);
		$cek = $this->komputer->cek($serial,'serial','serial');
		if($cek){
			$this->session->set_flashdata('message','Serial Sudah terdaftar');
			redirect($this->agent->referrer());
		}
		$ins = $this->komputer->insertItem('serial',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Serial');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Serial');
			redirect($this->agent->referrer());
		}	
	}
	
	public function add_stok_pembelian(){
		$id_item = $this->input->post('id_item');
		$id_cabang = $this->input->post('id_cabang');
		$serial = $this->input->post('serial');
		$pembelian = $this->input->post('id_pembelian');
		
		$data = array(
			'id_item' => $id_item,
			'cabang' => $id_cabang,
			'serial' => $serial,
			'status' => 3,
			'id_pembelian' => $pembelian,
		);
		$cek = $this->komputer->cek($serial,'serial','serial');
		if($cek){
			$this->session->set_flashdata('message','Serial Sudah terdaftar');
			redirect($this->agent->referrer());
		}
		$ins = $this->komputer->insertItem('serial',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Serial');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Serial');
			redirect($this->agent->referrer());
		}	
	}	
	
	public function ubah_serial($id){
		$serial = $this->input->post('serial');
		
		$data = array(
			'serial' => $serial,
			'kondisi' => $this->input->post('kondisi'),
			'cn' => $this->input->post('cn'),
			
		);
		$ins = $this->komputer->updateItem('serial', $data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Sukses Ubah Serial');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Ubah Serial');
			redirect($this->agent->referrer());
		}	
	}
	
	public function transfer_stok(){
		$username = $this->session->userdata('username');
		$check_admin = $this->komputer->isAdmin($username);	
		$id_cabang = $this->komputer->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode("%5E",$link);
			$tgl_trans = $t[0];
			$tgl_con = $t[1];
			$month = $t[2];
			$year = $t[3];
		}
		
		if(!empty($tgl_trans)){
			$this->db->where('YEAR(tgl_trans)',date('Y', $tgl_trans));
			$this->db->where('MONTH(tgl_trans)',date('m', $tgl_trans));
			$this->db->where('DAYOFMONTH(tgl_trans)',date('d', $tgl_trans));
		}
		
		if(!empty($month)){
			$this->db->where('MONTH(tgl_trans)',$month);
		}
		
		if(!empty($year)){
			$this->db->where('YEAR(tgl_trans)',$year);
		}
		
		if(!empty($tgl_con)){
			$this->db->where('YEAR(tgl_con)',date('Y', $tgl_con));
			$this->db->where('MONTH(tgl_con)',date('m', $tgl_con));
			$this->db->where('DAYOFMONTH(tgl_con)',date('d', $tgl_con));
		}
		
		if(!$check_admin) $this->db->where('id_from',$id_cabang);
		$data_result = $this->db->get('trans_stok')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/stok/transfer_stok/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/stok/transfer_stok/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		if(!empty($tgl_trans)){
			$this->db->where('YEAR(tgl_trans)',date('Y', $tgl_trans));
			$this->db->where('MONTH(tgl_trans)',date('m', $tgl_trans));
			$this->db->where('DAYOFMONTH(tgl_trans)',date('d', $tgl_trans));
		}
		
		if(!empty($month)){
			$this->db->where('MONTH(tgl_trans)',$month);
		}
		
		if(!empty($year)){
			$this->db->where('YEAR(tgl_trans)',$year);
		}
		
		if(!empty($tgl_con)){
			$this->db->where('YEAR(tgl_con)',date('Y', $tgl_con));
			$this->db->where('MONTH(tgl_con)',date('m', $tgl_con));
			$this->db->where('DAYOFMONTH(tgl_con)',date('d', $tgl_con));
		}
		
		if(!$check_admin) $this->db->where('id_from',$id_cabang);
		$this->db->order_by('id','desc');
		$query = $this->db->get('trans_stok',$config['per_page'],$from)->result_array();
		$data['data'] = $query; 
		$this->load->view('index',$data);
	}
	
	public function search_transfer_stok(){
		$tgl_trans = $this->input->post('tgl_trans');
		$tgl_con = $this->input->post('tgl_con');;
		$month = $this->input->post('month');;
		$year = $this->input->post('year');;
		redirect('stok/transfer_stok/'.$tgl_trans.'^'.$tgl_con.'^'.$month.'^'.$year);
	}

	public function penerima_stok(){
		$username = $this->session->userdata('username');
		$check_admin = $this->komputer->isAdmin($username);	
		$id_cabang = $this->komputer->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode('%5E',$link);
			$penerima = $t[0];
			$tgl_kirim = $t[1];
			$tgl_con = $t[2];
			$month = $t[3];
			$year = $t[4];
		}
		
		if(!empty($tgl_trans)){
			$this->db->where('YEAR(tgl_trans)',date('Y', $tgl_trans));
			$this->db->where('MONTH(tgl_trans)',date('m', $tgl_trans));
			$this->db->where('DAYOFMONTH(tgl_trans)',date('d', $tgl_trans));
		}
		
		if(!empty($month)){
			$this->db->where('MONTH(tgl_trans)',$month);
		}
		
		if(!empty($year)){
			$this->db->where('YEAR(tgl_trans)',$year);
		}
		
		if(!empty($tgl_con)){
			$this->db->where('YEAR(tgl_con)',date('Y', $tgl_con));
			$this->db->where('MONTH(tgl_con)',date('m', $tgl_con));
			$this->db->where('DAYOFMONTH(tgl_con)',date('d', $tgl_con));
		}
		if(!empty($penerima))$this->db->where('id_user',$penerima);
		
		if(!$check_admin) $this->db->where('id_to',$id_cabang);
		
		$data_result = $this->db->get('trans_stok')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/stok/penerima_stok/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/stok/penerima_stok/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		if(!empty($tgl_trans)){
			$this->db->where('YEAR(tgl_trans)',date('Y', $tgl_trans));
			$this->db->where('MONTH(tgl_trans)',date('m', $tgl_trans));
			$this->db->where('DAYOFMONTH(tgl_trans)',date('d', $tgl_trans));
		}
		
		if(!empty($month)){
			$this->db->where('MONTH(tgl_trans)',$month);
		}
		
		if(!empty($year)){
			$this->db->where('YEAR(tgl_trans)',$year);
		}
		
		if(!empty($tgl_con)){
			$this->db->where('YEAR(tgl_con)',date('Y', $tgl_con));
			$this->db->where('MONTH(tgl_con)',date('m', $tgl_con));
			$this->db->where('DAYOFMONTH(tgl_con)',date('d', $tgl_con));
		}
		
		if(!empty($penerima))$this->db->where('id_user',$penerima);
		
		if(!$check_admin) $this->db->where('id_to',$id_cabang);
		$this->db->order_by('id','desc');
		$query = $this->db->get('trans_stok',$config['per_page'],$from)->result_array();

		$data['data'] = $query; 
		$this->load->view('index',$data);
	}
	
	public function search_penerima_stok(){
		$penerima = $this->input->post('penerima');
		$tgl_kirim = strtotime($this->input->post('tgl_kirim'));
		$tgl_con = strtotime($this->input->post('tgl_con'));
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$url = $penerima.'^'.$tgl_kirim.'^'.$tgl_con.'^'.$month.'^'.$year;
		redirect('stok/penerima_stok/'.$url);
	}
	
	
	public function stok_opname(){
		$username = $this->session->userdata('username');
		$check_admin = $this->komputer->isAdmin($username);	
		$id_cabang = $this->komputer->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode('%5E',$link);
			$nama = $t[0];
			$tipe = $t[1];
			$id_kategori = $t[2];
			$id_cabang = $t[3];
		} else {
			$kode = '';
			$nama = '';
			$id_kategori = '';
			$id_cabang = '';
		}
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		if(!empty($id_kategori)) $this->db->where('id_kategori',$id_kategori);
		$data_result = $this->db->get('item')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/stok/stok_opname/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/stok/stok_opname/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		if(!empty($id_kategori)) $this->db->where('id_kategori',$id_kategori);
		$query = $this->db->get('item',$config['per_page'],$from)->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_stok_opname(){
		$nama = $this->input->post('nama');
		$tipe = $this->input->post('tipe');
		$kategori = $this->input->post('kategori');
		$id_cabang = $this->input->post('id_cabang');
		$url = $nama.'^'.$tipe.'^'.$kategori.'^'.$id_cabang;
		redirect('stok/stok_opname/'.$url);
	}
	
	public function add_transfer_stok(){
		$tgl_trans = $this->input->post('tgl_trans');
		$waktu_trans = $this->input->post('waktu_trans');
		$id_from = $this->input->post('id_from');
		$id_to = $this->input->post('id_to');
		$deskripsi = $this->input->post('deskripsi');
		
		if(!empty($waktu_trans)){
			$tgl_trans = $tgl_trans.' '.$waktu_trans;
		}
		$data = array(
			'tgl_trans' => $tgl_trans,
			'id_from' => $id_from,
			'id_to' => $id_to,
			'tgl_trans' => $tgl_trans,
			'deskripsi' => $deskripsi,
		);
		$ins = $this->komputer->insertItem('trans_stok',$data);
		if($ins){
			$id_baru = $this->db->insert_id();
			$this->session->set_flashdata('message','Sukses Menambah Transfer Stok');
			redirect('stok/daftar_item_stok/'.$id_baru.'^');
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Transfer Stok');
			redirect($this->agent->referrer());
		}	
	}
	
	public function ubah_transfer_stok($id){
		$tgl_trans = $this->input->post('tgl_trans');
		$waktu_trans = $this->input->post('waktu_trans');
		$id_from = $this->input->post('id_from');
		$id_to = $this->input->post('id_to');
		$deskripsi = $this->input->post('deskripsi');
		
		if(!empty($waktu_trans)){
			$tgl_trans = $tgl_trans.' '.$waktu_trans;
		}
		$data = array(
			'tgl_trans' => $tgl_trans,
			'id_from' => $id_from,
			'id_to' => $id_to,
			'tgl_trans' => $tgl_trans,
			'deskripsi' => $deskripsi,
		);
		$ins = $this->komputer->updateItem('trans_stok',$data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Transfer Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Transfer Stok');
			redirect($this->agent->referrer());
		}	
	}

	public function terima_stok($id,$id_user,$id_to){
	
		$data = array(
			'id_user' => $id_user,
			'status' => 1,
			'tgl_con' => date('Y-m-d H:i:s'),
		);
		
		$ins = $this->komputer->updateItem('trans_stok',$data, array('id' => $id));
		$send = array(
			'cabang' => $id_to,
		);
		if($ins){
			$trans_item = $this->komputer->cek($id,'id_trans_stok','trans_item');
				foreach($trans_item as $ti){
					$serial = $ti['serial'];
					$update = $this->komputer->updateItem('serial',$send, array('id' => $serial));	
				}
			$this->session->set_flashdata('message','Sukses Stok diterima');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Stok diterima');
			redirect($this->agent->referrer());
		}	
	}
	
	public function daftar_item_stok(){
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		}
		if($posisi > 0){
			$t = explode("%5E",$link);
			$id = $t[0];
			$search = $t[1];
			$search = str_replace("%20"," ",$search);
		}
		$trans_stok = $this->komputer->cek($id,'id','trans_stok');
		$id_from = $trans_stok[0]['id_from'];
		$id_to = $trans_stok[0]['id_to'];
		$deskripsi = $trans_stok[0]['deskripsi'];
		$tgl_trans = $trans_stok[0]['tgl_trans'];
		$status = $trans_stok[0]['status'];
		$id_user = $trans_stok[0]['id_user'];
		$tgl_con = $trans_stok[0]['tgl_con'];
		$this->db->where('id_trans_stok',$id);
		$query = $this->db->get('trans_item')->result_array();
		$data['id_trans'] = $id;
		$data['data'] = $query;
		$data['search'] = $search;
		$data['id_from'] = $id_from;
		$data['tgl_con'] = $tgl_con;
		$data['id_user'] = $id_user;
		$data['id_to'] = $id_to;
		$data['deskripsi'] = $deskripsi;
		$data['tgl_trans'] = $tgl_trans;
		$data['id_daftar'] = $id;
		$data['status'] = $status;
		$this->load->view('index',$data);
	}
	
	public function search_daftar_item(){
		$id = $this->input->post('id_daftar');
		$serial = $this->input->post('serial');
		$url = $id.'^'.$serial;
		redirect('stok/daftar_item_stok/'.$url);
	}
	
	public function add_daftar_item($id_daftar,$id_item,$serial){
		
		$data = array(
			'id_trans_stok' => $id_daftar,
			'id_item' => $id_item,
			'serial' => $serial,
			'unit' => 1,
		);
		
		$this->db->where('serial',$serial);
		$this->db->where('id_trans_stok',$id_daftar);
		$cek = $this->db->get('trans_item')->num_rows();
		if($cek && $serial != 0){
			$this->session->set_flashdata('message','SN telah terdaftar');
			redirect($this->agent->referrer());
		}
		$ins = $this->komputer->insertItem('trans_item',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah daftar Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah daftar Stok');
			redirect($this->agent->referrer());
		}	
	}
	
	public function add_daftar_item_status($id_daftar,$id_item,$serial,$cabang){
		
		$data = array(
			'id_trans_stok' => $id_daftar,
			'id_item' => $id_item,
			'serial' => $serial,
			'unit' => 1,
		);
		
		$check_item = $this->komputer->cek($id_item,'id','item');
		if($check_item[0]['serial'] == 1){
		$update = $this->komputer->updateItem('serial',array('cabang' => $cabang), array('id' => $serial));	
			$this->db->where('serial',$serial);
			$this->db->where('id_trans_stok',$id_daftar);
			$cek = $this->db->get('trans_item')->num_rows();
			if($cek){
				$this->session->set_flashdata('message','SN telah terdaftar');
				redirect($this->agent->referrer());
			}
		}
		$ins = $this->komputer->insertItem('trans_item',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah daftar Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah daftar Stok');
			redirect($this->agent->referrer());
		}	
	}
	
	public function ubah_unit($id){
		
		$data = array(
			'unit' => (int)$this->input->post('unit'),
		);
	
		$ins = $this->komputer->updateItem('trans_item',$data, array('id' => $id));

		if($ins){
			$this->session->set_flashdata('message','Sukses Merubah daftar Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Merubah daftar Stok');
			redirect($this->agent->referrer());
		}	
	}
	
	public function supplier()
	{	
		$data_result = $this->db->get('supplier')->num_rows();
		$config['base_url'] = base_url().'index.php/stok/supplier/';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(3);
		$this->pagination->initialize($config);
		$this->db->order_by('id','desc');
		$query = $this->db->get('supplier',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}

	public function tambah_supplier(){
		$supplier = $this->input->post('supplier');
		if(empty($supplier)){
			$this->session->set_flashdata('message','Supplier belum diisi');
			redirect($this->agent->referrer());
		}
		$data = array(
			'supplier' => $supplier,
		);
		$ins = $this->komputer->insertItem('supplier',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Supplier');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Supplier');
			redirect($this->agent->referrer());
		}
	}
	
	public function update_supplier($id){
		
		$data = array(
			'supplier' => $this->input->post('supplier'),
		);
		$update = $this->komputer->updateItem('supplier',$data,array('id'=> $id));
		if($update){
			$this->session->set_flashdata('message','Sukses Merubah Supplier');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Merubah Supplier');
			redirect($this->agent->referrer());
		}
	}
	
	public function detail_supplier($id){
		$data = array(
			'supplier' => $this->input->post('supplier'),
			'deskripsi' => $this->input->post('alamat').'^'.$this->input->post('hp'),
		);
		
		$update = $this->komputer->updateItem('supplier',$data,array('id'=> $id));
		if($update){
			$this->session->set_flashdata('message','Sukses Merubah Supplier');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Merubah Supplier');
			redirect($this->agent->referrer());
		}
	}
	
	public function pembelian_stok()
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
			$nota = $t[0];
			$tanggal = $t[1];
			$supplier = (int)$t[2];
			$month = (int)$t[3];
			$year = (int)$t[4];
			$kondisi = (int)$t[5];
			$cabang = (int)$t[6];
		}  else {
			$nota = '';
			$tanggal = '';
			$supplier = '';
			$month = date('m');
			$year = date('Y');
			$kondisi = '';
		}
		
		if(!empty($nota)) $this->db->like('nota',$nota);
		if(!empty($kondisi)) $this->db->where('status',$kondisi);
		
		if($supplier != 0){ 
			$this->db->where('id_supplier',$supplier);
		} 
		
		if(!empty($cabang)) $this->db->where('id_cabang',$cabang);
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		$data_result = $this->db->get('pembelian')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/stok/pembelian_stok/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/stok/pembelian_stok/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		if(!empty($nota)) $this->db->like('nota',$nota);
		if(!empty($kondisi)) $this->db->where('status',$kondisi);
		
		if($supplier != 0){ 
			$this->db->where('id_supplier',$supplier);
		} 
		if(!empty($cabang)) $this->db->where('id_cabang',$cabang);
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('pembelian',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_pembelian_stok(){
		$nota = $this->input->post('nota');
		$tanggal = strtotime($this->input->post('tanggal'));
		$supplier = $this->input->post('supplier');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$kondisi = $this->input->post('kondisi');
		$cabang = $this->input->post('cabang');
		$url = $nota.'^'.$tanggal.'^'.$supplier.'^'.$month.'^'.$year.'^'.$kondisi.'^'.$cabang;
		redirect('stok/pembelian_stok/'.$url);
	}
	
	public function tambah_pembelian_stok(){
		$data = array(
			'id_supplier' => $this->input->post('id_supplier'),
			'keterangan' => $this->input->post('keterangan'),
			'nota' => $this->input->post('nota'),
			'id_supplier' => $this->input->post('id_supplier'),
			'id_cabang' => $this->input->post('cabang'),
			'waktu' => $this->input->post('waktu'),
			'waktu_tempo' => $this->input->post('waktu_tempo'),
			'status' => 1,
		);
		$ins = $this->komputer->insertItem('pembelian',$data);
		if($ins){
			$id_baru = $this->db->insert_id();
			$this->session->set_flashdata('message','Sukses Menambah Pembelian Stok');
			redirect('stok/detail_pembelian_stok/'.$id_baru);
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Pembelian Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_stok_kongsi(){
		
		$serial = $this->input->post('serial');
		$check_serial = $this->komputer->cek($serial,'serial','serial');
		if(!empty($check_serial)){
			$this->session->set_flashdata('message','Serial Telah Ada');
			redirect($this->agent->referrer());
		}
		
		$data = array(
			'id_supplier' => $this->input->post('id_supplier'),
			'keterangan' => $this->input->post('keterangan'),
			'id_cabang' => $this->input->post('cabang'),
			'nota' => $this->input->post('nota'),
			'waktu' => $this->input->post('waktu'),
			'status' => 2,
		);
		$ins = $this->komputer->insertItem('pembelian',$data);
		
		$id_pembelian = $this->db->insert_id();
		
		$data_pembelian = array(
			'id_item' => $this->input->post('id_item'),
			'harga' => $this->input->post('harga_pokok'),
			'id_pembelian' => $id_pembelian,
		);
		if(!empty($this->input->post('unit'))){
			$data_pembelian['unit'] = $this->input->post('unit');
		} else {
			$data_pembelian['unit'] = 0;
		}	
		$ins = $this->komputer->insertItem('sub_pembelian',$data_pembelian);
		if(!empty($serial)){
			$data_serial = array(
				'id_item' => $this->input->post('id_item'),
				'serial' => $this->input->post('serial'),
				'cabang' => $this->input->post('cabang'),
				'kondisi' => $this->input->post('kondisi'),
				'status' => 0,
				'id_pembelian' => $id_pembelian,
			);
			$ins = $this->komputer->insertItem('serial',$data_serial);
		}	
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Pembelian stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Pembelian Stok');
			redirect($this->agent->referrer());
		}
	}
	
	
	public function ubah_pembelian_stok($id){
		$data = array(
			'id_supplier' => $this->input->post('id_supplier'),
			'keterangan' => $this->input->post('keterangan'),
			'nota' => $this->input->post('nota'),
			'id_supplier' => $this->input->post('id_supplier'),
			'waktu' => $this->input->post('waktu'),
			'id_cabang' => $this->input->post('cabang'),
			'waktu_tempo' => $this->input->post('waktu_tempo'),
		);
		$ins = $this->komputer->updateItem('pembelian',$data, array('id'=> $id));
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Pembelian Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Pembelian Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function detail_pembelian_stok(){
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		
		if($posisi > 0){
			$t = explode('%5E',$link);
			$id = $t[0];
			$nama = $t[1];
			$tipe = $t[2];
			$merek = $t[3];
			$warna = $t[4];
			$nama = str_replace("%20"," ",$nama);
			$warna = str_replace("%20"," ",$warna);
			$merek = str_replace("%20"," ",$merek);
			$tipe = str_replace("%20"," ",$tipe);
		} else {
			$id = $this->uri->segment(3);
			$nama = '';
			$tipe = '';
			$merek = '';
			$warna = '';
		}
		$this->db->where('id_pembelian',$id);
		$query = $this->db->get('serial')->result_array();
		
		$this->db->where('id_pembelian',$id);
		$sub_pembelian = $this->db->get('sub_pembelian')->result_array();
		
		$this->db->where('id',$id);
		$pembelian = $this->db->get('pembelian')->result_array();
		
		$data['nama'] = $nama;
		$data['warna'] = $warna;
		$data['tipe'] = $tipe;
		$data['merek'] = $merek;
		$data['pembelian'] = $pembelian;
		$data['sub_pembelian'] = $sub_pembelian;
		$data['serial'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_detail_pembelian_stok(){
		$id_pembelian = $this->input->post('id_pembelian');
		$nama = $this->input->post('nama');
		$tipe = $this->input->post('tipe');
		$merek = $this->input->post('merek');
		$warna = $this->input->post('warna');
		$url = $id_pembelian.'^'.$nama.'^'.$tipe.'^'.$merek.'^'.$warna;
		redirect('stok/detail_pembelian_stok/'.$url);
	}
	
	public function ubah_harga_pembelian($id){
		$data = array();
		
		if(!empty($this->input->post('unit'))){
			$data['unit'] = $this->input->post('unit');
		}
		
		if(!empty($this->input->post('harga'))){
			$harga = str_replace(".","",$this->input->post('harga'));
			$data['harga'] = $harga;
		} 
		
		$ins = $this->komputer->updateItem('sub_pembelian', $data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Sukses Ubah Harga');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Ubah Harga');
			redirect($this->agent->referrer());
		}	
	}
	
	public function bayar_sub_pembelian($id){
		$bayar = $this->input->post('bayar');
		$bayar = str_replace(".","",$bayar);
		$m_pembayaran = $this->input->post('tempat_kas');
		$nota = $this->input->post('nota');

		$data = array(
			'bayar' => $bayar,
			'waktu' => $this->input->post('waktu'),
		);

		if($this->input->post('total') <= $bayar){
			$data['status'] = 2;
		} else {
			$data['status'] = 1;
		}
		$ins = $this->komputer->updateItem('pembelian', $data, array('id' => $id));
		if($ins){
			$this->db->where('id_pembelian',$id);
			$cek = $this->db->get('kas')->result_array();

			$item = array(
				'id_cabang' => 100,
				'id_pembelian' => $id,
				'deskripsi' => 'PEMBELIAN'.$nota,
				'id_stor' => $m_pembayaran,
				'id_transaksi' => 0,
				'id_service' => 0,
				'status' => 3,
				'waktu' => $this->input->post('waktu'),

			);
			if(empty($cek)){
				$add = $this->komputer->insertItem('kas', $item);
			} else {
				$add = $this->komputer->updateItem('kas', $item, array('id' => $cek[0]['id']));
			}
			$this->session->set_flashdata('message','Sukses Ubah Harga');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Ubah Harga');
			redirect($this->agent->referrer());
		}	
	}
	
	public function add_sub_pembelian($id_item,$harga,$id_pembelian){
		$data = array(
			'id_item' => $id_item,
			'harga' => $harga,
			'id_pembelian' => $id_pembelian,
		);
		$this->db->where('id_item',$id_item);
		$this->db->where('id_pembelian',$id_pembelian);
		$cek = $this->db->get('sub_pembelian')->num_rows();
		if($cek){
			$this->session->set_flashdata('message','Item sudah ditambahkan');
			redirect($this->agent->referrer());
		}
		$ins = $this->komputer->insertItem('sub_pembelian',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Sub Pembelian Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Sub Pembelian Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function delete_sub_pembelian($id,$id_pembelian,$id_item){
		$delete_sub = $this->komputer->deleteItem('sub_pembelian',array('id'=>$id));
		if($delete_sub){
			$delete_serial = $this->komputer->deleteItem('serial',array('id_item'=>$id_item, 'id_pembelian'=>$id_pembelian));
			$this->session->set_flashdata('message','Sukses Menghapus Sub Pembelian');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menghapus Sub Pembelian');
			redirect($this->agent->referrer());
		}
	}
	
	public function remove_trans_item($id){
		$delete_sub = $this->komputer->deleteItem('trans_stok',array('id'=>$id));
		if($delete_sub){
			$this->session->set_flashdata('message','Sukses Menghapus Transfer Stok');
			redirect('stok/transfer_stok/^^'.date('m').'^'.date('Y'));
		} else {
			$this->session->set_flashdata('message','Gagal Menghapus Transfer Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function delete_pembelian($id){
		$delete_sub = $this->komputer->deleteItem('pembelian',array('id'=>$id));
		if($delete_sub){
			$delete_serial = $this->komputer->deleteItem('serial',array('id_pembelian'=>$id));
			$delete_sub_pembelian = $this->komputer->deleteItem('sub_pembelian',array('id_pembelian'=>$id));
			$this->session->set_flashdata('message','Sukses Menghapus Sub Pembelian');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menghapus Sub Pembelian');
			redirect($this->agent->referrer());
		}
	}
	
	public function simpan_pembelian($id){
		
		$this->db->where('id_pembelian',$id);
		$check_sub = $this->db->get('serial')->result_array();
		if(!empty($check_sub)){
			foreach($check_sub as $cs){
				if($cs['status'] != 1){
					$id_serial = $cs['id'];
					$data = array(
						'status' => 0,
					);
					$ins = $this->komputer->updateItem('serial', $data, array('id' => $id_serial));
				}		
			}
		}	
		if($ins){
			$this->session->set_flashdata('message','Sukses Ubah Harga');
			redirect($this->agent->referrer());	
		} else {
			$this->session->set_flashdata('message','Belum ada pembelian');
			redirect($this->agent->referrer());
		}	
	}
	
	public function proses_laporan_stok()
	{
		$id_cabang = $this->input->post('id_cabang');
		redirect('stok/laporan_stok/'.$id_cabang);
	}
	
	public function laporan_stok($url)
	{	$data['data'] = $url;
		$this->load->view('laporan_stok',$data);
	}
	
	public function return_stok(){
		$username = $this->session->userdata('username');
		$check_admin = $this->komputer->isAdmin($username);	
		$id_cabang = $this->komputer->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode("%5E",$link);
			$tgl_trans = $t[0];
			$tgl_con = $t[1];
			$month = $t[2];
			$year = $t[3];
		}
				
		
		$data_result = $this->db->get('return_stok')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/stok/return_stok/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/stok/return_stok/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		$this->db->order_by('id','desc');
		$query = $this->db->get('return_stok',$config['per_page'],$from)->result_array();
		$data['data'] = $query; 
		$this->load->view('index',$data);
	}
	
	public function add_return_stok(){
		$waktu = $this->input->post('waktu');
		$nota = $this->input->post('nota');
		$id_supplier = $this->input->post('id_supplier');
		$keterangan = $this->input->post('keterangan');
		
		$data = array(
			'waktu' => $tgl_trans,
			'nota' => $nota,
			'id_supplier' => $id_supplier,
			'keterangan' => $keterangan,
			'waktu' => $waktu,
		);
		
		$ins = $this->komputer->insertItem('return_stok',$data);
		if($ins){
			$id_baru = $this->db->insert_id();
			$this->session->set_flashdata('message','Sukses Menambah Return Stok');
			redirect('stok/daftar_return_stok/'.$id_baru.'^');
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Return Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_return_stok($id){
		$waktu = $this->input->post('waktu');
		$nota = $this->input->post('nota');
		$id_supplier = $this->input->post('id_supplier');
		$keterangan = $this->input->post('keterangan');
		
		$data = array(
			'waktu' => $tgl_trans,
			'nota' => $nota,
			'id_supplier' => $id_supplier,
			'keterangan' => $keterangan,
			'waktu' => $waktu,
		);
		
		$ins = $this->komputer->updateItem('return_stok',$data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Return Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Return Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function daftar_return_stok(){
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		}
		if($posisi > 0){
			$t = explode("%5E",$link);
			$id = $t[0];
			$search = $t[1];
		}
		$return = $this->komputer->cek($id,'id','return_stok');
		$data['id'] = $id;
		$data['return'] = $return;
		$data['search'] = $search;
		$this->load->view('index',$data);
	}
	
	public function search_return_item(){
		$id = $this->input->post('id');
		$search = $this->input->post('search');
		redirect('stok/daftar_return_stok/'.$id.'^'.$search);
	}
	
	public function add_return_item_list($id_return,$id_item,$id_serial,$unit){
		
		$data = array(
			'id_return_stok' => $id_return,
			'id_item' => $id_item,
			'id_serial' => $id_serial,
			'unit' => $unit,
		);
		
		$ins = $this->komputer->insertItem('return_item',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Return Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Return Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_unit_return_stok($id){
		$unit = $this->input->post('unit');
		$data = array(
			'unit' => $unit,
		);
		
		$ins = $this->komputer->updateItem('return_item',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Sukses Merubah Unit Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Merubah Unit Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_tukar_serial_return($id){
		
		$serial = $this->input->post('serial');
		$data = array(
			'serial' => $serial,
		);
		
		$ins = $this->komputer->updateItem('return_item',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Tukar Serial Return');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Tukar Serial Return');
			redirect($this->agent->referrer());
		}
	}
	
	public function proses_return_stok($id){
		
		$proses_return = $this->input->post('proses_return');
		$data = array(
			'status' => $proses_return,
		);
		$sub_return = $this->komputer->cek($id,'id_return_stok','return_item');
		$ins = $this->komputer->updateItem('return_stok',$data,array('id' => $id));
		if($ins){
			if($proses_return == 1){
				foreach($sub_return as $sr){
					$id_serial = $sr['id_serial'];
					$update = $this->komputer->updateItem('serial',array('status' => 4),array('id'=>$id_serial));
				}
			} elseif($proses_return == 2){
				foreach($sub_return as $sr){
					$id_sub_item = $sr['id'];
					$id_serial = $sr['id_serial'];
					if($id_serial != 0){
						$serial_first = $this->komputer->cek($id_serial,'id','serial');
						$serial2 = $serial_first[0]['serial'];
						$serial = $sr['serial'];
						$change = array(
							'status' => 0,
							'serial' => $serial,
						);
						$update = $this->komputer->updateItem('serial',$change,array('id'=>$id_serial));
						$chane_serial = $this->komputer->updateItem('return_stok',array('waktu_a' => date('Y-m-d')),array('id'=>$id));
						$chane_serial = $this->komputer->updateItem('return_item',array('serial' => $serial2),array('id'=>$id_sub_item));
					}
				}
			}
			$this->session->set_flashdata('message','Sukses Merubah Proses Return');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Merubah Proses Return');
			redirect($this->agent->referrer());
		}
	}
	
	public function stok_keluar(){
		$username = $this->session->userdata('username');
		$check_admin = $this->komputer->isAdmin($username);	
		$id_cabang = $this->komputer->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		
		if($posisi > 0){
			$t = explode("%5E",$link);
			$nota = $t[0];
			$tanggal = $t[1];
			$month = $t[2];
			$year = $t[3];
			$status = $t[4];
			$cabang2 = $t[5];
		}
				
		if(!empty($cabang2)) $this->db->where('id_cabang',$cabang2);
		if(!empty($nota)) $this->db->where('nota',$nota);
		if(!empty($year)) $this->db->where('YEAR(waktu)',$year);
		if(!empty($month)) $this->db->where('MONTH(waktu)',$month);
		
		$data_result = $this->db->get('stok_keluar')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/stok/stok_keluar/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/stok/stok_keluar/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		if(!empty($cabang2)) $this->db->where('id_cabang',$cabang2);
		if(!empty($nota)) $this->db->where('nota',$nota);
		if(!empty($year)) $this->db->where('YEAR(waktu)',$year);
		if(!empty($month)) $this->db->where('MONTH(waktu)',$month);
		
		$query = $this->db->get('stok_keluar',$config['per_page'],$from)->result_array();
		$data['data'] = $query; 
		$this->load->view('index',$data);
	}
	
	public function tambah_stok_keluar(){
		$data = array(
			'nota' => $this->input->post('nota'),
			'status' => 0,
			'id_cabang' => $this->input->post('cabang'),
			'keterangan' => $this->input->post('keterangan'),
			'waktu' => $this->input->post('waktu'),
		);
		
		$ins = $this->komputer->insertItem('stok_keluar',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses menambah Stok Keluar');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Stok Keluar');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_stok_keluar($id){
		$data = array(
			'nota' => $this->input->post('nota'),
			'id_cabang' => $this->input->post('cabang'),
			'keterangan' => $this->input->post('keterangan'),
			'waktu' => $this->input->post('waktu'),
		);
		
		$ins = $this->komputer->updateItem('stok_keluar',$data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Sukses ubah Stok Keluar');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal ubah Stok Keluar');
			redirect($this->agent->referrer());
		}
	}
	
	public function delete_sub_stok_keluar($id,$id_serial){
		$delete = $this->komputer->deleteItem('sub_stok_keluar',array('id_serial' => $id_serial));
		if($delete){
			$data = array(
				'status' => 0,
			);
			$update = $this->komputer->updateItem('serial',$data, array('id' => $id_serial));
			$this->session->set_flashdata('message','Sukses Pengembalian Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Pengembalian Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function search_stok_keluar(){
		$nota = $this->input->post('nota');
		$waktu = strtotime($this->input->post('waktu'));
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$status = $this->input->post('status');
		$cabang = $this->input->post('cabang');
		$url = $nota.'^'.$waktu.'^'.$month.'^'.$year.'^'.$status.'^'.$cabang;
		redirect('stok/stok_keluar/'.$url);
	}
	
	public function detail_stok_keluar($id){
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		
		if($posisi > 0){
			$t = explode('%5E',$link);
			$id = $t[0];
			$search = $t[1];
		} else {
			$search = '';
		}
		$stok_keluar = $this->komputer->cek($id,'id','stok_keluar');
		$data['search'] = $search;
		$data['stok_keluar'] = $stok_keluar;
		$this->load->view('index',$data);
	}	
	
	public function search_detail_stok_keluar(){
		$id = $this->input->post('id');
		$search = $this->input->post('search');
		$url = $id.'^'.$search;
		redirect('stok/detail_stok_keluar/'.$url);
	}
	
	public function add_stok_keluar_list($id_stok_keluar,$id_item,$id_serial,$unit){
		$data = array(
			'id_stok_keluar' => $id_stok_keluar,
			'id_item' => $id_item,
			'id_serial' => $id_serial,
			'unit' => $unit,
		);
		$ins = $this->komputer->insertItem('sub_stok_keluar',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Stok Keluar');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Stok Keluar');
			redirect($this->agent->referrer());
		}
	}
	
	public function simpan_stok_keluar($id_stok_keluar){
		$data = array(
			'status' => 1,
		);
		$ins = $this->komputer->updateItem('stok_keluar',$data,array('id'=>$id_stok_keluar));
		
		if($ins){
			$status = array(
				'status' => 3,
			);
			$this->db->where('id_stok_keluar',$id_stok_keluar);
			$serial = $this->db->get('sub_stok_keluar')->result_array();
			foreach($serial as $s){
				$id_serial = $s['id_serial'];
				$this->komputer->updateItem('serial',$status,array('id'=>$id_serial));
			}
			$this->session->set_flashdata('message','Sukses Menambah Stok Keluar');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Stok Keluar');
			redirect($this->agent->referrer());
		}
	}
	
	public function stok_pengembalian()
	{	
		
		$serial = $this->uri->segment(3);
		
		$this->db->select('*');
		$this->db->from('serial');
		$this->db->join('item','serial.id_item = item.id');
		if(!empty($serial))$this->db->like('serial.serial',$serial);
		$this->db->where('serial.status',3);
		$this->db->order_by('serial.id_item','asc');
		$data_result = $this->db->get()->num_rows();
		
		if(!empty($serial)){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/transaksi/daftar_penjualan/'.$serial;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/transaksi/daftar_penjualan/';
		}	
	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		$this->db->select('item.nama,item.merek,item.tipe,item.warna,serial.serial,serial.id');
		$this->db->join('item','serial.id_item = item.id');
		$this->db->where('serial.status',3);
		
		if(!empty($serial)) $this->db->like('serial.serial',$serial);
		$this->db->order_by('serial.id_item','asc');
		$query = $this->db->get('serial',$config['per_page'],$from)->result_array();
	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_stok_pengembalian(){
	$serial = $this->input->post('serial');
	redirect('stok/stok_pengembalian/'.$serial);
	
	}
	
	public function proses_stok_pengembalian($id){
		$data = array(
			'status' => 0,
			'cabang' => 1,
		);
		$ins = $this->komputer->updateItem('serial',$data,array('id'=>$id));
		if($ins){
			$this->session->set_flashdata('message','Sukses Mengembalikan Stok');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Mengembalikan Stok');
			redirect($this->agent->referrer());
		}
	}
	
	public function history_pembelian_stok(){
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode('%5E',$link);
			$id_item = $t[0];
			$tanggal = $t[1];
			$month = $t[2];
			$year = $t[3];
		} else {
			$tanggal = $t[1];
			$month = $t[2];
			$year = $t[3];
		}
		
		$this->db->select('*');
		$this->db->join('pembelian','sub_pembelian.id_pembelian = pembelian.id');
		$this->db->where('sub_pembelian.id_item',$id_item);
		$data_result = $this->db->get('sub_pembelian')->num_rows();	
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/stok/history_pembelian_stok/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/stok/history_pembelian_stok/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 30;
		$this->pagination->initialize($config);
		
		$this->db->select('*');
		$this->db->join('pembelian','sub_pembelian.id_pembelian = pembelian.id');
		$this->db->where('sub_pembelian.id_item',$id_item);
		$query = $this->db->get('sub_pembelian',$config['per_page'],$from)->result_array();

		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function edit_sub_stok_keluar($id){
		$unit = $this->input->post('unit');
		$data = array(
			'unit' => $unit,
		);
		$ins = $this->komputer->updateItem('sub_stok_keluar',$data,array('id'=>$id));
		if($ins){
			$this->session->set_flashdata('message','Berhasil diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function add_pelunasan($id){
		$nominal = $this->input->post('nominal');
		$nominal = str_replace(".","",$nominal);
		$bayar = $this->input->post('bayar');
		$bayar = str_replace(".","",$bayar);
		$total_pembelian = $this->input->post('total_pembelian');
		$waktu = strtotime($this->input->post('waktu'));
		$waktu_pembelian = $this->input->post('waktu_pembelian');
		$waktu_con = $this->input->post('waktu_con');
		$data = array(
			'nominal' => $nominal,
			'waktu' => $waktu,
			'id_pembelian' => $id,
		);
		$ins = $this->komputer->insertItem('pelunasan',$data);
		if($ins){
			$total_pelunasan = array();
			$this->db->where('id_pembelian', $id);
			$pelunasan = $this->db->get('pelunasan')->result_array();
			foreach($pelunasan as $p){
				$total_pelunasan[] = $p['nominal'];
			}
			$total_pelunasan = array_sum($total_pelunasan);
			$total_pembayaran = (int)$bayar + (int)$total_pelunasan;
			if($total_pembelian <= $total_pembayaran){
				$this->komputer->updateItem('pembelian',array('status' => 2, 'waktu' => $waktu_pembelian, 'waktu_tempo'=> $waktu_con), array('id'=>$id));
			} else {
				$this->komputer->updateItem('pembelian',array('status' => 1, 'waktu' => $waktu_pembelian, 'waktu_tempo'=> $waktu_con), array('id'=>$id));
			}
			$this->session->set_flashdata('message','Pembayaran ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Pembayaran gagal ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function delete_pelunasan($tabel,$id,$id_pembelian,$bayar,$total_pembelian,$waktu,$waktu_tempo){
		$data = array(
			'id' => $id
		);
		$delete = $this->komputer->deleteItem($tabel,$data);
		if($delete){
			$total_pelunasan = array();
			$this->db->where('id_pembelian', $id);
			$pelunasan = $this->db->get('pelunasan')->result_array();
			foreach($pelunasan as $p){
				$total_pelunasan[] = $p['nominal'];
			}
			$total_pelunasan = array_sum($total_pelunasan);
			$total_pembayaran = $bayar + $total_pelunasan;
			if($total_pembelian <= $total_pembayaran){
				$this->komputer->updateItem('pembelian',array('status' => 2, 'waktu' => $waktu_pembelian, 'waktu_tempo'=> $waktu_con), array('id'=>$id_pembelian));
			} else {
				$this->komputer->updateItem('pembelian',array('status' => 1, 'waktu' => $waktu_pembelian, 'waktu_tempo'=> $waktu_con), array('id'=>$id_pembelian));
			}
			$total_pembayaran = $bayar + $total_pelunasan;
			
			$this->session->set_flashdata('message','Sukses Menghapus Kategori');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Meghapus Kategori');
			redirect($this->agent->referrer());
		}
	}
	
	public function hutang()
	{	
		$this->db->select('id_supplier');
		$this->db->from('pembelian');
		$this->db->join('supplier','pembelian.id_supplier = supplier.id');
		$this->db->where('pembelian.status',1);
		$query = $this->db->get()->result_array();
		$supplier = array();
		$hutang = array();
		foreach($query as $sp){
			$id_supplier = $sp['id_supplier'];
			$supplier[] = $id_supplier;
		}
		
		$all_supplier = array_unique($supplier);
		$sub_hutang = array();
		$y = 1;
		foreach($all_supplier as $sup){
			$this->db->where('status',1);
			$this->db->where('id_supplier',(int)$sup);
			$data = $this->db->get('pembelian')->result_array();
			$x = 1;
			$total_pembelian = array();
			$total_pelunasan = array();
			$bayar = array();
			foreach($data as $d){
				$bayar[] = $d['bayar'];
				$subpem = $this->komputer->cek($d['id'],'id_pembelian','sub_pembelian');
				foreach($subpem as $sp){
					$harga = $sp['harga'];
					$check_serial = $this->komputer->cek($sp['id_item'],'id','item');
					if(!empty($check_serial)){
						$this->db->where('id_item',$sp['id_item']);
						$this->db->where('id_pembelian', $sp['id_pembelian']);
						$count_item = $this->db->get('serial')->num_rows();
						if($check_serial[0]['serial'] == 1){
							$total = $harga * $count_item;
						} else {
							$total = $harga * $sp['unit'];
						}
					} else {
						$total = 0;
					}
					$total_pembelian[] = $total;
				}
				//pelunasan
				$this->db->where('id_pembelian', $d['id']);
				$pelunasan = $this->db->get('pelunasan')->result_array();
				foreach($pelunasan as $p){
					$total_pelunasan[] = $p['nominal'];
				}
			}
			$bayar = array_sum($bayar);
			$total_pembelian = array_sum($total_pembelian);
			$total_pelunasan = array_sum($total_pelunasan);
			
			$sub_hutang['bayar'] = $bayar;
			$sub_hutang['pelunasan'] = $total_pelunasan;
			$sub_hutang['id_supplier'] = $sup;
			$sub_hutang['total_pembelian'] = $total_pembelian;
			$hutang[] = $sub_hutang;
		}
		$data['data'] = $hutang;	
		$this->load->view('index',$data);
	}
}
