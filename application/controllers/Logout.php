<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */
class Logout extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->config->load('routeros_conf');
		$this->load->helper(array('url'));
		$this->load->model(array('routerOs'));
		$this->load->library(array('template','access','session'));
		if (!$this->access->is_login()) {
			redirect('');
		}
	}
	public function index()
	{
		@$this->access->logout();
		redirect('');
	}
	
}