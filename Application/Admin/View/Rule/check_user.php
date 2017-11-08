<extend name="Public:left" />
<block name="main">
    <!-- page content -->
        <div class="page-header">
            <h1> 首页&gt; 用户组列表&gt; 用户组添加用户</h1>
        </div>
        <div class="col-xs-12">
            <div class="tabbable">
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                        <th width="10%"> 搜索用户名：</th>
                        <td>
                            <form class="form-inline" action="">
                                <input class="input-medium" type="text" name="username" value="{$Think.get.username}">
                                <input class="btn btn-sm btn-success" type="submit" value="搜索">
                            </form>
                        </td>
                    </tr>
                </table>
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                        <th width="10%">用户名</th>
                        <th>操作</th>
                    </tr>
                    <foreach name="user_data" item="v">
                        <tr>
                            <th>{$v['username']}</th>
                            <td>
                                <if condition="in_array($v['id'],$uids)">
                                    已经是{$group_name}
                                    <else/>
                                    <a href="{:U('Admin/Rule/add_user_to_group',array('uid'=>$v['id'],'group_id'=>$_GET['group_id'],'username'=>$_GET['username']))}">设为{$group_name}</a>
                                </if>
                            </td>
                        </tr>
                    </foreach>
                </table>
            </div>
        </div>
</block>
