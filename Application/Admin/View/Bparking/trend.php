<extend name="Public:left" />
<block name="main">
    <link href="__PUBLIC__/myjslib/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
                <div class="title_left">
                    <h3>车位管理&nbsp;&nbsp;&nbsp; <small>车辆停放变化趋势统计</small></h3>
					<span style="color:red;padding-bottom:10px;">提示：输入开始时间和结束时间，点击查询，显示这段时间的车辆变化趋势，默认时间为当天时间</span>
                </div>

               
	<div style="margin-bottom: 10px" class="input-group">
	<form method="POST" action="{:U('Admin/Bparking/trend')}">
			<input id="iid" type="hidden" name="iid" value="{$iid}" />
			<div class="input-group date form_datetime col-md-10" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input1">
                                <input name="start" id="start" class="form-control" size="16" type="text" placeholder="开始时间" value="{$start}" readonly style="width: 250px">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
							<div class="input-group date form_datetime col-md-10" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input1">
                                <input name="end" id="end" class="form-control" size="16" type="text" placeholder="结束时间" value="{$end}" readonly style="width: 250px">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
	<input type="submit" value="查询" class="btn btn-default"/>
    </form>
	</div>
	<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="main" style="width: 1200px;height:600px;"></div>
	
	<!--
	<input id="iid" type="hidden" value="{$id}" />
	
	<div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bicycle"></i> 实时停放车辆数 </span>
              <div class="count">{$exist}</div>
              <span class="count_bottom"><i class="green">（间隔60秒自动刷新） </i> </span>
            </div>
    </div>
	-->
	
	<!--
	<table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th class="column-title">车辆名称</th>
                                        <th class="column-title">车辆mac地址</th>
                                        <th class="column-title">信号强度rssi</th>
                                        <th class="column-title">最后采集到的时间</th>
                                        <th class="column-title">采集到的次数</th>
                                        <th class="column-title no-link last"><span class="nobr">操作</span>
                                        </th>
                                        <th class="bulk-actions" colspan="7">
                                            <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
									<volist name="arr_exist" id="vo">
                                    <tr class="even pointer">
                                            <td class="a-center ">
												<a href="#">{$vo.name}</a>
                                            </td>
											
											<td class="a-center ">
												<a href="#">{$vo.mac}</a>
                                            </td>
											<td class="a-center ">
												<a href="#">{$vo.rssi}</a>
                                            </td>
											<td class="a-center ">
												<a href="#">{$vo.lasttime}</a>
                                            </td>
											<td class="a-center ">
												<a href="#">{$vo.num}</a>
                                            </td>
                                            <td class="a-center ">
												<a id="{$vo.mac}" class="xq" href="#">详情</a>
                                            </td>
											
                                            
                                    </tr>  
									</volist>
										</tbody>
                                </table>
	-->
	
      
    <div class="modal fade" id="bjy-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel"> 添加车位</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Bparking/add')}" method="post">
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
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Bparking/edit')}" method="post">
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
       //   navOverflow_num="{$vo['Overflow_num']}" navNo="{$vo['No']}" navBlock_no="{$vo['Block_no']}"
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
	function realtime(){
		location.reload();
		console.log("realtime");
	}
	setInterval("realtime()",60000)
	//location.reload();
	$(".xq").click(function(){
		//alert(1);
		var url = "http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikedetail";
		var mac = $(this).attr("id");
		//alert(mac);
		//去后台获取数据
		var iid = $("#iid").val();
		url = url + '/id/'+iid+'/mac/'+mac;
		//alert(iid);
		location.href=url;
	})
	</script>
	
	
	<script type="text/javascript">
		
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
//var test = genData(50);
        
		var jstr1 = $.parseJSON('{$str1}');
		//alert(jstr1);
		var jstr2 = $.parseJSON('{$str2}');
		
		var value = '';
	
		option = {
    title: {
        text: 'P{$iid}车辆停放变化趋势',
    },
    tooltip: {
        trigger: 'axis'
    },
    legend: {
        data:['车辆数','车辆数']
    },
    toolbox: {
        show: true,
        feature: {
            dataZoom: {
                yAxisIndex: 'none'
            },
            dataView: {readOnly: false},
            magicType: {type: ['line', 'bar']},
            restore: {},
            saveAsImage: {}
        }
    },
    xAxis:  {
        type: 'category',
        boundaryGap: false,
		boundaryGap: [0, 0.01],  
		axisLabel:{
			interval:0,
			rotate:-30
		},
        data: jstr1
    },
	grid: { // 控制图的大小，调整下面这些值就可以，
             x: 40,
             x2: 100,
             y2: 150,// y2可以控制 X轴跟Zoom控件之间的间隔，避免以为倾斜后造成 label重叠到zoom上
    },
    yAxis: {
        type: 'value',
        axisLabel: {
            formatter: '{value}'
        }
    },
    series: [
        {
            name:'车辆数',
            type:'line',
            data:jstr2,
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'},
                    {type: 'min', name: '最小值'}
                ]
            },
            markLine: {
                data: [
                    {type: 'average', name: '平均值'}
                ]
            }
        }
    ]
};


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
    <!-- /page content -->
	<script type="text/javascript" src="__PUBLIC__/myjslib/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="__PUBLIC__/myjslib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/myjslib/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="__PUBLIC__/myjslib/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
	<script type="text/javascript">
        $('.form_datetime').datetimepicker({
            
			language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
			
        });

    </script>
</block>