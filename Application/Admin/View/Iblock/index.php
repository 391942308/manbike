<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>区块管理&nbsp;&nbsp;&nbsp; <small>区块列表</small></h3>
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

                            <p><a class="btn btn-primary" href="javascript:;" onclick="add()">添加区块信息</a></p>
                            <div style="margin-bottom: 10px" class="input-group">
                                <form method="GET" action="{:U('Admin/Iblock/index')}">
                                    <input id="name" type="text" class="form-control" style="width: 150px" placeholder="区块名称" name="title" value="{$title}"/>
                                    <input type="submit" value="查询" class="btn btn-default"/>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>

                                        </th>
                                        <th class="column-title">编号 </th>
                                        <th class="column-title">区块名称 </th>
                                        <th class="column-title">内容 </th>
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
                                            <td class=" ">{$vo.id}</td>
                                            <td class=" ">{$vo.title}</td>
                                            <td class=" ">{$vo.content} </td>
                                            <td class=" last">
                                                <a href="#">
                                                    <a href="javascript:;" navId="{$vo['id']}" navTitle="{$vo['title']}" navContent="{$vo['content']}" onclick="edit(this)">修改</a>
                                                    |<a href="javascript:if(confirm('确定删除？'))location='{:U('Admin/Iblock/delete',array('id'=>$vo['id']))}'">删除</a>
                                                    |<a href="{:U('Admin/Iblock/exist',array('id'=>$vo['id']))}">实时停放车辆</a>
                                                    |<a href="{:U('Admin/Iblock/sall',array('id'=>$vo['id']))}">历史停放车辆</a>
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
                    <h4 class="modal-title" id="myModalLabel"> 添加区块信息</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Iblock/add')}" method="post">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">区块名称：</th>
                                <td>
                                    <input class="input-medium" type="text" name="title">
                                </td>
                            </tr>
                            <tr>
                                <th>内容：</th>
                                <td>
                                    <textarea name="content" class="form-control"></textarea>
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
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bjy-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                    <h4 class="modal-title" id="myModalLabel"> 修改区块信息</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Iblock/edit')}" method="post">
                        <input type="hidden" name="id">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">区块名称：</th>
                                <td>
                                    <input class="input-medium" type="text" name="title">
                                </td>
                            </tr>
                            <tr>
                                <th>内容：</th>
                                <td>
                                    <textarea name="content" id="m_content" class="form-control"></textarea>
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
                </div>
            </div>
        </div>
    </div>
        <script type="text/javascript">
        // 添加菜单
        function add(){
            $("input[name='title'],input[name='mca']").val('');
            $('#bjy-add').modal('show');
        }
        // 修改菜单
            //navName="{$vo['name']}" navMac="{$vo['mac']}" navRssi="{$vo['rssi']}" navInfoid="{$vo['dwz_info_id']}" navLng_Lat="{$vo['lng']},{$vo['lat']}"
        function edit(obj){
            var navId=$(obj).attr('navId');
            var navTitle=$(obj).attr('navTitle');
            var navContent=$(obj).attr('navContent');
            $("input[name='id']").val(navId);
            $("input[name='title']").val(navTitle);
            $("#m_content").val(navContent);
            $('#bjy-edit').modal('show');
        }
    </script>
    <script>
        $("#datatable_paginate a").click(function(){
            var href = $(this).attr("href");
            // $(this).attr("href","#");
            var title = $("#title").val();
            if(title !=''){
                href = href + '&title='+title;
            }
            //alert(href);
            window.location.href=href;
            return false;
        });
    </script>
    <!-- /page content -->
</block>