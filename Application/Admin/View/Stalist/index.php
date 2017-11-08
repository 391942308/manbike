<extend name="Public:left" />
<block name="main">
    <link href="__PUBLIC__/myjslib/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>数据分析&nbsp;&nbsp;&nbsp; <small>车位分时数据统计</small></h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">

        </div>

        <div class="clearfix"></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <span style="color: red">
                        提示：<br>
                        填写车位ID，选择开始时间和结束时间，点击查询即可看到该车位该时间段内停放的车辆数和具体的车辆信息。
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
<!--                    <p><a class="btn btn-primary" href="javascript:;" onclick="add()">添加区块信息</a></p>-->
                    <div style="margin-bottom: 10px" class="input-group">
                        <form method="GET" action="{:U('Admin/Stalist/index')}">
                            <input id="name" type="text" class="form-control" style="width: 150px;margin-bottom: 10px;" placeholder="车位ID" name="dwz_info_id" value="{$dwz_info_id}"/>
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
                            <input type="submit" value="查询" class="btn btn-default"/>
                        </form>
                    </div>

                    <div id="main" style="width: 100%;height:300px;"></div>
                    <input id="iid" type="hidden" value="{$dwz_info_id}" />
                    <if condition="$dwz_info_id neq '' || $start neq '' || $end neq ''">
                        <div class="row tile_count">
                            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                                <span class="count_top"><i class="fa fa-bicycle"></i> 分时停放车辆数 </span>
                                <div class="count">{$stanum}</div>
                            </div>
                            <!--<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                              <span class="count_top"><i class="fa fa-bicycle"></i> 所有停放车辆数 </span>
                              <div class="count">{$all}</div>
                              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
                            </div>-->
                        </div>

                    </if>
                    <div class="table-responsive">
                        <if condition="$dwz_info_id neq '' || $start neq '' || $end neq ''">
                            <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th>

                                    </th>
                                    <th class="column-title">车辆名称 </th>
                                    <th class="column-title">车辆mac地址 </th>
                                    <th class="column-title">信号强度rssi </th>
                                    <th class="column-title">最后采集到的时间 </th>
                                    <th class="column-title">采集到的次数 </th>
                                    </th>
                                    <th class="bulk-actions" colspan="7">
                                        <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <volist name="arr_all" id="vo">
                                    <tr class="even pointer">
                                        <td class="a-center ">

                                        </td>
                                        <td class=" ">{$vo.name}</td>
                                        <td class=" ">{$vo.mac}</td>
                                        <td class=" ">{$vo.rssi}</td>
                                        <td class=" ">{$vo.lasttime}</td>
                                        <td class=" ">{$vo.num}</td>
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
    <script type="text/javascript">

        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
        //var test = genData(50);

        var jstr1 = $.parseJSON('{$str1}');
        var jstr2 = $.parseJSON('{$str2}');

        option = {
            title : {
                text: '分时车位各个公司单车数量',
                //subtext: '纯属虚构',
                x:'left'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
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
                    radius : '55%',
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

    <!-- /page content -->
</block>