@extends('layouts.cart')

@section('content')

<!-- Basic Examples -->
<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />
<link href="{{asset('/adminbsb-materialdesign/css/cartGoods.css')}}" rel="stylesheet">
<script src="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/ckeditor/ckeditor.js')}}"></script>

<div id='goodsBox' class="container-fluid _np">
    
    <div id='goodsInfo' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 '>
        
        <div id='goodsPic' class='col-md-4 col-sm-12 col-xs-12 _np ' >

            <img src="{{url('images')}}/{{$goods['main_pic']}}">  

        </div>

        <div id='goods' class='col-md-7 col-md-offset-1 col-sm-12 col-xs-12 _np' >

            <h3>{{$goods['name']}}</h3>

            <h4>售價: {{$goods['dealerPrice']}}</h4>
            <h4>編號: {{$goods['goods_sn']}}</h4>
        
            <form class="form-inline">

              <div class="form-group">
                <select class='form-control' id='chooseNum' name='chooseNum'>
                <option value='0'> 請選擇購買數量 </option>
                @for($i = 1; $i <= $goods['stock']; $i++)
                <option>{{$i}}</option>
                @endfor
                </select>
              </div>

              <button type="submit" class="btn btn-primary">加入購物車</button>

            </form>

        </div>        

    </div>

    <div class='goodsLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='商品描述'></div>
    <div id='goodsDesc' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 '>
        {!!$goods['desc']!!}
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
