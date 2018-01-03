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
	
	<!--<a href="{:U('Admin/Bparking/infobikesexist_es2',array('id'=>$id))}">查看详情</a>-->
	<!--<table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th class="column-title">车辆名称</th>
                                        <th class="column-title">车辆mac地址</th>
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
									<volist name="arr_exist4" id="vo">
                                    <tr class="even pointer">
                                            <td class="a-center ">
												<a href="#">{$vo.name}</a>
                                            </td>
											
											<td class="a-center ">
												<a href="#">{$vo.mac}</a>
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
                                </table>-->
	
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
		var jstr3 = $.parseJSON('{$str3}');

		option = {
			title : {
				text: '{$title}网格单车数量',
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
	<script>
	
	$(".xq").click(function(){
		//alert(1);
		var url = "http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikedetail";
//		var url = "http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikedetail";
		var mac = $(this).attr("id");
		//alert(mac);
		//去后台获取数据
		var iid = $("#iid").val();
		url = url + '/id/'+iid+'/mac/'+mac;
		//alert(iid);
		location.href=url;
	})
	
	</script>
    <!-- /page content -->
</block>