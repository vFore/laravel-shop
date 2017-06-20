@extends('admin.layouts.layout')
@section('x-body')
    <blockquote class="layui-elem-quote">
        <img src="{{ asset('templates/admin/images/logo.png') }}" class="layui-circle" style="width:50px;float:left">
        <dl style="margin-left:80px; color:#019688">
            <dt><span>{{$userinfo->nickname}}</dt>
            <dd style="margin-left:0">这家伙很懒，什么也没有留下</dd>
        </dl>
    </blockquote>
    <div class="pd-20">
        <table class="layui-table" lay-skin="line">
            <tbody>
            <tr>
                <th width="80">性别：</th>
                <td>
                    @if($userinfo->sex==1)
                        {{'男'}}
                    @else
                        {{'女'}}
                    @endif
                </td>
            </tr>
            <tr>
                <th>手机：</th>
                <td>{{$userinfo->tel}}</td>
            </tr>
            <tr>
                <th>邮箱：</th>
                <td>admin@mail.com</td>
            </tr>
            <tr>
                <th>地址：</th>
                <td>北京市 海淀区</td>
            </tr>
            <tr>
                <th>注册时间：</th>
                <td>2017-01-01</td>
            </tr>
            <tr>
                <th>积分：</th>
                <td>330</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('js')
<script>
    layui.use(['form', 'layer'], function () {
        $ = layui.jquery;
        var form = layui.form()
            , layer = layui.layer;

        //自定义验证规则
        form.verify({
            nikename: function (value) {
                if (value.length < 5) {
                    return '昵称至少得5个字符啊';
                }
            }
            , pass: [/(.+){6,12}$/, '密码必须6到12位']
            , repass: function (value) {
                if ($('#L_pass').val() != $('#L_repass').val()) {
                    return '两次密码不一致';
                }
            }
        });

        console.log(parent);
        //监听提交
        form.on('submit(add)', function (data) {
            console.log(data);
            //发异步，把数据提交给php
            layer.alert("增加成功", {icon: 6}, function () {
                // 获得frame索引
                var index = parent.layer.getFrameIndex(window.name);
                //关闭当前frame
                parent.layer.close(index);
            });
            return false;
        });


    });
</script>
@endsection