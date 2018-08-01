<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Hotspot User Profile
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
    <div class="col-md-12">
      <!-- list of hotspot uers -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">List of Hotspot User Profile</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form method="post" id="hs_uprofile_bulk" action="<?php echo current_url();?>">
          <div class="box-body">
            <div class="table-responsive">
              <table id="userProfiles" class="table table-bordered table-striped dt-responsive" >
                <thead>
                  <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Profile Name</th>
                    <th>Shared Users</th>
                    <th>Rate Limit</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $no=0;
                foreach ($hs_user_profiles as $UPkey => $UPval) {
                  ?>
                  <tr id='row<?php echo $UPval['.id'];?>'>
                    <td><input type="checkbox" name="uprofile[]" value="<?php echo $UPval['.id']; ?>"></td>
                    <td><?php echo $no+1; ?></td>
                    <td><?php echo $UPval['name']; ?></td>
                    <td><?php echo $UPval['shared-users']; ?></td>
                    <td><?php echo !empty($UPval['rate-limit'])?$UPval['rate-limit']:null; ?></td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-xs btn-info" data-toggle='modal' data-target='#upMdetail_<?php echo $UPval['.id'];?>' title='Detail' type="button"><span class="fa fa-eye"></span></button>
                        <button class="btn btn-xs btn-success" data-toggle='modal' data-target='#upMedit_<?php echo $UPval['.id'];?>' title='Edit'  type="button"><span class="fa fa-pencil"></span></button>
                        <button class="btn btn-xs btn-danger" data-toggle='modal' data-target='#upMdelete_<?php echo $UPval['.id'];?>' title='Delete'  type="button"><span class="fa fa-trash"></span></button>
                      </div>
                    </td>
                  </tr>
                  <?php
                $no++;
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <div class="form-group">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <select name="bulk_act" class="form-control" id="bulk_act">
                  <option>Bulk Action</option>
                  <option value="delete" >Delete</option>
                </select>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12 pull-right">
                <button class="btn bg-green pull-right" type="button" data-toggle='modal' data-target='#addUProfile'><span class="fa fa-plus"></span> Add Profile</button>
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
                Are you sure to delete selected profiles?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-primary pull-left" data-dismiss='modal'><span class="fa fa-times"></span> Cancel</button>
                <button type="submit" form="hs_uprofile_bulk" class="btn btn-outline btn-danger pull-right"><span class="fa fa-trash"></span> Delete</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal modal-success fade" id="addUProfile">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="fa fa-times"></span>
                </button>
                <h4 class="modal-title">Add new user profile</h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" id="hs_userprofile_form">
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Name</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input placeholder="Name" required class="form-control input-sm" type="text" name="name">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Shared Users</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input value="1" class="form-control input-sm" placeholder="Shared Users" type="number" min="1" name="shared-users">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Keepalive Timeout</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input class="form-control input-sm" value="00:02:00" placeholder="Keepalive Timeout" type="time" step="1" name="keepalive-timeout">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Users Validity</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <select onchange="updateScript(this)" name="users-validity"  class="form-control input-sm">
                            <?php
                            foreach ($validUntil as $vkey => $vval) {
                              ?>
                              <option value="<?php echo ros_limit($vkey);?>" <?php echo $vkey==0?'selected':null;?>><?php echo $vval; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Rate Limit</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input type="text" name="rate-limit" class="form-control input-sm" placeholder="780k/9M 0/0 0/0 0/0 3 256k/832k" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Transparent Proxy</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <div class="radio">
                            <label>
                              <input type="radio" name="transparent-proxy" value="yes">Yes
                            </label>
                            <label>
                              <input type="radio" checked name="transparent-proxy" value="no">No
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="col-md-12 col-sm-12 col-xs-12">On Login Script</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <textarea name="on-login" placeholder="On Login Script" class="form-control" rows="3"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="col-md-12 col-sm-12 col-xs-12">On Logout Script</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <textarea name="on-logout" placeholder="On Logout Script" class="form-control" rows="3"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-primary pull-left" data-dismiss='modal'><span class="fa fa-times"></span> Cancel</button>
                <button type="submit" form="hs_userprofile_form" class="btn btn-outline btn-success pull-right"><span class="fa fa-plus"></span> Add</button>
              </div>
            </div>
          </div>
        </div>
        <?php
        foreach ($hs_user_profiles as $mUPkey => $mUPval) {
        ?>
        <!-- user profile detail modal-->
        <div class="modal modal-info fade" id="upMdetail_<?php echo $mUPval['.id'];?>">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $mUPval['name'];?></h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                      <div class="col-md-5 col-sm-5 col-xs-12">ID</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo @$mUPval['.id'];?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5  col-sm-5 col-xs-12">Profil Name</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo @$mUPval['name'];?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5 col-sm-5 col-xs-12">Shared Users</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo @$mUPval['shared-users'];?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5 col-sm-5 col-xs-12">Address Pool</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo !empty($mUPval['address-pool'])?$mUPval['address-pool']:null;?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5 col-sm-5 col-xs-12">Rate Limit</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo !empty($mUPval['rate-limit'])?$mUPval['rate-limit']:null; ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                      <div class="col-md-5 col-sm-5 col-xs-12">Users Validity</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php
                          foreach ($validUntil as $vkey => $vval) {
                            if (ros_limit($vkey)==$mUPval['validity']) {
                              echo $vval;
                            }
                          }
                          ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5 col-sm-5 col-xs-12">Keepalive Timeout</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo ros_uptime($mUPval['keepalive-timeout']);?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5  col-sm-5 col-xs-12">Status Autorefresh</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo ros_uptime($mUPval['status-autorefresh']);?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5 col-sm-5 col-xs-12">Add MAC Cookie</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo $mUPval['add-mac-cookie'];?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5 col-sm-5 col-xs-12">Transparent Proxy</div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        : <?php echo $mUPval['transparent-proxy'];?>
                      </div>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="col-md-12 col-sm-12 col-xs-12">On Login:
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <textarea disabled class="form-control" rows="3"><?php echo !empty($mUPval['on-login'])?$mUPval['on-login']:null; ?></textarea>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="col-md-12 col-sm-12 col-xs-12">On Logout:
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <textarea disabled class="form-control" rows="3"><?php echo !empty($mUPval['on-logout'])?$mUPval['on-logout']:null; ?></textarea>
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
        <!--./user profile detail modal-->
        <!-- edit user profile modal-->
        <div class="modal modal-success fade" id="upMedit_<?php echo $mUPval['.id'];?>">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Editing Profile <?php echo $mUPval['name'];?></h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" id="formEdit<?php echo @$mUPval['.id'];?>">
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">ID</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input class="form-control input-sm" type="text" disabled value="<?php echo @$mUPval['.id'];?>">
                          <input type="hidden" name="id" value="<?php echo @$mUPval['.id'];?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Name</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input placeholder="Name" class="form-control input-sm" type="text" name="name" value="<?php echo @$mUPval['name'];?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Shared Users</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input class="form-control input-sm" placeholder="Shared Users" type="number" min="1" name="shared-users"  value="<?php echo @$mUPval['shared-users'];?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Keepalive Timeout</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input class="form-control input-sm" placeholder="Keepalive Timeout" type="time" step="1" name="keepalive-timeout"  value="<?php echo ros_uptime($mUPval['keepalive-timeout']);?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Users Validity</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <select onchange="updateScript(this)" name="users-validity"  class="form-control input-sm">
                            <?php
                            foreach ($validUntil as $vkey => $vval) {
                              ?>
                              <option value="<?php echo ros_limit($vkey);?>" <?php echo (ros_limit($vkey)==$mUPval['validity'])?'selected':null; ?> ><?php echo $vval; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Rate Limit</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <input type="text" name="rate-limit" class="form-control input-sm" placeholder="Rate Limit" value="<?php echo !empty($mUPval['rate-limit'])?$mUPval['rate-limit']:null; ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-5">Transparent Proxy</label>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <div class="radio">
                            <label>
                              <input type="radio" name="transparent-proxy" value="yes" <?php echo ($mUPval['transparent-proxy']=='true')?'checked':null;?>>Yes
                            </label>
                            <label>
                              <input type="radio" name="transparent-proxy" value="no" <?php echo ($mUPval['transparent-proxy']=='false')?'checked':null;?>>No
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="control-label col-md-12 col-sm-12 col-xs-12">On Login Script</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <textarea name="on-login" placeholder="On Login Script" class="form-control" rows="3"><?php echo !empty($mUPval['on-login'])?$mUPval['on-login']:null; ?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label class="control-label col-md-12 col-sm-12 col-xs-12">On Logout Script</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <textarea name="on-logout" placeholder="On Logout Script" class="form-control" rows="3"><?php echo !empty($mUPval['on-logout'])?$mUPval['on-logout']:null; ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
                <button type="button" onclick="editUProfile(this)" form="formEdit<?php echo @$mUPval['.id'];?>" class="btn btn-outline btn-success pull-right"><span class="fa fa-pencil"></span> Submit</button>
              </div>
            </div>
          </div>
        </div>
        <!--./edit user profile modal-->
        <!--delete user profile modal-->
        <div class="modal modal-danger fade" id="upMdelete_<?php echo $mUPval['.id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Delete <?php echo $mUPval['name'];?>?</h4>
              </div>
              <div class="modal-body">
                Are you sure to delete <?php echo $mUPval['name'];?> from server?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-primary pull-left" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
                <button type="button" data-delete-uid='<?php echo $mUPval['.id'];?>' onclick='delUProf(this);' class="btn btn-outline btn-danger btnDel"><span class="fa fa-trash"></span> Delete</button>
              </div>
            </div>
          </div>
        </div>
        <!--./delete user profile modal-->
        <?php
        }
        ?>
      </div>
      <!-- /.box -->
    </div>
    <!--/.col (left) -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<script>
  $(document).ready(function() {
    $('#userProfiles').DataTable({
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
    uProfileTable=$('#userProfiles').DataTable();
  });
  //update validity script
  function updateScript(validity) {
    var target1=$(validity).parents('form').find('textarea[name="on-login"]');
    var onlogin=target1.val();
    debug=target1;
    var findParam=new RegExp('\(\\/system scheduler add name="remove\\-user\\-\\$user" interval=")(\\d+d )?(\\d\\d):(\\d\\d):(\\d\\d)(" on\\-even=\"\\/ip hotspot active remove \\[find user=\\$user\\];\\/ip hotspot user remove \\[find name=\\$user\];\\/system scheduler remove \\[find name=remove\\-user\\-\\$user\\]\")');
    var newVal='/system scheduler add name="remove-user-$user" interval="'+$(validity).val()+'" on-even="/ip hotspot active remove [find user=$user];/ip hotspot user remove [find name=$user];/system scheduler remove [find name=remove-user-$user]"';
    if ($(validity).val()!=0) {
      if (onlogin.search(findParam)>-1) {
        var newOnlogin=onlogin.replace(findParam, newVal);
        target1.val(newOnlogin);
      }
      else{
        target1.val(onlogin+newVal);
      }
    }
    else{
      if (onlogin.search(findParam)>-1) {
        var newOnlogin=onlogin.replace(findParam,'');
        target1.val(newOnlogin);
      }
    }
  }
  //./update validity script
  //delete user profile
  function delUProf(dat) {
    $(dat).find('span').removeClass('fa-trash');
    $(dat).find('span').addClass('fa-refresh fa-spin');
    debug=dat;
    $.post('<?php echo base_url('ajax/deleteUProfile');?>', {id: $(dat).data('delete-uid')},
      function(deleted) {
        $('.modal').modal('hide');
        uProfileTable.row($('tr#row'+deleted.result.id)).remove().draw(false);
        $('modal').on('hidden.bs.modal',function() {
          $(dat).find('span').removeClass('fa-refresh fa-spin');
          $(dat).find('span').addClass('fa-trash');
          $('div#upMedit_'+deleted.result.id).remove();
          $('div#upMdetail_'+deleted.result.id).remove();
          $('div#upMdelete_'+deleted.result.id).remove();
        });
      },'json'
    );
  }
  //./delete user profile
  // edit user profile
  function editUProfile(editBtn) {
    $(editBtn).find('span').removeClass('fa-pencil');
    $(editBtn).find('span').addClass('fa-refresh fa-spin');
    var editForm=$('form#'+$(editBtn).attr('form'));
    editForm.submit(function(edit) {
      edit.preventDefault();
      $.ajax({
        url: '<?php echo base_url('ajax/editUProfile') ?>',
        type: 'POST',
        contentType:false,
        processData:false,
        dataType: 'json',
        data: new FormData(this),
      })
      .done(function(edRes) {
        debug=edRes;
        thisRow=uProfileTable.row($('tr#row'+edRes.result.id));
        thisRow.data(
          [thisRow.data()[0],
          thisRow.data()[1],
          edRes.result['name'],
          edRes.result['shared-users'],
          edRes.result['rate-limit']?edRes.result['rate-limit']:'',
          generateBtn(edRes.result.id,'hs_uprofile')]
        ).draw(false);
        $(editBtn).find('span').removeClass('fa-refresh fa-spin');
        $(editBtn).find('span').addClass('fa-check');
        $('.modal').modal('hide');
        $('.modal').on('hidden.bs.modal',function() {
          $(editBtn).find('span').removeClass('fa-check');
          $(editBtn).find('span').addClass('fa-pencil');
          $('div#upMedit_'+edRes.result.id).remove();
          $('div#upMdetail_'+edRes.result.id).remove();
          $('div#upMdelete_'+edRes.result.id).remove();
          $('#modals').append(generateModal(edRes,'hs_uprofile'));
        })
        
      })
      .fail(function() {
        console.log("error on view");
      })
    });
    editForm.submit();
  }
  //./edit user profile
  $(document).ready(function () {
    // add user profile
    $('#hs_userprofile_form').submit(function (d) {
        d.preventDefault();
        debug=this;
        var button=$(this).parents('div.modal').find('button[form='+$(this).attr('id')+']');
        var buttonspan=button.find('span');
        var inputs=$(this).find(':input');
        buttonspan.removeClass('fa-plus');
        buttonspan.addClass('fa-spin fa-refresh')
        
        $.ajax({
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          url: '<?php echo base_url('ajax/addUProfile');?>',
          type: 'POST',
          data:new FormData(this),
          success:function(addRes) {
            debug=addRes;
            buttonspan.removeClass('fa-spin fa-refresh');
            buttonspan.addClass('fa-check');

            //add table row
            //*
            uProfileTable.row.add([
              generateCheckbox(addRes.result.id,'hs_uprofile'),
              uProfileTable.rows().data().length+1,
              addRes.result.name,
              addRes.result['shared-users'],
              addRes.result['rate-limit']?addRes.result['rate-limit']:'',
              generateBtn(addRes.result.id,'hs_uprofile')
              ]).draw(false);
            uProfileTable.order(1,'asc').draw(false);
            uProfileTable.page('last').draw(false);
            $('td:contains('+addRes.result.name+')').parent().attr('id', 'row'+addRes.result.id);
            //*/
            //./add table row
            //add modal
            $('#modals').append(generateModal(addRes,'hs_uprofile'));
            //./add modal
            $('.modal').modal('hide');
            $('.modal').on('hidden.bs.modal', function() {
              buttonspan.removeClass('fa-check');
              buttonspan.addClass('fa-plus');
              $(inputs[0]).val('');
              $(inputs[1]).val(1);
              $(inputs[2]).val('00:02:00');
              $(inputs[3]).val(0);
              $(inputs[4]).val('');
              $(inputs[7]).val('');
              $(inputs[8]).val('');
            });
          },
          error:function(addErr,status,xhr) {
            console.log('err->');
            console.log(addErr);
            console.log(status);
            console.log(xhr);
          }
        });
        
      });
    //./add user profile
    //bulk action
    $('#bulk_act').change(function() {
      if ($(this).val()=='delete') {
        $('.modal#mBulkDelete').modal('show');$('.modal#mBulkDelete').modal('show');
      };
    });
  });
</script>