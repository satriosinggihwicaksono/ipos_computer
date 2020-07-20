<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('javascript');
		$this->load->library('pagination');
		$this->load->library('user_agent');
		$this->load->helper('url');
    	$this->load->library('form_validation');
	}
	
	public function index()
	{
		$this->load->view('login');
	}
	
	public function cabang()
	{
		$data_result = $this->db->get('cabang')->num_rows();
		$config['base_url'] = base_url().'index.php/auth/cabang/';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(3);
		$this->pagination->initialize($config);	
		$query = $this->db->get('cabang',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function pengguna()
	{
		$data_result = $this->db->get('user')->num_rows();
		$config['base_url'] = base_url().'index.php/auth/pengguna/';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(3);
		$this->pagination->initialize($config);	
		$query = $this->db->get('user',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_cabang(){
		$cabang = $this->input->post('cabang');
		$data = array(
			'nama' => $cabang,
		);
		$ins = $this->komputer->insertItem('cabang',$data);
		if($ins){
			$this->session->set_flashdata('message','Cabang Berhasil Ditambah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Cabang Tidak bisa Ditambah');
			redirect($this->agent->referrer());
		}
	
	}
	
	public function ubah_cabang($id){
		$cabang = $this->input->post('cabang');
		$data = array(
			'nama' => $cabang,
		);
		$ins = $this->komputer->updateItem('cabang',$data,array('id'=>$id));
		if($ins){
			$this->session->set_flashdata('message','Cabang Berhasil Dirubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','Cabang Tidak bisa Dirubah');
			redirect($this->agent->referrer());
		}
	
	}
	
	public function sigup()
	{
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');

		if($this->form_validation->run() === FALSE ){
			$this->load->view('login');
		} else {
			$username = $this->input->post('username');
			$check = $this->komputer->check('user','username',$username);
			$this->db->where('username',$username);
			$get_status = $this->db->get('user')->result_array();
			$get_status = $get_status[0];
			if($check->num_rows() === 1 && (int)$get_status['status'] === 1){
				$password = $this->input->post('password');
				$verify = password_verify($password, $check->row()->password);
			}	
			if($verify){
				$session['username'] = $check->row()->username;
				$session['logged_in'] = TRUE;
				$this->session->set_userdata($session);
				redirect('persedian_page/daftar_item');	
			} else if($get_status != NULL && $get_status['status'] != 1) {
				$this->session->set_flashdata('message','message','<b>Your account is banned');
				redirect('auth'); 
			} else if($check->num_rows() === 1 && !$verify) {
				$this->session->set_flashdata('message','message','<b>Your password wrong');
				redirect('auth');
			} else if($check->num_rows() != 1) {
				$this->session->set_flashdata('message','message','<b>Your account is not registered');
				redirect('auth'); 		
			} else {
				$this->session->set_flashdata('message','message','<b>Your account is not registered');
				redirect('auth'); 
			}
		}
	}
	
	public function update_password_pengguna($id)
	{	
		$username = $this->input->post('username');
		$check = $this->komputer->check('user','username',$username);
		$password = $this->input->post('old_password');
		$this->form_validation->set_rules('new_password','New password','required|alpha_numeric');
		$this->form_validation->set_rules('repassword','Repassword','required|matches[new_password]');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','message','password tidak sesuai' );
			redirect($this->agent->referrer());
		} else {
			$verify = password_verify($password, $check->row()->password);
			if($verify){
				$this->komputer->save($id);
				$this->session->set_flashdata('message','message','Password berhasil dirubah' );
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('message','message','Password lama tidak sesuai' );
				redirect($this->agent->referrer());
			}
		}
	}
	
	public function registration()
	{
		$this->form_validation->set_rules('username','Username','required|is_unique[user.username]');
		$this->form_validation->set_rules('name','Name','required|is_unique[user.name]');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('repassword','Repassword','required|matches[password]');
		$hakakses = $this->input->post('hakakses');
		if($this->form_validation->run() === FALSE ){
			$this->load->view('login');
		} else {	
			$data = array(
				'username' => $this->input->post('username'),
				'name' => $this->input->post('name'),
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			);
			$ins = $this->komputer->insertItem('user',$data);
			redirect($this->agent->referrer());
		}
	}
	
	public function update_pengguna($id){	
		$status = $this->input->post('status');
		$cabang = $this->input->post('cabang');
		$hakakses = $this->input->post('hakakses');
		
		$data = array(
			'status' => $status,
			'cabang' => $cabang,
			'hakakses' => $hakakses,
		);
		$ins = $this->komputer->updateItem('user',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','message','<b>Notification</b> user telah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','message','<b>Notification</b> user tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function logout(){
		$session = $this->session->userdata('username');
		$this->session->sess_destroy();
		redirect('auth/sigup');
	}
}

