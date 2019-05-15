@extends('layouts.cart')

@section('content')

<!-- Basic Examples -->
<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />
<link href="{{asset('/adminbsb-materialdesign/css/cartCategory.css')}}" rel="stylesheet">
<script src="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/ckeditor/ckeditor.js')}}"></script>

<div id='categoryBox' class="container-fluid _np">

    <div class='categoryLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label="{{$cateName}}"></div>

    <div id='categoryGoodsBox' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12'> 
        
        @foreach($goods as $good)
        <div class="col-md-3 col-sm-4 col-xs-6 ">
            <div class="thumbnail">
                <a href="{{url('')}}/{{$dealerDetect}}/goods/{{$good['id']}}" target="_blank">
                <img src="{{url('images')}}/{{$good['thumbnail']}}" alt="...">
                </a>
                <div class="caption">
            
                <h5> {{ $good['name'] }} </h5>
                <h4>價格:{{$good['goodsPrice'] }}</h4>
                
                <p class='itemBtn'>
                  <!-- <a href="#" class="btn btn-view" role="button"><span class="glyphicon glyphicon-search"></span>查看商品</a>  -->
                   <button class="btn btn-primary addone" role="button" goodsId="{{$good['id']}}"><span class="glyphicon glyphicon-shopping-cart"></span>加入購物車</button>
                </p>
                </div>
            </div>
        </div>
        @endforeach

        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            {!!$plist!!}
        </div>

    </div>

</div>

<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){

});
</script>
<!-- /專屬js -->

@endsection
