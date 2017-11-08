<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>设备管理&nbsp;&nbsp;&nbsp; <small>设备列表</small></h3>
                </div>

          
            </div>

            <div class="clearfix"></div>

            <div class="row">

                </div>

                <div class="clearfix"></div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        

                        <div class="x_content">

                            <p>
                                <a class="btn btn-primary" href="javascript:;" onclick="add()">添加设备</a>
                            </p>
                            <div style="margin-bottom: 10px" class="input-group">
                                <form method="get" action="{:U('Admin/Device/index')}">
                                    <input type="text" class="form-control" style="width: 150px" placeholder="类型" name="type" value="{$type}" id="type"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control" style="width: 150px" placeholder="mac地址" name="mac" value="{$mac}" id="mac"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control" style="width: 150px" placeholder="车位ID" name="info_id" value="{$info_id}" id="info_id"/>&nbsp;&nbsp;&nbsp;&nbsp;
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
                                        <th class="column-title">时间 </th>
                                        <th class="column-title">类型 </th>
                                        <th class="column-title">mac地址 </th>
                                        <th class="column-title">内存 </th>
                                        <th class="column-title">负载 </th>
                                        <th class="column-title">车位ID </th>
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
                                            <td class=" ">{$vo.timestamp} </td>
                                            <td class=" ">{$vo.type} </td>
                                            <td class=" ">{$vo.mac} </td>
                                            <td class=" ">{$vo.gatewayfree} </td>
                                            <td class=" ">{$vo.gatewayload} </td>
                                            <td class="a-right a-right ">{$vo.info_id}</td>
                                            <td class=" last">
                                                <a href="#">
                                                   <a href="javascript:;" navId="{$vo['id']}" navTimestamp="{$vo['timestamp']}" navType="{$vo['type']}" navMac="{$vo['mac']}" navGatewayfree="{$vo['gatewayfree']}" navGatewayload="{$vo['gatewayload']}" navInfoid="{$vo['info_id']}" onclick="edit(this)">修改</a>
                                                    | <a href="javascript:if(confirm('确定删除？'))location='{:U('Admin/Device/delete',array('id'=>$vo['id']))}'">删除</a>
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
                    <h4 class="modal-title" id="myModalLabel"> 添加设备</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Device/add')}" method="post">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">时间：</th>
                                <td>
                                    <input class="input-medium" type="text" name="timestamp">
                                </td>
                            </tr>
                           <tr>
                                <th>类型：</th>
                               <td>
                                    <input class="input-medium" type="text" name="type">
                                </td>
                            </tr>
                            <tr>
                                <th>mac地址：</th>
                                <td>
                                   <input class="input-medium" type="text" name="mac">
                               </td>
                            </tr>
                            <tr>
                                <th>内存：</th>
                                <td>
                                    <input class="input-medium" type="text" name="gatewayFree">
                                </td>
                            </tr>
                           <tr>
                                <th>负载：</th>
                                <td>
                                    <input class="input-medium" type="text" name="gatewayLoad">
                                </td>
                            </tr>
                            <tr>
                                <th>车位ID：</th>
                                <td>
                                    <input class="input-medium" type="text" name="info_id">
                                </td>
                           </tr>
                            <tr>
                                <th></th>
                               <td>
                                    <input class="btn btn-success" type="submit" value="添加">
                                </td>
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
                    <h4 class="modal-title" id="myModalLabel"> 修改设备</h4>
                </div>
                <div class="modal-body">
                    <form id="bjy-form" class="form-inline" action="{:U('Admin/Device/edit')}" method="post">
                        <input type="hidden" name="id">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th width="20%">时间：</th>
                                <td>
                                    <input class="input-medium" type="text" name="timestamp">
                                </td>
                            </tr>
                            <tr>
                                <th>类型：</th>
                                <td>
                                    <input class="input-medium" type="text" name="type">
                                </td>
                            </tr>
                            <tr>
                                <th>mac地址：</th>
                                <td>
                                    <input class="input-medium" type="text" name="mac">
                                </td>
                            </tr>
                            <tr>
                                <th>内存：</th>
                                <td>
                                    <input class="input-medium" type="text" name="gatewayFree">
                                </td>
                            </tr>
                            <tr>
                                <th>负载：</th>
                                <td>
                                    <input class="input-medium" type="text" name="gatewayLoad">
                                </td>
                            </tr>
                            <tr>
                                <th>车位ID：</th>
                                <td>
                                    <input class="input-medium" type="text" name="info_id">
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
            //$("input[name='title'],input[name='mca']").val('');
           // $("input[name='pid']").val(0);
            $('#bjy-add').modal('show');
        }
//        function add2(){
//                art.dialog.open('http://localhost/manbike0.3/index.php/Admin/Bparking/iframeA.html', {title: '提示'});
//
//        }
        // 修改菜单
        function edit(obj){
            var navId=$(obj).attr('navId');
            var navTimestamp=$(obj).attr('navTimestamp');
            var navType=$(obj).attr('navType');
            var navMac=$(obj).attr('navMac');
            var navGatewayfree=$(obj).attr('navGatewayfree');
            var navGatewayload=$(obj).attr('navGatewayload');
            var navInfoid=$(obj).attr('navInfoid');
            $("input[name='id']").val(navId);
            $("input[name='timestamp']").val(navTimestamp);
            $("input[name='type']").val(navType);
            $("input[name='mac']").val(navMac);
            $("input[name='gatewayFree']").val(navGatewayfree);
            $("input[name='gatewayLoad']").val(navGatewayload);
            $("input[name='info_id']").val(navInfoid);
            $('#bjy-edit').modal('show');
        }
    </script>
    <script>
        $("#datatable_paginate a").click(function(){
            var href = $(this).attr("href");
            // $(this).attr("href","#");
            var type = $("#type").val();
            var mac = $("#mac").val();
            var info_id = $("#info_id").val();
            if(type !== ''){
                href = href + '&type='+type;
            }
            if(mac !== ''){
                href = href + '&mac='+mac;
            }
            if(info_id !== ''){
                href = href + '&info_id='+info_id;
            }
            window.location.href=href;
            return false;
        });
    </script>
    <!-- /page content -->
</block>