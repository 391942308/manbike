<extend name="Public:left" />
<block name="main">
    <!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>菜单配置&nbsp;&nbsp;&nbsp; <small>菜单列表</small></h3>
                </div>

               
            </div>

            <div class="clearfix"></div>

            <div class="row">

                </div>

                <div class="clearfix"></div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        

                        <div class="x_content">

                            <p><a class="btn btn-primary" href="javascript:;" onclick="add()">添加菜单</a></p>

                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                    <tr class="headings">
                                        <th>

                                        </th>
                                        <th class="column-title">ID </th>
                                        <th class="column-title">菜单名称 </th>
                                        <th class="column-title">父菜单ID </th>
                                        <th class="column-title">图标 </th>
                                        <th class="column-title">链接 </th>
                                        <th class="column-title">菜单排序 </th>
                                        <th class="column-title">是否禁用（0为禁用，1为正常） </th>
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
                                            <td class=" ">{$vo.title} </td>
                                            <td class=" ">{$vo.pid} </td>
                                            <td class=" ">{$vo.icon} </td>
                                            <td class="a-right a-right ">{$vo.name}</td>
                                            <td class=" ">{$vo.sort} </td>
                                            <td class=" ">{$vo.status} </td>
                                            <td class=" last">
                                                <a href="#">
                                                    <a href="javascript:;" navId="{$vo['id']}" navName="{$vo['title']}" onclick="add_child(this)">添加子菜单</a>
                                                    | <a href="javascript:;" navId="{$vo['id']}" navTitle="{$vo['title']}" navName="{$vo['name']}" navIco="{$vo['icon']}" navPid="{$vo['pid']}" navSort="{$vo['sort']}" navStatus="{$vo['status']}" onclick="edit(this)">修改</a>
                                                    | <a href="javascript:if(confirm('确定删除？'))location='{:U('Admin/Menu/delete',array('id'=>$vo['id']))}'">删除</a>
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
                    <h4 class="modal-title" id="myModalLabel"> 添加菜单</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Menu/add')}" method="post">
                        <input type="hidden" name="pid" value="0">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="12%">菜单名：</th>
                                <td>
                                    <input class="input-medium" type="text" name="title">
                                </td>
                            </tr>
                            <tr>
                                <th>链接：</th>
                                <td>
                                    <input class="input-medium" type="text" name="name">
                                    输入模块/控制器/方法即可 例如 Admin/Nav/index
                                </td>
                            </tr>
                            <tr>
                                <th>图标：</th>
                                <td>
                                    <input class="input-medium" type="text" name="icon">
                                    font-awesome图标 输入fa fa- 后边的即可
                                </td>
                            </tr>
                            <tr>
                                <th>排序：</th>
                                <td>
                                    <input class="input-medium" type="text" name="sort">
                                    数值越大越靠前
                                </td>
                            </tr>
                            <tr>
                                <th>是否禁用：</th>
                                <td>
                                    <input class="input-medium" type="text" name="status">
                                    （0为禁用 1为正常）
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
                    <h4 class="modal-title" id="myModalLabel"> 修改菜单</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Menu/edit')}" method="post">
                        <input type="hidden" name="id">
                        <input type="hidden" name="pid">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="12%">菜单名：</th>
                                <td> <input class="input-medium" type="text" name="title">
                                </td>
                            </tr>
                            <tr>
                                <th>链接：</th>
                                <td>
                                    <input class="input-medium" type="text" name="name">
                                    输入模块/控制器/方法即可 例如 Admin/Nav/index
                                </td>
                            </tr>
                            <tr>
                                <th>图标：</th>
                                <td>
                                    <input class="input-medium" type="text" name="icon">
                                    font-awesome图标 输入fa fa- 后边的即可
                                </td>
                            </tr>
                            <tr>
                                <th>排序：</th>
                                <td>
                                    <input class="input-medium" type="text" name="sort">
                                    数值越大越靠前
                                </td>
                            </tr>
                            <tr>
                                <th>是否禁用：</th>
                                <td>
                                    <input class="input-medium" type="text" name="status">
                                    （0为禁用 1为正常）
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
        function edit(obj){
            var navId=$(obj).attr('navId');
            var navName=$(obj).attr('navName');
            var navTitle=$(obj).attr('navTitle');
            var navIco=$(obj).attr('navIco');
            var navPid=$(obj).attr('navPid');
            var navSort=$(obj).attr('navSort');
            var navStatus=$(obj).attr('navStatus');
            $("input[name='id']").val(navId);
            $("input[name='title']").val(navTitle);
            $("input[name='name']").val(navName);
            $("input[name='icon']").val(navIco);
            $("input[name='pid']").val(navPid);
            $("input[name='sort']").val(navSort);
            $("input[name='status']").val(navStatus);
            $('#bjy-edit').modal('show');
        }
    </script>
    <!-- /page content -->
</block>