<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
	<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
	<input id="iid" type="hidden" value="{$id}" />
	<table class="table table-striped jambo_table bulk_action">
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
                                </table>
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