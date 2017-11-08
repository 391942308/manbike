<extend name="Public:left" />
<block name="main">
    <script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.js"></script>
    <!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>用户组列表&nbsp;&nbsp;&nbsp; <small>分配区块权限</small></h3>
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
                    <form id="f_blocks" method="post" action="{:U('Rule/check_block')}">
                        <input type="hidden" name="ids" id="ids" value="" />
                        <input type="hidden" name="group_id"  value="<?php echo $_GET['group_id'] ?>" />
                        <div class="table-responsive">
                            <h4>区块名称：</h4>
                            <volist name="block_list" id="vo">
                                {$vo.title}&nbsp;<input type="checkbox" value="{$vo.id}" name="blocks"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </volist>
                            <div style="margin-left:60px;margin-top: 20px">
                                <input type="button" value="提交" class="btn btn-success" onclick="subblocks()"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
       function subblocks(){
           var ids = '';
           var cinputs=$("input[name='blocks']");
           for (var i = 0; i < cinputs.length; i++) {
               if(cinputs[i].checked){
                   ids+=cinputs[i].value+',';
               }
           }
           $("#ids").val(ids);
            $("#f_blocks").submit();
       }
    </script>

</block>
