<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <script src="__PUBLIC__/JS/distpicker.js"></script>
    <!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>权限配置&nbsp;&nbsp;&nbsp; <small>用户组列表</small></h3>
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

                    <p><a class="btn btn-primary" href="javascript:;" onclick="add()">添加用户组</a></p>

                    <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                            <tr class="headings">
                                <th>

                                </th>
                                <th class="column-title">用户组名称 </th>
                                <th class="column-title">省 </th>
                                <th class="column-title">市 </th>
                                <th class="column-title">区 </th>
                                <th class="column-title">行政级别 </th>
                                <th class="column-title no-link last"><span class="nobr">操作</span>
                                </th>
                                <th class="bulk-actions" colspan="7">
                                    <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <volist name="auth_group" id="vo">
                                <tr class="even pointer">
                                    <td class="a-center ">

                                    </td>
                                    <td class=" ">{$vo.title} </td>
                                    <td class=" ">{$vo.province} </td>
                                    <td class=" ">{$vo.city} </td>
                                    <td class=" ">{$vo.area} </td>
                                    <td class=" ">{$vo.class} </td>
                                    <td class=" last">
                                        <a href="#">
                                                | <a href="javascript:if(confirm('确定删除？'))location='{:U('Admin/Rule/delete_group',array('id'=>$vo['id']))}'">删除</a>
                                            | <a href="{:U('Admin/Rule/rule_group',array('id'=>$vo['id']))}">分配权限</a>
                                            | <a href="{:U('Admin/Rule/check_user',array('group_id'=>$vo['id']))}">添加成员</a>
                                            | <a href="{:U('Admin/Rule/check_block',array('group_id'=>$vo['id']))}">分配区块权限</a>
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
                    <h4 class="modal-title" id="myModalLabel"> 添加用户组</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline onef" action="{:U('Admin/Rule/add_group')}" method="post">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="18%">用户组名：</th>
                                <td>
                                    <input class="input-medium" type="text" name="title" >
                                </td>
                            </tr>
                            <tr>
                                <th width="18%">地区：</th>
                                <td>
<!--                                    <div data-toggle="distpicker" data-autoselect="3" data-province="浙江省">-->
<!--                                        <select name="province" class="province1"></select>-->
<!--                                        <select name="city"></select>-->
<!--                                        <select name="area"></select>-->
<!--                                    </div>-->
                                    <input type="text" name="province" placeholder="省" class="form-control"/>
                                    <input type="text" name="city" placeholder="市" class="form-control"/>
                                    <input type="text" name="area" placeholder="区" style="margin-top: 5px;" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <th width="18%">行政级别：</th>
                                <td>
                                    <div>
                                        <select name="class">
                                            <option value="省级">省级</option>
                                            <option value="市级">市级</option>
                                            <option value="区级">区级</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input class="btn btn-success" type="button" value="添加" onclick="ispro()">
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
                    <h4 class="modal-title" id="myModalLabel"> 修改用户组</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline twof" action="{:U('Admin/Rule/edit_group')}" method="post">
                        <input type="hidden" name="id">
                        <input type="hidden" name="pid">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="18%">用户组名：</th>
                                <td> <input class="input-medium" type="text" name="title">
                                </td>
                            </tr>
                            <tr>
                                <th width="18%">地区：</th>
                                <td>
<!--                                    <div data-toggle="distpicker" >-->
<!--                                        <select name="province" class="province2"></select>-->
<!--                                        <select name="city"></select>-->
<!--                                        <select name="area"></select>-->
<!--                                    </div>-->
                                    <input type="text" name="province" placeholder="省" class="form-control" value="{$province}"/>
                                    <input type="text" name="city" placeholder="市" class="form-control" value="{$city}"/>
                               <input type="text" name="area" placeholder="区" style="margin-top: 5px;" class="form-control" value="{$area}"/>
                                </td>
                            </tr>
                            <tr>
                                <th width="18%">行政级别：</th>
                                <td>
                                    <div>
                                        <select name="class">
                                            <option value="省级" <if condition="$class eq '省级'">selected</if>>省级</option>
                                            <option value="市级" <if condition="$class eq '市级'">selected</if>>市级</option>
                                            <option value="区级" <if condition="$class eq '区级'">selected</if>>区级</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input class="btn btn-success" type="button" value="修改" onclick="ispro2()">
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
            $("input[name='mca']").val('');
            $("input[name='icon']").val('');
            $('#bjy-add').modal('show');
        }

        // 修改菜单
        function edit(obj){
            var navId=$(obj).attr('navId');
            var navTitle=$(obj).attr('navTitle');
            var navProvince=$(obj).attr('navProvince');
            var navCity=$(obj).attr('navCity');
            var navArea=$(obj).attr('navArea');
            var navClass=$(obj).attr('navClass');
//            var navProvince=$(obj).attr('navProvince');
//            var navCity=$(obj).attr('navCity');
//            var navArea=$(obj).attr('navArea');
            $("input[name='id']").val(navId);
            $("input[name='title']").val(navTitle);
            $("input[name='province']").val(navProvince);
            $("input[name='city']").val(navCity);
            $("input[name='area']").val(navArea);
            $("select[name='class']").val(navClass);
//            $("select[name='province']").val(navProvince);
//            $("select[name='city']").val(navCity);
//            $("select[name='area']").val(navArea);
            $('#bjy-edit').modal('show');
        }
        //判断是否选择了省
        function ispro(){
            var province=$(".province1").val();
           // alert(province);
           // return false;
            if(province==''){
                alert('请选择省');
                return false;
            }else{
                $(".onef").submit();
            }
        }
        function ispro2(){
            var province2=$(".province2").val();
            if(province2==''){
                alert('请选择省');
                return false;
            }else{
                $(".twof").submit();
            }
        }
    </script>

</block>
