@extends('layouts.admin')

@section('content')

<!-- Basic Examples -->

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form action="{{url('/roleNewDo')}}" method="POST">
        {{ csrf_field() }}

        <div class="card">

        <!-- form 表格 -->
        <div class="header">
            <h2>
            身分新增表格
            <small>填寫以下表格以新增一組身分</small>
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
            
                

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>身分代碼</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name="code" placeholder="請輸入身分代碼 ex: goodsManager" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>身分名稱</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name='name' placeholder="請輸入身分名稱 ex: 商品管理者" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>身分描述</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name='note' placeholder="請輸入身分描述 ex: 該主要控商品相關模組操作" />
                            </div>
                        </div>
                    </div>
                </div>             
                



                
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <b class='col-red'>權限分配</b>
                    </div>
                    <div class="col-sm-6">
                    @foreach($permissions as $permission) 
                        <input type="checkbox" id="{{$permission->name}}" class="filled-in chk-col-teal" name="permissions[]" value="{{$permission->id}}">
                        <label for="{{$permission->name}}">{{$permission->display_name}}</label>                                     
                    @endforeach
                    </div>
                </div>
                                  
             

                <button class="btn btn-primary waves-effect" type="submit">新增</button>
            </form>

        </div>

        </div>

        <!-- 權限區塊 -->

        <!-- /權限區塊 -->

    </div>
</div>

@endsection
