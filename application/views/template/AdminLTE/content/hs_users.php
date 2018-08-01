<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Hotspot User
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <?php
    foreach ($path as $dest => $url) {
      $destination=ucfirst($dest);
      if ($destination=='Home') {
        ?>
        <li><a href="<?php echo $url; ?>"><i class="fa fa-home"></i> <?php echo $destination; ?></a></li>
        <?php
      }else{
      ?>
        <li><a href="<?php echo $url; ?>"><?php echo $destination; ?></a></li>
      <?php
      }
    }
    ?>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-9">
      <!-- list of hotspot uers -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">List of Hotspot Users</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form method="post" id="hs_users_form" action="<?php echo current_url();?>">
          <div class="box-body">
            <div class="table-responsive">
              <table id="users" class="table table-bordered table-striped dt-responsive" >
                <thead>
                  <tr>
                    <th><input type="checkbox" id="checkAll"></th><!--create check all-->
                    <th>No.</th>
                    <th>Username</th>
                    <th>Uptime</th>
                    <th>Used Quota</th>
                    <th>Profile</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($hs_users as $ukey => $uval) {
                  ?>
                  <tr id="row<?php echo $uval['.id'];?>">
                    <td><input class="inpcheck" type="checkbox" name="user[]" value="<?php echo $uval['.id'];?>"></td>
                    <td><?php echo $ukey+1; ?></td>
                    <td><?php echo $uval['name']; ?></td>
                    <td><?php echo ros_uptime($uval['uptime']); ?></td>
                    <td><?php
                        echo byte_format($uval['bytes-in']).'(U) ';
                        echo byte_format($uval['bytes-out']).'(D) ';
                        echo byte_format($uval['bytes-in']+$uval['bytes-out']).'(T) ';
                    ?>  
                    </td>
                    <td><?php echo !empty($uval['profile'])?$uval['profile']:null; ?></td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-xs btn-info" title="Detail" type="button" data-toggle='modal' data-target='#uMdetail_<?php echo $uval['.id'];?>' ><span class="fa fa-eye"></span> </button>
                        <button class="btn btn-xs btn-success" type="button" title="Edit" data-toggle='modal' data-target='#uMedit_<?php echo $uval['.id'];?>' ><span class="fa fa-pencil"></span> </button>
                        <button class="btn btn-xs btn-danger" type="button" title="Delete" data-toggle='modal' data-target='#uMdelete_<?php echo $uval['.id'];?>'><span class="fa fa-trash"></span> </button>
                      </div>
                    </td>
                  </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <div class="form-group">
              <div class="col-md-3">
                <select name="bulk_act" class="form-control" id="bulk_act">
                  <option>Bulk Action</option>
                  <option value="delete" >Delete</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div id="modals">
        <div class="modal modal-danger fade" id="mBulkDelete">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="fa fa-times"></span>
                </button>
                <h4 class="modal-title">Are you sure?</h4>
              </div>
              <div class="modal-body">
                Are you sure to delete selected users?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-primary pull-left" data-dismiss='modal'><span class="fa fa-times"></span> Cancel</button>
                <button type="submit" form="hs_users_form" class="btn btn-outline btn-danger pull-right"><span class="fa fa-trash"></span> Delete</button>
              </div>
            </div>
          </div>
        </div>
        <?php
        foreach ($hs_users as $mUkey => $mUval) {
        ?>
        <!-- user details modal-->
        <div class="modal modal-info fade" id="uMdetail_<?php echo $mUval['.id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $mUval['name'];?></h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">ID</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo @$mUval['.id'];?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6  col-sm-6 col-xs-6">Username</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo @$mUval['name'];?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">Password</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo @$mUval['password'];?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">Profile</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo @$mUval['profile'];?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">Disabled</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo @$mUval['disabled'];?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">Limit Uptime</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo !empty($mUval['limit-uptime'])?ros_uptime($mUval['limit-uptime']):'Unlimited';?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">Quota</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php
                          echo !empty($mUval['limit-bytes-total'])?byte_format($mUval['limit-bytes-total'])."(T) ":'Unlimited (T)<br>';
                          echo !empty($mUval['limit-bytes-total'])?byte_format($mUval['limit-bytes-in'])."(U) ":'Unlimited (U)<br>';
                          echo !empty($mUval['limit-bytes-total'])?byte_format($mUval['limit-bytes-out'])."(D)":'Unlimited (D)<br>';
                        ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">Uptime</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo ros_uptime($mUval['uptime']);?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">Uploaded</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo byte_format($mUval['bytes-in']);?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">Downloaded</div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        : <?php echo byte_format($mUval['bytes-out']);?>
                      </div>
                    </div> 
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
              </div>
            </div>
          </div>
        </div>
        <!--./user details modal-->
        <!-- edit user modal-->
        <div class="modal modal-success fade" id="uMedit_<?php echo $mUval['.id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Editing user <?php echo $mUval['name'];?></h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" id="formEdit<?php echo @$mUval['.id'];?>">
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">ID</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input class="form-control input-sm" type="text" disabled value="<?php echo @$mUval['.id'];?>">
                          <input type="hidden" name="id" value="<?php echo @$mUval['.id'];?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Username</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input placeholder="Username" class="form-control input-sm" type="text" name="name" value="<?php echo @$mUval['name'];?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Password</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input class="form-control input-sm" placeholder="Password" type="text" name="password"  value="<?php echo @$mUval['password'];?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Limit Uptime</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <select name="limit-uptime"  class="form-control input-sm">
                            <?php
                            foreach ($limituptime as $mLimitUkey  => $mLimitUval) {
                              if (!isset($mUval['limit-uptime'])) {
                                $limitUp='unlimited';
                              }
                              else{
                                $limitUp=$mUval['limit-uptime'];
                              }
                              ?>
                              <option <?php echo ($mLimitUkey==$limitUp)?'selected':null; ?> value="<?php echo $mLimitUkey; ?>"><?php echo $mLimitUval; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Profile</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <select name="profile"  class="form-control input-sm">
                            <?php
                            foreach ($hs_user_profiles as $mPrkey  => $mPrval) {
                              ?>
                              <option <?php echo ($mPrval['name']==@$mUval['profile'])?'selected':null; ?> value="<?php echo $mPrval['name']; ?>"><?php echo $mPrval['name']; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Disabled</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <div class="radio">
                            <label>
                              <input type="radio" name="disabled" value="yes" <?php echo ($mUval['disabled']=='true')?'checked':null;?> >Yes
                            </label>
                            <label>
                              <input type="radio" name="disabled" value="no" <?php echo ($mUval['disabled']=='false')?'checked':null;?>>No
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
                <button type="button" onclick="editUser(this)" form="formEdit<?php echo @$mUval['.id'];?>" class="btn btn-outline btn-success pull-right"><span class="fa fa-pencil"></span> Submit</button>
              </div>
            </div>
          </div>
        </div>
        <!--./edit user modal-->
        <!--delete user modal-->
        <div class="modal modal-danger fade" id="uMdelete_<?php echo $mUval['.id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Delete <?php echo $mUval['name'];?>?</h4>
              </div>
              <div class="modal-body">
                Are you sure to delete <?php echo $mUval['name'];?> from server?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-primary pull-left" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
                <button type="button" data-delete-uid='<?php echo $mUval['.id'];?>' onclick='delUser(this);' class="btn btn-outline btn-danger btnDel"><span class="fa fa-trash"></span> Delete</button>
              </div>
            </div>
          </div>
        </div>
        <!--./delete user modal-->
        <?php
        }
        ?>
      </div>
      <!-- /.box -->
    </div>
    <!--/.col (left) -->
    <!-- right column -->
    <div class="col-md-3">
      <!-- Add Single User -->
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Add User</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="adduser" method="post" role='form' action="">
          <div class="box-body">
            <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="text" name="name" required class="form-control" id="usernameAdd" placeholder="Username">
            </div>
            <div class="form-group">
              <label for="password" class="control-label">Password</label>
              <div class="input-group">
                <input required name="password" type="password" class="form-control" id="passwordAdd" placeholder="Password">
                <span class="input-group-btn">
                  <button id="showPass" type="button" class="btn btn-default"><span class="fa fa-eye"></span></button>
                </span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">Limit Uptime</label>
              <select class="form-control" name="limit-uptime">
                <?php
                  foreach ($limituptime as $limitukey => $limituval) {
                    ?>
                    <option value="<?php echo $limitukey;?>"><?php echo $limituval; ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label class="control-label">Profile</label>
              <select class="form-control" name="profile">
                <?php
                foreach ($hs_user_profiles as $prkey  => $prval) {
                  ?>
                  <option value="<?php echo $prval['name']; ?>"><?php echo $prval['name']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <script>
            $(document).ready(function() {
              $('#showPass').mousedown(function(){
                $('#passwordAdd').attr('type','text');
              });
              $('#showPass').mouseup(function() {
                $('#passwordAdd').attr('type','password');
              });
            });
          </script>
          <!-- /.box-body -->
          <div class="box-footer">
            <div id="addProgress" class="progress progress-sm active">
              <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%" >
                <span class="sr-only">In Progress</span>
              </div>
            </div>
            <button form="adduser" type="submit" class="btn btn-warning pull-right">Add</button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Bulk User</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="addBulkUser" method="post" role='form' action="">
          <div class="box-body">
            <div class="form-group">
              <label for="password" class="control-label">Count of Users</label>
              <input name="user_count" min="1" value="1" required type="number" class="form-control" id="user_count" placeholder="Count of users you want to add">
            </div>
            <div class="form-group">
              <label for="username" class="control-label">Username Prefix</label>
              <input type="text" name="username_prefix" required class="form-control" id="username_prefix" placeholder="Username Prefix" value="dacha_u">
            </div>
            <div class="form-group">
              <label for="username" class="control-label">Username Range</label>
              <div class="input-group">
                
                  <input type="number" min="0" value="0" name="user_frange" required class="form-control" id="user_frange" placeholder="First Range" value="">
                  <span class="input-group-addon">-</span>
                  <input type="number" min="1" value="0" disabled name="user_lrange" required class="form-control" id="user_lrange" placeholder="Last Range" value="">
                
              </div>
            </div>
            <div class="form-group">
              <label for="password" class="control-label">Password Length</label>
              <input name="password_length" min="1" value="3" max="12" required type="number" class="form-control" id="password_length" placeholder="Password Length">
            </div>
            <div class="form-group">
              <label class="control-label">Limit Uptime</label>
              <select id="bulkLimitUptime" class="form-control" name="limit-uptime">
                <?php
                  foreach ($limituptime as $limitukey2 => $limituval2) {
                    ?>
                    <option value="<?php echo $limitukey2;?>"><?php echo $limituval2; ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label class="control-label">Profile</label>
              <select id="bulkProfile" class="form-control" name="profile">
                <?php
                foreach ($hs_user_profiles as $prkey  => $prval) {
                  ?>
                  <option value="<?php echo $prval['name']; ?>"><?php echo $prval['name']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
            
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <div id="bulkProgress" class="progress progress-sm active">
              <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%" >
                <span class="sr-only">In Progress</span>
              </div>
            </div>
            <button form="addBulkUser" id="btnAddBulkUser" type="button" class="btn btn-success pull-right">Add</button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
      <!-- /.box -->
    </div>
    <!--/.col (right) -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<script>
  $('#bulkProgress').hide('fast');
  $('#addProgress').hide('fast');
  $(document).ready(function() {
    $('#users').DataTable({
      'paging'      : true,
      'responsive'  : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      'lengthMenu'  : [[10, 25, 50, -1], [10, 25, 50, "All"]],
    //  'processing'  : true
    });
  });
  //delete user
  function delUser(dat) {
    $(dat).find('span').removeClass('fa-trash');
    $(dat).find('span').addClass('fa-refresh fa-spin');
    $.post('<?php echo base_url('ajax/deleteUser');?>', {id: $(dat).attr('data-delete-uid')},
      function(deleted) {
        $('.modal').modal('hide');
        usersTable.row($('tr#row'+deleted.result.id)).remove().draw(false);
        $('modal').on('hidden.bs.modal',function() {
          $(dat).find('span').removeClass('fa-refresh fa-spin');
          $(dat).find('span').addClass('fa-trash');
          $('div#uMedit_'+deleted.result.id).remove();
          $('div#uMdetail_'+deleted.result.id).remove();
          $('div#uMdelete_'+deleted.result.id).remove();
        })
      },'json'
    );
  }
  //./delete user
  // edit user
  function editUser(editBtn) {
    $(editBtn).find('span').removeClass('fa-pencil');
    $(editBtn).find('span').addClass('fa-refresh fa-spin');
    var editForm=$('form#'+$(editBtn).attr('form'));
    editForm.submit(function(edit) {
      edit.preventDefault();
      $.ajax({
        url: '<?php echo base_url('ajax/editUser') ?>',
        type: 'POST',
        contentType:false,
        processData:false,
        dataType: 'json',
        data: new FormData(this),
      })
      .done(function(edRes) {
        console.log(edRes);
        var thisRow=usersTable.row($('tr#row'+edRes.result.id));
        thisRow.data([thisRow.data()[0],thisRow.data()[1],edRes.result.name,edRes.result.uptime,checkUsed(edRes.result['bytes-in'])+'(U) '+checkUsed(edRes.result['bytes-out'])+'(D) '+checkUsed(Number(edRes.result['bytes-in'])+Number(edRes.result['bytes-in']))+'(T)',edRes.result.profile,generateBtn(edRes.result.id)]).draw(false);
        $(editBtn).find('span').removeClass('fa-refresh fa-spin');
        $(editBtn).find('span').addClass('fa-check');
        $('.modal').modal('hide');
        $('.modal').on('hidden.bs.modal',function() {
          $(editBtn).find('span').removeClass('fa-check');
          $(editBtn).find('span').addClass('fa-pencil');
          $('div#uMedit_'+edRes.result.id).remove();
          $('div#uMdetail_'+edRes.result.id).remove();
          $('div#uMdelete_'+edRes.result.id).remove();
          $('#modals').append(generateModal(edRes));
        })
      })
      .fail(function() {
        console.log("error on view");
      })
    });
    editForm.submit();
  }
  //./edit user
  $(document).ready(function () {
    usersTable=$('#users').DataTable();

    function updateVal() {
      var frangeVal=Number($('#user_frange').val());
      var countVal=Number($('#user_count').val());
      $('#user_lrange').val((frangeVal+countVal-1).pad($('#user_frange').val().length));
      $('#user_lrange').attr('min',frangeVal);
    };
    
    $('#user_count').change(function(){updateVal();});
    $('#user_frange').change(function(){updateVal();});
    
    // add user
    $('#adduser').submit(function (d) {
        d.preventDefault();
        $('#addProgress').fadeIn('slow');
        $.ajax({
          contentType: false,
          processData: false,
          dataType: 'json',
          url: '<?php echo base_url('ajax/addUser');?>',
          type: 'POST',
          data:new FormData(this),
          success:function(addRes) {
            $('#addProgress').fadeOut('slow');
            usersTable.row.add([generateCheckbox(addRes.result.id),usersTable.rows().data().length+1,addRes.result.name,'00:00:00'/*need to improve this*/,checkUsed(addRes.result['bytes-in'])+'(U) '+checkUsed(addRes.result['bytes-out'])+'(D) '+checkUsed(Number(addRes.result['bytes-in'])+Number(addRes.result['bytes-in']))+'(T)',addRes.result.profile,generateBtn(addRes.result.id)]).draw(false);
            usersTable.order(1,'asc').draw(false);
            usersTable.page('last').draw(false);
            $('td:contains('+addRes.result.name+')').parent().attr('id', 'row'+addRes.result.id);
            $('#modals').append(generateModal(addRes));
          },
          error:function(addErr,status,xhr) {
            console.log('err->');
            console.log(status);
            console.log(xhr);
          }
        });
      });
    $('#btnAddBulkUser').click(function(){
      var addBulkUser=$('#addBulkUser').serializeArray()
      var count=$('#user_count').val();
      var username_prefix=$('#username_prefix').val();
      var user_frange=$('#user_frange').val();
      var eachName=Number(user_frange);
      var user_lrange=Number($('#user_lrange').val());
      var password_length=$('#password_length').val();
      var uptime=$('#bulkLimitUptime').val();
      var profile=$('#bulkProfile').val();
      insertedData=0;
      //functions
      $('#bulkProgress').fadeIn('slow');
      for(eachUser=1;eachUser<=count;eachUser++){
        var user={name:username_prefix+(eachName).pad(user_frange.length),password:makeRand(password_length,poolData.num),'limit-uptime':uptime,profile:profile}
        $.post(
          '<?php echo base_url('ajax/addUser');?>',user,
          function(bulkAddRes){
            usersTable.row.add([generateCheckbox(bulkAddRes.result.id),usersTable.rows().data().length+1,bulkAddRes.result.name,'00:00:00'/*need to improve this*/, checkUsed(bulkAddRes.result['bytes-in'])+'(U) '+checkUsed(bulkAddRes.result['bytes-out'])+'(D) '+checkUsed(Number(bulkAddRes.result['bytes-in'])+Number(bulkAddRes.result['bytes-in']))+'(T)',bulkAddRes.result.profile,generateBtn(bulkAddRes.result.id)]).draw(false);
            usersTable.page('last').draw(false);
            console.log(bulkAddRes);
            $('td:contains('+bulkAddRes.result.name+')').parent().attr('id', 'row'+bulkAddRes.result.id); //make it match by id
            usersTable.order(1,'asc').draw(false);
            $('#modals').append(generateModal(bulkAddRes));
            insertedData+=1;
            if (insertedData==count) {
              $('#bulkProgress').fadeOut('slow');
            };
          },'json'
        );
        eachName++;
      };
    });
    //./add user
    //bulk action
    $('#bulk_act').change(function() {
      if ($(this).val()=='delete') {
        $('.modal#mBulkDelete').modal('show');$('.modal#mBulkDelete').modal('show');
      };
    });
  });
</script>