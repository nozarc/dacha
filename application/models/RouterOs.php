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
	public function debug($param1=null,$mode=1)
	{
		if ($param1!=null) {
			$this->connect($this->session->userdata('routeros_data'));
			switch ($mode) {
				case 1:
					return $this->routeros_api->comm($param1);
					break;
				case 2:
					$this->routeros_api->write($param1);
					$read=$this->routeros_api->read(false);
					return $read;//$this->routeros_api->parseResponse($read);
					break;
				case 3:
					$this->routeros_api->write($param1);
					return $this->routeros_api->read();
					break;
			}
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
	public function hotspot_user_active($act='show',$id=null)
	{
		$this->connect($this->session->userdata('routeros_data'));
		switch ($act) {
			case 'show':
				$active_users=$this->routeros_api->comm('/ip/hotspot/active/print');
				$result=$active_users;
				foreach ($active_users as $aukey => $auval) {
					$result[$aukey]['.id']=ros_id($auval['.id']);
				}
				return $result;
				break;
			case 'remove':
				if ($id==null) {
					return false;
				}
				$this->routeros_api->comm('/ip/hotspot/active/remove',array('.id'=>ros_id($id,'asterisk')));
				return array('id'=>$id);
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
				$hs_user=$this->routeros_api->comm('/ip/hotspot/user/print',array('?.id'=>ros_id($id,'asterisk')));
				$result=$hs_user;
				foreach ($hs_user as $hsukey => $hsuval) {
					$newkey=ros_id($hsuval['.id']);
					$result[$hsukey]['.id']=$newkey;
				}
				return $result[0];
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
			$result=array('id'=>$id);
		}
		return $result;
	}
	//./hotspot user stuff
	//hotspot user profile stuff
	public function hotspot_user_profile($act='show_all')
	{
		$this->connect($this->session->userdata('routeros_data'));
		switch ($act) {
			case 'show_all':
				$this->routeros_api->write('/ip/hotspot/user/profile/print');
				$read=$this->routeros_api->read(false);
				$hs_user_profiles=$this->routeros_api->parseResponse($read);
			//	$hs_user_profiles=$this->routeros_api->comm('/ip/hotspot/user/profile/print');
				foreach ($hs_user_profiles as $hsupkey => $hsupval) {
					$newkey=ros_id($hsupval['.id']);
					$result[$newkey]=$hsupval;
					$result[$newkey]['.id']=$newkey;
					if (!empty($hsupval['on-login'])) {
						preg_match('/(\d+[wd] )?(..):(..):(..)/', $hsupval['on-login'],$valid);
						$result[$newkey]['validity']=$valid[0];
					}
					else{
						$result[$newkey]['validity']='0';
					}
				}
				return $result;
				break;
		}
	}
	public function hotspot_user_profile_add($input=null)
	{
		if (empty($input)) {
			return false;
		}
		$this->connect($this->session->userdata('routeros_data'));
		$add_id=$this->routeros_api->comm('/ip/hotspot/user/profile/add',$input);
		$result=$this->routeros_api->comm('/ip/hotspot/user/profile/print',array('?.id'=>$add_id))[0];
		$result['id']=ros_id($result['.id']);
		if (!empty($result['on-login'])) {
			preg_match('/(\d+[wd] )?(..):(..):(..)/', $result['on-login'],$valid);
			$result['validity']=$valid[0];
		}
		else{
			$result['validity']='0';
		}
		
		$result['rosuptime-keepalive-timeout']=ros_uptime($result['keepalive-timeout']);
		$result['rosuptime-status-autorefresh']=ros_uptime($result['status-autorefresh']);
		return $result;
	}
	public function hotspot_user_profile_edit($input=null)
	{
		if (empty($input)) {
			return false;
		}
		$this->connect($this->session->userdata('routeros_data'));
		$input['.id']=ros_id($input['id'],'asterisk');
		unset($input['id']);
		$this->routeros_api->comm('/ip/hotspot/user/profile/set',$input);
		$result=$this->routeros_api->comm('/ip/hotspot/user/profile/print',array('?.id'=>$input['.id']))[0];
		$result['id']=ros_id($result['.id']);
		if (!empty($result['on-login'])) {
			preg_match('/(\d+[wd] )?(..):(..):(..)/', $result['on-login'],$valid);
			$result['validity']=$valid[0];
		}
		else{
			$result['validity']='0';
		}
		$result['rosuptime-keepalive-timeout']=ros_uptime($result['keepalive-timeout']);
		$result['rosuptime-status-autorefresh']=ros_uptime($result['status-autorefresh']);
		return $result;
	}
	public function hotspot_user_profile_delete($input=null)
	{
		if (empty($input)) {
			return false;
		}
		$id=$input;
		$this->connect($this->session->userdata('routeros_data'));
		$this->routeros_api->comm('/ip/hotspot/user/profile/remove',array('.id'=>ros_id($id,'asterisk')));
		return array('id'=>$id);
	}
	//./hotspot user profile stuff
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