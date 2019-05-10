@extends('layouts.cart')

@section('content')

<!-- Basic Examples -->
<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />
<link href="{{asset('/adminbsb-materialdesign/css/cartCheckout.css')}}" rel="stylesheet">
<script src="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/ckeditor/ckeditor.js')}}"></script>

<div id='checkoutBox' class="container-fluid _np">

    <div class='checkoutLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='購買清單'></div>

    <div id='checkoutList' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12'> 
        
        <form action='' method=''>
        <div class="table">
            <table class="table">

                <thead>
                    <tr>
                        <th width='50%'>商品名稱</th>
                        <th>售價</th>
                        <th>數量</th>
                        <th>小計</th>
                        <th>操作</th>
                    </tr>
                </thead>
                
                <tbody>
                @php
                    $checkAmount = 0;
                @endphp
                @foreach( $carts as $cart)
                    <tr>
                        <td>
                            <div class="media">

                                <div class="media-left checkoutImgBox">
                                    <img class="media-object" src="{{url('images')}}/{{$cart['thumbnail']}}" alt="">
                                </div> 

                                <div class="media-body">
                                <!-- <h4 class="media-heading">Media heading</h4> -->
                                {{$cart['name']}}
                                </div>
                            </div>
                        
                        </td>
                        <td>{{$cart['goodsPrice']}}</td>
                        <td>
                            <select class='form-control checkoutNum' goodsId="{{$cart['id']}}" >
                                @for( $i = 0 ; $i<= $cart['stock'] ; $i++)
                                <option value="{{$i}}" 
                                @if( $cart['num'] == $i)
                                    SELECTED
                                @endif
                                >{{$i}}</option>
                                @endfor
                            </select>
                        </td>
                        <td>{{$cart['subTotal']}}</td>
                        <td>
                            <button class='btn btn-primary'>刪除</button>
                        </td>
                    </tr>
                    @php
                        $checkAmount += $cart['subTotal'];
                    @endphp
                @endforeach
                    <tr>
                        <td colspan='4'></td>
                        <td >總價:{{$checkAmount}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        </form>

    </div>
</div>

<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){

$(document).on( 'change', '.checkoutNum', function(e){
    
    if (e.handled !== true) {
        e.handled = true;

    }

});




})



</script>
<!-- /專屬js -->

@endsection
