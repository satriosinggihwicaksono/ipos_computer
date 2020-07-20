<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persedian_page extends CI_Controller {
	
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
		$this->load->view('index',$data);
	}
	
	public function daftar_item()
	{	
		$query = $this->db->get('item')->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function setting_item()
	{	
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode('%5E',$link);
			$nama = $t[0];
			$nama = str_replace("%20"," ",$nama);
			$merek = $t[1];
			$merek = str_replace("%20"," ",$merek);
			$kategori = $t[2];
		} 
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($kategori)) $this->db->where('id_kategori',$kategori);
		
		$data_result = $this->db->get('item')->num_rows();
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/persedian_page/setting_item/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/persedian_page/setting_item/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($kategori)) $this->db->where('id_kategori',$kategori);
		
		$query = $this->db->get('item',$config['per_page'],$from)->result_array();	
		
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_setting_item()
	{	
		$nama = $this->input->post('nama');
		$kategori = $this->input->post('kategori');
		$merek = $this->input->post('merek');
		$url = $nama.'^'.$merek.'^'.$kategori;
		redirect('persedian_page/setting_item/'.$url);
	}
	
	public function tambah_item(){
		$data = array(
			'kode' => $this->input->post('kode'),
			'nama' => $this->input->post('nama'),
			'merek' => $this->input->post('merek'),
			'tipe' => $this->input->post('tipe'),
			'serial' => (int)$this->input->post('serial'),
			'warna' => $this->input->post('warna'),
			'id_kategori' => $this->input->post('kategori'),
		);
		$ins = $this->komputer->insertItem('item',$data);
		if($ins){
			$id_item = $this->db->insert_id();
			$data = array(
				'id_item' => $id_item,
				'harga_pokok' => 0,
				'harga_jual' => $this->input->post('harga_jual'),
			);
			$this->komputer->insertItem('harga',$data);
		}	
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Item');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Item');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_item($id){
		$data = array(
			'kode' => $this->input->post('kode'),
			'nama' => $this->input->post('nama'),
			'merek' => $this->input->post('merek'),
			'tipe' => $this->input->post('tipe'),
			'warna' => $this->input->post('warna'),
			'serial' => $this->input->post('serial'),
			'id_kategori' => $this->input->post('kategori'),
		);
		$ins = $this->komputer->updateItem('item',$data,array('id' => $id));
		if($ins){
			$data = array(
				'id_item' => $id,
				'harga_pokok' => $this->input->post('harga_pokok'),
				'harga_jual' => $this->input->post('harga_jual'),
			);
			$ins = $this->komputer->updateItem('harga',$data,array('id_item' => $id));
		}
		
		if($ins){
			$this->session->set_flashdata('message','Sukses Merubah Item');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Merubah Item');
			redirect($this->agent->referrer());
		}
	}
	
	public function harga($id){
		$harga_pokok = $this->input->post('harga_pokok');
		$harga_jual = $this->input->post('harga_jual');
		$cek = $this->komputer->cek($id,'id_item','harga');
		$data = array(
			'id_item' => $id,
		);
		if(!empty($harga_pokok)){
			$data['harga_pokok'] = $harga_pokok;
		}
		
		if(!empty($harga_jual)){
			$data['harga_jual'] = $harga_jual;
		}
		
		if(empty($cek)){
			$ins = $this->komputer->insertItem('harga',$data);
		} else {
			$ins = $this->komputer->updateItem('harga',$data,array('id_item' => $id));
		}	
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Item');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Item');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_itensif($id){
		$ins_b = $this->input->post('ins_b');
		$ins_s = $this->input->post('ins_s');
		$cek = $this->komputer->cek($id,'id_item','itensif');
		$data = array(
			'id_item' => $id,
		);
		if(!empty($ins_b)){
			$data['ins_b'] = $ins_b;
		}
		
		if(!empty($ins_s)){
			$data['ins_s'] = $ins_s;
		}
		
		if(empty($cek)){
			$ins = $this->komputer->insertItem('itensif',$data);
		} else {
			$ins = $this->komputer->updateItem('itensif',$data,array('id_item' => $id));
		}	
		if($ins){
			$this->session->set_flashdata('message','Berhasil Menambah Itensif');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Itensif');
			redirect($this->agent->referrer());
		}
	}

	public function kategori()
	{	
		$data_result = $this->db->get('kategori')->num_rows();
		$config['base_url'] = base_url().'index.php/persedian_page/kategori/';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(3);
		$this->pagination->initialize($config);	
		$query = $this->db->get('kategori',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}

	public function tambah_kategori(){
		$kategori = $this->input->post('kategori');
		if(empty($kategori)){
			$this->session->set_flashdata('message','Kategori tidak ada');
			redirect($this->agent->referrer());
		}	
			$data = array(
				'kategori' => $kategori,
			);
		$ins = $this->komputer->insertItem('kategori',$data);
		if($ins){
			$this->session->set_flashdata('message','Sukses Menambah Kategori');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Menambah Kategori');
			redirect($this->agent->referrer());
		}
	}
	
	public function delete_item($tabel,$id){
		$data = array(
			'id' => $id
		);
		$delete = $this->komputer->deleteItem($tabel,$data);
		if($delete){
			$this->session->set_flashdata('message','Sukses Menghapus Kategori');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Meghapus Kategori');
			redirect($this->agent->referrer());
		}
	}
	
	public function delete_barang($tabel,$id){
		$data = array(
			'id' => $id
		);
		$delete = $this->komputer->deleteItem($tabel,$data);
		$delete = $this->komputer->deleteItem('harga',array('id_item' => $id));
		if($delete){
			$this->session->set_flashdata('message','Sukses Menghapus Kategori');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Meghapus Kategori');
			redirect($this->agent->referrer());
		}
	}
	
	public function update_kategori($id){
		
		$data = array(
			'kategori' => $this->input->post('kategori'),
		);
		$update = $this->komputer->updateItem('kategori',$data,array('id'=> $id));
		if($update){
			$this->session->set_flashdata('message','Sukses Menghapus Kategori');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Gagal Meghapus Kategori');
			redirect($this->agent->referrer());
		}
	}
	
	public function pencarian_sn(){
		$this->load->view('index');
	}
	
	public function search_pencarian_sn(){
		$serial = $this->input->post('serial');
		redirect('persedian_page/pencarian_sn/'.$serial.'^');
	}
	
	public function barang_sn()
	{	
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode('%5E',$link);
			$nama = $t[0];
			$nama = str_replace("%20"," ",$nama);
			$merek = $t[1];
			$merek = str_replace("%20"," ",$merek);
			$id_kategori = $t[2];
			$cabang = $t[3];
			$kondisi = $t[4];
			$id_supplier = $t[5];
		} else {
			$nama = '';
			$merek = '';
			$id_kategori = '';
			$cabang = '';
			$kondisi = 3;
			$id_supplier = '';
		}
		
		$this->db->select('item.nama,item.tipe,item.warna,serial.serial,serial.kondisi,serial.cabang,serial.status,pembelian.id_supplier');
		$this->db->join('item','serial.id_item = item.id');
		$this->db->join('pembelian','serial.id_pembelian = pembelian.id');
		$this->db->where('serial.status',0);
		
		if(!empty($id_supplier)) $this->db->where('pembelian.id_supplier',$id_supplier);
		if(!empty($nama))$this->db->like('item.nama',$nama);
		if(!empty($merek)) $this->db->like('item.merek',$merek);
		if(!empty($id_kategori)) $this->db->where('item.id_kategori',$id_kategori);
		if(!empty($cabang)) $this->db->where('serial.cabang',$cabang);
		if($kondisi != 3)$this->db->where('serial.kondisi',$kondisi);
		$data_result = $this->db->get('serial')->num_rows();	
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/persedian_page/barang_sn/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/persedian_page/barang_sn/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 50;
		$this->pagination->initialize($config);
		
		$this->db->select('item.nama,item.tipe,item.warna,serial.serial,serial.kondisi,serial.cabang,serial.status');
		$this->db->join('item','serial.id_item = item.id');
		$this->db->join('pembelian','serial.id_pembelian = pembelian.id');
		$this->db->where('.serial.status',0);
		
		if(!empty($id_supplier)) $this->db->where('pembelian.id_supplier',$id_supplier);
		if(!empty($nama))$this->db->like('item.nama',$nama);
		if(!empty($merek)) $this->db->like('item.merek',$merek);
		if(!empty($id_kategori)) $this->db->where('item.id_kategori',$id_kategori);
		if(!empty($cabang)) $this->db->where('serial.cabang',$cabang);
		if($kondisi != 3)$this->db->where('serial.kondisi',$kondisi);
		$query = $this->db->get('serial',$config['per_page'],$from)->result_array();
		
		$url = $nama.'^'.$merek.'^'.$id_kategori.'^'.$cabang.'^'.$kondisi;
		
		$data['data'] = $query;
		$data['nama'] = $nama;
		$data['merek'] = $merek;
		$data['id_kategori'] = $id_kategori;
		$data['posisi'] = $posisi;
		$data['kondisi'] = $kondisi;
		$data['cabang'] = $cabang;
		$data['link'] = $link;
		$data['url'] = $url;
		$data['id_supplier'] = $id_supplier;
		$this->load->view('index',$data);
	}
	
	public function search_barang_sn()
	{	
		$nama = $this->input->post('nama');
		$kategori = $this->input->post('kategori');
		$merek = $this->input->post('merek');
		$kondisi = $this->input->post('kondisi');
		$cabang = $this->input->post('cabang');
		$supplier = $this->input->post('supplier');
		$url = $nama.'^'.$merek.'^'.$kategori.'^'.$cabang.'^'.$kondisi.'^'.$supplier;
		redirect('persedian_page/barang_sn/'.$url);
	}
}

