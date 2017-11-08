<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>{$title} | 后台管理</title>

	<!-- Bootstrap -->
	<link href="__PUBLIC__/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="__PUBLIC__/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="__PUBLIC__/gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">

	<!-- Custom Theme Style -->
	<link href="__PUBLIC__/gentelella/build/css/custom.min.css" rel="stylesheet">
	<link id="artDialogSkin" href="__PUBLIC__/artDialog/skins/default.css" rel="stylesheet" type="text/css" />
</head>

<body class="nav-md">
<div class="container body">
	<div class="main_container">
		<div class="col-md-3 left_col">
			<div class="left_col scroll-view">
				<div class="navbar nav_title" style="border: 0;">
					<a href="index.html" class="site_title">
<!--						<i class="fa fa-paw"></i> -->
<img src="__PUBLIC__/images/bike_logo.png" style="width: 33px;margin-bottom: 5px;margin-left: 8px;"/>
						<!--<img src="__PUBLIC__/images/bike_logo.png" style="width: 15%;margin-bottom: 5px"/>-->
						<span style="font-size: smaller">共享单车泊位管理</span>
					</a>
				</div>

				<div class="clearfix"></div>

				<!-- menu profile quick info -->
				<div class="profile clearfix">
					<div class="profile_pic">
						<img src="__PUBLIC__/images/img.jpg" alt="..." class="img-circle profile_img">
					</div>
					<div class="profile_info">
						<span>欢迎您：</span>
						<h2>{$_SESSION['auth']['username']}</h2>
					</div>
				</div>
				<!-- /menu profile quick info -->

				<br />

				<!-- sidebar menu -->
				<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
					<div class="menu_section">
						<ul class="nav side-menu">
							<volist name="list" id="vo">
								<li><notempty name="vo.title"><a><i class="{$vo.icon}"></i> {$vo.title}<span class="fa fa-chevron-down"></span></a></notempty>
									<ul class="nav child_menu">
										<volist name="vo['_child']" id="voo">
											<?php
												if(strpos($voo['name'],'http')===0){
													?><li><a href="{$voo.name}">{$voo.title}</a></li><?php
												}else{
													?><li><a href="{:U($voo['name'])}">{$voo.title}</a></li><?php
												}
											?>
										</volist>
									</ul>
								</li>
							</volist>
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
					<a data-toggle="tooltip" data-placement="top" title="Logout" href="{:U('Login/logout')}">
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
								<img src="__PUBLIC__/images/img.jpg" alt="">{$_SESSION['auth']['username']}
								<span class=" fa fa-angle-down"></span>
							</a>
							<ul class="dropdown-menu dropdown-usermenu pull-right">
								<li><div style="height: 10px;"></div></li>
								<li><a href="{:U('Login/logout')}"><i class="fa fa-sign-out pull-right"></i> 退出</a></li>
							</ul>
						</li>

						<li role="presentation" class="dropdown">

							<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
								<li>
									<a>
										<span class="image"><img src="__PUBLIC__/images/img.jpg" alt="Profile Image" /></span>
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
										<span class="image"><img src="__PUBLIC__/images/img.jpg" alt="Profile Image" /></span>
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
										<span class="image"><img src="__PUBLIC__/images/img.jpg" alt="Profile Image" /></span>
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
										<span class="image"><img src="__PUBLIC__/images/img.jpg" alt="Profile Image" /></span>
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
			<block name="main"></block>
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
<script src="__PUBLIC__/gentelella/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="__PUBLIC__/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="__PUBLIC__/gentelella/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="__PUBLIC__/gentelella/vendors/nprogress/nprogress.js"></script>
<!-- validator -->
<script src="__PUBLIC__/gentelella/vendors/validator/validator.js"></script>
<script type="text/JavaScript" src="__PUBLIC__/artDialog/artDialog.js"></script>
<script type="text/javascript" src="__PUBLIC__/artDialog/plugins/iframeTools.js"></script> <!-- 对iframe的新工具 -->

<!-- Custom Theme Scripts -->
<script src="__PUBLIC__/gentelella/build/js/custom.min.js"></script>
<script type="text/javascript">
	$(function(){
		$(".m_child_menu").css('display','none');
	});
</script>
</body>
</html>
