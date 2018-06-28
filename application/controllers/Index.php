<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */
class Index extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->config->load('routeros_conf');
		$this->load->helper(array('url','form'));
		$this->load->model(array('routerOs'));
		$this->load->library(array('template','access','form_validation','session'));
		$this->router_conf=$this->config->item('routeros_conf');
		if ($this->access->is_login()) {
			redirect('dashboard');
		}
	}
	public function index()
	{
		$data=null;
		$data['ipaddress']=$this->router_conf['address'];
		$data['port']=$this->router_conf['port'];
		$data['username']=$this->router_conf['username'];
		$data['password']=$this->router_conf['password'];
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('ip','IP Address','required');
		$this->form_validation->set_rules('port','Port Number','required|is_natural');
		if ($this->form_validation->run()) {
			$post=$this->input->post(null,true);
			$this->access->login($post['username'],$post['password'],$post['ip'],$post['port']);
			redirect('dashboard');
		}
		$this->template->display('login',$data);
	}
}