<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>车辆管理&nbsp;&nbsp;&nbsp; <small>车辆信息列表</small></h3>
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
<!--
                            <p><a class="btn btn-primary" href="javascript:;" onclick="add()">添加车辆信息</a></p>
                            <div style="margin-bottom: 10px" class="input-group">
                                <form method="GET" action="{:U('Admin/Bike/index')}">
                                    <input id="name" type="text" class="form-control" style="width: 150px" placeholder="车辆名称" name="name" value="{$name}"/>
                                    <input id="mac" type="text" class="form-control" style="width: 150px" placeholder="mac地址" name="mac" value="{$mac}"/>
                                    <input id="dwz_info_id" type="text" class="form-control" style="width: 150px" placeholder="车位ID" name="dwz_info_id" value="{$dwz_info_id}"/>
                                    <input type="submit" value="查询" class="btn btn-default"/>
                                </form>
                            </div>-->
                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>

                                        </th>
                                        <th class="column-title">车位ID </th>
                                        <th class="column-title no-link last"><span class="nobr">操作</span>
                                        </th>
                                        <th class="bulk-actions" colspan="7">
                                            <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <volist name="a_menu_list" id="vo">
                                        <tr class="even pointer">
                                            <td class="a-center ">

                                            </td>
                                            <td class=" ">{$vo}</td>
                                            <td class=" last">
                                                <a href="#">
                                                    <a id="{$vo}" class="xq" href="javascript:void(0)">详情</a>
													
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
       
    
    <!-- /page content -->
	<script>
	$(".xq").click(function(){
		var mac = '{$mac}';
		var iid = $(this).attr("id");
		//alert(mac);
		//alert(iid);
		var url = "http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/infobikedetail";
		url=url + '/id/'+iid+'/mac/'+mac;
		location.href=url;
	})
	</script>
</block>