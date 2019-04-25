@extends('layouts.admin')

@section('content')

<!-- Basic Examples -->

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

        <!-- form 表格 -->
        <div class="header">
            <h2>
            權限編輯表格
            <small>填寫以下表格以編輯一組權限</small>
            </h2>

            <!-- 功能列表(暫時用不到)
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);">Action</a></li>
                        <li><a href="javascript:void(0);">Another action</a></li>
                        <li><a href="javascript:void(0);">Something else here</a></li>
                    </ul>
                </li>
            </ul>
            -->
        </div>
        <div class="body">
            <form action="{{url('/permissionsEditDo')}}" method="POST">
                {{ csrf_field() }}

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>權限代碼</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name="code" placeholder="請輸入權限代碼 ex: user_create"  value="{{$permissions->name}}"/>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>權限名稱</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name='name' placeholder="請輸入權限名稱 ex: 新增使用者" value="{{$permissions->display_name}}"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>權限描述</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name='note' placeholder="請輸入權限描述 ex: 有該權限的使用者能夠進行新增使用者的操作"  value="{{$permissions->description}}"/>
                            </div>
                        </div>
                    </div>
                </div>             

                <input type='hidden' name='id' value="{{$permissions->id}}">
                <button class="btn btn-primary waves-effect" type="submit">編輯</button>
            </form>

        </div>
        <!-- /form 表格 -->

        
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".form-line").removeClass( "focused" );
})
</script>
@endsection
