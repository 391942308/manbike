<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo ($title); ?> | 后台管理</title>

	<!-- Bootstrap -->
	<link href="/manbike0.3/Public/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="/manbike0.3/Public/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="/manbike0.3/Public/gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">

	<!-- Custom Theme Style -->
	<link href="/manbike0.3/Public/gentelella/build/css/custom.min.css" rel="stylesheet">
	<link id="artDialogSkin" href="/manbike0.3/Public/artDialog/skins/default.css" rel="stylesheet" type="text/css" />
</head>

<body class="nav-md">
<div class="container body">
	<div class="main_container">
		<div class="col-md-3 left_col">
			<div class="left_col scroll-view">
				<div class="navbar nav_title" style="border: 0;">
					<a href="index.html" class="site_title">
<!--						<i class="fa fa-paw"></i> -->
<img src="/manbike0.3/Public/images/bike_logo.png" style="width: 33px;margin-bottom: 5px;margin-left: 8px;"/>
						<!--<img src="/manbike0.3/Public/images/bike_logo.png" style="width: 15%;margin-bottom: 5px"/>-->
						<span style="font-size: smaller">共享单车泊位管理</span>
					</a>
				</div>

				<div class="clearfix"></div>

				<!-- menu profile quick info -->
				<div class="profile clearfix">
					<div class="profile_pic">
						<img src="/manbike0.3/Public/images/img.jpg" alt="..." class="img-circle profile_img">
					</div>
					<div class="profile_info">
						<span>欢迎您：</span>
						<h2><?php echo ($_SESSION['auth']['username']); ?></h2>
					</div>
				</div>
				<!-- /menu profile quick info -->

				<br />

				<!-- sidebar menu -->
				<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
					<div class="menu_section">
						<ul class="nav side-menu">
							<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><?php if(!empty($vo["title"])): ?><a><i class="<?php echo ($vo["icon"]); ?>"></i> <?php echo ($vo["title"]); ?><span class="fa fa-chevron-down"></span></a><?php endif; ?>
									<ul class="nav child_menu">
										<?php if(is_array($vo['_child'])): $i = 0; $__LIST__ = $vo['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i; if(strpos($voo['name'],'http')===0){ ?><li><a href="<?php echo ($voo["name"]); ?>"><?php echo ($voo["title"]); ?></a></li><?php
 }else{ ?><li><a href="<?php echo U($voo['name']);?>"><?php echo ($voo["title"]); ?></a></li><?php
 } endforeach; endif; else: echo "" ;endif; ?>
									</ul>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					</div>
				</div>
				<!-- /sidebar menu -->

				<!-- /menu footer buttons 
				<div class="sidebar-footer hidden-small">
					<a data-toggle="tooltip" data-placement="top" title="Settings">
						<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
					</a>
					<a data-toggle="tooltip" data-placement="top" title="FullScreen">
						<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
					</a>
					<a data-toggle="tooltip" data-placement="top" title="Lock">
						<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
					</a>
					<a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo U('Login/logout');?>">
						<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
					</a>
				</div>-->
				<!-- /menu footer buttons -->
			</div>
		</div>

		<!-- top navigation -->
		<div class="top_nav">
			<div class="nav_menu">
				<nav>
					<div class="nav toggle">
						<a id="menu_toggle"><i class="fa fa-bars"></i></a>
					</div>

					<ul class="nav navbar-nav navbar-right">
						<li class="">
							<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<img src="/manbike0.3/Public/images/img.jpg" alt=""><?php echo ($_SESSION['auth']['username']); ?>
								<span class=" fa fa-angle-down"></span>
							</a>
							<ul class="dropdown-menu dropdown-usermenu pull-right">
								<li><div style="height: 10px;"></div></li>
								<li><a href="<?php echo U('Login/logout');?>"><i class="fa fa-sign-out pull-right"></i> 退出</a></li>
							</ul>
						</li>

						<li role="presentation" class="dropdown">

							<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
								<li>
									<a>
										<span class="image"><img src="/manbike0.3/Public/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
									</a>
								</li>
								<li>
									<a>
										<span class="image"><img src="/manbike0.3/Public/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
									</a>
								</li>
								<li>
									<a>
										<span class="image"><img src="/manbike0.3/Public/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
									</a>
								</li>
								<li>
									<a>
										<span class="image"><img src="/manbike0.3/Public/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
									</a>
								</li>
								<li>
									<div class="text-center">
										<a>
											<strong>See All Alerts</strong>
											<i class="fa fa-angle-right"></i>
										</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<!-- /top navigation -->

		<!-- page content -->
		<div class="right_col" role="main">
			
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>设备管理&nbsp;&nbsp;&nbsp; <small>设备列表</small></h3>
                </div>

          
            </div>

            <div class="clearfix"></div>

            <div class="row">

                </div>

                <div class="clearfix"></div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        

                        <div class="x_content">

                            <p>
                                <a class="btn btn-primary" href="javascript:;" onclick="add()">添加设备</a>
                            </p>
                            <div style="margin-bottom: 10px" class="input-group">
                                <form method="get" action="<?php echo U('Admin/Device/index');?>">
                                    <input type="text" class="form-control" style="width: 150px" placeholder="类型" name="type" value="<?php echo ($type); ?>" id="type"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control" style="width: 150px" placeholder="mac地址" name="mac" value="<?php echo ($mac); ?>" id="mac"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control" style="width: 150px" placeholder="车位ID" name="info_id" value="<?php echo ($info_id); ?>" id="info_id"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="submit" value="查询" class="btn btn-default"/>
                                </form>
                            </div>


                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>

                                        </th>
                                        <th class="column-title">ID </th>
                                        <th class="column-title">时间 </th>
                                        <th class="column-title">类型 </th>
                                        <th class="column-title">mac地址 </th>
                                        <th class="column-title">内存 </th>
                                        <th class="column-title">负载 </th>
                                        <th class="column-title">车位ID </th>
                                        <th class="column-title no-link last"><span class="nobr">操作</span>
                                        </th>
                                        <th class="bulk-actions" colspan="7">
                                            <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php if(is_array($bparking_list)): $i = 0; $__LIST__ = $bparking_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="even pointer">
                                            <td class="a-center ">

                                            </td>
                                            <td class=" "><?php echo ($vo["id"]); ?></td>
                                            <td class=" "><?php echo ($vo["timestamp"]); ?> </td>
                                            <td class=" "><?php echo ($vo["type"]); ?> </td>
                                            <td class=" "><?php echo ($vo["mac"]); ?> </td>
                                            <td class=" "><?php echo ($vo["gatewayfree"]); ?> </td>
                                            <td class=" "><?php echo ($vo["gatewayload"]); ?> </td>
                                            <td class="a-right a-right "><?php echo ($vo["info_id"]); ?></td>
                                            <td class=" last">
                                                <a href="#">
                                                   <a href="javascript:;" navId="<?php echo ($vo['id']); ?>" navTimestamp="<?php echo ($vo['timestamp']); ?>" navType="<?php echo ($vo['type']); ?>" navMac="<?php echo ($vo['mac']); ?>" navGatewayfree="<?php echo ($vo['gatewayfree']); ?>" navGatewayload="<?php echo ($vo['gatewayload']); ?>" navInfoid="<?php echo ($vo['info_id']); ?>" onclick="edit(this)">修改</a>
                                                    | <a href="javascript:if(confirm('确定删除？'))location='<?php echo U('Admin/Device/delete',array('id'=>$vo['id']));?>'">删除</a>
                                                </a>
                                            </td>
                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                <?php echo ($show); ?>
            </div>
            </div>
        </div>
    <div class="modal fade" id="bjy-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel"> 添加设备</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="<?php echo U('Admin/Device/add');?>" method="post">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">时间：</th>
                                <td>
                                    <input class="input-medium" type="text" name="timestamp">
                                </td>
                            </tr>
                           <tr>
                                <th>类型：</th>
                               <td>
                                    <input class="input-medium" type="text" name="type">
                                </td>
                            </tr>
                            <tr>
                                <th>mac地址：</th>
                                <td>
                                   <input class="input-medium" type="text" name="mac">
                               </td>
                            </tr>
                            <tr>
                                <th>内存：</th>
                                <td>
                                    <input class="input-medium" type="text" name="gatewayFree">
                                </td>
                            </tr>
                           <tr>
                                <th>负载：</th>
                                <td>
                                    <input class="input-medium" type="text" name="gatewayLoad">
                                </td>
                            </tr>
                            <tr>
                                <th>车位ID：</th>
                                <td>
                                    <input class="input-medium" type="text" name="info_id">
                                </td>
                           </tr>
                            <tr>
                                <th></th>
                               <td>
                                    <input class="btn btn-success" type="submit" value="添加">
                                </td>
                        </table>
                    </form>
                </div>
            </div>
       </div>
    </div>
    <div class="modal fade" id="bjy-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                    <h4 class="modal-title" id="myModalLabel"> 修改设备</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="<?php echo U('Admin/Device/edit');?>" method="post">
                        <input type="hidden" name="id">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">时间：</th>
                                <td>
                                    <input class="input-medium" type="text" name="timestamp">
                                </td>
                            </tr>
                            <tr>
                                <th>类型：</th>
                                <td>
                                    <input class="input-medium" type="text" name="type">
                                </td>
                            </tr>
                            <tr>
                                <th>mac地址：</th>
                                <td>
                                    <input class="input-medium" type="text" name="mac">
                                </td>
                            </tr>
                            <tr>
                                <th>内存：</th>
                                <td>
                                    <input class="input-medium" type="text" name="gatewayFree">
                                </td>
                            </tr>
                            <tr>
                                <th>负载：</th>
                                <td>
                                    <input class="input-medium" type="text" name="gatewayLoad">
                                </td>
                            </tr>
                            <tr>
                                <th>车位ID：</th>
                                <td>
                                    <input class="input-medium" type="text" name="info_id">
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input class="btn btn-success" type="submit" value="修改">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <script type="text/javascript">
        // 添加菜单
        function add(){
            //$("input[name='title'],input[name='mca']").val('');
           // $("input[name='pid']").val(0);
            $('#bjy-add').modal('show');
        }
//        function add2(){
//                art.dialog.open('http://localhost/manbike0.3/index.php/Admin/Bparking/iframeA.html', {title: '提示'});
//
//        }
        // 修改菜单
        function edit(obj){
            var navId=$(obj).attr('navId');
            var navTimestamp=$(obj).attr('navTimestamp');
            var navType=$(obj).attr('navType');
            var navMac=$(obj).attr('navMac');
            var navGatewayfree=$(obj).attr('navGatewayfree');
            var navGatewayload=$(obj).attr('navGatewayload');
            var navInfoid=$(obj).attr('navInfoid');
            $("input[name='id']").val(navId);
            $("input[name='timestamp']").val(navTimestamp);
            $("input[name='type']").val(navType);
            $("input[name='mac']").val(navMac);
            $("input[name='gatewayFree']").val(navGatewayfree);
            $("input[name='gatewayLoad']").val(navGatewayload);
            $("input[name='info_id']").val(navInfoid);
            $('#bjy-edit').modal('show');
        }
    </script>
    <script>
        $("#datatable_paginate a").click(function(){
            var href = $(this).attr("href");
            // $(this).attr("href","#");
            var type = $("#type").val();
            var mac = $("#mac").val();
            var info_id = $("#info_id").val();
            if(type !== ''){
                href = href + '&type='+type;
            }
            if(mac !== ''){
                href = href + '&mac='+mac;
            }
            if(info_id !== ''){
                href = href + '&info_id='+info_id;
            }
            window.location.href=href;
            return false;
        });
    </script>
    <!-- /page content -->

		</div>
		<!-- /page content -->

		<!-- footer content -->
		<footer>
			<div class="pull-right">
				共享单车泊位管理 powered by <a href="#">云岛科技</a>
			</div>
			<div class="clearfix"></div>
		</footer>
		<!-- /footer content -->
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
<!-- validator -->
<script src="/manbike0.3/Public/gentelella/vendors/validator/validator.js"></script>
<script type="text/JavaScript" src="/manbike0.3/Public/artDialog/artDialog.js"></script>
<script type="text/javascript" src="/manbike0.3/Public/artDialog/plugins/iframeTools.js"></script> <!-- 对iframe的新工具 -->

<!-- Custom Theme Scripts -->
<script src="/manbike0.3/Public/gentelella/build/js/custom.min.js"></script>
<script type="text/javascript">
	$(function(){
		$(".m_child_menu").css('display','none');
	});
</script>
</body>
</html>