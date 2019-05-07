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
            <div class="header">
                <h2>訂單基本資料</h2>
            </div>

            <div class='body'>
                
                <form action="{{ url('/orderEditBasicDo') }}" method='POST' >

                {{ csrf_field() }}
                <input type='hidden' name='orderId' value="{{$order['id']}}" >
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class='bg-grey' width='10%'>經銷商</td>
                            <td>
                                @role('Admin')
                                <select style='width:auto;' class="form-control" name='dealerId'>
                                    
                                    <option value="0">尚未指定經銷商</option>
                                    
                                    @foreach( $dealers as $dealer)
                                    <option value="{{$dealer['id']}}">{{$dealer['name']}}</option>
                                    @endforeach
                                    

                                </select>
                                @endrole

                                @role('Dealer')
                                {{$dealerName}}
                                @endrole

                            </td>
                            <td class='bg-grey' width='10%' >訂單編號</td>
                            <td>{{ $order['order_sn'] }}</td>
                            <td class='bg-grey' width='10%' >訂單總價</td>
                            <td>{{ $order['amount'] }}</td>                                        
                        </tr>
                        <tr>
                            <td class='bg-grey' width='10%'>購買房號</td>
                            <td><input type='text' class="form-control" value="{{$order['room']}}" style='width:auto;' name='room'></td>
                            <td class='bg-grey' width='10%'>訂單狀態</td>
                            <td>
                                @switch( $order['status'] )
                                    @case(1)
                                    尚未處理完成
                                    @break

                                    @case(2)
                                    待處理
                                    @break

                                    @case(3)
                                    已出貨
                                    @break

                                    @case(4)
                                    取消
                                    @break                                                                                                            
                                @endswitch
                            </td>
                            <td class='bg-grey' width='10%'>出貨時間</td>
                            <td>@if( empty($order['ship_at']) ) 尚未出貨 @else {{$order['ship_at']}} @endif</td>                                        
                        </tr>

                        <tr>
                            <td colspan='6' class='align-center'>
                                
                                @if( $isNew )
                                <input type='hidden' name='isNew' value='1'>
                                @endif

                                <input type='submit' value='完成' class='btn btn-primary waves-effect' >
                                
                                @if( ! $isNew )
                                <a href="{{url('/orderInfo/'.$order['id'])}}">
                                    <span class='btn btn-primary waves-effect'>返回訂單資訊</span>
                                </a>
                                @endif
                            </td>
                        </tr>                                    
                    </tbody>
                </table>
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

})
</script>
<!-- /專屬js -->

@endsection
