<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
class Template
{
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->helper('url');
		$this->ci->load->library('session');
		$this->ci->load->model(array('routerOs'));
		$this->ci->config->load('routeros_conf');
	}
	public function display($content,$data=null)
	{
		$template=$this->ci->config->item('template');
		$data['_state']=$this->ci->config->item('state');
		$data['sess']=$this->ci->session->userdata();
		$data['version']=$this->ci->config->item('dacha')['version'];
		$data['base_url']=$this->ci->config->item('base_url');
		$data['_tpath']=$this->ci->config->item('template_path').$template.'/';
		$path0=uri_string();
		$path1=explode('/', $path0);
		$path['home']=base_url();
		foreach ($path1 as $pkey => $pval) {
			$path2[]=$pval;
			$path3=implode('/', $path2);
			$path[$pval]=base_url($path3);
		}
		$data['path']=$path;
		if ($content!='login' && $content!='logout' && $content!='register' && $content!='print_voucher') {
			$data['_content']=$this->ci->load->view('template/'.$template.'/content/'.$content,$data,true);
			$data['_htmlhead']=$this->ci->load->view('template/'.$template.'/htmlhead',$data,true);
			$data['_jscript']=$this->ci->load->view('template/'.$template.'/jscript',$data,true);
			$data['_mainheader']=$this->ci->load->view('template/'.$template.'/mainheader',$data,true);
			$data['_mainsidebar']=$this->ci->load->view('template/'.$template.'/mainsidebar',$data,true);
			$data['_mainfooter']=$this->ci->load->view('template/'.$template.'/mainfooter',$data,true);
			$data['_ctrlsidebar']=$this->ci->load->view('template/'.$template.'/controlsidebar',$data,true);
			$this->ci->load->view('template/'.$template.'/main',$data);
		}
		else{
			$this->ci->load->view('template/'.$template.'/'.$content,$data);
		}
	}
}