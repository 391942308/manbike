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
                <h3>权限配置&nbsp;&nbsp;&nbsp; <small>用户组列表</small></h3>
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

                    <p><a class="btn btn-primary" href="javascript:;" onclick="add()">添加用户组</a></p>

                    <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                            <tr class="headings">
                                <th>

                                </th>
                                <th class="column-title">用户组名称 </th>
                                <th class="column-title">省 </th>
                                <th class="column-title">市 </th>
                                <th class="column-title">区 </th>
                                <th class="column-title">行政级别 </th>
                                <th class="column-title no-link last"><span class="nobr">操作</span>
                                </th>
                                <th class="bulk-actions" colspan="7">
                                    <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php if(is_array($auth_group)): $i = 0; $__LIST__ = $auth_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="even pointer">
                                    <td class="a-center ">

                                    </td>
                                    <td class=" "><?php echo ($vo["title"]); ?> </td>
                                    <td class=" "><?php echo ($vo["province"]); ?> </td>
                                    <td class=" "><?php echo ($vo["city"]); ?> </td>
                                    <td class=" "><?php echo ($vo["area"]); ?> </td>
                                    <td class=" "><?php echo ($vo["class"]); ?> </td>
                                    <td class=" last">
                                        <a href="#">
                                             <a href="javascript:;" navId="<?php echo ($vo['id']); ?>" navTitle="<?php echo ($vo['title']); ?>" navProvince="<?php echo ($vo['province']); ?>" navCity="<?php echo ($vo['city']); ?>" navArea="<?php echo ($vo['area']); ?>" navClass="<?php echo ($vo['class']); ?>" onclick="edit(this)">修改</a>
                                            | <a href="javascript:if(confirm('确定删除？'))location='<?php echo U('Admin/Rule/delete_group',array('id'=>$vo['id']));?>'">删除</a>
                                            | <a href="<?php echo U('Admin/Rule/rule_group',array('id'=>$vo['id']));?>">分配权限</a>
                                            | <a href="<?php echo U('Admin/Rule/check_user',array('group_id'=>$vo['id']));?>">添加成员</a>
                                            | <a href="<?php echo U('Admin/Rule/check_block',array('group_id'=>$vo['id']));?>">分配区块权限</a>
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
                    <h4 class="modal-title" id="myModalLabel"> 添加用户组</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline onef" action="<?php echo U('Admin/Rule/add_group');?>" method="post">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="18%">用户组名：</th>
                                <td>
                                    <input class="input-medium" type="text" name="title" >
                                </td>
                            </tr>
                            <tr>
                                <th width="18%">地区：</th>
                                <td>
<!--                                    <div data-toggle="distpicker" data-autoselect="3" data-province="浙江省">-->
<!--                                        <select name="province" class="province1"></select>-->
<!--                                        <select name="city"></select>-->
<!--                                        <select name="area"></select>-->
<!--                                    </div>-->
                                    <input type="text" name="province" placeholder="省" class="form-control"/>
                                    <input type="text" name="city" placeholder="市" class="form-control"/>
                                    <input type="text" name="area" placeholder="区" style="margin-top: 5px;" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <th width="18%">行政级别：</th>
                                <td>
                                    <div>
                                        <select name="class">
                                            <option value="省级">省级</option>
                                            <option value="市级">市级</option>
                                            <option value="区级">区级</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input class="btn btn-success" type="button" value="添加" onclick="ispro()">
                                </td>
                            </tr>
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
                    <h4 class="modal-title" id="myModalLabel"> 修改用户组</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline twof" action="<?php echo U('Admin/Rule/edit_group');?>" method="post">
                        <input type="hidden" name="id">
                        <input type="hidden" name="pid">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="18%">用户组名：</th>
                                <td> <input class="input-medium" type="text" name="title">
                                </td>
                            </tr>
                            <tr>
                                <th width="18%">地区：</th>
                                <td>
<!--                                    <div data-toggle="distpicker" >-->
<!--                                        <select name="province" class="province2"></select>-->
<!--                                        <select name="city"></select>-->
<!--                                        <select name="area"></select>-->
<!--                                    </div>-->
                                    <input type="text" name="province" placeholder="省" class="form-control" value="<?php echo ($province); ?>"/>
                                    <input type="text" name="city" placeholder="市" class="form-control" value="<?php echo ($city); ?>"/>
                               <input type="text" name="area" placeholder="区" style="margin-top: 5px;" class="form-control" value="<?php echo ($area); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th width="18%">行政级别：</th>
                                <td>
                                    <div>
                                        <select name="class">
                                            <option value="省级" <?php if($class == '省级'): ?>selected<?php endif; ?>>省级</option>
                                            <option value="市级" <?php if($class == '市级'): ?>selected<?php endif; ?>>市级</option>
                                            <option value="区级" <?php if($class == '区级'): ?>selected<?php endif; ?>>区级</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input class="btn btn-success" type="button" value="修改" onclick="ispro2()">
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
            $("input[name='title'],input[name='mca']").val('');
            $("input[name='pid']").val(0);
            $('#bjy-add').modal('show');
        }
        // 添加子菜单
        function add_child(obj){
            var navId=$(obj).attr('navId');
            $("input[name='pid']").val(navId);
            $("input[name='title']").val('');
            $("input[name='mca']").val('');
            $("input[name='icon']").val('');
            $('#bjy-add').modal('show');
        }

        // 修改菜单
        function edit(obj){
            var navId=$(obj).attr('navId');
            var navTitle=$(obj).attr('navTitle');
            var navProvince=$(obj).attr('navProvince');
            var navCity=$(obj).attr('navCity');
            var navArea=$(obj).attr('navArea');
            var navClass=$(obj).attr('navClass');
//            var navProvince=$(obj).attr('navProvince');
//            var navCity=$(obj).attr('navCity');
//            var navArea=$(obj).attr('navArea');
            $("input[name='id']").val(navId);
            $("input[name='title']").val(navTitle);
            $("input[name='province']").val(navProvince);
            $("input[name='city']").val(navCity);
            $("input[name='area']").val(navArea);
            $("select[name='class']").val(navClass);
//            $("select[name='province']").val(navProvince);
//            $("select[name='city']").val(navCity);
//            $("select[name='area']").val(navArea);
            $('#bjy-edit').modal('show');
        }
        //判断是否选择了省
        function ispro(){
            var province=$(".province1").val();
           // alert(province);
           // return false;
            if(province==''){
                alert('请选择省');
                return false;
            }else{
                $(".onef").submit();
            }
        }
        function ispro2(){
            var province2=$(".province2").val();
            if(province2==''){
                alert('请选择省');
                return false;
            }else{
                $(".twof").submit();
            }
        }
    </script>


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