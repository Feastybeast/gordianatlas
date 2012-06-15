<?php
class User extends CI_Controller 
{
	public function view()
	{
		$this->load->model('UserModel');
		$page_data['user'] = $this->UserModel->read(1);
		$page_data['this'] = $this;
		
		$this->load->view('user/view', $page_data);
	}
}
?>