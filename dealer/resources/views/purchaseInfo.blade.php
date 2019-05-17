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
                <h2>訂單基本資料</h2>
            </div>

            <div class='body'>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class='bg-grey' width='10%'>進貨單編號</td>
                            <td>{{$purchaseData['purchase_sn']}}</td>
                            <td class='bg-grey' width='10%'>下單時間</td>
                            <td>{{$purchaseData['created_at']}}</td> 
                            <td class='bg-grey' width='10%'>進貨狀態</td>
                            <td>{{$purchaseData['statusTxt']}}</td>                            
                        </tr>

                        <tr>
                            <td class='bg-grey' width='10%'>出貨時間</td>
                            <td>
                            @if( !empty( $purchaseData['shipdate'] ) )
                            {{$purchaseData['shipdate']}}
                            @else
                            尚未出貨
                            @endif
                            </td>
                            <td class='bg-grey' width='10%'>經銷商編號</td>
                            <td>{{$purchaseData['dealer_id']}}</td> 
                            <td class='bg-grey' width='10%'>收件人</td>
                            <td>{{$purchaseData['consignee']}}</td>                            
                        </tr>

                        <tr>
                            <td class='bg-grey' width='10%'>收件人地址</td>
                            <td>{{$purchaseData['address']}}</td>
                            <td class='bg-grey' width='10%'>連絡手機</td>
                            <td>{{$purchaseData['phone']}}</td> 
                            <td class='bg-grey' width='10%'>連絡電話</td>
                            <td>{{$purchaseData['tel']}}</td>                            
                        </tr>
                        <tr>
                            <td class='bg-grey' width='10%'>管理員備註</td>
                            <td colspan='5'>{{$purchaseData['admin_note']}}</td>                          
                        </tr>                        
                        <tr>
                            <td class='bg-grey' width='10%'>買家備註</td>
                            <td colspan='5'>{{$purchaseData['dealer_note']}}</td>                          
                        </tr>                        
                        <!--
                        <tr>
                            <td colspan='6' class='align-center'>
                                <a href="{{url('/')}}">
                                <span class='btn btn-primary waves-effect'>編輯訂單基本資料</span>
                                </a>
                            </td>
                        </tr> 
                        -->

                    </tbody>
                </table>                
            </div>
            <!-- /進貨單基本資料 -->

        </div>


        <div class='card'>

            <!-- 進貨單基本資料 -->
            <div class="header bg-red">
                <h2>進貨單明細</h2>
            </div>

            <div class='body'>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class='bg-grey' >商品貨號</th>
                            <th class='bg-grey' >商品名稱</th>
                            <th class='bg-grey' >批發價</th>
                            <th class='bg-grey' >數量</th>
                            <th class='bg-grey' >小計</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                    $total = 0;
                    @endphp

                    @foreach( $purchaseDetails as $purchaseDetail)
                    <tr>

                        <td>{{$purchaseDetail['goods_sn']}}</td>

                        <td>{{$purchaseDetail['goods_name']}}</td>

                        <td>{{$purchaseDetail['w_price']}}</td> 

                        <td>{{$purchaseDetail['num']}}</td>                                                                 

                        <td>{{$purchaseDetail['w_price'] * $purchaseDetail['num']}}</td>                        
                    </tr>

                    @php
                    $total += $purchaseDetail['w_price'] * $purchaseDetail['num'];
                    @endphp

                    @endforeach
                    <tr>
                        <td colspan='4' class='align-right'>運費</td>
                        <td>{{$purchaseData['ship_fee']}}</td>
                    </tr>
                    <tr>

                        <td colspan='4' class='align-right'>總金額</td>
                        <td>{{$purchaseData['final_amount']}}</td>
                    </tr>

                    </tbody>


                </table>

            </div>

        </div>

        <!-- 操作區塊 -->
        <div class='card'>

            
            <div class="header bg-red">
                <h2>操作</h2>
            </div>

            <div class='body'>
                
                <form action="{{url('/puchaseStatus')}}" method="POST">

                    {{ csrf_field() }}

                    <div class="align-left">
                        <input type="hidden" name="purchaseId" value="{{$purchaseData['id']}}">
                        @role('Admin')
                       
                        <input type="submit" name="pending" class="btn btn-primary waves-effect" value="待處理">
                        <input type="submit" name="checked" class="btn btn-primary waves-effect" value="已確認">
                        <input type="submit" name="shipped" class="btn btn-primary waves-effect" value="已出貨">
                        <input type="submit" name="cancel" class="btn btn-primary waves-effect" value="取消">
                        @endrole

                        @role('Dealer')
                            @if($purchaseData['status'] == 1)
                            <input type="submit" name="cancel" class="btn btn-primary waves-effect" value="取消">
                            @endif

                            @if($purchaseData['status'] == 3)
                            <!--<input type="submit" name="addStock" class="btn btn-primary waves-effect" value="加入庫存">-->
                            
                            <!-- 針對細項的加入庫存 -->
                            <a href="{{url('addStockException')}}/{{$purchaseData['id']}}" class="btn btn-primary waves-effect">
                                加入庫存
                            </a>
                            <!-- /針對細項加入庫存 -->
                            @endif
                        @endrole
                    </div>

                </form>
            </div>

        </div>
        
        <!-- /操作區塊 -->



        
        <!-- 操作紀錄表 -->
        <div class='card'>

            <div class="header bg-red">
                <h2>進貨單操作紀錄</h2>
            </div>

            <div class='body'>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class='bg-grey'>操作人</th>
                            <th class='bg-grey'>狀態</th>
                            <th class='bg-grey'>操作描述</th>
                            <th class='bg-grey'>時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $purchaseLogs as $purchaseLogs )
                        <tr>
                            <td>{{ $purchaseLogs['user_name'] }}</td>
                            <td>{{ $purchaseLogs['purchase_status_text'] }}</td>
                            <td>{{ $purchaseLogs['desc'] }}</td>
                            <td>{{ $purchaseLogs['created_at'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>        
        <!-- /操作紀錄表 -->


</div>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){

})
</script>
<!-- /專屬js -->

@endsection
