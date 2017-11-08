<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>历史消息列表--车辆实时调度系统v0.2</title>

    <!-- Bootstrap -->
    <link href="/manbike0.3/Public/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <!-- Datatables -->
    <link href="/manbike0.3/Public/gentelella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Theme Style -->
    <link href="/manbike0.3/Public/gentelella/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">



        <!-- page content -->
        <div class="right_col" role="main" style="margin-left:0px;min-height: 0px;">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>历史消息列表 <small></small></h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                   
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>

                        <tr>
                          <th>编号</th>
                          <th>消息内容</th>
                          <th>创建时间</th>
                        </tr>
                      </thead>


                      <tbody>
					  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                          <td><?php echo ($vo["id"]); ?></td>
                          <td><?php echo ($vo["content"]); ?></td>
                          <td><?php echo ($vo["time"]); ?></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>  
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>


              
            </div>
          </div>
        </div>
        <!-- /page content -->

      </div>
    </div>

    <!-- jQuery -->
    <script src="/manbike0.3/Public/gentelella/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/manbike0.3/Public/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/manbike0.3/Public/gentelella/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="/manbike0.3/Public/gentelella/vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="/manbike0.3/Public/gentelella/vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/jszip/dist/jszip.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="/manbike0.3/Public/gentelella/build/js/custom.min.js"></script>

  </body>
</html>