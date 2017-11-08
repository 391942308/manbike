<extend name="Public:left" />
<block name="main">
    <link href="__PUBLIC__/myjslib/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>车位管理&nbsp;&nbsp;&nbsp; <small>车位车辆流动情况统计</small></h3>
                </div>

               
            </div>

            <div class="clearfix"></div>

            <div class="row">

                </div>

                <div class="clearfix"></div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
					
                        <div class="x_title">
						<span style="color:red;">
						提示：<br />
						定义一：小于允许时间间隔的两个时刻为连续时间，反之不连续，单位（秒）<br />
						定义二：小于规则时间的为流动车辆，反之为非流动,单位（秒）
						
						</span>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Settings 1</a>
                                        </li>
                                        <li><a href="#">Settings 2</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            <!--<p><a class="btn btn-primary" href="javascript:;" onclick="add()">添加车辆信息</a></p>-->
                            <div style="margin-bottom: 10px" class="input-group">
                                <form method="POST" action="{:U('Admin/Blast/oneblock')}">
                                
                                 
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
							<input id="bid" type="text" class="form-control" style="width: 250px" placeholder="社区名称" name="bid" value="{$bid}"/>
									<!--<input id="st" type="text" class="form-control" style="width: 250px" placeholder="持续时间" name="st" value="{$st}"/>-->
                                    <input id="judge" type="text" class="form-control" style="width: 250px" placeholder="允许的时间间隔" name="judge" value="{$judge}"/>
                                    <input id="rule" type="text" class="form-control" style="width: 250px" placeholder="规则" name="rule" value="{$rule}"/>
                                    <input type="submit" value="查询" class="btn btn-default"/>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>

                                        </th>
                                        <th class="column-title">车辆名称 </th>
                                        <th class="column-title">mac地址 </th>
                                        <th class="column-title">信号强度 </th>
										<th class="column-title">车位编号 </th>
										<th class="column-title">是否为流动车辆 </th>
                                        <th class="column-title no-link last"><span class="nobr">操作</span>
                                        </th>
                                        <th class="bulk-actions" colspan="7">
                                            <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <volist name="all" id="vo">
                                        <tr class="even pointer">
                                            <td class="a-center ">

                                            </td>
                                            <td class=" ">{$vo.name}</td>
                                            <td class=" ">{$vo.mac} </td>
                                            <td class=" ">{$vo.rssi} </td>
                                            <td class=" ">{$vo.iid} </td>
                                            <if condition="$vo.flag eq 1 ">
												<td class=" ">非流动 </td>
												<else /> 
												<td class=" ">流动 </td>
											</if>
											
                                            <td class=" last">
                                                <a href="#">
                                                    <a href="javascript:;" navName="{$vo['name']}" navMac="{$vo['mac']}" navRssi="{$vo['rssi']}" navInfoid="{$vo['dwz_info_id']}" navLng_Lat="{$vo['lng']},{$vo['lat']}" onclick="edit(this)">修改</a>
                                                    | <a href="javascript:if(confirm('确定删除？'))location='{:U('Admin/Bike/delete',array('mac'=>$vo['mac']))}'">删除</a>
                                                    | <a id="{$vo.mac}" class="xq" href="javascript:void(0)">详情</a>
													
                                                </a>
                                            </td>
                                        </tr>
                                    </volist>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                {$show}
            </div>
            </div>
        </div>
    <div class="modal fade" id="bjy-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel"> 添加车辆信息</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Bike/add')}" method="post">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">车辆名称：</th>
                                <td>
                                    <input class="input-medium" type="text" name="name">
                                </td>
                            </tr>
                            <tr>
                                <th>mac地址：</th>
                                <td>
                                    <input class="input-medium" type="text" name="mac">
                                </td>
                            </tr>
                            <tr>
                                <th>信号强度：</th>
                                <td>
                                    <input class="input-medium" type="text" name="rssi">
                                </td>
                            </tr>
                            <tr>
                                <th>车位ID：</th>
                                <td>
                                    <input class="input-medium" type="text" name="dwz_info_id">
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
                            </tr>
                        </table>
                    </form>
                    <div style="width:400px; height:300px; position: absolute; left: 330px; top:160px;">
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
                    <h4 class="modal-title" id="myModalLabel"> 修改菜单</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Bike/edit')}" method="post">
                        <input type="hidden" name="mac">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">车辆名称：</th>
                                <td>
                                    <input class="input-medium" type="text" name="name">
                                </td>
                            </tr>
                            <tr>
                                <th>信号强度：</th>
                                <td>
                                    <input class="input-medium" type="text" name="rssi">
                                </td>
                            </tr>
                            <tr>
                                <th>车位ID：</th>
                                <td>
                                    <input class="input-medium" type="text" name="dwz_info_id">
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
                    <div style="width:400px; height:300px; position: absolute; left: 330px; top:160px;">
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
            $("input[name='title'],input[name='mca']").val('');
            $("input[name='pid']").val(0);
            $('#bjy-add').modal('show');
        }
        // 添加子菜单
        function add_child(obj){
            var navId=$(obj).attr('navId');
            $("input[name='pid']").val(navId);
            $("input[name='title']").val('');
            $("input[name='name']").val('');
            $("input[name='icon']").val('');
            $('#bjy-add').modal('show');
        }

        // 修改菜单
            //navName="{$vo['name']}" navMac="{$vo['mac']}" navRssi="{$vo['rssi']}" navInfoid="{$vo['dwz_info_id']}" navLng_Lat="{$vo['lng']},{$vo['lat']}"
        function edit(obj){
            var navName=$(obj).attr('navName');
            var navMac=$(obj).attr('navMac');
            var navRssi=$(obj).attr('navRssi');
            var navInfoid=$(obj).attr('navInfoid');
            var navLng_Lat=$(obj).attr('navLng_Lat');
            $("input[name='name']").val(navName);
            $("input[name='mac']").val(navMac);
            $("input[name='rssi']").val(navRssi);
            $("input[name='dwz_info_id']").val(navInfoid);
            $("input[name='lng_lat']").val(navLng_Lat);
            $('#bjy-edit').modal('show');
        }
    </script>
    <script>
        $("#datatable_paginate a").click(function(){
            var href = $(this).attr("href");
           // $(this).attr("href","#");

            var name = $("#name").val();
            var mac = $("#mac").val();
            var dwz_info_id = $("#dwz_info_id").val();
            //alert(href);
            //alert(name);
            //alert(mac);
            //alert(dwz_info_id);
            if(name != ''){
                href = href + '&name='+name;
            }
            if(mac != ''){
                href = href + '&mac='+mac;
            }
            if(dwz_info_id != ''){
                href = href + '&dwz_info_id='+dwz_info_id;
            }
            //alert(href);
            window.location.href=href;
            return false;
        });
    </script>
    <!-- /page content -->
	<script>
	$(".xq").click(function(){
		var url = "http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bike/allcw";
		var mac = $(this).attr("id");
		//var iid = '{$iid}';
		url=url + '/mac/'+mac;
		location.href=url;
	})
	</script>
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