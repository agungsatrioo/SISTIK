<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_login extends CI_Controller {

	public function __construct(){
	  parent:: __construct();
	  $this->load->model('M_login');
  	}

	public function index(){
		if(null!==($this->session->userdata('ses_username'))){
			$url = base_url('C_crud');
			redirect($url);
		}else{
            $values['page_title'] = "Login";
            $values['view'] = "v_login";
            $this->load->view('index',$values);
		}
	}

	public function auth(){
		$username	= htmlspecialchars($this->input->post('username',TRUE),ENT_QUOTES);
		$password	= htmlspecialchars($this->input->post('password',TRUE),ENT_QUOTES);
		$cek 		= $this->M_login->auth($username, $password);
		$level		= $this->M_login->level($username);

		if($cek->num_rows() > 0){
			$data=$cek->row_array();
			$this->session->set_userdata('masuk',TRUE);
			$this->session->set_userdata('ses_username',$data['username']);
			if($level == 1){
				$this->session->set_userdata('akses','1');
				redirect(base_url());
			}
        }else{
			$this->session->set_flashdata('error', 'Username atau password salah!');
            redirect(base_url("login"));
		}
	}

	public function create(){
		$username	= htmlspecialchars($this->input->post('username',TRUE),ENT_QUOTES);
		$password	= htmlspecialchars($this->input->post('password',TRUE),ENT_QUOTES);
		$level		= $_POST['level'];
		$password	= md5($password);

		$data = array(
			'username'	=> 	$username,
			'password'	=>	$password,
			'level'		=>	$level,
		);

		$this->db->insert('user', $data);
		redirect();
	}

	public function logout(){
		$this->session->sess_destroy();
        $url=base_url('');
        redirect($url);
	}
}
