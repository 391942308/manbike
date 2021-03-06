<extend name="Public:left" />
<block name="main">
    <div class="page-header">
        <h1><i class="fa fa-home"></i> 首页 &gt; 权限管理 &gt; 添加管理员</h1>
    </div>
    <div class="col-xs-12">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab">
                <li>
                    <a href="{:U('Admin/Rule/admin_user_list')}">管理员列表</a>
                </li>
                <li class="active">
                    <a href="{:U('Admin/Rule/add_admin')}">添加管理员</a>
                </li>
            </ul>
            <div class="tab-content">
                <form class="form-inline" method="post">
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <tr>
                            <th>管理组</th>
                            <td>
                                <foreach name="auth_group" item="v"> {$v['title']}
                                    <input class="xb-icheck" type="checkbox" name="group_ids[]" value="{$v['id']}"> &emsp;
                                </foreach>
                            </td>
                        </tr>
                        <tr>
                            <th>用户名</th>
                            <td>
                                <input class="input-medium" type="text" name="username">
                            </td>
                        </tr>
                        <tr>
                            <th>初始密码</th>
                            <td>
                                <input class="input-medium" type="text" name="password">
                            </td>
                        </tr>
                        <tr>
                            <th>

                            </th>
                            <td>
                                <input class="btn btn-success" type="submit" value="添加">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</block>