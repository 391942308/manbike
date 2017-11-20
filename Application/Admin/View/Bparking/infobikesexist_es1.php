<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
	<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <!-- page content -->
	<div id="main" style="width: 100%;height:450px;"></div>
	<input id="iid" type="hidden" value="{$id}" />
	
	<div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bicycle"></i> 实时停放车辆数 </span>
              <div class="count">{$exist}</div>
              <span class="count_bottom"><i class="green">（间隔60秒自动刷新） </i> </span>
            </div>
    </div>
	<a href="{:U('Admin/Bparking/infobikesexist_es2',array('id'=>$id))}">查看详情</a>
	
	<script>
	function realtime(){
		location.reload();
		console.log("realtime");
	}
	setInterval("realtime()",60000)
	</script>
	<script type="text/javascript">
		
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
//var test = genData(50);
        
		var jstr1 = $.parseJSON('{$str1}');
		var jstr2 = $.parseJSON('{$str2}');
		var jstr3 = $.parseJSON('{$str_color}');
		option = {
			title : {
				text: '实时车位各个公司单车数量',
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
				right: 30,
				top: 50,
				bottom: 20,
				data: jstr2
			},
			series : [
				{
					name: '车企',
					type: 'pie',
					radius : '80%',
					center: ['40%', '50%'],
					data: jstr2,
					itemStyle: {
						emphasis: {
							shadowBlur: 10,
							shadowOffsetX: 0,
							shadowColor: 'rgba(0, 0, 0, 0.5)'
						}
					}
				}
			]
		};


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
    <!-- /page content -->
</block>