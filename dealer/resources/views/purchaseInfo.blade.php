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
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class='bg-grey' width='10%'>進貨單編號</td>
                            <td></td>
                            <td class='bg-grey' width='10%'>下單時間</td>
                            <td></td> 
                            <td class='bg-grey' width='10%'>進貨狀態</td>
                            <td></td>                            
                        </tr>

                        <tr>
                            <td class='bg-grey' width='10%'>出貨時間</td>
                            <td></td>
                            <td class='bg-grey' width='10%'>經銷商編號</td>
                            <td></td> 
                            <td class='bg-grey' width='10%'>收件人</td>
                            <td></td>                            
                        </tr>

                        <tr>
                            <td class='bg-grey' width='10%'>收件人地址</td>
                            <td></td>
                            <td class='bg-grey' width='10%'>連絡電話</td>
                            <td></td> 
                            <td class='bg-grey' width='10%'>備註</td>
                            <td></td>                            
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
        


            <!-- 訂單操作紀錄 -->
            <div class="header ">
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
