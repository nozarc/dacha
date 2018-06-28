<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*  
*/
class Access
{
	
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('session');
		$this->ci->load->model('routerOs');
		$this->login=&$this->ci->login;
	}
	public function login($username,$password,$ip,$port=null)
	{
		$result=$this->ci->routerOs->connect($username,$password,$ip,$port);
		if ($result) {
			$data=	array(
							'sess_username'		=>$username,
							'sess_remote_ip'	=>$this->ci->routerOs->remote_addr()
						);
			$this->ci->session->set_userdata($data);
			return true;
		}
		return false;
	}
	public function is_login()
	{
		return $this->ci->session->has_userdata('sess_username');
	}
	public function logout()
	{
	//	$this->routerOs->disconnect();
		ini_set('session.gc_max_lifetime', 0);
		ini_set('session.gc_probability', 1);
		ini_set('session.gc_divisor', 1);
		return session_destroy();
	}
}
