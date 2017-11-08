<extend name="Public:left" />
<block name="main">
<script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.js"></script>
<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>

<div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-database  "></i> 所有采集到的数据 </span>
              <div class="count">{$ano}</div>
              <!--<span class="count_bottom"><i class="green">4% </i> </span>-->
            </div>
			<a style="float:right;" id="ss" href="http://116.62.171.54:8082/" target="_blank" type="button" class="btn btn-primary" >弹出分析工具</a>
</div>



<iframe frameborder=0 width=1200 height=600 marginheight=0 marginwidth=0 scrolling="yes" src="http://116.62.171.54:8082/"></iframe>

<script>
	
</script>
</block>
