<extend name="Public:left" />
<block name="main">
    <!-- page content -->
    <div class="col-xs-12">
            <div class="tabbable">
                <div class="title_left">
                    <h3>用户组列表&nbsp;&nbsp;&nbsp; <small>分配权限</small></h3>
                </div>
                <div class="tab-content">
                    <h1 class="text-center">为<span style="color:red">{$group_data['title']}</span>分配权限
                    </h1>
                    <form action="" method="post">
                        <input type="hidden" name="id" value="{$group_data['id']}">
                        <table class="table table-striped table-bordered table-hover table-condensed
					">
                            <foreach name="rule_data" item="v">
                                <empty name="v['_child']">
                                    <tr class="b-group">
                                        <th width="10%">
                                            <label>{$v['title']}
                                                <input type="checkbox" name="rule_ids[]" value="{$v['id']}"
                                                <if condition="in_array($v['id'],$group_data['rules'])"> checked="checked"</if>
                                                onclick="checkAll(this)" >
                                            </label>
                                        </th>
                                        <td>

                                        </td>
                                    </tr>
                                    <else/>
                                    <tr class="b-group">
                                        <th width="10%">
                                            <label>{$v['title']}
                                                <input type="checkbox" name="rule_ids[]" value="{$v['id']}"
                                                <if condition="in_array($v['id'],$group_data['rules'])"> checked="checked"</if>
                                                onclick="checkAll(this)">
                                            </label>
                                        </th>
                                        <td class="b-child">
                                            <foreach name="v['_child']" item="n">
                                                <table class="table table-striped table-bordered table-hover table-condensed">
                                                    <tr class="b-group">
                                                        <th width="10%">
                                                            <label>{$n['title']}
                                                                <input type="checkbox" name="rule_ids[]" value="{$n['id']}"
                                                                <if condition="in_array($n['id'],$group_data['rules'])"> checked="checked"</if>
                                                                onclick="checkAll(this)">
                                                            </label>
                                                        </th>
                                                        <td>
                                                            <notempty name="n['_child']">
                                                                <volist name="n['_child']" id="c">
                                                                    <label>&emsp;{$c['title']}
                                                                        <input type="checkbox" name="rule_ids[]" value="{$c['id']}"
                                                                        <if condition="in_array($c['id'],$group_data['rules'])"> checked="checked"</if> >
                                                                    </label>
                                                                </volist>
                                                            </notempty>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </foreach>
                                        </td>
                                    </tr>
                                </empty>
                            </foreach>
                            <tr>
                                <th>

                                </th>
                                <td>
                                    <input class="btn btn-success" type="submit" value="提交">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    <script>
            function checkAll(obj){
                $(obj).parents('.b-group').eq(0).find("input[type='checkbox']").prop('checked', $(obj).prop('checked'));
            }
        </script>
</block>
