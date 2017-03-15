<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	function do_upload()
	{	
		$fn = $this->input->post('fn');

		$config['upload_path'] = './uploads/';
		$config['file_name'] = $fn;
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = 'jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '5000';
		$config['max_height']  = '5000';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			print_r($error);
			exit;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			redirect ('/users/people');
		}
	}

	public function index()
	{	

		$li = $this->session->userdata('logged_in');
		if($li == TRUE){
			redirect ('/users/home');
		}
		else{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->load->view('users/index');
		}
		
	}

	public function index2()
	{	

		$li = $this->session->userdata('logged_in');
		if($li == TRUE){
			redirect ('/users/home');
		}
		else{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->load->view('users/index_2');
		}
		
	}
 
	public function Login()
	{
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			
		if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('users/index');
			}
		
		
		else
		{
			if ($this->input->post())
			{
				$this->load->model('Users_model');				
				$data = $this->input->post();
				$this->Users_model->login($data['username'], $data ['password']);
		
				$result=$this->Users_model->login($data['username'], $data ['password']);
				
				if(!$result) {
					redirect('/users/index2');
				}
				
				else {
					$newdata = array(
			        'logged_in' => TRUE,
			        'id_no' => $result['0']['id_users']
					);
					$this->session->set_userdata($newdata);
					redirect ('/users/home');
				}
				
				echo $result;
				exit();
			}
		}
		
	}
		

	public function home()
	{	
		$li = $this->session->userdata('logged_in');
		if($li == TRUE){
			$this->load->model('reqconfess_model'); // load model
			$this->load->model('active_model'); // load model
			$this->data['posts'] = $this->reqconfess_model->getPosts();
			$this->data['notifications'] = $this->reqconfess_model->getNoti();
			$this->data['comments'] = $this->reqconfess_model->getComments(); 
			$this->data['details'] = $this->active_model->getPosts();
			$this->data['details2'] = $this->active_model->getPosts2();
			$this->data['details3'] = $this->active_model->getPosts3();

			$this->load->view('welcome_message_2', $this->data);
		}
		else{
			redirect ('/users/');
		}

		
	}
	
	public function register()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->load->view('users/register');
	}
	
	public function register2()
	{	
		
		$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[20]|is_unique[users.username]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|max_length[20]|matches[cpassword]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|min_length[3]|max_length[20]');
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|min_length[3]|max_length[40]');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|min_length[3]|max_length[20]');
		$this->form_validation->set_rules('hidden_name', 'Hidden Name', 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('student_no', 'Student No', 'trim|required|min_length[3]|max_length[20]|is_unique[student_info.student_no]');
		$this->form_validation->set_rules('course', 'Course', 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('college', 'College', 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('middlename', 'Middle Name', 'trim|required|min_length[3]|max_length[30]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('contact_no', 'Contact No', 'trim|required|min_length[3]|max_length[20]');
		$this->form_validation->set_rules('birthdate', 'Birthdate', 'trim|required|min_length[3]|max_length[20]');
		$this->form_validation->set_rules('sex', 'Sex', 'trim|required|min_length[3]|max_length[20]');
				
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('users/register');
		}
		else
		{
			$this->load->model('Users_model');
			
			if($this->input->post()) {
				$data = $this->input->post();
				$result = $this->Users_model->add($data);
				redirect('users/');
		}
		}
		
	}
	
	public function reqcon(){
		$this->load->model('reqconfess_model');

			if($this->input->post()) {
				$data = $this->input->post();
				$result = $this->reqconfess_model->req($data);
				redirect('users/home');
		}
	}
	
	
	public function update_info(){
			$this->load->model('active_model');
			
			if($this->input->post()) {
				$data = $this->input->post();
				$result = $this->active_model->update($data);
				redirect('users/home');
			}
	}
	
	public function update_info2(){
			$this->load->model('active_model');
			
			if($this->input->post()) {
				$data = $this->input->post();
				$result = $this->active_model->update2($data);
				redirect('users/home');
			}
	}

	public function update_info3(){
			$this->load->model('active_model');
			
			if($this->input->post()) {
				$data = $this->input->post();
				$result = $this->active_model->update3($data);
				redirect('users/home');
			}
	}

	public function logout(){
		$this->session->unset_userdata('logged_in');

		$this->load->model('active_model');

		$data = array(
		'log' => 0
		);

		$result = $this->active_model->logout($data);
		redirect('users/');
		
	}

	public function feed(){
		
		redirect('users/home');	
		
	}

	public function people(){
		$li = $this->session->userdata('logged_in');
		if($li == TRUE){
			$this->load->database(); // load database
			$this->load->model('reqconfess_model'); // load model
			$this->load->model('Users_model'); // load model
			$this->load->model('active_model'); // load model
			$this->data['posts'] = $this->reqconfess_model->getPosts(); 
			$this->data['details'] = $this->active_model->getPosts();
			$this->data['details2'] = $this->active_model->getPosts2();
			$this->data['details3'] = $this->active_model->getPosts3();
			$this->data['users'] = $this->Users_model->getUsers();
		
			$this->load->view('users/people', $this->data);	
		}
		else{
			redirect('users/');
		}
	}

	public function peopleSearch(){
		$li = $this->session->userdata('logged_in');
		if($li == TRUE){
		
		$this->load->model('Users_model');
		$this->load->model('admin_model');
			
			if($this->input->post()) {
				$fn = $this->input->post('search');
				$result = $this->Users_model->getUsers2($fn);

				$this->load->model('reqconfess_model'); // load model
				$this->load->model('active_model'); // load model
				$this->data['posts'] = $this->reqconfess_model->getPosts();  
				$this->data['details'] = $this->active_model->getPosts();
				$this->data['details2'] = $this->active_model->getPosts2();
				$this->data['details3'] = $this->active_model->getPosts3();
				$this->data['users'] = $result;
			
				$this->load->view('users/people', $this->data);	
			}

		}
		else{
			redirect('users/');
		}
	}

	
	public function admin(){
		$li = $this->session->userdata('logged_in');
		if($li == TRUE){
			$this->load->model('reqconfess_model'); // load model
			$this->load->model('active_model'); // load model
			$this->load->model('suggestions_model'); // load model
			$this->load->model('admin_model'); // load model
			$this->data['posts'] = $this->reqconfess_model->getPosts2();
			$this->data['suggestions'] = $this->reqconfess_model->getSuggestions();
			$this->data['approved'] = $this->reqconfess_model->getApproved();
			$this->data['declined'] = $this->reqconfess_model->getDeclined();   
			$this->data['posts2'] = $this->reqconfess_model->getPosts3(); 
			$this->data['details'] = $this->active_model->getPosts();
			$this->data['details2'] = $this->active_model->getPosts2();
			$this->data['details3'] = $this->active_model->getPosts3();
			$this->data['suggestions'] = $this->suggestions_model->getSuggestions();
			$this->data['users'] = $this->admin_model->getUsers();
			$this->data['requests'] = $this->admin_model->getRequests();
			$this->data['appr'] = $this->admin_model->getApproved();
			$this->data['suggs'] = $this->admin_model->getSuggestions();
			$this->data['active'] = $this->admin_model->getActive();
			$this->data['admins'] = $this->admin_model->getAdmin();
		
			$this->load->view('users/admin', $this->data);	
		}
		else{
			redirect('users/');
		}
	}
	
	
	public function approveRequest(){
		
		$this->load->model('admin_model');
			
			if($this->input->post()) {
				$data = $this->input->post();
				$result = $this->admin_model->approve($data);
				redirect('users/admin');
			}
	}

	public function declineRequest(){
		

		$this->load->model('admin_model');
			if($this->input->post()) {
				$data = $this->input->post();
				$result = $this->admin_model->decline($data);
				redirect('users/admin');
			}
	}

	public function agree(){
		$this->load->model('confession_model');
		$a = $this->input->get('agr');
		$d = $this->input->get('disagr');
		if($d==0){
			if($a==0){
				if($this->input->get()){
				$idc = $this->input->get('idc');
				$idu = $this->input->get('idu');
				$rd = "users/home#agree".$idc;
				$result = $this->confession_model->agree($idc,$idu);
				redirect($rd);
				}
			}
			else if($a==1){
				$idc = $this->input->get('idc');
				$idu = $this->input->get('idu');
				$rd = "users/home#agree".$idc;
				$result = $this->confession_model->agree2($idc,$idu);
				redirect($rd);
			}
			
		}

		else if($d==1){
		
			if($this->input->get()){
			$idc = $this->input->get('idc');
			$idu = $this->input->get('idu');
			$rd = "users/home#agree".$idc;
			$result = $this->confession_model->agree2($idc,$idu);
			$result2 = $this->confession_model->agree($idc,$idu);
			redirect($rd);
			}
			
		}
			

	}

	public function disagree(){
		$this->load->model('confession_model');
		$a = $this->input->get('agr');
		$d = $this->input->get('disagr');

		if($a==0){
			if($d==0){
				if($this->input->get()){
				$idc = $this->input->get('idc');
				$idu = $this->input->get('idu');
				$rd = "users/home#agree".$idc;
				$result = $this->confession_model->disagree($idc,$idu);
				redirect($rd);				
				}
			}
			else if($d==1){
				if($this->input->get()){
				$idc = $this->input->get('idc');
				$idu = $this->input->get('idu');
				$rd = "users/home#agree".$idc;
				$result = $this->confession_model->agree2($idc,$idu);
				redirect($rd);
				}
			}
		}

		else if($a==1){
			if($this->input->get()){
				$idc = $this->input->get('idc');
				$idu = $this->input->get('idu');
				$rd = "users/home#agree".$idc;
				$result = $this->confession_model->agree2($idc,$idu);

				$result2 = $this->confession_model->disagree($idc,$idu);
				redirect($rd);
				}

		}
	}

	public function comment(){
		$this->load->model('reqconfess_model');
			
			if($this->input->post()) {
			$data = $this->input->post();	

			$result = $this->reqconfess_model->addComment($data);
			redirect('users/home');
		}
	}	

	public function about(){
		$li = $this->session->userdata('logged_in');
		if($li == TRUE){
			$this->load->model('reqconfess_model'); // load model
			$this->load->model('active_model'); // load model
			$this->data['posts'] = $this->reqconfess_model->getPosts2(); 
			$this->data['posts2'] = $this->reqconfess_model->getPosts3(); 
			$this->data['details'] = $this->active_model->getPosts();
			$this->data['details2'] = $this->active_model->getPosts2();
			$this->data['details3'] = $this->active_model->getPosts3();
		
			$this->load->view('users/about', $this->data);	
		}
		else{
			redirect('users/');
		}
	}
	
	public function contact_us(){
		$li = $this->session->userdata('logged_in');
		if($li == TRUE){
			$this->load->model('reqconfess_model'); // load model
			$this->load->model('active_model'); // load model
			$this->data['posts'] = $this->reqconfess_model->getPosts2(); 
			$this->data['posts2'] = $this->reqconfess_model->getPosts3(); 
			$this->data['details'] = $this->active_model->getPosts();
			$this->data['details2'] = $this->active_model->getPosts2();
			$this->data['details3'] = $this->active_model->getPosts3();
		
			$this->load->view('users/contact_us', $this->data);	
		}
		else{
			redirect('users/');
		}
	}

	public function suggestion(){
		$this->load->model('suggestions_model');
			
			if($this->input->post()) {
			$data = $this->input->post();	

			$result = $this->suggestions_model->addSuggestion($data);
			redirect('users/contact_us');
		}
	}	
}
