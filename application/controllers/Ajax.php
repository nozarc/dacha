<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */
class Ajax extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->config->load('routeros_conf');
		$this->load->helper(array('url','form','number','ros'));
		$this->load->model(array('routerOs'));
		$this->load->library(array('template','access','form_validation'));
		$this->router_conf['routeros_conf']=$this->config->item('routeros_conf');
		//hs user stuff
		$res['hotspot']['uprofile']=$this->routerOs->hotspot_user_profile();
		$res['hotspot']['limituptime']=$this->config->item('limit-uptime');
		//hs user profile stuff
		foreach ($this->config->item('valid-until') as $validkey => $validval) {
			$validUntil[ros_limit($validkey)]=$validval;
		}
		$res['validUntil']=$validUntil;
		//./hs user profile stuff
		$res['debug']=$validUntil;
		$res['message']='success';
		$this->res=$res;
		if (!$this->access->is_login()) {
			redirect('');
		}
	}
	//hs user stuff
	public function addUser()
	{
		$result=$this->res;
		$this->form_validation->set_rules('name','Username','required');
		$this->form_validation->set_rules('password','Password','required');
		if ($this->form_validation->run()) {
			$input=$this->input->post(null,true);
			$input['limit-uptime']=ros_limit($input['limit-uptime']);
			$result['result']=$this->routerOs->hotspot_user_add($input);
			if (!empty($result['result']['limit-uptime'])) {
				$result['result']['limituptime']=ros_uptime($result['result']['limit-uptime']);
			}
			$result['result']['uptime']=ros_uptime($result['result']['uptime']);
		}
		else{
			$result['message']='error -> '.validation_errors();
		}
		echo  json_encode($result);
	}
	public function editUser()
	{
		$result=$this->res;
		$this->form_validation->set_rules('name','Username','required');
		if ($this->form_validation->run()) {
			$input=$this->input->post(null,true);
			$input['limit-uptime']=ros_limit($input['limit-uptime']);
			$result['result']=$this->routerOs->hotspot_user_edit($input);
			if (!empty($result['result']['limit-uptime'])) {
				$result['result']['limituptime']=ros_uptime($result['result']['limit-uptime']);
			}
			$result['result']['uptime']=ros_uptime($result['result']['uptime']);
			$result['debug']=$input;
		}
		else{
			$result['message']='error -> ';
			$result['err_message']=validation_errors();
		}
		echo  json_encode($result);
	}
	public function deleteUser()
	{
		$result=$this->res;
		$id=$this->input->post('id',true);
		$result['result']=$this->routerOs->hotspot_user_delete($id);
		echo  json_encode($result);
	}
	//./hs user stuff
	public function resource($param1=null)
	{
		$result=$this->res;
		switch ($param1) {
			case 'cpu':
				$result['resource']['cpu']=$this->routerOs->get_resource()['cpu-load'];
				break;
			case 'memory':
				$result['resource']['free_memory']=$this->routerOs->get_resource()['free-memory'];
				$result['resource']['total_memory']=$this->routerOs->get_resource()['total-memory'];
				break;
			case 'uptime':
				$result['resource']['uptime']=$this->routerOs->get_resource()['uptime'];
				break;
			default:
				$result['resource']['cpu']=$this->routerOs->get_resource()['cpu-load'];
				$result['resource']['free_memory']=$this->routerOs->get_resource()['free-memory'];
				$result['resource']['total_memory']=$this->routerOs->get_resource()['total-memory'];
				break;
		}
		echo  json_encode($result);
	}
	//hs user profile stuff
	public function addUProfile()
	{
		$result=$this->res;
		$this->form_validation->set_rules('name','Name','required');
		if ($this->form_validation->run()) {
			$input=$this->input->post(null,true);
			unset($input['users-validity']);
			$preresult=$this->routerOs->hotspot_user_profile_add($input);
			$result['result']=$preresult;
		}
		else
		{
			$result['message']='error';
			$result['err_message']=validation_errors();
		}
		echo json_encode($result);
	}
	public function editUProfile()
	{
		$result=$this->res;
		$this->form_validation->set_rules('name','Name','required');
		if ($this->form_validation->run()) {
			$input=$this->input->post(null, true);
			unset($input['users-validity']);
			$result['input']=$input;
			$result['result']=$this->routerOs->hotspot_user_profile_edit($input);//nyampek sini
		}
		else{
			$result['message']='error';
			$result['err_message']=validation_errors();
		}
		echo json_encode($result);
	}
	public function deleteUProfile()
	{
		$result=$this->res;
		$this->form_validation->set_rules('id','ID','required');
		if ($this->form_validation->run()) {
			$id=$this->input->post('id',true);
			$result['result']=$this->routerOs->hotspot_user_profile_delete($id);
		}
		else{
			$result['message']='error';
			$result['err_message']=validation_errors();	
		}
		echo json_encode($result);
	}
	//./hs user profile stuff
	//hs active user stuff
	public function removeActvUser()
	{
		$result=$this->res;
		$this->form_validation->set_rules('id','ID','required');
		if ($this->form_validation->run()) {
			$id=$this->input->post('id',true);
			$result['result']=$this->routerOs->hotspot_user_active('remove',$id);
		}
		else{
			$result['message']='error';
			$result['err_message']=$validation_errors;
		}
		echo json_encode($result);
	}
}