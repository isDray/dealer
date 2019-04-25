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

        
        <!-- 進貨單預估表單 -->
        <div class='card'>

            <div class="header">
                <h2>進貨單預估表單</h2>
            </div>

            <div class='body' id='orderGoodsList'>
                <div class="row clearfix">
                    <div class="col-md-6 col-xs-12 col-sm-12">

                        <form action="{{url('/purchaseEstimateDo')}}" method='post'>
                        {{ csrf_field() }}

                        @if( $isAdmin )
                        <div class="input-group">
                            
                            <span class="input-group-addon">
                            經銷商:
                            </span>
                            
                            <select class="form-control show-tick" name='dealerId'>
                            <option value="0">請選擇經銷商</option>
                            @foreach( $allDealers as $allDealer )
                            <option value="{{$allDealer['id']}}">{{$allDealer['name']}}</option>
                            @endforeach
                            </select>
                           
                        </div>
                        @else
                        <input type="hidden" value="{{$DealerId}}" name="dealerId">
                        @endif
                        <div class="input-group">
                            
                            <span class="input-group-addon">
                            最近:
                            </span>
                            
                            <div class="form-line">
                                <input type="number" class="form-control align-center" min="0" placeholder="" name='dayNum' value="@if( isset($reference) ){{$reference}}@endif">
                            </div>

                            <span class="input-group-addon">
                            天的每日平均量×&nbsp;&nbsp;
                            </span>

                            <div class="form-line">
                                <input type="number" class="form-control align-center" min="0" placeholder="" name='average' value="@if( isset($safeDays) ){{$safeDays}}@endif">
                            </div>
                            
                            <span class="input-group-addon">
                            天的安全庫存量 - 目前庫存量 &nbsp;&nbsp;=&nbsp;&nbsp; <input type="submit" class="btn btn-primary waves-effect" value='需補貨數'>
                            </span>
                           
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        @if( isset($goodsId) && count($goodsId)>0 )
        <div class='card'>

            <div class="header">
                <h2>進貨單預估表單</h2>
            </div>
            
            <div class='body'>

                <form action="{{url('/purchaseOrder')}}" method='POST' >
                    {{ csrf_field() }}

                    <input type='hidden' name='dealerId' value="{{$DealerId}}"> 
                    <table class="table table-bordered" id='goodsAndNum'>
                        <thead>
                            <tr class='bg-grey'>
                                <th>貨號</th>
                                <th>商品名稱</th>
                                <th>銷售數量</th>
                                <th>庫存</th>
                                <th width='20%'>需補貨數量</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < count($goodsId); $i++)
                            <input type='hidden' value='{{$goodsId[$i]}}' name='goodsId[]'>
                            <tr>
                                <th>{{$goodsSn[$i]}}</th>
                                <td>{{$goodsName[$i]}}</td>
                                <td>{{$salesNum[$i]}}</td>
                                <td>0</td>
                                <td><input type='number' name='needNum[]' value="{{$needNum[$i]}}" class="form-control" ></td>
                            </tr>                            
                            @endfor
                            
                        </tbody>
                    </table>

                    <table class="table table-bordered" id='goodsAndNum'>
                        <thead>
                            <tr>
                                <th class='bg-grey'>連絡手機</th>
                                <th><input type='text' name='phone' class="form-control" value="{{$dealerPhone}}" ></th>

                                <th class='bg-grey'>收件地址</th>
                                <th><input type='text' name='address' class="form-control" value="{{$dealerAddress}}" ></th>
                            </tr>   
                                                    
                        </thead>                        
                        <tbody>
                            <tr>
                                <td colspan='4' class='align-center'>
                                    <input type='submit' class='btn btn-primary waves-effect' value='產生出貨單'>
                                </td>
                            </tr> 
                        </tbody>
                    </table> 
                </form>

            </div>

        </div>
        @elseif( isset($goodsId) && count($goodsId)<=0 )
        <div class='card'>

            <div class="header">
                <h2>無銷售資料</h2>
            </div>
            
            <div class='body'>

            </div>

        </div>        
        @endif
        <!-- /進貨單預估表單 -->
    
    </div>
</div>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
<!-- 專屬js -->
<script type="text/javascript">

</script>
<!-- /專屬js -->

@endsection
