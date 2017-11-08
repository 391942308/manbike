<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
    <script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
    <div class="page-title">
        <div class="title_left" style="margin-bottom: 20px;">
            <h3>车辆管理&nbsp;&nbsp;&nbsp; <small>详情</small></h3>
            <span class="count_bottom"><i class="green">（间隔60秒自动刷新） </i> </span>
        </div>
    </div>
    <a style="float:right;" id="ss" href="http://116.62.171.54:8082/" target="_blank" type="button" class="btn btn-primary">弹出分析工具</a>
    <iframe src="{$href}" width="90%" height="600px" frameborder="0"></iframe>
<!--    60秒更新一次-->
    <script>
        function realtime(){
            location.reload();
            console.log("realtime");
        }
        setInterval("realtime()",60000)
    </script>
</block>
