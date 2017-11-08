<extend name="Public:left" />
<block name="main">
    <link href="http://116.62.171.54:8080/manbike0.3/Public/myjslib/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <script src="__PUBLIC__/JS/distpicker.js"></script>
    <!-- page content -->
    <input type="hidden" value="{$tj}" id="returntj"/>
    <input type="hidden" value="{$area}" id="h_area"/>
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>车辆增长管理&nbsp;&nbsp;&nbsp; <small>车辆增长数据统计</small></h3>
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
                        填写车位ID，选择开始时间和结束时间，点击查询即可看到该时间段车辆新增情况。<br>
                        不选看到的是总的车辆新增数据。
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
                    <select class="form-control" style="width: 200px;height:35px;" id="tj">
                        <option value="1" <if condition="$tj eq 1">selected</if>>根据车位查询</option>
                        <option value="2" <if condition="$tj eq 2">selected</if>>根据行政区查询</option>
                    </select>
                    <div style="height: 30px"></div>
                    <div style="margin-bottom: 10px;" class="input-group" id="tj1">
                        <form method="GET" action="{:U('Admin/Growthbikes/index',array('tj'=>1))}">
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
                    <div style="margin-bottom: 10px;" class="input-group" id="tj2">
                        <form method="GET" action="{:U('Admin/Growthbikes/index',array('tj'=>2))}">
                            <div data-toggle="distpicker" id="target">
                                <select name="province" style="height: 30px" ></select>
                                <select name="city" style="height: 30px"></select>
                                <select name="area" style="height: 30px"></select>
                            </div>
                            <div style="height: 20px"></div>
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
                </div>
            </div>
        </div>
    </div>
<div id="main" style="width: 100%;height:450px;"></div>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    //var test = genData(50);
//    app.title = '坐标轴刻度与标签对齐';
    var jstr1 = $.parseJSON('{$str1}');
    var jstr2 = $.parseJSON('{$str2}');
    option = {
        color: ['#3398DB'],
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                data : jstr2,
                axisTick: {
                    alignWithLabel: true
                }
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'直接访问',
                type:'bar',
                barWidth: '60%',
                data:jstr1
            }
        ]
    };


    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>
    <script>
        var area = $("#h_area").val();
        options = {
            province: '浙江省',
            city:'杭州市' ,
            district: area,
        };
        $('#target').distpicker(options);

    </script>
    <script type="text/javascript" src="http://116.62.171.54:8080/manbike0.3/Public/myjslib/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="http://116.62.171.54:8080/manbike0.3/Public/myjslib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://116.62.171.54:8080/manbike0.3/Public/myjslib/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="http://116.62.171.54:8080/manbike0.3/Public/myjslib/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
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
    <script>
        $(function(){
            $("#tj2").hide();
            var returntj = $("#returntj").val();
            if(returntj==1){
                $("#tj1").show();
                $("#tj2").hide();
            }else if(returntj==2){
                $("#tj1").hide();
                $("#tj2").show();
            }
        });
        $("#tj").change(function(){
            var tj = $("#tj").val();
            if(tj==1){
                $("#tj1").show();
                $("#tj2").hide();
            }else if(tj==2){
                $("#tj1").hide();
                $("#tj2").show();
            }
        });
    </script>

<!-- /page content -->
</block>