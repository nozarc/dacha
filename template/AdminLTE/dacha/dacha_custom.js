/*! Dacha Custom JS Functions
*
*
*
*/
debug='';
// formatBytes 
//original source by https://stackoverflow.com/questions/15900485/correct-way-to-convert-size-in-bytes-to-kb-mb-gb-in-javascript
function byte_format(a,b){
	if(0==a)return"0 Bytes";
	var c=1024,d=b||2,e=["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"],f=Math.floor(Math.log(a)/Math.log(c));
	return parseFloat((a/Math.pow(c,f)).toFixed(d))+" "+e[f];
}
//zeroTime, add zero in front of number lower than 10
function zeroTime(time) {
	if (time<10) {time='0'+time};
	return time;
}

//dacha custom
poolData={num:'0123456789',alphalow:'abcdefghijklmnopqrstuvwxyz',alphaup:'ABCDEFGHIJKLMNOPQRSTUVWXYZ',alpha:'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',numalpha:'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'};
function makeRand(randlength,randpool) {
	var randRes='';
	for(randRes1=0;randRes1<randlength;randRes1++){
	randRes+=randpool.charAt(Math.floor(Math.random()*randpool.length));
	}
	return randRes;
};
Number.prototype.pad = function(size) {
	var num = String(this);
	while (num.length < (size || 2)) {num = "0" + num;}
	return num;
};

function checkQuota(checkBytes) {
    if (checkBytes) {
      return byte_format(Number(checkBytes));
    }
    else{
      return 'Unlimited';
    }
}
function checkUsed(used) {
	if (used) {
	  return byte_format(Number(used));
	}
	else{
	  return '0 Bytes';
	}
}
function checkUptime(uptime) {
	if (uptime) {
	  return uptime;
	}
	else{
	  return '00:00:00';
	}
}

// generator
    function generateCheckbox(id) {
      return '<input type="checkbox" name="user[]" value="'+id+'">';
    }
    function generateBtn(id) {
      return ''
		+'<div class="btn btn-group">'
			+'<button class="btn btn-xs btn-info" title="Detail" type="button" data-toggle="modal" data-target="#uMdetail_'+id+'" >'
				+'<span class="fa fa-eye"></span>'
			+'</button>'
			+'<button class="btn btn-xs btn-success" title="Edit" type="button" data-toggle="modal" data-target="#uMedit_'+id+'" >'
				+'<span class="fa fa-pencil"></span>'
			+'</button>'
			+'<button class="btn btn-xs btn-danger" title="Delete" type="button" data-toggle="modal" data-target="#uMdelete_'+id+'">'
				+'<span class="fa fa-trash"></span>'
			+'</button>'
		+'</div>';
    }
    DisabledY='';
    DisabledN='';
    
    function generateModal(data) {
    	var optLimituptime='';
    	var optProfile='';
    	var checkPr=[];
    	var checkLim=[];
    	data['profile']=data.hotspot.uprofile;
    	data['limituptime']=data.hotspot.limituptime;
    	function compSelect(param1,param2) {
    		if (param1==param2) {
    			return 'selected';
    		}
    		return '';
    	};
		switch (data.result.disabled) {
			case 'true':
				DisabledY='checked';
				break;
			case 'false':
				DisabledN='checked';
				break;
			case 'yes':
				DisabledY='checked';
				break;
			case 'no':
				DisabledN='checked';
				break;
		}
		for(var prKey in data['profile']){
			checkPr[prKey]=compSelect(data.profile[prKey].name,data.result.profile);
			optProfile+='<option '+checkPr[prKey]+' value="'+data.profile[prKey].name+'">'+data['profile'][prKey].name+'</option>'
		};
		for(var limKey in data['limituptime']){
			checkLim[limKey]=compSelect(limKey,data.result['limit-uptime']);
			optLimituptime+='<option '+checkLim[limKey]+' value="'+limKey+'">'+data.limituptime[limKey]+'</option>'
		};
		if (data.result['limit-uptime']) {
			var limituptime=data.result['limit-uptime'];
		}
		else{
			var limituptime='Unlimited';
		}
		debug=data;
      	return ''
		+'<div class="modal modal-info fade" id="uMdetail_'+data.result.id+'">'
			+'<div class="modal-dialog">'
				+'<div class="modal-content">'
					+'<div class="modal-header">'
						+'<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
							+'<span aria-hidden="true">&times;</span>'
						+'</button>'
						+'<h4 class="modal-title">'+data.result.name+'</h4>'
					+'</div>'
				+'<div class="modal-body">'
					+'<div class="row">'
						+'<div class="col-md-6">'
							+'<div class="row">'
						      +'<div class="col-md-6">ID</div>'
						      +'<div class="col-md-6">: '+data.result.id+'</div>'
							+'</div>'
							+'<div class="row">'
						      +'<div class="col-md-6">Username</div>'
						      +'<div class="col-md-6">: '+data.result.name+'</div>'
							+'</div>'
							+'<div class="row">'
						  		+'<div class="col-md-6">Password</div>'
								+'<div class="col-md-6">: '+data.result.password+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-md-6">Profile</div>'
								+'<div class="col-md-6">: '+data.result.profile+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-md-6">Disabled</div>'
								+'<div class="col-md-6">: '+data.result.disabled+'</div>'
							+'</div>'
						+'</div>'
						+'<div class="col-md-6 pull-right">'
							+'<div class="row">'
								+'<div class="col-md-6">Limit Uptime</div>'
								+'<div class="col-md-6">: '+limituptime+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-md-6">Quota</div>'
								+'<div class="col-md-6">: '
									+checkQuota(data.result['limit-bytes-total'])+' (T) '
									+checkQuota(data.result['limit-bytes-in'])+' (U) '
									+checkQuota(data.result['limit-bytes-out'])+' (D)'
								+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-md-6">Uptime</div>'
								+'<div class="col-md-6">: '+checkUptime(data.result.uptime)+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-md-6">Uploaded</div>'
								+'<div class="col-md-6">: '+checkUsed(data.result['bytes-in'])+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-md-6">Downloaded</div>'
								+'<div class="col-md-6">: '+checkUsed(data.result['bytes-out'])+'</div>'
							+'</div> '
						+'</div>'
					+'</div>'
				+'</div>'
				+'<div class="modal-footer">'
					+'<button type="button" class="btn btn-outline pull-right" data-dismiss="modal">'
						+'<span class="fa fa-times"></span> Close'
					+'</button>'
				+'</div>'
				+'</div>'
			+'</div>'
		+'</div>'
		+'<div class="modal modal-success fade" id="uMedit_'+data.result.id+'">'
			+'<div class="modal-dialog">'
				+'<div class="modal-content">'
					+'<div class="modal-header">'
						+'<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
							+'<span aria-hidden="true" class="fa fa-times"></span>'
						+'</button>'
						+'<h4 class="modal-title">Editing user '+data.result.name+'</h4>'
					+'</div>'
					+'<div class="modal-body">'
						+'<form class="form-horizontal" id="formEdit'+data.result.id+'">'
							+'<div class="row">'
								+'<div class="col-md-6 col-sm-6 col-xs-12">'
									+'<div class="form-group">'
										+'<label class="control-label col-md-5 col-sm-5 col-xs-5">ID</label>'
										+'<div class="col-md-7 col-sm-7 col-xs-7">'
											+'<input class="form-control input-sm" type="text" disabled value="'+data.result.id+'">'
											+'<input type="hidden" name="id" value="'+data.result.id+'">'
										+'</div>'
									+'</div>'
									+'<div class="form-group">'
										+'<label class="control-label col-md-5 col-sm-5 col-xs-5">Username</label>'
										+'<div class="col-md-7 col-sm-7 col-xs-7">'
											+'<input placeholder="Username" class="form-control input-sm" type="text" name="name" value="'+data.result.name+'">'
										+'</div>'
									+'</div>'
									+'<div class="form-group">'
										+'<label class="control-label col-md-5 col-sm-5 col-xs-5">Password</label>'
										+'<div class="col-md-7 col-sm-7 col-xs-7">'
											+'<input class="form-control input-sm" placeholder="Password" type="text" name="password"  value="'+data.result.password+'">'
										+'</div>'
									+'</div>'
								+'</div>'
								+'<div class="col-md-6 col-sm-6 col-xs-12">'
									+'<div class="form-group">'
										+'<label class="control-label col-md-5 col-sm-5 col-xs-5">Limit Uptime</label>'
										+'<div class="col-md-7 col-sm-7 col-xs-7">'
											+'<select name="limit-uptime"  class="form-control input-sm">'
												+optLimituptime
											+'</select>'
										+'</div>'
									+'</div>'
									+'<div class="form-group">'
										+'<label class="control-label col-md-5 col-sm-5 col-xs-5">Profile</label>'
										+'<div class="col-md-7 col-sm-7 col-xs-7">'
											+'<select name="profile"  class="form-control input-sm">'
												+optProfile		
											+'</select>'
										+'</div>'
									+'</div>'
									+'<div class="form-group">'
										+'<label class="control-label col-md-5 col-sm-5 col-xs-5">Disabled</label>'
										+'<div class="col-md-7 col-sm-7 col-xs-7">'
											+'<div class="radio">'
												+'<label>'
													+'<input type="radio" name="disabled" value="yes" '+DisabledY+' >Yes '
												+'</label>'
												+'<label>'
													+'<input type="radio" name="disabled" value="no" '+DisabledN+'> No '
												+'</label>'
											+'</div>'
										+'</div>'
									+'</div>'
								+'</div>'
							+'</div>'
						+'</form>'
					+'</div>'
					+'<div class="modal-footer">'
						+'<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">'
							+'<span class="fa fa-times"></span> Cancel'
						+'</button>'
						+'<button type="submit" onclick="editUser(this)" form="formEdit'+data.result.id+'" class="btn btn-outline btn-success pull-right">'
							+'<span class="fa fa-pencil"></span> Submit'
						+'</button>'
					+'</div>'
				+'</div>'
			+'</div>'
		+'</div>'
		+'<div class="modal modal-danger fade" id="uMdelete_'+data.result.id+'">'
			+'<div class="modal-dialog">'
				+'<div class="modal-content">'
					+'<div class="modal-header">'
						+'<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
							+'<span aria-hidden="true">&times;</span>'
						+'</button>'
						+'<h4 class="modal-title">Delete '+data.result.name+'?</h4>'
					+'</div>'
					+'<div class="modal-body">Are you sure to delete '+data.result.name+' from server?</div>'
					+'<div class="modal-footer">'
						+'<button type="button" class="btn btn-outline btn-primary pull-left" data-dismiss="modal">'
							+'<span class="fa fa-times"></span> Cancel'
						+'</button>'
						+'<button type="button" data-delete-uid="'+data.result.id+'" onclick="delUser(this)" class="btn btn-outline btn-danger btnDel">'
							+'<span class="fa fa-trash"></span> Delete'
						+'</button>'
					+'</div>'
				+'</div>'
			+'</div>'
		+'</div>';
    }
    //.generator