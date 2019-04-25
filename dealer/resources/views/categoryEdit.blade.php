@extends('layouts.admin')

@section('content')

<!-- Basic Examples -->
<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form action="{{url('/categoryEditDo')}}" method="POST">
        {{ csrf_field() }}

        <div class="card">

        <!-- form 表格 -->
        <div class="header">
            <h2>
            {{$title}}表格
            <small>填寫以下表格以編輯一組類別</small>
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
                        <b>類別名稱</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control " name="name" placeholder="" value="{{$editCategory['name']}}"/>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <p><b>父類別</b></p>
                        <select class="form-control show-tick" name='parents'>
                            <option value="0">無父類別</option>
                            @foreach( $categorys as $category)
                            
                            <!-- 排除自己 -->
                            @if( $category['id'] != $editCategory['id'] )

                            <option value="{{$category['id']}}"
                                @if( $category['id'] == $editCategory['parent'])
                                selected
                                @endif
                            > {{$category['level']}}{{$category['name']}} </option>

                            @endif

                            @endforeach
                        </select>
                    </div> 
                </div>

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>類別關鍵字</b>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name="keyword" placeholder="" name='keyword' value="{{$editCategory['keyword']}}"/>
                            </div>
                        </div>
                    </div>
                </div>             

                <div class="row clearfix">
                    <div class="col-sm-6">
                        <b>類別描述</b>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" class="form-control no-resize" placeholder="請輸入商品描述" name='desc' >{{$editCategory['desc']}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                    

                <div class="row clearfix">
                    <div class="col-sm-3">
                       <p><b>是否啟用</b></p>
                        <div class="form-group">
                            <div class="demo-checkbox">
                                <input type="checkbox" id="status" name='status'
                                @if( $editCategory['status'] == True)
                                checked
                                @endif
                                />
                                <label for="status">勾選啟用</label>
                            </div>              
                        </div>                  
                    </div>

                    <div class="col-md-3">
                        <p><b>排序(越小越前面)</b></p>
                        <div id="nouislider_basic_example"></div>
                        <div class="m-t-20 font-12"><b>排序: </b><span class="js-nouislider-value"></span></div>
                        <input type='hidden' id='sort' name='sort' value="{{$editCategory['sort']}}">
                    </div>   

                </div>                                                   
             
                <input type='hidden' name='id' value="{{$editCategory['id']}}">
                <button class="btn btn-primary waves-effect" type="submit">編輯</button>
            </form>

        </div>

        </div>

        <!-- 權限區塊 -->

        <!-- /權限區塊 -->

    </div>
</div>

<script src="{{asset('adminbsb-materialdesign/plugins/nouislider/nouislider.js')}}"></script>

<script type="text/javascript">
    
    // 選擇排序js
    var sliderBasic = document.getElementById('nouislider_basic_example');
    noUiSlider.create(sliderBasic, {
        start: ["{{$editCategory['sort']}}"],
        connect: 'lower',
        step: 1,
        range: {
            'min': [0],
            'max': [100]
        }
    });
    getNoUISliderValue(sliderBasic, true);

    function getNoUISliderValue(slider, percentage) {
    slider.noUiSlider.on('update', function () {
        var val = slider.noUiSlider.get();
        if (percentage) {
            val = parseInt(val);
            val += '';
        }
        $(slider).parent().find('span.js-nouislider-value').text(val);
        $("#sort").val(val);
    });
    }

$(function(){
    
    // 一開始先將全部被focus的元素解除focus
    $(".focused:not(.error)").removeClass('focused');

})
</script>
@endsection
