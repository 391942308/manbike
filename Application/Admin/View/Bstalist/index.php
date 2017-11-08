<extend name="Public:left" />
<block name="main">
    <link href="__PUBLIC__/myjslib/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <style type="text/css">
        select {
            /*Chrome和Firefox里面的边框是不一样的，所以复写了一下*/
            border: solid 1px #000;

            /*很关键：将默认的select选择框样式清除*/
            appearance:none;
            -moz-appearance:none;
            -webkit-appearance:none;
        }
    </style>
    <!-- page content -->
    <div class="">
        <div class="page-title">
            <if condition="$id neq '' || $start neq '' || $end neq ''">
            <div class="title_left">
                <h3>该时段停放车辆数：{$stanum}</h3>
            </div>
            </if>
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

<!--                    <p><a class="btn btn-primary" href="javascript:;" onclick="add()">添加区块信息</a></p>-->
                    <div style="margin-bottom: 10px" class="input-group">
                        <form method="POST" action="{:U('Admin/Bstalist/index')}">
                            <select name="id" class="btn btn-default">
                                <option value="">请选择社区：</option>
                                <volist name="block_list" id="vo">
                                    <option value="{$vo.id}" <if condition="$vo.id eq $id">selected</if>>{$vo.id}&nbsp;&nbsp;&nbsp;{$vo.title}</option>
                                </volist>
                            </select>
                            <div class="input-group date form_datetime col-md-10" data-date="2017-9-8T00:00:00Z" data-date-format="yyyy-mm-dd hh:mm:ss" data-link-field="dtp_input1">
                                <input name="start" id="start" class="form-control" size="16" type="text" placeholder="开始时间" value="{$start}" readonly style="width: 250px">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                            <div class="input-group date form_datetime col-md-10" data-date="2017-9-8T00:00:00Z" data-date-format="yyyy-mm-dd hh:mm:ss" data-link-field="dtp_input1">
                                <input name="end" id="end" class="form-control" size="16" type="text" placeholder="结束时间" value="{$end}" readonly style="width: 250px">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                            <input type="submit" value="查询" class="btn btn-default"/>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <if condition="$id neq '' || $start neq '' || $end neq ''">
                            <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th>

                                    </th>
                                    <th class="column-title">车辆名称 </th>
                                    <th class="column-title no-link last"><span class="nobr">车辆mac地址</span>
                                    </th>
                                    <th class="bulk-actions" colspan="7">
                                        <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <volist name="stalist3" id="vo">
                                    <tr class="even pointer">
                                        <td class="a-center ">

                                        </td>
                                        <td class=" ">{$vo.name}</td>
                                        <td class="last">{$vo.mac}</td>
                                    </tr>
                                </volist>
                                </tbody>
                            </table>
                        </if>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript" src="__PUBLIC__/myjslib/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="__PUBLIC__/myjslib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/myjslib/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="__PUBLIC__/myjslib/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    <script type="text/javascript">
        $('.form_datetime').datetimepicker({
            //language:  'fr',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });

    </script>

    <!-- /page content -->
</block>