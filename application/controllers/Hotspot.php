<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */
class Hotspot extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->config->load('routeros_conf');
		$this->load->helper(array('url','form','number','ros'));
		$this->load->model(array('routerOs'));
		$this->load->library(array('template','access','form_validation','session'));
		$data['routeros_conf']=$this->config->item('routeros_conf');
		$data['limituptime']=$this->config->item('limit-uptime');
		//data
		$data['sidebar']['parent']['hotspot']='active';
		$this->data=$data;
		if (!$this->access->is_login()) {
			redirect('');
		}
	}
	public function user($param1='list')
	{
		$data=$this->data;
		switch ($param1) {
			case 'list':
				$data['sidebar']['child']['user']='active';
				$data['hs_users']=$this->routerOs->hotspot_user_show();
			//	$data['debug']=$data['hs_users'];
				$data['hs_user_profiles']=$this->routerOs->hotspot_user_profile();
				$this->form_validation->set_rules('bulk_act','Bulk Action','required|alpha');
				if ($this->form_validation->run()) {
					$userIds=$this->input->post('user',true);
					foreach ($userIds as $idkey => $idval) {
						$this->routerOs->hotspot_user_delete($idval);
					}
					redirect('hotspot/user');
				}
				$this->template->display('hs_users',$data);
				break;
			case 'profile':
				$data['hs_user_profiles']=$this->routerOs->hotspot_user_profile();
				$data['validUntil']=$this->config->item('valid-until');
				$data['debug'][]=$data['hs_user_profiles'];
				$data['debug'][]=$data['validUntil'];
				$data['debug'][]=ros_uptime('6h');
				$this->template->display('hs_uprofile',$data);
				break;
		}
	}
}