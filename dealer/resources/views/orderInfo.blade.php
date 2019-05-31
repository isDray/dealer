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
                <h2>訂單基本資料</h2>
            </div>

            <div class='body'>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class='bg-grey' width='10%'>經銷商</td>
                            <td>@if(empty ($order['name']))尚未指定經銷商 @else {{$order['name']}} @endif</td>
                            <td class='bg-grey' width='10%' >訂單編號</td>
                            <td>{{ $order['order_sn'] }}</td>
                            <td class='bg-grey' width='10%' >訂單總價</td>
                            <td>{{ $order['amount'] }}</td>                                        
                        </tr>
                        <tr>
                            <td class='bg-grey' width='10%'>購買房號</td>
                            <td>@if(empty ($order['room']))尚未指定房號 @else {{$order['room']}} @endif</td>
                            <td class='bg-grey' width='10%'>訂單狀態</td>
                            <td>
                                @switch( $order['status'] )
                                    @case(1)
                                    尚未處裡完成
                                    @break

                                    @case(2)
                                    待處理
                                    @break

                                    @case(3)
                                    已確認
                                    @break

                                    @case(4)
                                    已出貨
                                    @break

                                    @case(5)
                                    取消
                                    @break                                                                                                            
                                @endswitch
                            </td>
                            <td class='bg-grey' width='10%'>出貨時間</td>
                            <td>@if( empty($order['ship_at']) ) 尚未出貨 @else {{$order['ship_at']}} @endif</td>                                        
                        </tr>
                        <tr>
                            <td colspan='6' class='align-center'>
                                <a href="{{url('/orderEditBasic/edit/'.$order['id'])}}">
                                <span class='btn btn-primary waves-effect'>編輯訂單基本資料</span>
                                </a>
                            </td>
                        </tr> 

                    </tbody>
                </table>                
            </div>
            <!-- /訂單基本資料 -->
        
            <!-- 訂單明細 -->
            <div class="header bg-red">
                <h2>訂單明細</h2>
            </div>

            <div class='body'>
                <table class="table">
                <thead>
                    <tr>
                        <th class='bg-grey'>商品縮圖</th>
                        <th class='bg-grey'>商品貨號</th>
                        <th class='bg-grey'>價格</th>
                        <th class='bg-grey'>數量</th>
                        <th class='bg-grey'>小計</th>
                    </tr>
                </thead>
                <tbody>
                    @if( count($orderGoods) > 0 )
                        
                        @php
                            $tmpTotal = 0;
                        @endphp

                        @foreach( $orderGoods as $orderGood)
                            
                            @php
                                $tmpTotal += $orderGood['subtotal'];
                            @endphp                        
                        <tr>
    
                            <td><img src="{{url('/images/'.$orderGood['thumbnail'])}}" width='64px;'></td>
                            <td>{{ $orderGood['goods_sn'] }}</td>
                            <td>{{ $orderGood['price'] }}</td>
                            <td>{{ $orderGood['num'] }}</td>
                            <td>{{ $orderGood['subtotal'] }}</td>

                        </tr>
                        @endforeach

                        <tr>

                            <td colspan='4' class='align-right'>總和:</td>
                            <td>{{ $tmpTotal }}</td>

                        </tr>                        
                    @else
                        <tr>
                            <td colspan='5'>暫無細項</td>
                        </tr>                    
                    @endif           
                </tbody>
                </table> 
                
                <div class='row align-center'>
                    @if($order['status'] == 4)
                    <span class='btn btn-primary waves-effect' disabled>編輯訂單商品</span>
                    @else
                    <a href="{{url('/orderEdit/'.$order['id'])}}">
                        <span class='btn btn-primary waves-effect' >編輯訂單商品</span>
                    </a>
                    @endif
                </div>
            </div>        
            <!-- /訂單明細 --> 

            <!-- 訂單金額 -->
            <div class="header bg-red">
                <h2>費用資訊</h2>
            </div>            
            <div class='body'>
                <table class="table">
                    <thead>
                        <tr>
                           <td class="align-right">商品價格:{{$order['amount']}}-折扣價格:{{$order['discount']}}</td> 
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           <td class="align-right">訂單總金額:{{$order['final_amount']}}</td> 
                        </tr>
                    </tbody>
                </table>
                <div class='row align-center'>
                    <a href="{{url('/orderFeeEdit/'.$order['id'])}}">
                        <span class='btn btn-primary waves-effect'>編輯費用</span>
                    </a>
                </div>                
            </div>
            <!-- /訂單金額 -->

            <!-- 訂單操作 -->
            <div class="header bg-red">
                <h2>訂單操作</h2>
            </div>

            <div class='body'>

                <form action="{{url('/orderStatus')}}" method='POST'>

                    {{ csrf_field() }}

                    <div class='align-left'>
                        <input type='hidden' name='orderId' value="{{$order['id']}}">
                        
                        <input type='submit' name='pending' class="btn @if( !in_array(2,$useableStatus) ) bg-grey @else btn-primary @endif waves-effect" value='待處理' @if( !in_array(2,$useableStatus) )disabled="disabled" @endif > 

                        <input type='submit' name='checked' class='btn @if( !in_array(3,$useableStatus) ) bg-grey @else btn-primary @endif waves-effect" waves-effect' value='已確認' @if( !in_array(3,$useableStatus) )disabled="disabled" @endif>

                        <input type='submit' name='shipped' class='btn @if( !in_array(4,$useableStatus) ) bg-grey @else btn-primary @endif waves-effect" waves-effect' value='已出貨' @if( !in_array(4,$useableStatus) )disabled="disabled" @endif>

                        <input type='submit' name='cancel' class='btn @if( !in_array(5,$useableStatus) ) bg-grey @else btn-primary @endif waves-effect" waves-effect' value='取消' @if( !in_array(5,$useableStatus) )disabled="disabled" @endif>
                    </div>

                </form>

            </div>
            <!-- /訂單操作 -->

            <!-- 訂單操作紀錄 -->
            <div class="header orderInfo">
                <h2>訂單操作紀錄</h2>
            </div>
         
            <div class='body'>
                @if(isset($orderLogs))
                <table class="table table-bordered">
                
                    <thead>
                        <tr>
                            <th>操作人</th>
                            <th>訂單狀態</th>
                            <th>操作描述</th>
                            <th>時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $orderLogs as $orderLog)
                        <tr>
                            <td>{{$orderLog['user_name']}}</td>
                            <td>{{$orderLog['order_status']}}</td>
                            <td>{{$orderLog['desc']}}</td>
                            <td>{{$orderLog['created_at']}}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                @endif

            </div>            
            <!-- /訂單操作紀錄 -->
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
