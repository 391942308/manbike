<extend name="Public:left" />
<block name="main">
    <link href="__PUBLIC__/myjslib/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

    <script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
    <script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <script type="text/javascript" src="__PUBLIC__/myjslib/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="__PUBLIC__/myjslib/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    <style>
        table tr td{
            padding-right: 15px;
        }
        .c_submit{
            padding-left: 40px;
        }
        .date2{
            margin-top: 10px;
        }
        .c_submit2{
            padding-top: 5px;
        }
        .pca{
            height: 34px;width: 100px;text-align: center;
        }
    </style>
    <!-- page content -->
    <input type="hidden" value="{$tj}" id="returntj"/>
    <input type="hidden" value="{$area}" id="h_area"/>
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>车位峰谷值管理&nbsp;&nbsp;&nbsp; <small>车位峰谷值趋势图</small></h3>
            </div>
        </div>
        <div class="row">

        </div>

        <div class="clearfix"></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div style="margin-bottom: 10px;" class="input-group" id="tj1">
                        <form method="GET" action="{:U('Admin/Pevatrend/index')}">
                            <table>
                                <tr>
                                    <td>
                                        <select class="form-control" style="width: 200px;height:35px;margin-bottom: 10px;" name="dwz_info_id">
                                            <option value="请选择车位">请选择车位：</option>
                                            <volist name="did_arr" id="vo">
                                                <option value="{$vo.id}" <if condition='$dwz_info_id eq $vo["id"]'>selected</if>>{$vo.id}&nbsp;&nbsp;{$vo.title}</option>
                                            </volist>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group date date1 form_datetime col-md-10" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1">
                                            <input name="start" id="start" class="form-control" size="16" type="text" placeholder="开始时间" value="{$start}" readonly style="width: 250px">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group date date1 form_datetime col-md-10" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1">
                                            <input name="end" id="end" class="form-control" size="16" type="text" placeholder="结束时间" value="{$end}" readonly style="width: 250px">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                    </td>
                                    <td class="c_submit">
                                        <input type="submit" value="查询" class="btn btn-default"/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div id="main" style="width: 100%;height:610px;">

                    </div>
                    <input id="iid" type="hidden" value="{$dwz_info_id}" />
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    //var test = genData(50);
//    app.title = '坐标轴刻度与标签对齐';
    var j_ts = $.parseJSON('{$j_ts}');
    var j_max = $.parseJSON('{$j_max}');
    var j_min = $.parseJSON('{$j_min}');
    option = {
        title : {
            text: '车位峰谷值趋势图'
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['最大值','最小值']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                data : j_ts
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'最大值',
                type:'line',
                data:j_max
            },
            {
                name:'最小值',
                type:'line',
                data:j_min
            }
        ]
    };
    myChart.setOption(option);
</script>

    <script src="__PUBLIC__/JS/distpicker.js"></script>
    <script>
        var area = $("#h_area").val();
        options = {
            province: '浙江省',
            city:'杭州市' ,
            district: area,
        };
        $('#target').distpicker(options);
    </script>
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