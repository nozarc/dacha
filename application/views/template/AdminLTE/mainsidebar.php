<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="<?php echo $_tpath; ?>/dist/img/avatar5.png" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo $sess['routeros_data']['username']; ?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $sess['sess_remote_ip']; ?></a>
    </div>
  </div>
  <!-- search form 
  <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
    </div>
  </form>
   /.search form -->
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>
    <li class="<?php echo !empty($sidebar['parent']['dashboard'])?$sidebar['parent']['dashboard']:null; ?>" ><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
    <li class="treeview  <?php echo !empty($sidebar['parent']['hotspot'])?$sidebar['parent']['hotspot']:null; ?>">
      <a href="#">
        <i class="fa fa-wifi"></i> <span>Hotspot</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="<?php echo !empty($sidebar['child']['user'])?$sidebar['child']['user']:null; ?>"><a href="<?php echo base_url('hotspot/user'); ?>"><i class="fa fa-circle-o"></i> <span>User</span></a></li>
        <li class="<?php echo !empty($sidebar['child']['user_profile'])?$sidebar['child']['user_profile']:null; ?>"><a href="<?php echo base_url('hotspot/user'); ?>"><i class="fa fa-circle-o"></i> <span>User Profile</span><span class="label pull-right bg-green">Coming Soon</span></a></li>
        <li class="<?php echo !empty($sidebar['child']['active_users'])?$sidebar['child']['active_users']:null; ?>"><a href="<?php echo base_url('hotspot/user'); ?>"><i class="fa fa-circle-o"></i> <span>Active User</span><span class="label pull-right bg-green">Coming Soon</span></a></li>
      </ul>
    </li>
    <li><a href="#"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
  </ul>
</section>
<!-- /.sidebar -->