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
    <script src="/Public/JS/distpicker.js"></script>
    <!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>车位设置&nbsp;&nbsp;&nbsp; <small>车位列表</small></h3>
					<img src="http://116.62.171.54:8080/manbike0.3/Public/myimages/green.png" />&nbsp;<span>正常状态</span><br />
					<img src="http://116.62.171.54:8080/manbike0.3/Public/myimages/light.png" />&nbsp;<span>通知阈值：当车位数量小于该值时为通知状态</span><br />
					<img src="http://116.62.171.54:8080/manbike0.3/Public/myimages/yellow.png" />&nbsp;<span>紧急阈值：当车位数量大于该值时为紧急状态</span><br />
					<img src="http://116.62.171.54:8080/manbike0.3/Public/myimages/red.png" />&nbsp;<span>报警阈值：当车位数量大于该值时为报警状态</span><br />
					<span>当阈值设置为0时为不启用,使用默认配置</span>
                </div>
<!--
                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                        </div>
                    </div>
                </div>-->
            </div>

            <div class="clearfix"></div>

            <div class="row">

                </div>

                <div class="clearfix"></div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <!--<div class="x_title">
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
                        </div>-->

                        <div class="x_content">

                            <p>
                                <a class="btn btn-primary" href="javascript:;" onclick="add()">添加车位</a>
                            </p>
                            <div style="margin-bottom: 10px" class="input-group">
                                <form method="get" action="<?php echo U('Admin/Bparking/index');?>">
                                    <input type="text" class="form-control" style="width: 150px" placeholder="车位编号" name="no" value="<?php echo ($no); ?>" id="no"/>&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="text" class="form-control" style="width: 150px" placeholder="车位名称" name="title" value="<?php echo ($title); ?>" id="title"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="hidden" class="form-control" style="width: 150px" placeholder="可容纳车辆" name="usable_num" value="<?php echo ($usable_num); ?>" id="usable_num"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="hidden" class="form-control" style="width: 150px" placeholder="已存储车辆" name="storage_num" value="<?php echo ($storage_num); ?>" id="storage_num"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="submit" value="查询" class="btn btn-default"/>
                                </form>
								
                            </div>


                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>

                                        </th>
										<th class="column-title">运行情况 </th>
                                        <th class="column-title">ID </th>
                                        <th class="column-title">车位名称 </th>
                                        <th class="column-title">可容纳车辆 </th>
                                        <th class="column-title">通知阈值</th>
                                        <th class="column-title">紧急阈值</th>
                                        <th class="column-title">报警阈值</th>
                                       <!-- <th class="column-title">车位编号 </th>-->
                                        <th class="column-title">省</th>
                                        <th class="column-title">市</th>
                                        <th class="column-title">区</th>
                                        <th class="column-title">经度 </th>
                                        <th class="column-title">纬度 </th>
										<th class="column-title">状态</th>
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
                                            <td class=" "> <i id="i<?php echo ($vo["id"]); ?>" class="fa fa-circle"  aria-hidden="true"></i>
                                            </td>
                                            <td class=" "><a href="http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikesexist_es1/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["id"]); ?></a></td>
                                            <td class=" "><a href="http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikesall1/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></td>
                                            <td class=" "><?php echo ($vo["usable_num"]); ?> </td>
                                            <td class=" "><?php echo ($vo["la"]); ?> </td>
                                            <td class=" "><?php echo ($vo["lb"]); ?> </td>
                                            <td class=" "><?php echo ($vo["lc"]); ?> </td>
                                           <!-- <td class=" "><?php echo ($vo["no"]); ?> </td>-->
                                            <td class="a-right a-right "><?php echo ($vo["province"]); ?></td>
                                            <td class="a-right a-right "><?php echo ($vo["city"]); ?></td>
                                            <td class="a-right a-right "><?php echo ($vo["area"]); ?></td>
                                            <td class="a-right a-right "><?php echo ($vo["lng"]); ?></td>
                                            <td class="a-right a-right "><?php echo ($vo["lat"]); ?></td>
											<td class="a-right a-right "><span id="cs<?php echo ($vo["id"]); ?>" data-id="<?php echo ($vo["id"]); ?>" data-status="<?php echo ($vo["status"]); ?>" class="cstatus"><?php if($vo["status"] == 1 ): ?>隐藏中<?php else: ?> 显示中<?php endif; ?></span></td>
                                            <td class=" last">
                                                <a href="#">
                                                   <a href="javascript:;" navId="<?php echo ($vo['id']); ?>" navTitle="<?php echo ($vo['title']); ?>" navUsable_num="<?php echo ($vo['usable_num']); ?>" navStorage_num="<?php echo ($vo['storage_num']); ?>" navOverflow_num="<?php echo ($vo['overflow_num']); ?>" navNo="<?php echo ($vo['no']); ?>" navBlock_no="<?php echo ($vo['block_no']); ?>" navLng_Lat="<?php echo ($vo['lng']); ?>,<?php echo ($vo['lat']); ?>" navStatus="<?php echo ($vo['status']); ?>" la="<?php echo ($vo['la']); ?>" lb="<?php echo ($vo['lb']); ?>" lc="<?php echo ($vo['lc']); ?>" onclick="edit(this)">修改</a>
                                                    | <a href="javascript:if(confirm('确定删除？'))location='<?php echo U('Admin/Bparking/delete',array('id'=>$vo['id']));?>'">删除</a>
                                                    | <a id="<?php echo ($vo['id']); ?>" class="xiangqing" href="javascript:void(0);">实时停放</a>
                                                    | <a href="http://localhost/index.php/Admin/Bparking/infobikesall1/id/<?php echo ($vo["id"]); ?>">历史总停放</a>
													<?php if($uid == '1'): ?>| <a href="<?php echo U('Admin/Bparking/fssj',array('id'=>$vo['id']));?>">变化趋势和实时数据</a><?php endif; ?>
													<?php if($uid == '1'): ?>| <a href="<?php echo U('Admin/Bparking/history',array('id'=>$vo['id']));?>">总量变化趋势</a><?php endif; ?>
                                                   <!-- | <a id="<?php echo ($vo['id']); ?>" class="trend" href="javascript:void(0);">变化趋势</a>
                                                    | <a id="<?php echo ($vo['id']); ?>" class="fenxi" href="javascript:void(0);">分时数据</a>
                                                    | <a id="<?php echo ($vo['id']); ?>" class="liudong" href="javascript:void(0);">流动情况</a>-->
                                                    | <a id="<?php echo ($vo['id']); ?>" class="clear" href="javascript:void(0);">清除数据</a>
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
								<th>
								区域：
								</th>
								<td data-toggle="distpicker" data-autoselect="3" data-province="浙江省">
									<select name="province" class="form-control"></select>
									<select name="city" class="form-control"></select>
									<select name="area" class="form-control"></select>
								</td>	
							</tr>
							<tr>
                                <th>状态（0正常）：</th>
                                <td>
                                    <input class="input-medium" type="text" name="status">
                                </td>
                            </tr>
							<tr>
                                <th>通知阈值：</th>
                                <td>
                                    <input class="input-medium" type="text" name="la">
                                </td>
                            </tr>
							<tr>
                                <th>紧急阈值：</th>
                                <td>
                                    <input class="input-medium" type="text" name="lb">
                                </td>
                            </tr>
							<tr>
                                <th>报警阈值：</th>
                                <td>
                                    <input class="input-medium" type="text" name="lc">
                                </td>
                            </tr>
							<tr>
                                <th></th>
                               <td>
                                    <input class="btn btn-success" type="submit" value="添加">
                                </td>
							</tr>
                        </table>
						
						
						
                    </form>
                    <div style="width:400px; height:30px; position: absolute; left: 330px; top:230px;">
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
								<th>
								区域：
								</th>
								<td data-toggle="distpicker" data-autoselect="3" data-province="浙江省" >
									<select name="province" class="form-control" value=""></select>
									<select name="city" class="form-control" value=""></select>
									<select name="area" class="form-control" value=""></select>
								</td>	
							</tr>
							<tr>
                                <th>状态（0正常）：</th>
                                <td>
                                    <input class="input-medium" type="text" name="status">
                                </td>
                            </tr>
							<tr>
                                <th>通知阈值：</th>
                                <td>
                                    <input class="input-medium" type="text" name="la">
                                </td>
                            </tr>
							<tr>
                                <th>紧急阈值：</th>
                                <td>
                                    <input class="input-medium" type="text" name="lb">
                                </td>
                            </tr>
							<tr>
                                <th>报警阈值：</th>
                                <td>
                                    <input class="input-medium" type="text" name="lc">
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
                    <div style="width:400px; height:30px; position: absolute; left: 330px; top:230px;">
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

                var path = art.dialog.data('homeDemoPath') || 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bike/';

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

                //var path = art.dialog.data('homeDemoPath') || './';//

				var path = art.dialog.data('homeDemoPath') || 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bike/';

				
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
			var navStatus=$(obj).attr('navStatus');
			var la=$(obj).attr('la');
			var lb=$(obj).attr('lb');
			var lc=$(obj).attr('lc');
            $("input[name='id']").val(navId);
            $("input[name='title']").val(navTitle);
            $("input[name='usable_num']").val(navUsable_num);
            $("input[name='storage_num']").val(navStorage_num);
            $("input[name='overflow_num']").val(navOverflow_num);
            $("input[name='no']").val(navNo);
            $("input[name='block_no']").val(navBlock_no);
            $("input[name='lng_lat']").val(navLng_Lat);
			$("input[name='status']").val(navStatus);
			$("input[name='la']").val(la);
			$("input[name='lb']").val(lb);
			$("input[name='lc']").val(lc);
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
            if(title != ''){
                href = href + '&title='+title;
            }
            if(usable_num != ''){
                href = href + '&usable_num='+usable_num;
            }
            if(storage_num != ''){
                href = href + '&storage_num='+storage_num;
            }
            if(no != ''){
                href = href + '&no='+no;
            }
            //alert(href);
            window.location.href=href;
            return false;
        });
    </script>
	
	<script>
	$(".xiangqing").click(function(){
		//alert(1);
		var id = $(this).attr("id");
		//alert(id);
		//去后台获取数据
		var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikesexist_es1/id/'+ id;
		location.href=url;
	})
	$(".xiangqing1").click(function(){
		//alert(1);
		var id = $(this).attr("id");
		//var url = "http://115.29.238.250:5601/app/kibana#/visualize/create?type=pie&savedSearchId=AV7R_qVXuWTBJ8LcyXE6&_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-30d,mode:quick,to:now))&_a=(filters:!(('$state':(store:appState),meta:(alias:!n,disabled:!f,index:AV7HWv9wqeodvWtZMBvt,key:dwz_info_id,negate:!f,type:phrase,value:'"+id+"'),query:(match:(dwz_info_id:(query:'"+id+"',type:phrase))))),linked:!t,query:(match_all:()),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(field:mac),schema:metric,type:cardinality),(enabled:!t,id:'2',params:(field:company,order:desc,orderBy:'1',size:5),schema:segment,type:terms)),listeners:(),params:(addLegend:!t,addTooltip:!t,isDonut:!f,legendPosition:right,type:pie),title:'New%20Visualization',type:pie))";
		var url = "http://115.29.238.250:5601/app/kibana#/visualize/create?type=pie&savedSearchId=AV7R9wvkuWTBJ8LcyXE4&_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-30d,mode:quick,to:now))&_a=(filters:!((%27$state%27:(store:appState),meta:(alias:!n,disabled:!f,index:AV7HWv9wqeodvWtZMBvt,key:dwz_info_id,negate:!f,type:phrase,value:%27"+id+"%27),query:(match:(dwz_info_id:(query:%27"+id+"%27,type:phrase))))),linked:!t,query:(match_all:()),uiState:(spy:(mode:(fill:!f,name:table))),vis:(aggs:!((enabled:!t,id:%271%27,params:(field:mac),schema:metric,type:cardinality),(enabled:!t,id:%272%27,params:(field:company,order:desc,orderBy:%271%27,size:5),schema:segment,type:terms)),listeners:(),params:(addLegend:!t,addTooltip:!t,isDonut:!f,legendPosition:right,type:pie),title:%27New%20Visualization%27,type:pie))";
		//alert(id);
		//去后台获取数据
		//var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikesall/id/'+ id;
		location.href=url;
	})
	$(".fenxi").click(function(){
		//alert(1);
		var id = $(this).attr("id");
		//alert(id);
		//去后台获取数据
		var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Stalist/index/dwz_info_id/'+ id;
		location.href=url;
	})
	$(".liudong").click(function(){
		//alert(1);
		var id = $(this).attr("id");
		//alert(id);
		//去后台获取数据
		var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Blast/oneinfo/iid/'+ id;
		location.href=url;
	})
	$(".trend").click(function(){
		//alert(1);
		var id = $(this).attr("id");
		//alert(id);
		//去后台获取数据
		var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/trend/iid/'+ id;
		location.href=url;
	})
	$(".clear").click(function(){
		
		if(window.confirm('确定要清除数据吗')){
                 var id = $(this).attr("id");
				//alert(id);
				//去后台获取数据
				var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/clear/id/'+ id;
				location.href=url;
                 return true;
              }else{
                 //alert("取消");
                 return false;
             }
		
		//var id = $(this).attr("id");
		//alert(id);
		//去后台获取数据
		//var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/clear/id/'+ id;
		//location.href=url;
	})
	
	</script>
	
	<script>
        function isstop(){
            var cont = 1;
            $.post("<?php echo U('Admin/Bparking/isstop');?>",{cont:cont},
                function(data){
                    for (var i=0;i<data.length;i++){
                          $("#"+"i"+data[i]["dwz_info_id"]).attr("style",data[i]["icon"]);
//                          $("#"+data[i]["dwz_info_id"]).text(data[i]["sta"]);
                        //alert(data[i]["dwz_info_id"]);
                    }
                },
                "json");//这里返回的类型有：json,html,xml,text
        }
		isstop();
        setInterval("isstop()",60000)
        $(".xiangqing").click(function(){
            //alert(1);
            var id = $(this).attr("id");
            //alert(id);
            //去后台获取数据
//            var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikesexist_es1/id/'+ id;
            var url = 'http://localhost/index.php/Admin/Bparking/infobikesexist_es1/id/'+ id;
            location.href=url;
        })
        $(".xiangqing1").click(function(){
            //alert(1);
            var id = $(this).attr("id");
            //alert(id);
            //去后台获取数据
//            var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikesall1/id/'+ id;
            var url = 'http://localhost/index.php/Admin/Bparking/infobikesall1/id/'+ id;
            location.href=url;
        })
		
		$(".cstatus").click(function(){
			var id = $(this).attr("data-id");
			var status = $(this).attr("data-status");
			
			//console.log(arr);
			//alert(id);
			$.post("<?php echo U('Admin/Bparking/cstatus');?>",
				{
					id:id,
					status:status,
				},
                function(data){
                    //console.log(data);
					if(data.error_code == 0){
						var id = '#cs'+ data.res.id;
						$(id).attr('data-status',data.res.status);
						$(id).text(data.res.text);
						console.log(data.res.id);
						console.log(data.res.text);
						
					}
					//if(data.error_code == 0 and arr[1]==0)  $(this).text("隐藏中");
					//if(data.error_code == 0 and arr[1]==1)  $(this).text("显示中");
            },"json");//这里返回的类型有：json,html,xml,text
			
		})
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