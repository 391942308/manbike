<extend name="Public:left" />
<block name="main">
	
    <link href="__PUBLIC__/myjslib/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	
    <script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
	<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
	
    <!-- page content -->
	
	<div class="row tile_count">
    <h1>{$b.title}总量变化趋势图</h1>
    </div>
	
	<!--<div style="margin-bottom: 10px" class="input-group">
                                <form method="post" action="/manbike0.3/index.php/Admin/Bparking/history">
									<div class="input-group date form_datetime col-md-20" data-date-format="yyyy-mm-dd hh:mm:ss" data-link-field="dtp_input1">
										<input name="start1" id="start1" class="form-control" size="16" type="text" placeholder="起始时间" value="{$start}" readonly>
										<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
										<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
									</div>
									<div class="input-group date form_datetime col-md-20" data-date-format="yyyy-mm-dd hh:mm:ss" data-link-field="dtp_input1">
										<input name="start2" id="start2" class="form-control" size="16" type="text" placeholder="结束时间" value="{$end}" readonly>
										<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
										<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
									</div>
                                    <input type="hidden" class="form-control" style="width: 150px" name="id" value="{$id}" id="id">&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="submit" value="查询" class="btn btn-default">
                                </form>
    </div>-->
	
	<div id="main" style="width: 100%;height:500px;"></div>
	
    

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
	

		var jstr1 = $.parseJSON('{$str1}');
	//alert(jstr1);
        var iid = '{$iid}';
		var mac = '{$mac}';
		
		// 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        option = {
			title : {
				//text : 'P'+iid+' MAC'+mac+' 24小时停车状态图',
				//subtext : '1为在车位中，0为离开状态'
			},
			tooltip : {
				trigger: 'item',
				formatter : function (params) {
					var date = new Date(params.value[0]);
					data = date.getFullYear() + '-'
						   + (date.getMonth() + 1) + '-'
						   + date.getDate() + ' '
						   + date.getHours() + ':'
						   + date.getMinutes();
					return data + '<br/>'
						   + params.value[1] + ', ' 
						   + params.value[2];
				}
			},
			toolbox: {
				show : true,
				feature : {
					mark : {show: true},
					dataView : {show: true, readOnly: false},
					restore : {show: true},
					saveAsImage : {show: true}
				}
			},
			dataZoom: {
				show: true,
				start : 0
			},
			legend : {
				data : ['series1']
			},
			grid: {
				y2: 80
			},
			xAxis : [
				{
					type : 'time',
					splitNumber:10
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name: 'series1',
					type: 'line',
					showAllSymbol: true,
					symbolSize: 1,
					data: jstr1,
								markPoint : {
									data : [
										{type : 'max', name: '最大值'},
										{type : 'min', name: '最小值'}
									]
								},
								markLine : {
									data : [
										{type : 'average', name: '平均值'}
									]
								}
					//itemStyle : { normal: {label : {show: true}}},
				}
			]
		};

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
		
		$(document).ready(function(){
			
			$('#myDatepicker4').datetimepicker();
			alert(0)
		})
    </script>
	
	
	<script type="text/javascript" src="__PUBLIC__/myjslib/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
	<script type="text/javascript" src="__PUBLIC__/myjslib/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/myjslib/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript" src="__PUBLIC__/myjslib/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
	<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	
</script>
    <!-- /page content -->
</block>