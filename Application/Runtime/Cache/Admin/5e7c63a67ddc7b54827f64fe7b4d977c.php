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
			
<script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>

<div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-group"></i> 所有分组 </span>
              <div class="count"><?php echo ($gno); ?></div>
              <!--<span class="count_bottom"><i class="green">4% </i> </span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> 所有用户 </span>
              <div class="count"><?php echo ($uno); ?></div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-pie-chart"></i> 所有区块 </span>
              <div class="count"><?php echo ($ibno); ?></div>
             <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-sitemap"></i> 所有车位 </span>
              <div class="count"><?php echo ($ino); ?></div>
             <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user-plus"></i> 所有车企 </span>
              <div class="count"><?php echo ($bcno); ?></div>
             <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bicycle"></i> 所有车辆 </span>
              <div class="count"><?php echo ($bno); ?></div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
          </div>
<div>
	<div style="float:left;width:60%;"> 
	<?php if(is_array($list1)): $i = 0; $__LIST__ = $list1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a id="<?php echo ($vo["keyword"]); ?>" href="javascript:void(0);" type="button" class="btn btn-primary xx" ><?php echo ($vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<div style="float:left;width:40%;">
	<a id="day1" href="javascript:void(0);" type="button" class="btn btn-primary">当天</a>
	<a id="day3" href="javascript:void(0);" type="button" class="btn btn-primary">3天</a>
	<a id="day7" href="javascript:void(0);" type="button" class="btn btn-primary">7天</a>
	<a id="day30" href="javascript:void(0);" type="button" class="btn btn-primary">30天</a>
	</div>
	<div style="clear:both;"></div>
</div>
<div>
<div id="main" style="width:50%;height:500px;float:left;"></div>
<div id="center" style="width:6%;height:500px;float:left;"></div>
<div id="main1" style="width:40%;height:430px;float:left;"></div>
<div style="clear:both"; > </div>
</div>
<!--可以隔一定时间记录一次，然后显示变化情况-->
 <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <script type="text/javascript">
		
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
//var test = genData(50);
        
		var jstr1 = $.parseJSON('<?php echo ($str1); ?>');
		var jstr2 = $.parseJSON('<?php echo ($str2); ?>');
		var jstr3 = $.parseJSON('<?php echo ($str_color); ?>');

		option = {
			title : {
				text: '单车总量统计表',
				//subtext: '纯属虚构',
				x:'left'
			},
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			color:jstr3,
			legend: {
				type: 'scroll',
				orient: 'vertical',
				right: 10,
				top: 20,
				bottom: 20,
				data: jstr1
			},
			series : [
				{
					name: '车企',
					type: 'pie',
					radius : '55%',
					center: ['40%', '50%'],
					data: jstr2,
					itemStyle: {
						emphasis: {
							shadowBlur: 10,
							shadowOffsetX: 0,
							shadowColor: 'rgba(0, 0, 0, 0.5)'
						},
						normal:{ 
							label:{ 
								show: true, 
								formatter: '{b} :  \n ({d}%)' 
							}, 
							labelLine :{show:true} 
						} 
					}
				}
			]
		};


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
		
		var jstr11 = $.parseJSON('<?php echo ($str11); ?>');
		var jstr22 = $.parseJSON('<?php echo ($str22); ?>');
		var jstr33 = $.parseJSON('<?php echo ($str_color33); ?>');
		bar(jstr11,jstr22,jstr33);
		function bar(jstr11,jstr22,jstr33){
			// 基于准备好的dom，初始化echarts实例
			var myChart1 = echarts.init(document.getElementById("main1"));
			var app = {};
			option = null;
			option1 = {
				title: {
					text: '<?php echo ($day); ?>天新增量'
				},
				tooltip: {},
				legend: {
					data:['数量']
				},
				xAxis: {
					type: 'category',  
					boundaryGap: [0, 0.01],  
					axisLabel:{
						interval:0,
						rotate:-30,
					},
					data: jstr11
				},
				yAxis: {},
				series: [{
					name: '数量',
					type: 'bar',
					itemStyle: {
						normal: {
							color: function(params) {
								// build a color map as your need.
//								var colorList =['yellow', 'green','yellow','blue','white'];
								var colorList =jstr33;
								return colorList[params.dataIndex]
							},
							label: {
								show: true,
								position: 'top',
								formatter: '{b}\n{c}'
							}
						}
					},
					data: jstr22
				}]
			};;
			
			myChart1.setOption(option1, true);
		}
		
		
		
		function realtime(){
			location.reload();
			console.log("realtime");
		}
		setInterval("realtime()",60000)
    </script>
	
	<script>
	//ajax 获取到数据 ，然后显示
		$("#day1").click(function(){
			//alert("day1");
			var url= 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Index/index'+'/day/1';
			location.href=url;
		})
		$("#day3").click(function(){
			//alert("day3");
			var url= 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Index/index'+'/day/3';
			location.href=url;
		})
		$("#day7").click(function(){
			//alert("day7");
			var url= 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Index/index'+'/day/7';
			location.href=url;
		})
		$("#day30").click(function(){
			//alert("day30");
			var url= 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Index/index'+'/day/30';
			location.href=url;
		})
	</script>
	
	
	<script>
	$(".xx").click(function(){
		var id = $(this).attr("id");
		var title=$(this).text();
		//alert(id);
		var url="http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bike/index"+"/name/"+id+"/company/"+title;
		window.open(url);
		
		
	});
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