<extend name="Public:left" />
<block name="main">
    <div class="page-header">
        <h1><i class="fa fa-home"></i> 首页 &gt; 后台管理 &gt; 修改管理员</h1>
    </div>
    <div class="col-xs-12">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab">
                <li>
                    <a href="{:U('Admin/Rule/admin_user_list')}">管理员列表</a>
                </li>
                <li class="active">
                    <a href="{:U('Admin/Rule/add_admin')}">修改管理员</a>
                </li>
            </ul>
            <div class="tab-content">
                <form class="form-inline" method="post">
                    <input type="hidden" name="id" value="{$user_data['id']}">
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <tr>
                            <th>管理组</th>
                            <td>
                                <foreach name="data" item="v"> {$v['title']}
                                    <input class="xb-icheck" type="checkbox" name="group_ids[]" value="{$v['id']}" <in name="v['id']" value="$group_data"> checked="checked"</in> > &emsp;
                                </foreach>
                            </td>
                        </tr>
                        <tr>
                            <th>用户名</th>
                            <td>
                                <input class="input-medium" type="text" name="username" value="{$user_data['username']}">
                            </td>
                        </tr>
                        <tr>
                            <th>初始密码</th>
                            <td>
                                <input class="input-medium" type="text" name="password">如不改密码；留空即可
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td> <input class="btn btn-success" type="submit" value="修改"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</block>