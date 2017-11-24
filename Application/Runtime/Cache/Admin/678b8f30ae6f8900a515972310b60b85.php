<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>后台管理 | </title>

	<!-- Bootstrap -->
	<link href="/Public/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="/Public/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="/Public/gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">

	<!-- Custom Theme Style -->
	<link href="/Public/gentelella/build/css/custom.min.css" rel="stylesheet">
	<link id="artDialogSkin" href="/Public/artDialog/skins/default.css" rel="stylesheet" type="text/css" />
</head>

<body class="nav-md">
<div class="container body">
	<div class="main_container">
		<div class="col-md-3 left_col">
			<div class="left_col scroll-view">
				<div class="navbar nav_title" style="border: 0;">
					<a href="index.html" class="site_title">
<!--						<i class="fa fa-bicycle"></i>-->
<!--						<i class="fa fa-paw"></i>-->
						<img src="/Public/images/bike_logo.png" style="width: 33px;margin-bottom: 5px;margin-left: 5px;"/>
						<span style="font-size: smaller">共享单车泊位管理</span>
					</a>
				</div>

				<div class="clearfix"></div>

				<!-- menu profile quick info -->
				<div class="profile clearfix">
					<div class="profile_pic">
						<img src="/Public/images/img.jpg" alt="..." class="img-circle profile_img">
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

				<!-- /menu footer buttons -->
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
				</div>
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
								<img src="/Public/images/img.jpg" alt=""><?php echo ($_SESSION['auth']['username']); ?>
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
										<span class="image"><img src="/Public/images/img.jpg" alt="Profile Image" /></span>
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
										<span class="image"><img src="/Public/images/img.jpg" alt="Profile Image" /></span>
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
										<span class="image"><img src="/Public/images/img.jpg" alt="Profile Image" /></span>
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
										<span class="image"><img src="/Public/images/img.jpg" alt="Profile Image" /></span>
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
        <div class="title_left">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-bicycle"></i> 所有停放车辆数 </span>
                <div class="count" style="font-size: 40px;line-height: 47px;font-weight: 600;"><?php echo ($sum); ?></div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">

        </div>

        <div class="clearfix"></div>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a>
                                </li>
                                <li><a href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">

                    <p>
                        <!--                                <a class="btn btn-primary" href="javascript:;" onclick="add()">添加车位</a>-->
                    </p>
                    <!--                            <div style="margin-bottom: 10px" class="input-group">-->
                    <!--                                <form method="get" action="<?php echo U('Admin/Bparking/index');?>">-->
                    <!--                                    <input type="text" class="form-control" style="width: 150px" placeholder="车位名称" name="title" value="<?php echo ($title); ?>" id="title"/>&nbsp;&nbsp;&nbsp;&nbsp;-->
                    <!--                                    <input type="text" class="form-control" style="width: 150px" placeholder="可容纳车辆" name="usable_num" value="<?php echo ($usable_num); ?>" id="usable_num"/>&nbsp;&nbsp;&nbsp;&nbsp;-->
                    <!--                                    <input type="text" class="form-control" style="width: 150px" placeholder="已存储车辆" name="storage_num" value="<?php echo ($storage_num); ?>" id="storage_num"/>&nbsp;&nbsp;&nbsp;&nbsp;-->
                    <!--                                    <input type="text" class="form-control" style="width: 150px" placeholder="车位编号" name="no" value="<?php echo ($no); ?>" id="no"/>&nbsp;&nbsp;&nbsp;&nbsp;-->
                    <!--                                    <input type="submit" value="查询" class="btn btn-default"/>-->
                    <!--                                </form>-->
                    <!--                            </div>-->


                    <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                            <tr class="headings">
                                <th>

                                </th>
                                <th class="column-title">车辆mac地址 </th>
                                <th class="column-title">车位ID</th>
                                <th class="column-title">采集到的次数 </th>
                                <th class="column-title no-link last"><span class="nobr">操作</span>
                                </th>
                                <th class="bulk-actions" colspan="7">
                                    <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php if(is_array($arr_all)): $i = 0; $__LIST__ = $arr_all;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="even pointer">
                                    <td class="a-center ">

                                    </td>
                                    <td class=" "><?php echo ($vo["mac"]); ?></td>
                                    <td class=" "><?php echo ($vo["id"]); ?></td>
                                    <td class=" "><?php echo ($vo["num"]); ?></td>
                                    <td class=" last">
                                        <a href="#">
                                            <!--                                                    <a href="<?php echo U('Admin/Bparking/index',array('id'=>$vo['dwz_info_id'],'mac'=>$vo['mac']));?>">删除</a>-->
                                            <a href="http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikedetail/id/<?php echo ($vo["id"]); ?>/mac/<?php echo ($vo["mac"]); ?>">查看详情</a>
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
                    <h4 class="modal-title" id="myModalLabel"> 添加车位</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="<?php echo U('Admin/Bparking/add');?>" method="post">
                        <input type="hidden" name="pid" value="0">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">车位名称：</th>
                                <td>
                                    <input class="input-medium" type="text" name="title">
                                </td>
                            </tr>
                            <tr>
                                <th>可容纳车辆：</th>
                                <td>
                                    <input class="input-medium" type="text" name="usable_num">
                                </td>
                            </tr>
                            <tr>
                                <th>已存储车辆：</th>
                                <td>
                                    <input class="input-medium" type="text" name="storage_num">
                                </td>
                            </tr>
                            <tr>
                                <th>溢出车辆数量：</th>
                                <td>
                                    <input class="input-medium" type="text" name="overflow_num">
                                </td>
                            </tr>
                            <tr>
                                <th>车位编号：</th>
                                <td>
                                    <input class="input-medium" type="text" name="no">
                                </td>
                            </tr>
                            <tr>
                                <th>区域编号：</th>
                                <td>
                                    <input class="input-medium" type="text" name="block_no">
                                </td>
                            </tr>
                            <tr>
                                <th>经纬度：</th>
                                <td>
                                    <input id="aInput" name="lng_lat" value="拖动鼠标获取经纬度">
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input class="btn btn-success" type="submit" value="添加">
                                </td>
                        </table>
                    </form>
                    <div style="width:400px; height:300px; position: absolute; left: 330px; top:230px;">
                        <button id="aButton">点击获取经纬度</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bjy-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                    <h4 class="modal-title" id="myModalLabel"> 修改车位</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="<?php echo U('Admin/Bparking/edit');?>" method="post">
                        <input type="hidden" name="id">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">车位名称：</th>
                                <td>
                                    <input class="input-medium" type="text" name="title">
                                </td>
                            </tr>
                            <tr>
                                <th>可容纳车辆：</th>
                                <td>
                                    <input class="input-medium" type="text" name="usable_num">
                                </td>
                            </tr>
                            <tr>
                                <th>已存储车辆：</th>
                                <td>
                                    <input class="input-medium" type="text" name="storage_num">
                                </td>
                            </tr>
                            <tr>
                                <th>溢出车辆数量：</th>
                                <td>
                                    <input class="input-medium" type="text" name="overflow_num">
                                </td>
                            </tr>
                            <tr>
                                <th>车位编号：</th>
                                <td>
                                    <input class="input-medium" type="text" name="no">
                                </td>
                            </tr>
                            <tr>
                                <th>区域编号：</th>
                                <td>
                                    <input class="input-medium" type="text" name="block_no">
                                </td>
                            </tr>
                            <tr>
                                <th>经纬度：</th>
                                <td>
                                    <input id="aInput2" name="lng_lat" value="拖动鼠标获取经纬度">
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
                    <div style="width:400px; height:300px; position: absolute; left: 330px; top:230px;">
                        <button id="aButton2">点击获取经纬度</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // 传递给B页面
        document.getElementById('aButton').onclick = function () {
            var aValue = document.getElementById('aInput').value;
            art.dialog.data('aValue', aValue);// 存储数据

            var path = art.dialog.data('homeDemoPath') || './';//

            art.dialog.open(path + 'iframeB.html?fd', {
                id: 'AAA',
                width:500,
                left:900,
                close: function () {
                    var bValue = art.dialog.data('bValue');// 读取B页面的数据
                    if (bValue !== undefined) document.getElementById('aInput').value = bValue;
                }
            }, false);
        };
        // 传递给B页面
        document.getElementById('aButton2').onclick = function () {
            var aValue = document.getElementById('aInput2').value;
            art.dialog.data('aValue', aValue);// 存储数据

            var path = art.dialog.data('homeDemoPath') || './';//

            art.dialog.open(path + 'iframeB.html?fd', {
                id: 'AAA',
                width:500,
                left:900,
                close: function () {
                    var bValue = art.dialog.data('bValue');// 读取B页面的数据
                    if (bValue !== undefined) document.getElementById('aInput2').value = bValue;
                }
            }, false);
        };
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
        // 添加子菜单
        function add_child(obj){
            var navId=$(obj).attr('navId');
            $("input[name='pid']").val(navId);
            $("input[name='title']").val('');
            $("input[name='mca']").val('');
            $("input[name='icon']").val('');
            $('#bjy-add').modal('show');
        }
        //   navOverflow_num="<?php echo ($vo['Overflow_num']); ?>" navNo="<?php echo ($vo['No']); ?>" navBlock_no="<?php echo ($vo['Block_no']); ?>"
        // 修改菜单
        function edit(obj){
            var navId=$(obj).attr('navId');
            var navTitle=$(obj).attr('navTitle');
            var navUsable_num=$(obj).attr('navUsable_num');
            var navStorage_num=$(obj).attr('navStorage_num');
            var navOverflow_num=$(obj).attr('navOverflow_num');
            var navNo=$(obj).attr('navNo');
            var navBlock_no=$(obj).attr('navBlock_no');
            var navLng_Lat=$(obj).attr('navLng_Lat');
            $("input[name='id']").val(navId);
            $("input[name='title']").val(navTitle);
            $("input[name='usable_num']").val(navUsable_num);
            $("input[name='storage_num']").val(navStorage_num);
            $("input[name='overflow_num']").val(navOverflow_num);
            $("input[name='no']").val(navNo);
            $("input[name='block_no']").val(navBlock_no);
            $("input[name='lng_lat']").val(navLng_Lat);
            $('#bjy-edit').modal('show');
        }
    </script>
    <script>
        $("#datatable_paginate a").click(function(){
            var href = $(this).attr("href");
            // $(this).attr("href","#");
            var title = $("#title").val();
            var usable_num = $("#usable_num").val();
            var storage_num = $("#storage_num").val();
            var no = $("#no").val();
            if(title !== ''){
                href = href + '&title='+title;
            }
            if(usable_num !== ''){
                href = href + '&usable_num='+usable_num;
            }
            if(storage_num !== ''){
                href = href + '&storage_num='+storage_num;
            }
            if(no !== ''){
                href = href + '&no='+no;
            }
            //alert(href);
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
				共享单车泊位管理 powered by 云岛科技
			</div>
			<div class="clearfix"></div>
		</footer>
		<!-- /footer content -->
	</div>
</div>
<!-- jQuery -->
<script src="/Public/gentelella/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="/Public/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="/Public/gentelella/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="/Public/gentelella/vendors/nprogress/nprogress.js"></script>
<!-- validator -->
<script src="/Public/gentelella/vendors/validator/validator.js"></script>
<script type="text/JavaScript" src="/Public/artDialog/artDialog.js"></script>
<script type="text/javascript" src="/Public/artDialog/plugins/iframeTools.js"></script> <!-- 对iframe的新工具 -->

<!-- Custom Theme Scripts -->
<script src="/Public/gentelella/build/js/custom.min.js"></script>
<script type="text/javascript">
	$(function(){
		$(".m_child_menu").css('display','none');
	});
</script>
</body>
</html>