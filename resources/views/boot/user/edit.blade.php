@extends('boot.layouts.base')

@section('title','编辑用户')

@section('css')
    <link rel="stylesheet" href="/static/boot/css/app.css">
    <style>
        .container {
            margin-top: 30px;
            margin-bottom: 30px;
            width: 1000px;
        }

        #error {
            position: fixed;
            top: 40px;
            right: 90px;
            height: 598px;
            width: 200px;
            z-index: 1040;
        }
    </style>
@endsection

@section('body')
    <div id="error">

    </div>
    <div class="container">
        <div class="twelve wide column">
            <div class="ui segment">
                <div class="content extra-padding">
                    <div class="ui header text-center text gery" style="margin:10px 0 40px">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑用户
                    </div>
                    <form method="post" action="{{ route('user-update') }}" accept-charset="UTF-8" class="ui form"
                          style="min-height: 50px;" id="insert">
                        {{ csrf_field() }}

                        <div class="field">
                            <input class="form-control" type="text" name="name" id="title-field" placeholder="用户名" value="{{ $user -> name }}">
                            <input type="hidden" name="id" value="{{ $user -> id }}">
                        </div>
                        <div class="field">
                            <input class="form-control" type="text" name="email" id="email-field" placeholder="邮箱" value="{{ $user -> email }}">
                        </div>

                        <div class="field">
                            <label>密码(不改请勿操作)</label>
                            <input class="form-control" type="password" name="password" id="title-field"
                                   placeholder="{{ $user -> password }}" value="{{ $user -> password }}">
                        </div>



                        <br/>
                        <div class="field">
                            <input class="form-control" type="text" name="true_name" id="title-field"
                                   placeholder="用户真实姓名" value="{{ $user -> true_name }}">
                        </div>

                        <br/>

                        <div class="field">
                            <label>用户权限</label>

                            <select class="form-control ui search multiple selection tags dropdown  category"
                                    name="role_id">
                                @foreach($roles as $role)
                                    <option value="{{ $role -> id }}" {{ $user->role_id == $role ->id ? 'selected' :'' }}>{{ $role -> chinese_name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <br>

                        <div class="ui segment private-checkbox">
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" class="js-switch" name="status" {{ $user -> status ? "checked" :'' }}  style="margin-left: -2px;"/>

                                    <label>开启登陆</label>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="ui message">
                            <button type="submit" class="ui button teal publish-btn" id="">
                                <i class="glyphicon glyphicon-pencil"></i>
                                确认
                            </button>
                            &nbsp;&nbsp;or&nbsp;&nbsp;
                            <a href="{{ route('user-index') }}" class="ui button"  name="subject" value="draft">
                                <i class="glyphicon glyphicon-repeat"></i> 返回列表
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="/static/boot/js/jquery.form.js"></script>
    <script type="text/javascript">

        function objToArray(array) {
            var arr = [];
            for (var i in array) {
                arr.push(array[i]);
            }
            console.log(arr);
            return arr;
        }
        var opt = {
            success: insertOk,
            dataType: 'json',
            timeout: 5000
        };
        $('#insert').ajaxForm(opt);

        function insertOk(res) {
            if (res.success === false) {
                var items = objToArray(res.errors);
                $("#error").empty();

                if (!res.errors) {
                    $('#error').append(
                        "<div style='opacity:1;' class='alert alert-danger'><ul><li>" + res.msg + "</li></ul> </div>"

                    );
                }
                items.map(function (value) {
                    $('#error').append(
                        "<div style='opacity:1;' class='alert alert-danger'><ul><li>" + value[0] + "</li></ul> </div>"

                    );
                })
            } else {
                window.location.href = res.url;
            }
        }

    </script>


@endsection



