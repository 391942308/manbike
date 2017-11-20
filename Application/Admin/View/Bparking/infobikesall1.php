<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
    <script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <div class="row tile_count">
        <input type="hidden" id="id" value="{$id}"/>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-bicycle"></i> {$id}历史停放车辆数 </span>
            <div class="count">{$sum}</div>
            <span class="count_bottom"><i class="green">（间隔60秒自动刷新） </i> </span>
        </div>
        <!--<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-bicycle"></i> 所有停放车辆数 </span>
          <div class="count">{$all}</div>
          <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
        </div>-->
    </div>
    <div id="main" style="width: 100%;height:600px;"></div>
    <a href="{:U('Admin/Bparking/infobikesall3',array('id'=>$id))}">查看详情</a>

	<!--<a href="{:U('Admin/Bparking/infobikesall2')}">查看详情</a>-->
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
                text: '车位历史各个单车公司单车数量统计表',
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


</block>
