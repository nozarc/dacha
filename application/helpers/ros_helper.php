<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */

function ros_uptime($param1=null)
{
	if (!function_exists('num_uptime')) {
		function num_uptime($uptime,$target='s')
		{
			if ($target!=('s'||'m'||'h'||'d'||'w')) {
				return false;
			}
			$arr=array();
			$pos=stripos($uptime,$target);
			if(!empty($pos)){
			    $stpos=$pos-2;
			    if($stpos<0){
			        $stpos=0;
			    }
			    $string=substr($uptime,$stpos,2);
			    $stringarr=str_split($string);
			    foreach($stringarr as $strArK => $strArV){
					if(is_numeric($strArV)){
						array_push($arr,$strArV);
			    	}
				}
				return implode(null,$arr);
			}
		}
	}
	$second=leadZeroTime(!empty(num_uptime($param1,'s'))?num_uptime($param1,'s'):0);
	$minute=leadZeroTime(!empty(num_uptime($param1,'m'))?num_uptime($param1,'m'):0);
	$hour=leadZeroTime(!empty(num_uptime($param1,'h'))?num_uptime($param1,'h'):0);
	$day=!empty(num_uptime($param1,'d'))?num_uptime($param1,'d').'d':null;
	$week=!empty(num_uptime($param1,'w'))?num_uptime($param1,'w').'w':null;
	return ltrim("$week $day $hour:$minute:$second");
}
function leadZeroTime($param1=null)
{
	if ($param1<10) {
		$param1="0$param1";
	}
	return $param1;
}
function ros_limit($param1=null)
{
	//improve this
	switch ($param1) {
		case '6h':
			return ros_uptime('6h');
			break;
		case '1d':
			return ros_uptime('1d');
			break;
		case '2d':
			return ros_uptime('2d');
			break;
		case '1w':
			return ros_uptime('7d');
			break;
		case '2w':
			return ros_uptime('14d');
			break;
		case '4w2d':
			return ros_uptime('30d');
			break;
		case 'unlimited':
			return '0';
			break;
		case '0':
			return '0';
			break;
	}
}
function ros_id($param1=null,$param2='nullify')
{
	switch ($param2) {
		case 'nullify':
			return str_replace('*', null, $param1);
			break;
		case 'asterisk':
			return '*'.$param1;
			break;
	}
}