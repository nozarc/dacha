<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class RouterOs extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('routeros_api','session');
		$this->load->config('routeros_conf');
		$this->load->helper('ros');
	//	$this->routerOsAPI=$this->routerOs_API;
		$this->routeros_conf=$this->config->item('routeros_conf');
	}
	public function connect($username=null, $password=null,$ip=null,$port=null)
	{
		if (is_array($username)) {
			extract($username);
		}
		if (empty($port)) {
			$port=$this->routeros_conf['port'];
		}
		$this->routeros_api->debug=false;
		$this->routeros_api->port=$port;
		$routeros_data=array(
								'username'	=>$username,
								'password'	=>$password,
								'ip'		=>$ip,
								'port'		=>$port
									);
		$this->session->set_userdata('routeros_data',$routeros_data);
		return $this->routeros_api->connect($ip,$username,$password);
	}
	public function debug($param1=null)
	{
		if ($param1!=null) {
			$this->connect($this->session->userdata('routeros_data'));
			return $this->routeros_api->comm($param1);
		}
	}
	public function get_resource($param1='all')
	{
		$this->connect($this->session->userdata('routeros_data'));
		switch ($param1) {
			case 'all':
				return $this->routeros_api->comm('/system/resource/print')[0];
				break;
			
			default:
				return false;
				break;
		}
	}
	public function hotspot_user_active($act='show')
	{
		$this->connect($this->session->userdata('routeros_data'));
		switch ($act) {
			case 'show':
				return $this->routeros_api->comm('/ip/hotspot/active/print');
				break;
			
			default:
				return false;
				break;
		}
	}
	public function system($param1='clock')
	{
		$this->connect($this->session->userdata('routeros_data'));
		switch ($param1) {
			case 'clock':
				return $this->routeros_api->comm('/system/clock/print')[0];
				break;
			
			default:
				return false;
				break;
		}
	}
	public function hotspot_user_show($act='all',$id=null)
	{
		$this->connect($this->session->userdata('routeros_data'));
		switch ($act) {
			case 'all':
				$hs_users=$this->routeros_api->comm('/ip/hotspot/user/print');
				$result=$hs_users;
				foreach ($hs_users as $hsukey => $hsuval) {
					$newkey=ros_id($hsuval['.id']);
					$result[$hsukey]['.id']=$newkey;
				}
				return $result;
				break;
			
			case 'id':
				return $this->routeros_api->comm('/ip/hotspot/user/print',array('?.id'=>$id));
				break;
		}
	}
	public function hotspot_user_profile($act='show_all')
	{
		$this->connect($this->session->userdata('routeros_data'));
		switch ($act) {
			case 'show_all':
				$hs_user_profiles=$this->routeros_api->comm('/ip/hotspot/user/profile/print');
				foreach ($hs_user_profiles as $hsupkey => $hsupval) {
					$newkey=ros_id($hsupval['.id']);
					$result[$newkey]=$hsupval;
					$result[$newkey]['.id']=$newkey;
				}
				return $result;
				break;
		}
	}
	public function hotspot_user_add($input=null)
	{
		$result=false;
		if (!empty($input)) {
			$this->connect($this->session->userdata('routeros_data'));
			$add_id=$this->routeros_api->comm('/ip/hotspot/user/add',$input);
			$out=$this->routeros_api->comm('/ip/hotspot/user/print',array('?.id'=>$add_id));
			$result=$out[0];
			$result['id']=ros_id($result['.id']);
		}
		return $result;
	}
	public function hotspot_user_edit($input=null)
	{
		$result=false;
		if (!empty($input)) {
			$this->connect($this->session->userdata('routeros_data'));
			$input['.id']=ros_id($input['id'],'asterisk');
			unset($input['id']);
			$this->routeros_api->comm('/ip/hotspot/user/set',$input);

			$out=$this->routeros_api->comm('/ip/hotspot/user/print',array('?.id'=>$input['.id']));
			$result=$out[0];
			$result['id']=ros_id($result['.id']);
		}
		return $result;
	}
	public function hotspot_user_delete($id=null)
	{
		$result=false;
		if (!empty($id)) {
			$this->connect($this->session->userdata('routeros_data'));
			$this->routeros_api->comm('/ip/hotspot/user/remove',array('.id'=>ros_id($id,'asterisk')));
			return array('id'=>$id);
		}
	}
	public function interfaces($act='print')
	{
		$this->connect($this->session->userdata('routeros_data'));
		return $this->routeros_api->comm('/interface/'.$act);
	}
	public function remote_addr($act='print',$where=null)
	{
		$this->connect($this->session->userdata('routeros_data'));
		return $this->routeros_api->comm('/user/active/'.$act,array('?via' =>'api','?name'=>$this->session->userdata('routeros_data')['username'],'.proplist'=>'address'))[0]['address'];
	}
	public function disconnect()
	{
		$this->routeros_api->disconnect();
	}
}