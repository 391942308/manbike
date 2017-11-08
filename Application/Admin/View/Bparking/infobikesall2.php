<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
	<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <!-- page content -->
	<input id="iid" type="hidden" value="{$id}" />
	
	<div class="row tile_count">
            <!--<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bicycle"></i> 所有停放车辆数 </span>
              <div class="count">{$all}</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
            </div>-->
    </div>
	
	
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
									<volist name="view" id="vo">
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
	
	
        <!-- /page content -->
	<!--
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>车位详情信息&nbsp;&nbsp;&nbsp; <small>车位列表</small></h3>
                </div>

                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

                </div>

                <div class="clearfix"></div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
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

                            <p>
                                <a class="btn btn-primary" href="javascript:;" onclick="add()">添加车位</a>
                            </p>
                            <div style="margin-bottom: 10px" class="input-group">
                                <form method="get" action="{:U('Admin/Bparking/index')}">
                                    <input type="text" class="form-control" style="width: 150px" placeholder="车位名称" name="title" value="{$title}" id="title"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control" style="width: 150px" placeholder="可容纳车辆" name="usable_num" value="{$usable_num}" id="usable_num"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control" style="width: 150px" placeholder="已存储车辆" name="storage_num" value="{$storage_num}" id="storage_num"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control" style="width: 150px" placeholder="车位编号" name="no" value="{$no}" id="no"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="submit" value="查询" class="btn btn-default"/>
                                </form>
                            </div>


                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>

                                        </th>
                                        <th class="column-title">ID </th>
                                        <th class="column-title">车位名称 </th>
                                        <th class="column-title">可容纳车辆 </th>
                                        <th class="column-title">已存储车辆 </th>
                                        <th class="column-title">溢出车辆数量 </th>
                                        <th class="column-title">车位编号 </th>
                                        <th class="column-title">区域编号 </th>
                                        <th class="column-title">经度 </th>
                                        <th class="column-title">纬度 </th>
                                        <th class="column-title no-link last"><span class="nobr">操作</span>
                                        </th>
                                        <th class="bulk-actions" colspan="7">
                                            <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <volist name="bparking_list" id="vo">
                                        <tr class="even pointer">
                                            <td class="a-center ">

                                            </td>
                                            <td class=" ">{$vo.id}</td>
                                            <td class=" ">{$vo.title} </td>
                                            <td class=" ">{$vo.usable_num} </td>
                                            <td class=" ">{$vo.storage_num} </td>
                                            <td class=" ">{$vo.overflow_num} </td>
                                            <td class=" ">{$vo.no} </td>
                                            <td class="a-right a-right ">{$vo.block_no}</td>
                                            <td class="a-right a-right ">{$vo.lng}</td>
                                            <td class="a-right a-right ">{$vo.lat}</td>
                                            <td class=" last">
                                                <a href="#">
                                                   <a href="javascript:;" navId="{$vo['id']}" navTitle="{$vo['title']}" navUsable_num="{$vo['usable_num']}" navStorage_num="{$vo['storage_num']}" navOverflow_num="{$vo['overflow_num']}" navNo="{$vo['no']}" navBlock_no="{$vo['block_no']}" navLng_Lat="{$vo['lng']},{$vo['lat']}" onclick="edit(this)">修改</a>
                                                    | <a href="javascript:if(confirm('确定删除？'))location='{:U('Admin/Bparking/delete',array('id'=>$vo['id']))}'">删除</a>
                                                    | <a id="{$vo['id']}" class="xiangqing" href="javascript:void(0);">详情</a>
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
		-->
    <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
        {$show}
    </div>
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
    <!-- /page content -->
	<script>
		function realtime(){
			location.reload();
			console.log("realtime");
		}
		setInterval("realtime()",60000)
	</script>
</block>