@extends('layouts.admin')

@section('content')

<!-- Basic Examples -->

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form action="{{url('/accountEditDo')}}" method="POST">
        {{ csrf_field() }}

        <div class="card">

        <!-- form 表格 -->
        <div class="header">
            <h2>
            {{$title}}表格
            <small>填寫以下表格以編輯一組帳號</small>
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
                        <b>姓名</b>
                        <div class="form-group">
                            <div class="form-line myborder">
                                <input type="text" class="form-control" name="name" placeholder="" value="{{$user->name}}" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>信箱</b>
                        <div class="form-group">
                            <div class="form-line myborder">
                                <input type="text" class="form-control" name='email' placeholder="" value="{{$user->email}}"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>密碼</b>
                        <div class="form-group">
                            <div class="form-line myborder">
                                <input type="password" class="form-control" name='password' placeholder=""/>
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>密碼確認</b>
                        <div class="form-group">
                            <div class="form-line myborder">
                                <input type="password" class="form-control" name='password2' placeholder=""/>
                            </div>
                        </div>
                    </div>
                </div>

<!--                 <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>手機</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name="phone" placeholder="" value="{{$user->phone}}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>地址</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name="address" placeholder="" value="{{$user->address}}" />
                            </div>
                        </div>
                    </div>
                </div> -->

                
                <div class="row clearfix" style='display:none;'>
                    <div class="col-sm-12">
                        <b class='col-red'>管理平台</b>
                    </div>
                    <div class="col-sm-6">
                    @foreach ($rootRoles as $rootRole) 

                        <input name="useTarget" type="radio" id="useTarget_{{$rootRole->id}}" class="with-gap radio-col-teal rootRole" value="{{$rootRole->name}}" 
                        @if( $useRootRole == $rootRole->name)
                        checked
                        @endif
                        >
                        <label for="useTarget_{{$rootRole->id}}">{{$rootRole->display_name}}</label>

                       
                    @endforeach
                    </div>
                </div> 
                         


                <div class="row clearfix adminChildRoleBox" 
                
                @if( $useRootRole == 'Dealer')
                style='display:none;'
                @endif
                >
                    <div class="col-sm-12">
                        <b class='col-red'>分配身分</b>
                    </div>
                    
                    @foreach( $groupRole['Admin'] as $adminRoleK => $adminRole)
                    <div class="col-sm-12 ">
                        @foreach( $adminRole as $tmpk => $tmp)
                            
                        
                        <input name="addRole[]" type="checkbox" id="useTarget_{{ $childRoles[$tmp]['id'] }}" class="with-gap radio-col-teal adminChildRole" value="{{ $childRoles[$tmp]['name'] }}" 
                            
                        @if( in_array( $childRoles[$tmp]['name'] , $useChildRoles) )    
                        checked
                        @endif
                        >
                        <label for="useTarget_{{ $childRoles[$tmp]['id'] }}">{{ $childRoles[$tmp]['display_name'] }}</label>


                        @endforeach
                    </div>
                    @endforeach
                    
                </div>                                  

                 
                <input type="hidden" value="{{$user->id}}" name='id'>
                <button class="btn btn-primary waves-effect" type="submit">確定</button>
            </form>

        </div>

        </div>

        <!-- 權限區塊 -->

        <!-- /權限區塊 -->

    </div>
</div>

<script type="text/javascript">
$(function(){
    
    $(".rootRole").change(function(){
        
        if( $(this).val() == 'Dealer' ){
            
            $(".adminChildRole").prop('checked', false);
            $(".adminChildRoleBox").hide();

        }else if( $(this).val() == 'Admin' ){

            $(".adminChildRoleBox").show();
        }
    })
    
    $(".form-line").removeClass( "focused" );
})
</script>
@endsection
