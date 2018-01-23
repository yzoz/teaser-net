<?php

class Go extends Controller {

	function Go()
	{
		parent::Controller();
	}
	
	function index()
	{
		$this->load->view('view_main');
	}
	
	function out($blog_id, $post_id)
	{
		$this->load->model('Counter');
		$this->Counter->blog_out($blog_id);
		$this->Counter->post_out($post_id);
		$data['post_url'] = $this->Get->post_url($post_id);
		$this->load->view('go', $data);
	}
	
	function to($blog_id, $post_id)
	{
		$this->load->model('Counter');
		$this->Counter->blog_out($blog_id);
		$data['post_url'] = $this->Get->post_url($post_id);
		$this->load->view('go', $data);
	}
}

?>