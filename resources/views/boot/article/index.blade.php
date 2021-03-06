@extends('boot.layouts.base')

@section('title','文章列表')
@section('css')
    <style>
        .alert {
            margin-bottom: -20px;
        }

        .center {
            text-align: center;
        }
    </style>
@endsection
@section('body')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>文章列表 {{ $recover == false ? "" : "/ 文章回收站"}} {{ $search  ? "/ 文章搜索" : ""}}</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-2" style="width: 100px">
                            <div class="input-group">
                                <a href="{{ route('article-create') }}">
                                    <button class="btn btn-outline btn-danger" type="button">添加文章</button>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-2" style="width: 100px">
                            <div class="input-group">
                                @if ($recover == false)
                                    <a onclick="deleteAll()">
                                        <button class="btn btn-outline btn-warning" type="button">批量删除</button>
                                    </a>
                                @else
                                    <a onclick="ForceDeleteAll()">
                                        <button class="btn btn-outline btn-warning" type="button">永久删除</button>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-2" style="width: 100px">
                            <div class="input-group">
                                @if ($recover == false)
                                    <a href="{{ route('article-index',['recover' => true]) }}">
                                        <button class="btn  btn-default" type="button"><i class="fa fa-trash-o"></i> 回收站
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('article-index') }}">
                                        <button class="btn  btn-info" type="button"><i class="fa fa-list-alt"></i>
                                            &nbsp;列 表
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group" style="width: 270px">
                                <input type="text" class="form-control" id="search" name="title" value="{{ $search }}"
                                       placeholder="Search"/>
                                <span class="input-group-btn">
                                    <a onclick="searchArticle()">
                                          <button class="btn  btn-info" type="button"><i class="fa fa-search"></i> Search</button>
                                    </a>
                                </span>
                            </div>
                        </div>

                        @include('boot.layouts.prompt')
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="example-wrap">
                    <div class="example">
                        <form method="post" action="{{ route('article-sort') }}">
                            {{ csrf_field() }}
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="3%"><input type="checkbox" name="chk_all" id="chk_all"/></th>
                                    <th width="5%" class="center"><a
                                                href="{{ route('article-index', $articleOrm ->attributes($recover,'sort',$order,$search)) }}">排序</a>
                                    </th>
                                    <th width="5%"><a
                                                href="{{ route('article-index', $articleOrm ->attributes($recover,'id',$order,$search)) }}">ID</a>
                                    </th>
                                    <th>标题</th>
                                    <th width="8%"><a
                                                href="{{ route('article-index', $articleOrm ->attributes($recover,'c_id',$order,$search)) }}">所属分类</a>
                                    </th>
                                    <th width="8%">文章封面</th>
                                    <th width="8%"><a
                                                href="{{ route('article-index',$articleOrm ->attributes($recover,'created_at',$order,$search)) }}">发布时间</a>
                                    </th>
                                    <th width="8%"><a
                                                href="{{ route('article-index',$articleOrm ->attributes($recover,'views',$order,$search)) }}">浏览量</a>
                                    </th>
                                    <th width="8%"><a
                                                href="{{ route('article-index',$articleOrm ->attributes($recover,'status',$order,$search)) }}">状态</a>
                                    </th>
                                    <th width="8%"><a
                                                href="{{ route('article-index',$articleOrm ->attributes($recover,'recommend',$order,$search)) }}">推荐位置</a>
                                    </th>
                                    <th width="8%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="chk_list" class="chk_list"
                                                       id="chk_list_{{ $article -> id }}" value="{{ $article -> id }}"/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control center"
                                                       name="sort[{{ $article -> id }}]" value="{{ $article -> sort }}">
                                            </td>
                                            <th>{{ $article -> id }}</th>
                                            <td>{{ $article -> title }}</td>

                                            <td>{{ $article -> category -> category }}</td>
                                            <td>
                                                <img src="{{ $article -> photo }}" height="50"
                                                     onError='this.src="{{ asset('/static/boot/img/no.png') }}"'>
                                            </td>
                                            <td>{{ $article -> created_at }}</td>

                                            <td>{{ $article -> views }}</td>
                                            <th>{{ $article -> status($article -> status) }}</th>
                                            <th>
                                                {{ $article ->recommend($article -> recommend) }}
                                            </th>
                                            <td>
                                                @if ($recover == false)
                                                    <a class="btn btn-primary btn-outline btn-xs "
                                                       href="{{ route('article-edit',['id' => $article->id ]) }}"
                                                       title="编辑">
                                                        <i class="fa fa-paste"></i>
                                                    </a>
                                                @else
                                                    <a class="btn btn-primary btn-outline btn-xs " onclick="restore({{ $article->id }})" title="恢复">
                                                        <i class="fa fa-undo"></i>
                                                    </a>
                                                @endif
                                                <span>|</span>
                                                <a href="{{ $recover == false ? route('article-destroy',['id' => $article -> id]) : route('article-ForceDelete',['id' => $article -> id])}}"
                                                   class="btn btn-warning btn-outline btn-xs delArtciel"
                                                   onclick="return confirm('确定删除?');" title="删除">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>
                            </table>
                            <hr>
                            <div class="clearfix" style="line-height: 70px; margin-top: -20px;">
                                <div style="float: left">
                                    <button class="btn btn-outline btn-success" type="submit">排序</button>
                                </div>
                                <div style="float: right">
                                    {{ $articles ->links() }}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        //chk_all
        $("#chk_all").click(function () {
            if (this.checked) {
                $("input[name='chk_list']:checkbox").each(function () {
                    $(this).attr("checked", true);
                    $(this).prop("checked", true);
                })
            } else {
                $("input[name='chk_list']:checkbox").each(function () {
                    $(this).attr("checked", false);
                    $(this).prop("checked", false);
                })
            }
        });

        //deleteAll
        function deleteAll() {
            var deleteId = 'null,';
            $("input[name='chk_list']:checked").each(function (j) {
                if (j >= 0) {
                    deleteId += $(this).val() + ",";
                }
            });

            if (deleteId == 'null,') {
                $('.alert.alert-danger').css("display", "block");
                $('#info').html('暂未选择数据');
                return false;
            }

            if (confirm('确定批量删除?')) {
                window.location.href = "{{ route('article-destroy',['id' => '']) }}/" + deleteId;
            } else {
                return false;
            }
        }

        //ForceDeleteAll
        function ForceDeleteAll() {
            var deleteId = 'null,';
            $("input[name='chk_list']:checked").each(function (j) {
                if (j >= 0) {
                    deleteId += $(this).val() + ",";
                }
            });

            if (deleteId == 'null,') {
                $('.alert.alert-danger').css("display", "block");
                $('#info').html('暂未选择数据');
                return false;
            }

            if (confirm('确定批量删除?')) {
                window.location.href = "{{ route('article-ForceDelete',['id' => '']) }}/" + deleteId;
            } else {
                return false;
            }
        }


        function searchArticle() {
            var search = $('#search').val();
            if (search == '') {
                $('.alert.alert-danger').css("display", "block");
                $('#info').html('暂未输入数据');
                return false;
            }

            window.location.href = "{{ route('article-index') }}/{{ $recover == false ? 0 : 1 }}/0/0/" + search;
        }

        function restore(id) {
            $.get("{{ route('article-restore',['id' => '']) }}/"+id, function(result){
                if (result.success == false){
                    $('.alert.alert-danger').css("display", "block");
                    $('#info').html(result.msg);
                } else{
                    location.reload();
                }
            });
        }
    </script>
@endsection
