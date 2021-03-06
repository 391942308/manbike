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
                <h3>车辆增长管理&nbsp;&nbsp;&nbsp; <small>车辆增长数据统计</small></h3>
            </div>
        </div>
        <div class="row">

        </div>

        <div class="clearfix"></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <span style="color: red">
                        提示：填写车位，选择开始时间和结束时间，点击查询即可看到该时间段车辆新增情况。选择行政区，选择开始时间和结束时间，点击查询即可看到该时间段车辆新增情况。不选看到的是总的车辆新增数据。
                    </span>
                    <ul class="nav navbar-right panel_toolbox">
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                        <select class="form-control" style="width: 200px;height:35px;margin-bottom: 10px;" id="tj">
                            <option value="1" <if condition="$tj eq 1">selected</if>>根据车位查询</option>
                            <option value="2" <if condition="$tj eq 2">selected</if>>根据行政区查询</option>
                        </select>
                    <div style="margin-bottom: 10px;" class="input-group" id="tj1">
                        <form method="GET" action="{:U('Admin/Growthbikes/index',array('tj'=>1))}">
                            <table>
                                <tr>
                                    <td>
                                        <select class="form-control" style="width: 200px;height:35px;margin-bottom: 10px;" name="dwz_info_id">
                                            <option value="请选择车位">请选择车位：</option>
                                            <volist name="arr_id" id="vo">
                                                <option value="{$vo.id}" <if condition='$dwz_info_id eq $vo["id"]'>selected</if>>{$vo.id}&nbsp;&nbsp;{$vo.title}</option>
                                            </volist>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group date date1 form_datetime col-md-10" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input1">
                                            <input name="start" id="start" class="form-control" size="16" type="text" placeholder="开始时间" value="{$start}" readonly style="width: 250px">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group date date1 form_datetime col-md-10" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input1">
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
                    <div style="margin-bottom: 10px;" class="input-group" id="tj2">
                        <form method="GET" action="{:U('Admin/Growthbikes/index',array('tj'=>2))}">
                            <table>
                                <tr>
                                    <td>
                                        <if condition="$uid eq 1">
                                            <div data-toggle="distpicker" id="target">
                                                <select name="province" style="height: 34px" ></select>
                                                <select name="city" style="height: 34px"></select>
                                                <select name="area" style="height: 34px"></select>
                                            </div>
                                            <else />
                                                <div>
                                                    <input type="text" class="pca" name="province"  value="{$province2}" readonly="readonly"/>
                                                    <input type="text" class="pca" name="city" value="{$city2}" readonly="readonly"/>
                                                    <input type="text" class="pca" name="area"  value="{$area2}" readonly="readonly"/>
                                                </div>
                                        </if>

                                    </td>
                                    <td>
                                        <div class="input-group date date2 form_datetime col-md-10" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input1">
                                            <input name="start" id="start" class="form-control" size="16" type="text" placeholder="开始时间" value="{$start}" readonly style="width: 250px">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group date date2 form_datetime col-md-10" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input1">
                                            <input name="end" id="end" class="form-control" size="16" type="text" placeholder="结束时间" value="{$end}" readonly style="width: 250px">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                    </td>
                                    <td class="c_submit2">
                                        <input type="submit" value="查询" class="btn btn-default"/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="over" style="z-index: 50;position:relative;">
                        <div class="allbike" style="position:absolute;right:183px;margin-top: 5px;">
                            <a><img class="allbike12" src="__PUBLIC__/images/allbike.png"/></a>
                        </div>

                    </div>
                    <div class="over2" style="z-index: 50;position:relative;">
                        <div class="allbike2" style="position:absolute;right:210px;margin-top: 5px;">
                            <a><img class="allbike34" src="__PUBLIC__/images/allbike4.png"/></a>
                        </div>
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
    var j_kq = $.parseJSON('{$j_kq}');
    var j_kq_color = $.parseJSON('{$j_kq_color}');
    var j_mb = $.parseJSON('{$j_mb}');
    var j_mb_color = $.parseJSON('{$j_mb_color}');
    var j_xm = $.parseJSON('{$j_xm}');
    var j_xm_color = $.parseJSON('{$j_xm_color}');
    var j_ofo = $.parseJSON('{$j_ofo}');
    var j_ofo_color = $.parseJSON('{$j_ofo_color}');
    var j_hb = $.parseJSON('{$j_hb}');
    var j_hb_color = $.parseJSON('{$j_hb_color}');
    var j_ts = $.parseJSON('{$j_ts}');
    var j_sum = $.parseJSON('{$j_sum}');
    option = {
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            },
            formatter: function (params){
                var str = '';
                var sum = 0;
                var size = params.length;
                str+=params[0].name + '<br/>';
                for(var i=0;i<size;i++){
                    sum+=params[i].value;
                    str+=params[i].seriesName + ':' + params[i].value + '<br/>';
                }
                str+='总量:'+sum;
                return str
            }
        },
        legend: {
            data:['酷骑', '摩拜','小鸣单车','ofo','HelloBike']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
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
                type : 'value',
                boundaryGap: [0, 0.1]
            }
        ],
        series : [
            {
                name:'酷骑',
                type:'line',
                tiled: 'sum',
//                barCategoryGap: '50%',
                itemStyle: {
                    normal: {
                        color: j_kq_color,
                        barBorderColor: j_kq_color,
                        barBorderWidth: 6,
                        barBorderRadius:0,
//                        label : {
//                            show: false, position: 'insideTop'
//                        }
                    }
                },
                data:j_kq
            },
            {
                name:'摩拜',
                type:'line',
                tiled: 'sum',
                itemStyle: {
                    normal: {
                        color: j_mb_color,
                        barBorderColor: j_mb_color,
                        barBorderWidth: 6,
                        barBorderRadius:0,
//                        label : {
//                            show: true, position: 'insideTop'
//                        }
                    }
                },
                data:j_mb
            },
            {
                name:'小鸣单车',
                type:'line',
                tiled: 'sum',
                itemStyle: {
                    normal: {
                        color: j_xm_color,
                        barBorderColor: j_xm_color,
                        barBorderWidth: 6,
                        barBorderRadius:0,
//                        label : {
//                            show: true, position: 'insideTop'
//                        }
                    }
                },
                data:j_xm
            },
            {
                name:'ofo',
                type:'line',
                tiled: 'sum',
                itemStyle: {
                    normal: {
                        color: j_ofo_color,
                        barBorderColor: j_ofo_color,
                        barBorderWidth: 6,
                        barBorderRadius:0,
//                        label : {
//                            show: true, position: 'insideTop'
//                        }
                    }
                },
                data:j_ofo
            },
            {
                name:'HelloBike',
                type:'line',
                tiled: 'sum',
                itemStyle: {
                    normal: {
                        color:j_hb_color ,
                        barBorderColor:j_hb_color ,
                        barBorderWidth: 1,
                        barBorderRadius:0,
//                        label : {
//                            show: true, position: 'insideTop'
//                        }
                    }
                },
                data:j_hb
            },
        ]
    };
    option2 = {
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            },
            formatter: function (params){
                var str = '';
                var sum = 0;
                var size = params.length;
                str+=params[0].name + '<br/>';
                for(var i=0;i<size;i++){
                    sum+=params[i].value;
                    str+=params[i].seriesName + ':' + params[i].value + '<br/>';
                }
                str+='总量:'+sum;
                return str
            }
        },
        legend: {
            data:['车辆总量']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
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
                type : 'value',
                boundaryGap: [0, 0.1]
            }
        ],
        series : [
            {
                name:'车辆总量',
                type:'line',
                stack: 'sum',
                itemStyle: {
                    normal: {
                        color: '#8FBC8F',
                        barBorderColor: '#8FBC8F',
                        barBorderWidth: 6,
                        barBorderRadius:0,
//                        label : {
//                            show: false, position: 'insideTop'
//                        }
                    }
                },
                data:j_sum
            }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    $(function(){
        $(".over").mouseover(
            function(){
                $(".allbike12").css("padding-left","10px");
                $(".allbike12").attr("src","__PUBLIC__/images/allbike2.png");
            }
        );
        $(".over").mouseout(
            function(){
                $(".allbike12").attr("src","__PUBLIC__/images/allbike.png");
            }
        );
        $(".over2").mouseover(
            function(){
                $(".allbike34").css("padding-left","10px");
                $(".allbike34").attr("src","__PUBLIC__/images/allbike3.png");
            }
        );
        $(".over2").mouseout(
            function(){
                $(".allbike34").attr("src","__PUBLIC__/images/allbike4.png");
            }
        );
        $(".allbike34").attr("src","__PUBLIC__/images/allbike6.png");
        myChart.clear();
        myChart.setOption(option);
    });
    $(".allbike12").click(function(){
        $(this).attr("src","__PUBLIC__/images/allbike5.png");
        $(".allbike34").attr("src","__PUBLIC__/images/allbike4.png");
        myChart.clear();
        myChart.setOption(option2);

    });
    $(".allbike34").click(function(){
        $(this).attr("src","__PUBLIC__/images/allbike6.png");
        $(".allbike12").attr("src","__PUBLIC__/images/allbike.png");
        myChart.clear();
        myChart.setOption(option);
    });

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