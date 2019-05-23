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

            <!-- 訂單基本資料 -->
            <div class="header bg-red">
                <h2>編輯費用</h2>
            </div>

            <div class='body'>
                
                <form action="{{ url('/orderFeeEditDo') }}" method='POST' >

                {{ csrf_field() }}
                <input type='hidden' name='orderId' value="{{$order['id']}}" >
                    <div class="row clearfix">
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <p><b>商品金額</b></p>
                                <div class="form-line myborder">
                                   <input type='text' class="form-control" value="{{$order['amount']}}" style='width:auto;' disabled>
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <p><b>折扣金額</b></p>
                                <div class="form-line myborder">
                                   <input type='number' class="form-control" value="{{$order['discount']}}" name='discount'>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <input type='submit' class='btn btn-primary waves-effect' value="確定">
                        </div>
                    </div>
                </form>

            </div>
            <!-- /訂單基本資料 -->
        
            
        </div>

    </div>

</div>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){
    
    $(".focused").removeClass('focused');
})
</script>
<!-- /專屬js -->

@endsection
