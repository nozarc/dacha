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
		$this->load->library(array('template','access','form_validation','session','excel'));
		$data['routeros_conf']=$this->config->item('routeros_conf');
		$data['limituptime']=$this->config->item('limit-uptime');
		//data
		$data['sidebar']['parent']['hotspot']='active';
	//	$data['reminder']='hapus modal ketika data terhapus';
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
				$data['hs_user_profiles']=$this->routerOs->hotspot_user_profile();
				$this->form_validation->set_rules('bulk_act','Bulk Action','required|alpha');
				
				if ($this->form_validation->run()) {
					$action=$this->input->post('bulk_act',true);
					$userIds=$this->input->post('user',true);
					switch ($action) {
						case 'delete':
							foreach ($userIds as $idkey => $idval) {
								$this->routerOs->hotspot_user_delete($idval);
							}
							redirect('hotspot/user');
							break;
						case 'exportxls':
							foreach ($userIds as $idkey => $idval) {
								$exportedUsers[]=$this->routerOs->hotspot_user_show('id',$idval);
							}
							//---export into excel---//
							//excel document properties
							$excelColumn=1;
							$excelData= new $this->excel();
							$excelData->getProperties()->setCreator('Dacha Usermanager')
										->setLastModifiedBy('Dacha Usermanager')
										->setTitle('Hotspot Users')
										->setSubject("Office 2007 XLSX Test Document")
										->setDescription("Exported by Dacha Usermanager.")
										->setKeywords("mikrotik hotspot users dacha usermanager")
										->setCategory("file");
							//excel document data
							$excelData->setActiveSheetIndex(0);
							$excelData->getActiveSheet()->setCellValue('A'.$excelColumn,'No.');
							$excelData->getActiveSheet()->setCellValue('B'.$excelColumn,'Username');
							$excelData->getActiveSheet()->setCellValue('C'.$excelColumn,'Password');
							$excelData->getActiveSheet()->setCellValue('D'.$excelColumn,'Profile');
							$excelData->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
							foreach ($exportedUsers as $exkey => $exvalue) {
								$excelColumn++;
								$excelData->getActiveSheet()->setCellValue('A'.$excelColumn,$exkey+1);
								$excelData->getActiveSheet()->setCellValue('B'.$excelColumn,$exvalue['name']);
								$excelData->getActiveSheet()->setCellValue('C'.$excelColumn,$exvalue['password']);
								$excelData->getActiveSheet()->setCellValue('D'.$excelColumn,$exvalue['profile']);
							}
							//change worksheet title
							$excelData->getActiveSheet()->setTitle('Hotspot_Users');
							//change worksheet default format
							$excelData->getActiveSheet()->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
							// Redirect output to a client’s web browser (Excel5)
							header('Content-Type: application/vnd.ms-excel');
							header('Content-Disposition: attachment;filename="Hotspot_Users.xls"');
							header('Cache-Control: max-age=0');
							// If you're serving to IE 9, then the following may be needed
							header('Cache-Control: max-age=1');

							// If you're serving to IE over SSL, then the following may be needed
							header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
							header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
							header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
							header ('Pragma: public'); // HTTP/1.0

							$excelWriter = PHPExcel_IOFactory::createWriter($excelData, 'Excel5');
							$excelWriter->save('php://output');
							//---./export into excel---//
							break;
						/*	//Coming Soon
						case 'print':
							foreach ($userIds as $idkey => $idval) {
								$fdata[]=$this->routerOs->hotspot_user_show('id',$idval);
							}
							$this->session->set_flashdata('print_voucher',$fdata);
							redirect('hotspot/user/print_voucher');
							break;
						*/
					}
				}
				$this->template->display('hs_users',$data);
				break;
			/*	//Coming Soon
			case 'print_voucher':
				$data['vouchers']=$this->session->flashdata('print_voucher');
				$this->template->display('print_voucher',$data);
				break;
			*/
			case 'profile':
				$data['sidebar']['child']['user_profile']='active';
				$data['hs_user_profiles']=$this->routerOs->hotspot_user_profile();
				$data['validUntil']=$this->config->item('valid-until');
				$this->form_validation->set_rules('bulk_act','Bulk Action','required|alpha');
				if ($this->form_validation->run()) {
					$userIds=$this->input->post('uprofile',true);
					foreach ($userIds as $idkey => $idval) {
						$this->routerOs->hotspot_user_profile_delete($idval);
					}
					redirect('hotspot/user/profile');
				}
				$this->template->display('hs_uprofile',$data);
				break;
			case 'active':
				$data['sidebar']['child']['active_users']='active';
				$data['hs_active_users']=$this->routerOs->hotspot_user_active();
				$this->template->display('hs_active_users',$data);
				break;
		}
	}
}