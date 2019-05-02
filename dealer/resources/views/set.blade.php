@extends('layouts.admin')

@section('content')

<!-- Basic Examples -->
<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />
<script src="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/ckeditor/ckeditor.js')}}"></script>
<script>
  var options = {
    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token=',
  };
</script>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        
        <div class='card'>

            <!-- 進貨單基本資料 -->
            <div class="header bg-red">
                <h2>網站相關設定</h2>
            </div>

            <div class='body'>
                <div class="row clearfix">
                <div class='col-md-4 col-xs-12 col-sm-12'>
                <form action="{{url('/setDo')}}" method='POST'>
                    {{ csrf_field() }}
                    <div class="input-group">
                            
                        <span class="input-group-addon">
                        網站名稱:
                        </span>
                            
                        <div class="form-line">
                            <input type="text" class="form-control align-left myborder" name='name' value="{{$webSet->name}}">
                        </div>
       
                    </div>
                    
                    <div class="input-group">
                            
                        <span class="input-group-addon">
                        呈現方式:
                        </span>
                        
                        <input name="showType" id='showType1' type="radio" class="with-gap radio-col-teal rootRole" value="1" @if( $webSet->show_type == 1) checked @endif />
                        <label for="showType1">表格</label>

                        <input name="showType" id='showType2' type="radio" class="with-gap radio-col-teal rootRole" value="2" @if( $webSet->show_type == 2) checked @endif />
                        <label for="showType2">列表</label>
                        
                    </div>  

                    <div class="input-group">
                            
                        <span class="input-group-addon">
                        排序方式:
                        </span>
                        
                        <input name="sortType" id='sortType1' type="radio" class="with-gap radio-col-teal rootRole" value="1" @if( $webSet->sort_type == 1) checked @endif />
                        <label for="sortType1">上架時間</label>

                        <input name="sortType" id='sortType2' type="radio" class="with-gap radio-col-teal rootRole" value="2" @if( $webSet->sort_type == 2) checked @endif />
                        <label for="sortType2">價格</label>
                        
                    </div>

                    <div class="input-group" id='sortSize'>
                            
                        <span class="input-group-addon">
                        排序規則:
                        </span>
                        
                        <input name="way" id='way1' type="radio" class="with-gap radio-col-teal rootRole" value="1" @if( $webSet->sort_way == 1) checked @endif />
                        <label for="way1">小到大</label>

                        <input name="way" id='way2' type="radio" class="with-gap radio-col-teal rootRole" value="2" @if( $webSet->sort_way == 2) checked @endif />
                        <label for="way2">大到小</label>
                        
                    </div>                                                                
                    
                    <input type='submit' class='btn btn-primary waves-effect' value='送出'>
                </form>
                </div>
                </div>
            </div>
            <!-- /進貨單基本資料 -->

        </div>





</div>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){

})
</script>
<!-- /專屬js -->

@endsection
