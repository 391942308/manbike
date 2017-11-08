<extend name="Public:left" />
<block name="main">
<script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>

<div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-group"></i> 所有分组 </span>
              <div class="count">{$gno}</div>
              <!--<span class="count_bottom"><i class="green">4% </i> </span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> 所有用户 </span>
              <div class="count">{$uno}</div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-pie-chart"></i> 所有区块 </span>
              <div class="count">{$ibno}</div>
             <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-sitemap"></i> 所有车位 </span>
              <div class="count">{$ino}</div>
             <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user-plus"></i> 所有车企 </span>
              <div class="count">{$bcno}</div>
             <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bicycle"></i> 所有车辆 </span>
              <div class="count">{$bno}</div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>-->
            </div>
          </div>

<div id="main" style="width: 100%;height:600px;"></div>
<div style="text-align:right;">
<volist name="list1" id="vo">
<a id="{$vo.keyword}" href="javascript:void(0);" type="button" class="btn btn-primary xx" >{$vo.title}</a>
</volist>
<!--
<a id="xcj" href="#" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">摩拜</a>
<a id="cx" href="#" type="button" class="btn btn-primary" >酷骑</a>
<a id="cx" href="#" type="button" class="btn btn-primary" >hellobike</a>
<a id="cx" href="#" type="button" class="btn btn-primary" >小明单车</a>
<a id="cx" href="#" type="button" class="btn btn-primary" >蜜蜂出游</a>
<a id="cx" href="#" type="button" class="btn btn-primary" >其他</a>-->
</div>
<!--可以隔一定时间记录一次，然后显示变化情况-->
 <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <script type="text/javascript">
		
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
//var test = genData(50);
        
		var jstr1 = $.parseJSON('{$str1}');
		var jstr2 = $.parseJSON('{$str2}');
		

		option = {
			title : {
				text: '当前各个单车公司单车数量统计表',
				//subtext: '纯属虚构',
				x:'left'
			},
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
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
						}
					}
				}
			]
		};


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
		
		function realtime(){
			location.reload();
			console.log("realtime");
		}
		setInterval("realtime()",60000)
    </script>
	<script>
	$(".xx").click(function(){
		var id = $(this).attr("id");
		var title=$(this).text();
		//alert(id);
		location.href="http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bike/index"+"/name/"+id+"/company/"+title;
	});
	</script>


</block>
