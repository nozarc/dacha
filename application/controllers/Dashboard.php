<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */
class Dashboard extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->config->load('routeros_conf');
		$this->load->helper(array('url','form','number','ros'));
		$this->load->model(array('routerOs'));
		$this->load->library(array('template','access','form_validation','session'));
		$this->router_conf['routeros_conf']=$this->config->item('routeros_conf');
		$this->router_conf['limituptime']=$this->config->item('limit-uptime');
		if (!$this->access->is_login()) {
			redirect('');
		}
	}
	public function index($query1=null)
	{
		$data=$this->router_conf;
		$data['sidebar']['parent']['dashboard']='active';
	//	$data['debug']['res1']=$this->routerOs->get_resource();
		$data['resource']=$this->routerOs->get_resource();
		$data['active_users']=$this->routerOs->hotspot_user_active();
		$data['clock']=$this->routerOs->system('clock');
	//	$data['debug']['res2']=$data['active_users'];
	//	$data['debug']['res3']=$data['clock'];
		$datetime=implode(' ', array($data['clock']['date'],$data['clock']['time']));
		$datetime1=date_create_from_format('M/d/Y H:i:s',$datetime);
		$datetime2=date_format($datetime1,'H,i,s,n,d,Y');
		@$datetime3=mktime($datetime2);
	//	$data['debug']['datetime']=date('D d/F/Y H:i:s',$datetime3);
	//	$data['debug']['sess']=$this->session->userdata();
		$this->template->display('dashboard',$data);

	}
}