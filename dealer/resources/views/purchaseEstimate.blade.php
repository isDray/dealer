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

                    <div class="col-md-12 col-xs-12 col-sm-12">

                        <button class="btn btn-primary waves-effect" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false"
                                    aria-controls="collapseExample">
                        追加商品
                        </button>  

                        <div class="collapse" id="collapseExample">

                            <div class="well">
                            輸入格式 : <span class='bg-teal'>商品編號</span>_<span class='bg-deep-orange'>數量</span><br>
                            例如:<br>
                            <span class='bg-teal'>941688</span>_<span class='bg-deep-orange'>2</span><br>
                            <span class='bg-teal'>511688</span>_<span class='bg-deep-orange'>8</span><br>
                            則會添加2個編號為941688的商品及8個511688的商品
                            <textarea  class="form-control" id='goodsText' rows='4'></textarea>

                            <button class="btn btn-primary waves-effect" id='addGoods'>追加</button> 
                            </div>

                        </div>                            

                    </div> 

                </div>
            </div>
        </div>
        
        <div class='card'>

            <div class="header bg-red">
                <h2>進貨單預估表單</h2>
            </div>
            
            <div class='body'>
                <h4 style='color:red;'>免運門檻:{{$setData->free_price}}</h4>
                <form action="{{url('/purchaseAjaxOrder')}}" method='POST' id='purchaseAjaxNew'>
                    {{ csrf_field() }}

                    <input type='hidden' name='dealerId' value="" id='purchaseDealerId'> 
                    <table class="table table-bordered" id='goodsAndNum' style='display:none;'>
                        <thead>
                            <tr class='bg-grey'>
                                <th>貨號</th>
                                <th>商品名稱</th>
                                <th>銷售數量</th>
                                <th>庫存</th>
                                <th width='20%'>需補貨數量</th>
                                <th>小計</th>
                            </tr>
                        </thead>
                        <tbody id='purchaseFormTable'>
                            
                        </tbody>
                    </table>

                    <table class="table table-bordered" id='purchaseData' style='display:none;'>
                        <thead>
                            <tr>
                                <th class='bg-grey'>收件人姓名</th>
                                <th><input type='text' name='name' class="form-control" value="" id="shipName"></th>
                                
                                <th class='bg-grey'>收件人手機</th>
                                <th><input type='text' name='phone' class="form-control" value="" id="shipPhone"></th>

                                <th class='bg-grey'>收件人電話</th>
                                <th><input type='text' name='tel' class="form-control" value="" id="shipTel"></th>

                            </tr>   
                            <tr>
                                <th class='bg-grey'>收件地址</th>
                                <th colspan='5'><input type='text' name='address' class="form-control" value="" id="shipAddress"></th>
                            </tr>
                            <tr>
                                <th class='bg-grey'>備註</th>
                                <th colspan='5'><textarea  class="form-control" name='dealer_note' rows='6'></textarea></th>
                            </tr>                                                    
                        </thead>                        
                        <tbody>
                            <tr>
                                <td colspan='4' class='align-center'>
                                    <input type='submit' class='btn btn-primary waves-effect' value='產生進貨單'>
                                </td>
                            </tr> 
                        </tbody>
                    </table> 
                </form>

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
        
        // 表單html設為空字串
        formHtml = '';

        // 如果商品id有一個以上才需要產生表單
        if( totalNeedNum > 0 ){
            
            for (var i = 0 ; i < totalNeedNum ; i++) {

                formHtml += "<tr>";
                formHtml += "<input type='hidden' value='"+_datas['goodsId'][i]+"' name='goodsId[]' class='goodsId' />";
                formHtml += "<td>"+_datas['goodsSn'][i]+"</td>";
                formHtml += "<td>"+_datas['goodsName'][i]+"</td>";
                formHtml += "<td>"+_datas['salesNum'][i]+"</td>";
                formHtml += "<td>"+_datas['stock'][i]+"</td>";
                formHtml += "<td><input type='number' name='needNum[]' value='"+_datas['needNum'][i]+"' class='form-control changeNum' min='0' w_price='"+_datas['w_price'][i]+"' changet='total"+i+"'/></td>";
                formHtml += "<td id='total"+i+"'>"+ _datas['needNum'][i] * _datas['w_price'][i] +"</td>";
                formHtml += "</tr>";

            };
            
            // 清空所有form
            $('#purchaseFormTable').empty();
            
            // 將取回資料產生form
            $('#purchaseFormTable').append( formHtml );

        }else{
            
            // 如果沒有商品則呈現無商品訊息
            formHtml += "<tr>";
            formHtml += "<td colspan='5'> 無銷售紀錄 </td>";
            formHtml += "</tr>";
            // 清空所有form
            $('#purchaseFormTable').empty();
            
            // 將取回資料產生form
            $('#purchaseFormTable').append( formHtml );            
        } 

        // 填寫預設配送資訊
        $("#shipName").val( $.trim(_datas['ship']['ship_name']) );
        $("#shipTel").val( $.trim(_datas['ship']['ship_tel']) );
        $("#shipPhone").val( $.trim(_datas['ship']['ship_phone']) );
        $("#shipAddress").val( $.trim(_datas['ship']['ship_address']) );
        $("#purchaseDealerId").val( $.trim(_datas['dealerId']) );
 

        $("#purchaseData").show();
        $("#goodsAndNum").show();
     
    }



    /*----------------------------------------------------------------
     | 呈現成功訊息
     |----------------------------------------------------------------
     */
     function estimateSuccess( _msg ){
        swal.fire({
            
            title: "成功",
            html: _msg,
            type:'success'

        });
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



    /*----------------------------------------------------------------
     | 新增表單送出
     |----------------------------------------------------------------
     |
     */
    $("#purchaseAjaxNew").submit(function(e) {

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
                    //console.log( returnData );
                    
                    
                    if( returnData['res'] == true ){

                        estimateSuccess( returnData['msg'] );
                        
    
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
     | 新增商品到預估單
     |----------------------------------------------------------------
     |
     */
    $("#addGoods").click(function(){
        
        if( !$("#purchaseDealerId").val() ){
            estimateErr('請先產生需補貨數');
            return;
        }

        $.ajax({
                type: "POST",
                url: "{{url('/purchaseAjaxAddGoods')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "goodsText": $('#goodsText').val(),
                    'dealerId':$("#purchaseDealerId").val(),
                },
                dataType:'json',
                success: function( returnData )
                {   
                    
                    if( returnData['res'] == true ){

                        estimateSuccess( returnData['msg'] );

                        var existGoods = new Array();
                        
                        // 將表單內所有的商品id 整理成array用於後續判斷
                        $.each( $(".goodsId") , function() {

                            existGoods.push( parseInt($(this).val()) );
                        });
                        console.log( existGoods);

                        $.each( returnData['datas'] , function(index,detail){

                            if( $.inArray(detail['goodsData']['id'],existGoods) < 0 ){

                                addRow( detail['goodsData'] );
                            
                            }
                        });
                        
                    }else if( returnData['res'] == false ){
                   
                        estimateErr( returnData['msg'] );
                    }
                    
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 

                    /*
                    console.log("Status: " + textStatus); 

                    console.log("Error: " + errorThrown);
                    */
                }            
        });        
    });




    /*----------------------------------------------------------------
     | 添加一欄位
     |----------------------------------------------------------------
     | 
     */
    function addRow( _datas ){
        
        var formHtml  = '';
        formHtml += "<tr>";
        formHtml += "<input type='hidden' value='"+_datas['id']+"' name='goodsId[]' class='goodsId' />";
        formHtml += "<td>"+_datas['goods_sn']+"</td>";
        formHtml += "<td>"+_datas['name']+"</td>";
        formHtml += "<td>"+0+"</td>";
        formHtml += "<td>0</td>";
        formHtml += "<td><input type='number' name='needNum[]' value='"+_datas['addNum']+"' class='form-control' /></td>";
        formHtml += "<td>"+ _datas['addNum'] * _datas['w_price']+"</td>";
        formHtml += "</tr>";

        $('#purchaseFormTable').append( formHtml );
    }




    /*----------
     |
     |
     |
     */
    $('body').on('click keyup', '.changeNum', function() {
        //console.log($(this).attr('w_price'));
        //console.log($(this).attr('changet'));
        $("#"+$(this).attr('changet')).html( $(this).val() * $(this).attr('w_price') );
    });
}) 
</script>
<!-- /專屬js -->

@endsection
