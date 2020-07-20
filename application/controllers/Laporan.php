<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

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
	
	public function penjualan()
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
		
		if(!$check_admin) {
			$this->db->where('cabang',$id_username);
		}
		
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		if(!empty($seles)) $this->db->where('id_user',$seles);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)', date('Y',$tanggal));
			$this->db->where('MONTH(waktu)', date('m',$tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		$this->db->where('status',1);
		$data_result = $this->db->get('transaksi')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/laporan/penjualan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/laporan/penjualan/';
		}
		
		if(!$check_admin) {
			$this->db->where('cabang',$id_username);
		}
		
		if($check_admin && $cabang != 0){
			$this->db->where('cabang',$cabang);
		}
		
		$config['total_rows'] = $data_result;
		$config['per_page'] = 0;
		$this->pagination->initialize($config);
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		if(!empty($seles)) $this->db->where('id_user',$seles);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)', date('Y',$tanggal));
			$this->db->where('MONTH(waktu)', date('m',$tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		$this->db->where('status',1);
		$query = $this->db->get('transaksi',$config['per_page'],$from)->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_penjualan(){
		$cabang = $this->input->post('cabang');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		$seles = $this->input->post('seles');
		$tanggal = strtotime($this->input->post('tanggal'));
		redirect('laporan/penjualan/'.$cabang.'^'.$month.'^'.$year.'^'.$tanggal.'^'.$seles);
	}
	
	public function history_penjualan(){
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		
		if($posisi > 0){
			$t = explode('%5E',$link);
			$nama = $t[4];
			$nama = str_replace("%20"," ",$nama);
			$id_kategori = $t[5];
		}
		
		if(!empty($id_kategori)) $this->db->where('id_kategori',$id_kategori);
		if(!empty($nama)) $this->db->like('nama',$nama);
		$data_result = $this->db->get('item')->num_rows();
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/laporan/history_penjualan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/laporan/history_penjualan/';
		}
		
		$config['total_rows'] = $data_result;
		$config['per_page'] = 40;
		$this->pagination->initialize($config);
		
		if(!empty($id_kategori)) $this->db->where('id_kategori',$id_kategori);
		if(!empty($nama)) $this->db->like('nama',$nama);
		$query = $this->db->get('item',$config['per_page'],$from)->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function history_penjualan_item(){
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode('%5E',$link);
			$id_item = $t[0];
			$month = $t[1];
			$year = $t[2];
			$cabang = $t[3];
			$seles = $t[4];
		}
		
		$this->db->select('*');
		$this->db->from('sub_transaksi');
		$this->db->join('transaksi','transaksi.id = sub_transaksi.id_transaksi');
		$this->db->where('MONTH(transaksi.waktu)', $month);
		$this->db->where('YEAR(transaksi.waktu)',$year);
		$this->db->where('sub_transaksi.id_item',$id_item);	
		if(!empty($seles)) $this->db->where('transaksi.id_user',$seles);	
		if(!empty($cabang)) $this->db->where('transaksi.cabang',$cabang);	
		$data_result = $this->db->get()->num_rows();
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/laporan/history_penjualan_item/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/laporan/history_penjualan_item/';
		}
		
		$config['total_rows'] = $data_result;
		$config['per_page'] = 20;
		$this->pagination->initialize($config);
		
		$this->db->select('*');
		$this->db->join('transaksi','transaksi.id = sub_transaksi.id_transaksi');
		$this->db->where('MONTH(transaksi.waktu)', $month);
		$this->db->where('YEAR(transaksi.waktu)',$year);
		$this->db->where('sub_transaksi.id_item',$id_item);	
		if(!empty($seles)) $this->db->where('transaksi.id_user',$seles);	
		if(!empty($cabang)) $this->db->where('transaksi.cabang',$cabang);
		$query = $this->db->get('sub_transaksi',$config['per_page'],$from)->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_history_penjualan(){
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$seles = $this->input->post('seles');
		$nama = $this->input->post('nama');
		$kategori = $this->input->post('kategori');
		redirect('laporan/history_penjualan/'.$cabang.'^'.$month.'^'.$year.'^'.$seles.'^'.$nama.'^'.$kategori);
	}

	public function stok_opname()
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
			$users = (int)$t[4];
		}  else {
			$cabang = '';
			$month = '';
			$year = '';
			$tanggal = '';
			$users = '';
		}
		
		if(!$check_admin) {
			$this->db->where('id_cabang',$id_username);
		}
		
		if(!$check_admin) {
			$this->db->where('users',$users);
		}
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)', date('Y',$tanggal));
			$this->db->where('MONTH(waktu)', date('m',$tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		$data_result = $this->db->get('stok_opname')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/laporan/penjualan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/laporan/penjualan/';
		}
		
		if(!$check_admin) {
			$this->db->where('id_cabang',$id_username);
		}
		
		if($check_admin && $cabang != 0){
			$this->db->where('id_cabang',$cabang);
		}
		
		if(!$check_admin) {
			$this->db->where('users',$users);
		}

		$config['total_rows'] = $data_result;
		$config['per_page'] = 0;
		$this->pagination->initialize($config);
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)', date('Y',$tanggal));
			$this->db->where('MONTH(waktu)', date('m',$tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		$query = $this->db->get('stok_opname',$config['per_page'],$from)->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}

	public function tambah_stok_opname(){
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'ket' => $this->input->post('ket'),
			'id_cabang' => $this->input->post('cabang'),
			'users' => '',
		);
		$ins = $this->komputer->insertItem('stok_opname',$data);
		
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Item');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Item');
			redirect($this->agent->referrer());
		}
	}

	public function delete_stok_opname($id){
		$data = array(
			'id' => $id
		);
		$delete = $this->komputer->deleteItem('stok_opname',$data);
		if($delete){
			$this->session->set_flashdata('message','Sukses Menghapus Stok Opname');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Meghapus Stok Opname');
			redirect($this->agent->referrer());
		}
	}

	public function detail_stok_opname($id){
		$this->db->where('serial',0);
		$item = $this->db->get('item')->result_array();

		$this->db->where('id',$id);
		$query = $this->db->get('stok_opname')->result_array();
		
		$data['data'] = $query;
		$data['item'] = $item;
		$this->load->view('index',$data);
	}
	
	public function add_stok_opname($id){
		$this->db->where('serial',0);
		$item = $this->db->get('item')->result_array();
		foreach($item as $is){
			$name = 'stok_'.$is['id'];
			$stok = $this->input->post($name);
			
			$data = array(
				'id_item' => $is['id'],
				'id_stok_opname' => $id,
				'stok' => $stok,
			);

			$this->db->where('id_stok_opname',$id);
			$this->db->where('id_item',$is['id']);
			$cek = $this->db->get('detail_stok_opname')->result();

			if(empty($cek)){
				$ins = $this->komputer->insertItem('detail_stok_opname',$data);
			} else {
				$ins = $this->komputer->updateItem('detail_stok_opname',$data,array('id' => $cek[0]->id));
			}
		}
		
		if($ins){
			$this->session->set_flashdata('message','Sukses Merubah Item');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Merubah Item');
			redirect($this->agent->referrer());
		}
	}
}
