<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {
	
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
	
	public function transaksi()
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
			$cabang = (int)$t[2];
			$month = (int)$t[3];
			$year = (int)$t[4];
			$seles = (int)$t[5];
		}  else {
			$nota = '';
			$tanggal = '';
			$cabang = '';
			$month = date('m');
			$year = date('Y');		
		}
		
		$this->db->order_by('id','desc');
		if(!empty($nota)) $this->db->like('nota',$nota);
		
		if($cabang != 0){ 
			$this->db->where('cabang',$cabang);
		} 
		
		if(!$check_admin) {
			$this->db->where('cabang',$id_username);
		}
		
		if(!empty($seles)) {
			$this->db->where('id_user',$seles);
		}
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		$data_result = $this->db->get('transaksi')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/transaksi/transaksi/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/transaksi/transaksi/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		if(!empty($nota)) $this->db->like('nota',$nota);
		
		if($cabang != 0){ 
			$this->db->where('cabang',$cabang);
		} 
		
		if(!$check_admin) {
			$this->db->where('cabang',$id_username);
		}
		
		if(!empty($seles)) {
			$this->db->where('id_user',$seles);
		}
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('transaksi',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_transaksi(){
		$nota = $this->input->post('nota');
		$tanggal = strtotime($this->input->post('tanggal'));
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$seles = $this->input->post('seles');
		redirect('transaksi/transaksi/'.$nota.'^'.$tanggal.'^'.$cabang.'^'.$month.'^'.$year.'^'.$seles);
	}
	
	public function tambah_transaksi(){
		$username = $this->session->userdata('username');
		$id_username = $this->komputer->getIduser($username);
		if(!empty($this->input->post('seles'))){
			$id_username = $this->input->post('seles');
		}
		
		$cabang = $this->input->post('cabang');
		$nota = $this->input->post('nota');
		$waktu = $this->input->post('waktu');
		$alamat = $this->input->post('alamat');
		$pembeli = $this->input->post('pembeli');
		$keterangan = $this->input->post('keterangan');
		$deskripsi = $pembeli.','.$alamat.','.$keterangan;
		
		$data = array(
			'cabang' => $cabang,
			'nota' => '',
			'waktu' => $waktu.' '.date('H:i:s'),
			'id_user' => $id_username,
			'deskripsi' => $deskripsi,
		);
		
		$ins = $this->komputer->insertItem('transaksi',$data);
		
		if($ins){
		$id_baru = $this->db->insert_id();
			$this->session->set_flashdata('message','message','message','Sukses Menambah Transaksi');
			redirect('transaksi/detail_transaksi/'.$id_baru);
		} else {
			$this->session->set_flashdata('message','message','message','Gagal Menambah Transaksi');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_transaksi($id){
		$cabang = $this->input->post('cabang');
		$nota = $this->input->post('nota');
		$waktu = $this->input->post('waktu');
		$jam = $this->input->post('jam');
		$alamat = $this->input->post('alamat');
		$pembeli = $this->input->post('pembeli');
		$keterangan = $this->input->post('keterangan');
		$deskripsi = $pembeli.','.$alamat.','.$keterangan;
		
		$data = array(
			'cabang' => $cabang,
			'nota' => $nota,
			'cabang' => $cabang,
			'id_user' => $this->input->post('seles'),
			'waktu' => $waktu.' '.$jam,
			'deskripsi' => $deskripsi,
		);
		
		$ins = $this->komputer->updateItem('transaksi',$data, array('id' => $id));
		
		$cek = $this->komputer->cek($id,'id_service','kas');
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'id_cabang' => $this->input->post('cabang'),
			'status' => 1,
			'deskripsi' => 'TRANSAKSI'.$this->input->post('nota'),
			'id_service' => $id,
		); 
			
		if($cek){
			$this->komputer->updateItem('kas',$data,array('id'=> $id));
		}
		
		
		if($ins){
			$this->session->set_flashdata('message','message','message','Sukses Merubah Transaksi');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','message','message','Gagal Merubah Transaksi');
			redirect($this->agent->referrer());
		}
	}
	
	public function detail_transaksi(){
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
			$search = str_replace("%20"," ",$search);
			$kongsi = $t[2];
			$kongsi = str_replace("%20"," ",$kongsi);
		} else {
			$id = $this->uri->segment(3);
			$search = '';
			$kongsi = '';
		}
		$this->db->where('id_transaksi',$id);
		$query = $this->db->get('sub_transaksi')->result_array();
		
		$this->db->where('id',$id);
		$transaksi = $this->db->get('transaksi')->result_array();
		
		$data['search'] = $search;
		$data['kongsi'] = $kongsi;
		$data['transaksi'] = $transaksi;
		$data['id_cabang'] = $transaksi[0]['cabang'];
		$data['sub_transaksi'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_detail_transaksi(){
		$search = $this->input->post('search');
		$kongsi = $this->input->post('kongsi');
		$id = $this->input->post('id');
		redirect('transaksi/detail_transaksi/'.$id.'^'.$search.'^'.$kongsi);
	}
	
	public function add_sub_transaksi($id_trans,$id_item,$id_serial,$harga_jual,$total){
		if($total < 1){
			$this->session->set_flashdata('message','message','message','Stok Habis');
			redirect($this->agent->referrer());
		}
		$data = array(
			'id_transaksi' => $id_trans,
			'id_item' => $id_item,
			'id_serial' => $id_serial,
			'harga' => $harga_jual,
			'unit' => 1,
		);
		if($id_serial != 0){
			$cek_sub = $this->komputer->cek($id_serial,'id_serial','sub_transaksi');
			if($cek_sub){
				$this->session->set_flashdata('message','message','message','Serial sudah ditambahkan');
				redirect($this->agent->referrer());
			}
		}	
		$ins = $this->komputer->insertItem('sub_transaksi', $data);
		if($ins){
			$this->session->set_flashdata('message','message','message','Berhasil menambah keranjang');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','message','message','Gagal menambah keranjang');
			redirect($this->agent->referrer());
		}	
	}

	public function add_sub_transaksi_serial($id_trans){
		$serial = $this->input->post('serial');
		$cek_serial = $this->komputer->cek($serial,'serial','serial');
		if($cek_serial){
			$harga = $this->komputer->cek($cek_serial[0]['id_item'],'id_item','harga');
			if(!empty($harga)){
				$harga_jual = $harga[0]['harga_jual'];
			} else {
				$harga_jual = $harga[0]['harga_jual'];
			}
			$data = array(
				'id_transaksi' => $id_trans,
				'id_item' => $cek_serial[0]['id_item'],
				'id_serial' => $cek_serial[0]['id'],
				'harga' => $harga_jual,
				'unit' => 1,
			);

			$cek_sub = $this->komputer->cek($cek_serial[0]['id_serial'],'id_serial','sub_transaksi');
		} else {
			$this->session->set_flashdata('message','message','message','Serial Tidak ditemukan');
			redirect($this->agent->referrer());
		}	
		if($cek_sub){
			$this->session->set_flashdata('message','message','message','Serial sudah ditambahkan');
			redirect($this->agent->referrer());
		}
		$ins = $this->komputer->insertItem('sub_transaksi', $data);
		if($ins){
			$this->session->set_flashdata('message','message','message','Berhasil menambah keranjang');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','message','message','Gagal menambah keranjang');
			redirect($this->agent->referrer());
		}	
	}

	public function ubah_sub_transaksi($id){
		$harga = $this->input->post('harga');
		$harga = str_replace(".","",$harga);
		
		$data = array(
			'unit' => $this->input->post('unit'),
			'harga' => $harga,
		);
		
		$ins = $this->komputer->updateItem('sub_transaksi',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','message','message','Data Berhasil dirubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','message','message','Data Gagal dirubah');
			redirect($this->agent->referrer());
		}	
	}
	
	public function bayar_sub_transaksi($id){
		$bayar = $this->input->post('bayar');
		$bayar = str_replace(".","",$bayar);
		$data = array(
			'total' => $this->input->post('total'),
			'bayar' => $bayar,
			'waktu' => $this->input->Post('waktu'),
		);
		
		$ins = $this->komputer->updateItem('transaksi',$data,array('id' => $id));
		
		if($ins){	
			$this->session->set_flashdata('message','message','message','Transaksi Berhasil disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','message','message','Transaksi Gagal disimpan');
			redirect($this->agent->referrer());
		}	
	}
	
	public function simpan_transaksi($id){
		$data = array(
			'status' => 1,
			'total' => $this->input->post('total'),
		);
		$ins = $this->komputer->updateItem('transaksi',$data,array('id' => $id));
		if($ins){
			$sub_trans = $this->komputer->cek($id,'id_transaksi','sub_transaksi');
			foreach($sub_trans as $s){
				$id_serial = $s['id_serial'];
				$data = array(
					'status' => 1,
				);
				$this->komputer->updateItem('serial',$data,array('id' => $id_serial));
			}
			
			$cek = $this->komputer->cek($id,'id_transaksi','kas');
			$transaksi = $this->komputer->cek($id,'id','transaksi');
			
			$data = array(
				'waktu' => $transaksi[0]['waktu'],
				'id_cabang' => $transaksi[0]['cabang'],
				'status' => 1,
				'deskripsi' => 'TRANSAKSI'.$transaksi[0]['nota'],
				'saldo' => $this->input->post('total'),
				'id_transaksi' => $transaksi[0]['id'],
			); 
			
			if(empty($cek)){
				$this->komputer->insertItem('kas',$data);
			} else {
				$this->komputer->updateItem('kas',$data,array('id'=> $cek[0]['id']));
			}
			
			$this->session->set_flashdata('message','message','message','Transaksi Berhasil disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','message','message','Transaksi Gagal disimpan');
			redirect($this->agent->referrer());
		}	
	}
	
	public function delete_sub_transaksi($id,$id_serial){
		$delete = $this->komputer->deleteItem('sub_transaksi', array('id' => $id));
		if($delete){
			$data = array(
				'status' => 0,
			);
			$update = $this->komputer->updateItem('serial',$data,array('id' => $id_serial));
			$this->session->set_flashdata('message','message','message','Transaksi Berhasil disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','message','message','Transaksi Gagal disimpan');
			redirect($this->agent->referrer());
		}	
	}
	
	public function delete_transaksi($id,$url){
		$delete = $this->komputer->deleteItem('transaksi', array('id' => $id));
		if($delete){
			$delete = $this->komputer->deleteItem('kas', array('id_transaksi' => $id));
			$sub_transaksi = $this->komputer->cek($id,'id_transaksi','sub_transaksi');
			foreach($sub_transaksi as $st){
				$data = array(
					'status' => 0,
				);
				$update = $this->komputer->updateItem('serial',$data,array('id' => $st['id_serial']));
			}
			$delete = $this->komputer->deleteItem('sub_transaksi', array('id_transaksi' => $id));		
			$this->session->set_flashdata('message','message','message','Transaksi Berhasil disimpan');
			if($url == 'transaksi'){
				redirect($this->agent->referrer());
			} else {
				redirect('transaksi/transaksi');
			}	
			
		} else {
			$this->session->set_flashdata('message','message','message','Transaksi Gagal disimpan');
			redirect($this->agent->referrer());
		}	
	}
	
	public function daftar_penjualan()
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
			$tanggal = (int)$t[3];
			$seles = (int)$t[4];
		}  else {
			$cabang = '';
			$month = '';
			$year = '';
			$tanggal = '';
			$seles = '';
		}
		
		$this->db->select('*');
		$this->db->from('sub_transaksi');
		$this->db->join('transaksi','sub_transaksi.id_transaksi = transaksi.id');
		$this->db->where('sub_transaksi.id_serial !=',0);
		
		if(!$check_admin) {
			$this->db->where('cabang',$id_username);
		}
		
		if($check_admin && $cabang != 0){
			$this->db->where('cabang',$cabang);
		}
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		if(!empty($seles)) $this->db->where('id_user',$seles);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)', date('Y',$tanggal));
			$this->db->where('MONTH(waktu)', date('m',$tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		$data_result = $this->db->get()->num_rows();
		
		if(!empty($posisi)){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/transaksi/daftar_penjualan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/transaksi/daftar_penjualan/';
		}	
	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		$this->db->select('*');
		$this->db->join('transaksi','sub_transaksi.id_transaksi = transaksi.id');
		$this->db->where('sub_transaksi.id_serial !=',0);
		
		if(!$check_admin) {
			$this->db->where('cabang',$id_username);
		}
		
		if($check_admin && $cabang != 0){
			$this->db->where('cabang',$cabang);
		}
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		if(!empty($seles)) $this->db->where('id_user',$seles);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)', date('Y',$tanggal));
			$this->db->where('MONTH(waktu)', date('m',$tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		$query = $this->db->get('sub_transaksi',$config['per_page'],$from)->result_array();
	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_daftar_penjualan(){
		$cabang = $this->input->post('cabang');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		$seles = $this->input->post('seles');
		$tanggal = strtotime($this->input->post('tanggal'));
		redirect('transaksi/daftar_penjualan/'.$cabang.'^'.$month.'^'.$year.'^'.$tanggal.'^'.$seles);
	}
}
