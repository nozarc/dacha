    <section class="content-header">
      <h1>
        Dashboard
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
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 id="uptime"><?php echo ros_uptime($resource['uptime']); ?></h3>

              <p>Uptime</p>
            </div>
            <div class="icon">
              <i class="fa fa-clock-o"></i>
            </div>
            <a href="#" class="small-box-footer">&nbsp;</a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><span id="memory"><?php echo 100-floor($resource['free-memory']/$resource['total-memory']*100);?></span><sup style="font-size: 20px">%</sup></h3>

              <p>Used Memory</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="#" class="small-box-footer">&nbsp;</a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo count($active_users); ?></h3>

              <p>Active Users</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="<?php echo base_url('hotspot/user/active');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $clock['time']; ?></h3>
              <p><?php echo $clock['date']; ?></p>
            </div>
            <div class="icon">
              <i class="glyphicon glyphicon-time"></i>
            </div>
            <a href="#" class="small-box-footer">Router Time</a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 col-md-12 col-xs-12 col-sm-12 connectedSortable">
          <div class="box box-warning">
            <div class="box-header with-border">
              <span class="fa fa-gears"></span>
              <h3 class="box-title">CPU Load</h3>
              <div class="box-tools pull-right">
                Real time
                <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                  <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button>
                  <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div id="cpuLoad" style="height: 300px;"></div>
            </div>
            <div class="box-footer">
              
            </div>
          </div>
        </section>
        <!-- /.Left col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
<script>
  $(document).ready(function() {
    /*
     * Flot Interactive Chart
     * -----------------------
     */
    // We use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [], totalPoints = 50,y=0
    var free_memory=0,total_memory=0
    debug=[];
    function getResData() {

      if (data.length > 0)
        data = data.slice(1)

      // Do a random walk
      while (data.length < totalPoints) {
        $.getJSON('<?php echo base_url('ajax/resource')?>', function(getRes) {
          y = Number(getRes.resource.cpu);
          free_memory=Number(getRes.resource.free_memory);
          total_memory=Number(getRes.resource.total_memory);
        });
        if (y < 0) {
          y = 0
        } else if (y > 100) {
          y = 100
        }

        data.push(y)
      }

      // Zip the generated y values with the x values
      var res = []
      for (var i = 0; i < data.length; ++i) {
        res.push([i, data[i]])
      }

      return res
    }

    var cpuLoad_plot = $.plot('#cpuLoad', [getResData()], {
      grid  : {
        borderColor: '#f3f3f3',
        borderWidth: 1,
        tickColor  : '#f3f3f3'
      },
      series: {
        shadowSize: 0, // Drawing is faster without shadows
        color     : '#3c8dbc'
      },
      lines : {
        fill : true, //Converts the line chart to area chart
        color: '#3c8dbc'
      },
      yaxis : {
        min : 0,
        max : 100,
        show: true
      },
      xaxis : {
        show: false
      }
    });
    function memoryPercentage() {
      var memPercentage=100-Math.floor(free_memory/total_memory*100);
      $('#memory').text(memPercentage);
      //console.log(memPercentage);
    }
    var updateInterval = 2000 //Fetch data ever x milliseconds
    var realtime       = 'on' //If == to on then fetch data every x seconds. else stop fetching
    function update() {

      cpuLoad_plot.setData([getResData()]);

      // Since the axes don't change, we don't need to call plot.setupGrid()
      cpuLoad_plot.draw();
      memoryPercentage();
      if (realtime === 'on')
        setTimeout(update, updateInterval)
    }

    //INITIALIZE REALTIME DATA FETCHING
    if (realtime === 'on') {
      update()
    }
    //REALTIME TOGGLE
    $('#realtime .btn').click(function () {
      if ($(this).data('toggle') === 'on') {
        realtime = 'on'
      }
      else {
        realtime = 'off'
      }
      update()
    })
    /*
     * END INTERACTIVE CHART
     */

  }); 
</script>