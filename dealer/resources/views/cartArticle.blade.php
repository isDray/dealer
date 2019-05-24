@extends('layouts.cart')

@section('content')

<!-- Basic Examples -->
<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />
<link href="{{asset('/adminbsb-materialdesign/css/cartArticle.css')}}" rel="stylesheet">
<script src="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/ckeditor/ckeditor.js')}}"></script>

<div id='articleBox' class="container-fluid ">

    <div class='articleLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label="{{$article['name']}}"></div>

    <div id='articleMsgBox' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12'> 
        {!!$article['content']!!}
    </div>
    </div>
</div>

<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){
    adjHeight = $("#fixBox").height();
    // 計算高度
    $("#articleBox").css("margin-top",adjHeight);
});
</script>
<!-- /專屬js -->

@endsection
