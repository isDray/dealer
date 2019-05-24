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
                <form action="{{url('/setFeeDo')}}" method='POST'>
                    {{ csrf_field() }}
                    <table>
                    <div class="input-group">
                            
                        <span class="input-group-addon">
                        新會員免運門檻:
                        </span>
                            
                        <div class="form-line">
                            <input type="text" class="form-control align-left myborder" name='new_free_price' value="{{$webSet->new_free_price}}">
                        </div>
       
                    </div>
                            
                    <div class="input-group">
                            
                        <span class="input-group-addon">
                        一般免運門檻:
                        </span>
                            
                        <div class="form-line">
                            <input type="text" class="form-control align-left myborder" name='free_price' value="{{$webSet->free_price}}">
                        </div>
       
                    </div>                                                                                   

                    <div class="input-group">
                            
                        <span class="input-group-addon">
                        運費:
                        </span>
                            
                        <div class="form-line">
                            <input type="text" class="form-control align-left myborder" name='ship_fee' value="{{$webSet->ship_fee}}">
                        </div>
       
                    </div>                    
                    </table>
                    <input type='submit' class='btn btn-primary waves-effect' value='確定'>
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
