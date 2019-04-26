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

            <div class="header bg-red">
                <h2>進貨單預估表單</h2>
            </div>

            <div class='body' id='orderGoodsList'>
                <div class="row clearfix">
                    <div class="col-md-6 col-xs-12 col-sm-12">

                        <form action="{{url('/purchaseAjaxEstimateDo')}}" method='post' id='purchaseAjaxEstimateDo'>
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
        
        <div class='card'>

            <div class="header bg-red">
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
                        <tbody id='purchaseFormTable'>
                            
                        </tbody>
                    </table>

                    <table class="table table-bordered" id='goodsAndNum'>
                        <thead>
                            <tr>
                                <th class='bg-grey'>收件人姓名</th>
                                <th><input type='text' name='name' class="form-control" value="" ></th>
                                
                                <th class='bg-grey'>收件人手機</th>
                                <th><input type='text' name='phone' class="form-control" value="" ></th>

                                <th class='bg-grey'>收件人電話</th>
                                <th><input type='text' name='phone' class="form-control" value="" ></th>

                            </tr>   
                            <tr>
                                <th class='bg-grey'>收件地址</th>
                                <th colspan='5'><input type='text' name='address' class="form-control" value="" ></th>
                            </tr>
                            <tr>
                                <th class='bg-grey'>備註</th>
                                <th colspan='5'><textarea  class="form-control" name='dealer_note' rows='6'></textarea></th>
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

        <div class='card'>

            <div class="header">
                <h2>無銷售資料</h2>
            </div>
            
            <div class='body'>

            </div>

        </div>        

        <!-- /進貨單預估表單 -->
    
    </div>
</div>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
<!-- 專屬js -->
<script type="text/javascript">

$(function(){
    
    $("#purchaseAjaxEstimateDo").submit(function(e) {

    e.preventDefault(); // 避免真實表單送出

    var form = $(this);
    var url = form.attr('action');

    $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            dataType:'json',
            success: function( returnData )
            {   
                console.log( returnData['res'] );

                if( returnData['res'] == true ){
                    
                    estimateGenerator( returnData['datas'] );

                }else if( returnData['res'] == false ){

                    estimateErr( returnData['msg'] );
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                console.log("Status: " + textStatus); 
                console.log("Error: " + errorThrown); 
            }            
         });


    });    




    /*----------------------------------------------------------------
     | ajax 取回訂貨單諮詢後 , 動態產生表單
     |----------------------------------------------------------------
     |
     */
    function estimateGenerator( _datas ){
        
        // 使用商品id的陣列長度來計算總共有多少筆商品
        totalNeedNum = _datas['goodsId'].length;
        
        // 如果商品id有一個以上才需要產生表單
        if( totalNeedNum > 0 ){
            
            // 表單html設為空字串
            formHtml = '';

            for (var i = 0 ; i < totalNeedNum ; i++) {
                formHtml += "<input type='hidden' value='"+_datas['goodsId'][i]+"' name='goodsId[]' />";
                formHtml += "<tr>";
                formHtml += "<td>"+_datas['goodsSn'][i]+"</td>";
                formHtml += "<td>"+_datas['goodsName'][i]+"</td>";
                formHtml += "<td>"+_datas['salesNum'][i]+"</td>";
                formHtml += "<td>0</td>";
                formHtml += "<td><input type='number' name='needNum[]' value='"+_datas['needNum'][i]+"' class='form-control' /></td>";
                formHtml += "</tr>";

            };
            
            $('#purchaseFormTable').empty();

            $('#purchaseFormTable').append( formHtml );

        }else{

        }

    }




    /*----------------------------------------------------------------
     | 呈現錯誤訊息
     |----------------------------------------------------------------
     |
     */
    function estimateErr( _msg ){
        
        swal.fire({
            
            title: "執行失誤",
            html: _msg,
            type:'error'

        });
    }
}) 
</script>
<!-- /專屬js -->

@endsection
