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
<div>
	<div style="float:left;width:60%;"> 
	<volist name="list1" id="vo">
	<a id="{$vo.keyword}" href="javascript:void(0);" type="button" class="btn btn-primary xx" >{$vo.title}</a>
	</volist>
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
        
		var jstr1 = $.parseJSON('{$str1}');
		var jstr2 = $.parseJSON('{$str2}');
		var jstr3 = $.parseJSON('{$str_color}');

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
		
		var jstr11 = $.parseJSON('{$str11}');
		var jstr22 = $.parseJSON('{$str22}');
		bar(jstr11,jstr22);
		function bar(jstr11,jstr22){
			// 基于准备好的dom，初始化echarts实例
			var myChart1 = echarts.init(document.getElementById("main1"));
			var app = {};
			option = null;
			option1 = {
				title: {
					text: '{$day}天新增量'
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
						rotate:-30
					},
					data: jstr11
				},
				yAxis: {},
				series: [{
					name: '数量',
					type: 'bar',
					data: jstr22
				}],
				color:['#4876FF', 'green','yellow','blueviolet']
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


</block>
