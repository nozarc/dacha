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
          <h3 class="box-title">List of Hotspot Active Users</h3>
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
                    <th>Server</th>
                    <th>User</th>
                    <th>Address</th>
                    <th>Uptime</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $no=0;
                foreach ($hs_active_users as $AUkey => $AUval) {
                  ?>
                  <tr id='row<?php echo $AUval['.id'];?>'>
                    <td><input type="checkbox" name="activeU[]" value="<?php echo $AUval['.id']; ?>"></td>
                    <td><?php echo $no+1; ?></td>
                    <td><?php echo $AUval['server']; ?></td>
                    <td><?php echo $AUval['user']; ?></td>
                    <td><?php echo $AUval['address']; ?></td>
                    <td><?php echo ros_uptime($AUval['uptime']); ?></td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-xs btn-danger" data-toggle='modal' data-target='#auMremove_<?php echo $AUval['.id'];?>' title='Delete'  type="button"><span class="fa fa-trash"></span></button>
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
                  <option value="delete" >Remove</option>
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
                Are you sure to remove selected user?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-primary pull-left" data-dismiss='modal'><span class="fa fa-times"></span> Cancel</button>
                <button type="submit" form="hs_uprofile_bulk" class="btn btn-outline btn-danger pull-right"><span class="fa fa-trash"></span> Remove</button>
              </div>
            </div>
          </div>
        </div>
        
        <?php
        foreach ($hs_active_users as $mAUkey => $mAUval) {
        ?>
        <!--remove active user modal-->
        <div class="modal modal-danger fade" id="auMremove_<?php echo $mAUval['.id'];?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Remove <?php echo $mAUval['user'];?>?</h4>
              </div>
              <div class="modal-body">
                Are you sure to remove <?php echo $mAUval['user'];?> from server?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-primary pull-left" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
                <button type="button" data-delete-uid='<?php echo $mAUval['.id'];?>' onclick='removeActvUser(this);' class="btn btn-outline btn-danger btnDel"><span class="fa fa-trash"></span> Delete</button>
              </div>
            </div>
          </div>
        </div>
        <!--./remove active user modal-->
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
  //remove active user
  function removeActvUser(dat) {
    $(dat).find('span').removeClass('fa-trash');
    $(dat).find('span').addClass('fa-refresh fa-spin');
    debug=dat;
    $.post('<?php echo base_url('ajax/removeActvUser');?>', {id: $(dat).data('delete-uid')},
      function(removed) {
        $('.modal').modal('hide');
        uProfileTable.row($('tr#row'+removed.result.id)).remove().draw(false);
        $('modal').on('hidden.bs.modal',function() {
          $(dat).find('span').removeClass('fa-refresh fa-spin');
          $(dat).find('span').addClass('fa-trash');
          $('div#auMremove_'+removed.result.id).remove();
        })
      },'json'
    );
  }
  //./remove active user
  $(document).ready(function () {
    //bulk action
    $('#bulk_act').change(function() {
      if ($(this).val()=='delete') {
        $('.modal#mBulkDelete').modal('show');$('.modal#mBulkDelete').modal('show');
      };
    });
  });
</script>