<extend name="Public:left" />
<block name="main">
    <div class="col-xs-12">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab">
                <li class="active">
                    <a href="{:U('Admin/Rule/admin_user_list')}">管理员列表</a>
                </li>
                <li>
                    <a href="{:U('Admin/Rule/add_admin')}">添加管理员</a>
                </li>
            </ul>
            <div class="tab-content">
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                        <th width="10%">用户名</th>
                        <th>操作</th>
                    </tr>
                    <foreach name="auth_user" item="v">
                        <tr>
                            <input type="hidden" value="$v['id']" name="uid[]"/>
                            <td>
                                {$v['username']}
                            </td>
                            <td>
                                <a href="{:U('Admin/Rule/edit_admin',array('id'=>$v['id']))}">修改权限或密码</a>
                            </td>
                        </tr>
                    </foreach>
                </table>
            </div>
        </div>
    </div>
</block>