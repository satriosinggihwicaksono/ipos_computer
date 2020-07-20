<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {
	
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
	
	public function service(){
		
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
			$cabang = $t[2];
			$month = $t[3];
			$year = $t[4];
			$teknisi = $t[5];
		} 
		
		if(!empty($cabang)) $this->db->where('cabang',$cabang);
		if(!empty($nota)) $this->db->where('nota',$nota);
		if(!empty($teknisi)) $this->db->where('teknisi',$teknisi);
		$data_result = $this->db->get('service')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/service/service/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/service/service/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		$this->db->order_by('id','desc');
		if(!empty($cabang)) $this->db->where('cabang',$cabang);
		if(!empty($nota)) $this->db->where('nota',$nota);
		if(!empty($teknisi)) $this->db->where('teknisi',$teknisi);
		$query = $this->db->get('service',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function delete_service($id,$url){
		$this->db->where('id_service',$id);
		$sub_service = $this->db->get('sub_service')->result_array();
		foreach($sub_service as $s){
			$id_serial = $s['id_serial'];
			$id_sub_service= $s['id'];
			if(!empty($id_serial)){
				$data_serial = array(
					'status' => 0,
				);
				$update = $this->komputer->updateItem('serial',$data_serial,array('id'=> $id_serial));
			}
			
			$data = array(
				'id' => $id_sub_service,
			);
			
			$delete = $this->komputer->deleteItem('sub_service',$data);
		}
		$delete = $this->komputer->deleteItem('service',array('id' => $id));
		
		if($delete){
			if(!empty($id_serial)){
				$data = array(
					'status' => 0,
				);
				$update = $this->komputer->updateItem('serial',$data,array('id' => $id_serial));
			}
			$this->session->set_flashdata('message','Service Berhasil dihapus');
			if($url == 'detail_service'){
				redirect('service/service/^^^'.date('m').'^'.date('Y'));
			} else {	
				redirect($this->agent->referrer());
			}	
		} else {
			$this->session->set_flashdata('message','Service Gagal disimpan');
			redirect($this->agent->referrer());
		}
		
	}
	
	public function search_service(){
		$nota = $this->input->post('nota');
		$tanggal = strtotime($this->input->post('tanggal'));
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$teknisi = $this->input->post('teknisi');
		redirect('service/service/'.$nota.'^'.$tanggal.'^'.$cabang.'^'.$month.'^'.$year.'^'.$teknisi);
	}
	
	public function tambah_service(){
		$nama = $this->input->post('nama');
		$telepon = $this->input->post('telepon');
		$keterangan = $this->input->post('keterangan');
		$deskripsi = $nama.','.$telepon.','.$keterangan;
		$data = array(
			'nota' => $this->input->post('nota'),
			'deskripsi' => $deskripsi,
			'waktu' => $this->input->post('waktu'),
			'cabang' => $this->input->post('cabang'),
			'teknisi' => $this->input->post('teknisi'),
			'status' => $this->input->post('status'),
		);
		
		$ins = $this->komputer->insertItem('service',$data);
		if($ins){
			$this->session->set_flashdata('message','Service telah ditambah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Service gagal telah ditambah');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_service($id){
		$nama = $this->input->post('nama');
		$telepon = $this->input->post('telepon');
		$keterangan = $this->input->post('keterangan');
		$deskripsi = $nama.','.$telepon.','.$keterangan;
		$data = array(
			'nota' => $this->input->post('nota'),
			'deskripsi' => $deskripsi,
			'waktu' => $this->input->post('waktu'),
			'cabang' => $this->input->post('cabang'),
			'teknisi' => $this->input->post('teknisi'),
		);
		
		$cek = $this->komputer->cek($id,'id_service','kas');

		$data_kas = array(
			'waktu' => date('Y-m-d'),
			'id_cabang' => $this->input->post('cabang'),
			'status' => 1,
			'deskripsi' => 'SERVICE '.$this->input->post('nota'),
			'id_service' => $id,
		); 
			
		if($cek){
			$this->komputer->updateItem('kas',$data_kas,array('id'=> $id));
		}
		
		$ins = $this->komputer->updateItem('service',$data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Service telah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Service gagal telah diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function detail_service($id){
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
		
		$this->db->where('id',$id);
		$service = $this->db->get('service')->result_array();
		$this->db->where('id_service',$id);
		$query = $this->db->get('sub_service')->result_array();
		$data['service'] = $service;
		$data['id_cabang'] = $service[0]['cabang'];
		$data['data'] = $query;
		$data['search'] = $search;
		$data['kongsi'] = $kongsi;
		$this->load->view('index',$data);
	}
	
	public function search_detail_service(){
		$id = $this->input->post('id');
		$search = $this->input->post('search');
		$kongsi = $this->input->post('kongsi');
		redirect('/service/detail_service/'.$id.'^'.$search.'^'.$kongsi);
	}
	
	public function add_service($id){
		$service = $this->input->post('service');
		$biaya = $this->input->post('biaya');
		$data = array(
			'id_service' => $id,
			'service' => $service,
			'harga' => $biaya,
			'unit' => 1,
			'id_item' => 0,
			'id_serial' => 0,
		);
		$ins = $this->komputer->insertItem('sub_service',$data);
		if($ins){
			$this->session->set_flashdata('message','Service telah ditambah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Service tidak bisa ditambah');
			redirect($this->agent->referer());
		}
	}
	
	public function add_sub_service($id,$id_item,$id_serial,$harga){
		$data = array(
			'id_service' => $id,
			'harga' => $harga,
			'unit' => 1,
			'id_item' => $id_item,
			'id_serial' => $id_serial,
		);
		$ins = $this->komputer->insertItem('sub_service',$data);
		if($ins){
			$this->session->set_flashdata('message','Service telah ditambah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Service tidak bisa ditambah');
			redirect($this->agent->referer());
		}
	}
	
	public function delete_sub_service($id,$id_serial){
		$delete = $this->komputer->deleteItem('sub_service', array('id' => $id));
		if($delete){
			$data = array(
				'status' => 0,
			);
			$update = $this->komputer->updateItem('serial',$data,array('id' => $id_serial));
			$this->session->set_flashdata('message','Transaksi Berhasil disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Transaksi Gagal disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_sub_service($id){
		$harga = $this->input->post('harga');
		$harga = str_replace('.','',$harga);
		$data = array(
			'unit' => $this->input->post('unit'),
			'harga' => $harga,
		);
		
		$ins = $this->komputer->updateItem('sub_service',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','Data Berhasil dirubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Data Gagal dirubah');
			redirect($this->agent->referrer());
		}	
	}
	
	public function service_selesai($id,$total){
		$data = array(
			'status' => 1,
			'waktu_con' => date('Y-m-d'),
		);
		
		$ins = $this->komputer->updateItem('service',$data,array('id' => $id));
		if($ins){
			$this->db->where('id_service', $id);
			$item_penjualan = $this->db->get('sub_service')->result_array();
			foreach($item_penjualan as $ip){
				if($ip['id_item'] != 0 && !empty($ip['id_serial'])){
					$send = array(
						'status' => 1,
					);
					$ins = $this->komputer->updateItem('serial',$send, array('id' => $ip['id_serial']));
				}
			}
			
			$cek = $this->komputer->cek($id,'id_service','kas');
			$service = $this->komputer->cek($id,'id','service');
			
			$data = array(
				'waktu' => date('Y-m-d'),
				'id_cabang' => $service[0]['cabang'],
				'status' => 1,
				'deskripsi' => 'SERVICE '.$service[0]['nota'],
				'saldo' => $total,
				'id_service' => $id,
			); 
			
			if(empty($cek)){
				$this->komputer->insertItem('kas',$data);
			} else {
				$this->komputer->updateItem('kas',$data,array('id'=> $cek[0]['id']));
			}
			
			$this->session->set_flashdata('message','Service Selesai');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Service Gagal');
			redirect($this->agent->referrer());
		}	
	}
	
	public function laporan_service(){
		$query = $this->db->get('service')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
}
