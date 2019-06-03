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
                            
                            <select class="form-control show-tick myborder" name='dealerId' >
                            <option value="0">請選擇經銷商</option>
                            @foreach( $allDealers as $allDealer )
                            <option value="{{$allDealer['id']}}">{{$allDealer['name']}}</option>
                            @endforeach
                            </select>
                           
                        </div>
                        @else
                        <input type="hidden" value="{{$DealerId}}" name="dealerId">
                        @endif
                    </div>
                    <div class="col-md-8 col-xs-12 col-sm-12">
                        <div class="input-group">
                            
                            <span class="input-group-addon">
                            最近:
                            </span>
                            
                            <div class="form-line myborder">
                                <input type="number" class="form-control align-center" min="0" placeholder="" name='dayNum' value="@if( isset($reference) ){{$reference}}@endif">
                            </div>

                            <span class="input-group-addon">
                            天的每日平均量×&nbsp;&nbsp;
                            </span>

                            <div class="form-line myborder">
                                <input type="number" class="form-control align-center" min="0" placeholder="" name='average' value="@if( isset($safeDays) ){{$safeDays}}@endif">
                            </div>
                            
                            <span class="input-group-addon">
                            天的安全庫存量 - 目前庫存量 &nbsp;&nbsp;=&nbsp;&nbsp; <input type="submit" class="btn btn-primary waves-effect" value='需補貨數'>
                            </span>
                           
                        </div>

                        </form>
                    </div>
                    @role('Admin')
                    <div class="col-md-12 col-xs-12 col-sm-12">

                        <button class="btn btn-primary waves-effect" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false"
                                    aria-controls="collapseExample">
                        追加商品
                        </button>  

                        <div class="collapse" id="collapseExample">

                            <div class="well">
                            輸入格式 : <span class=''>商品編號</span>_<span class=''>數量(一組編號一列)</span><br>
                            例如:<br>
                            <span class=''>941688</span>_<span class=''>2</span><br>
                            <span class=''>511688</span>_<span class=''>8</span><br>
                            則會添加2個編號為941688的商品及8個511688的商品
                            <textarea  class="form-control" id='goodsText' rows='4'></textarea>

                            <button class="btn btn-primary waves-effect" id='addGoods'>追加</button> 
                            </div>

                        </div>    
                                                 

                    </div> 
                    @endrole
                    <div class="col-md-12 col-xs-12 col-sm-12">

                        <button id='addZero'class="btn btn-primary waves-effect" type="button">增加庫存0商品</button>                             

                    </div>
                </div>
            </div>
        </div>
        
        <div class='card'>

            <div class="header bg-red">
                <h2>進貨單預估表單</h2>
            </div>
            
            <div class='body'>

                <input type='hidden' id="free_price" value="0" >
                <input type='hidden' id="ship_fee" value="0" >

                <form action="{{url('/purchaseAjaxOrder')}}" method='POST' id='purchaseAjaxNew'>
                    {{ csrf_field() }}
                    <input type='hidden' name='dealerId' value="" id='purchaseDealerId'> 
                    <table class="table table-bordered" id='goodsAndNum' style='display:none;'>
                        <thead>
                            <tr class='bg-grey'>
                                <th>貨號</th>
                                <th>商品名稱</th>
                                <th class='align-center'>總銷售數量</th>
                                <th class='align-center'>銷售數量</th>
                                <th class='align-center'>庫存</th>
                                <th class='align-center'>批發價</th>
                                <th class='align-center' width='20%'>需補貨數量</th>
                                <th class='align-right'>小計</th>
                            </tr>
                        </thead>
                        <tbody id='purchaseFormTable'>
                            
                        </tbody>

                        <tbody id='purchaseDetail'>

                        </tbody>
                    </table>

                    <table class="table table-bordered" id='purchaseData' style='display:none;'>
                        <thead>
                            <tr>
                                <th class='bg-grey align-right' style="vertical-align: middle;">收件人姓名</th>
                                <th><input type='text' name='name' class="form-control" value="" id="shipName"></th>
                                
                                <th class='bg-grey align-right' style="vertical-align: middle;">收件人手機</th>
                                <th><input type='text' name='phone' class="form-control" value="" id="shipPhone"></th>

                                <th class='bg-grey align-right' style="vertical-align: middle;">收件人電話</th>
                                <th><input type='text' name='tel' class="form-control" value="" id="shipTel"></th>

                            </tr>  
                            <tr>
                                <th  class='bg-grey align-right' style="vertical-align: middle;">統編</th>
                                <th><input type='text' name='ein' class="form-control" value="" id="ein"></th>

                                <th class='bg-grey align-right' style="vertical-align: middle;">公司抬頭</th>
                                <th colspan="3"><input type='text' name='company' class="form-control" value="" id="company"></th>
                                
                            </tr>                             
                            <tr>
                                <th class='bg-grey align-right' style="vertical-align: middle;">收件地址</th>
                                <th colspan='5'><input type='text' name='address' class="form-control" value="" id="shipAddress"></th>
                            </tr>
                            <tr>
                                <th class='bg-grey align-right' style="vertical-align: middle;">備註</th>
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
        
        var allTotal = 0;
        // 如果商品id有一個以上才需要產生表單
        if( totalNeedNum > 0 ){
            
            for (var i = 0 ; i < totalNeedNum ; i++) {
                 
                formHtml += "<tr>";
                formHtml += "<input type='hidden' value='"+_datas['goodsId'][i]+"' name='goodsId[]' class='goodsId' />";
                formHtml += "<td>"+_datas['goodsSn'][i]+"</td>";
                formHtml += "<td>"+_datas['goodsName'][i]+"</td>";
                formHtml += "<td class='align-center'>"+_datas['allSalesNum'][i]+"</td>";
                formHtml += "<td class='align-center'>"+_datas['salesNum'][i]+"</td>";
                formHtml += "<td class='align-center'>"+_datas['stock'][i]+"</td>";
                formHtml += "<td class='align-center'>"+_datas['w_price'][i]+"</td>";
                formHtml += "<td class='align-center'><input type='number' name='needNum[]' value='"+_datas['needNum'][i]+"' class='form-control changeNum' min='0' w_price='"+_datas['w_price'][i]+"' changet='total"+i+"'/></td>";
                formHtml += "<td class='align-right subtotal' id='total"+i+"' >"+ _datas['needNum'][i] * _datas['w_price'][i] +"</td>";
                formHtml += "</tr>";
                
                allTotal +=  _datas['needNum'][i] * _datas['w_price'][i] ;
            };

            // 添加稅
            // 清空所有form
            $('#purchaseFormTable').empty();
            
            // 將取回資料產生form
            $('#purchaseFormTable').append( formHtml );

            formHtml = '';
            // 添加價格總計
            formHtml += "<tr>";
            formHtml += "<td colspan='6'></td>";
            formHtml += "<td class='align-right'>總計</td>";
            formHtml += "<td id='allTotal' class='align-right' >"+allTotal+"</td>";
            formHtml += "</tr>";

            // 添加運費

            if( allTotal >= _datas['free_price'] ){
                
                shipFee = 0;

            }else{
                shipFee  = _datas['shipfee'];
            }

            diffFree = _datas['free_price'] - allTotal;
            
            if( diffFree < 0){

                diffFree = 0;
            }

            formHtml += "<tr>";
            formHtml += "<td colspan='6' class='align-right' id='taxTip'>免運門檻:"+_datas['free_price']+"元,再"+diffFree+"元即可免運</td>";
            formHtml += "<td class='align-right'>運費</td>";
            formHtml += "<td id='shipFee' class='align-right' freeFee='"+_datas['free_price']+"' shipFee='"+shipFee+"'>"+shipFee+"</td>";
            formHtml += "</tr>";               

            // 計算稅金
            if( _datas['ein'] =='' ||  _datas['ein'] == null ){

                tax = 0;

            }else{
                
                tax = Math.round( (allTotal+shipFee) * 0.05 );
            }
            formHtml += "<tr>";
            formHtml += "<td colspan='6'></td>";
            formHtml += "<td class='align-right'>稅金</td>";
            formHtml += "<td id='tax' class='align-right' >"+tax+"</td>";
            formHtml += "</tr>";            
            
            
            // 應付金額

            needPay = allTotal+shipFee+tax;

            formHtml += "<tr>";
            formHtml += "<td colspan='6'></td>";
            formHtml += "<td class='align-right'>應付金額</td>";
            formHtml += "<td id='needPay' class='align-right' >"+needPay+"</td>";
            formHtml += "</tr>"; 

            $('#purchaseDetail').empty();
            
            // 將取回資料產生form
            $('#purchaseDetail').append( formHtml );


        }else{
            
            // 如果沒有商品則呈現無商品訊息
            formHtml += "<tr id='noRecord'>";
            formHtml += "<td colspan='8'> 無銷售紀錄,或不需要補貨 </td>";
            formHtml += "</tr>";
            // 清空所有form
            $('#purchaseFormTable').empty();
            
            // 將取回資料產生form
            $('#purchaseFormTable').append( formHtml );            

            formHtml = '';
            // 添加價格總計
            formHtml += "<tr>";
            formHtml += "<td colspan='6'></td>";
            formHtml += "<td class='align-right'>總計</td>";
            formHtml += "<td id='allTotal' class='align-right' >"+allTotal+"</td>";
            formHtml += "</tr>";

            // 添加運費

            if( allTotal >= _datas['free_price'] ){
                
                shipFee = 0;

            }else{
                shipFee  = _datas['shipfee'];
            }

            diffFree = _datas['free_price'] - allTotal;
            
            if( diffFree < 0){

                diffFree = 0;
            }

            formHtml += "<tr>";
            formHtml += "<td colspan='6' class='align-right' id='taxTip'>免運門檻:"+_datas['free_price']+"元,再"+diffFree+"元即可免運</td>";
            formHtml += "<td class='align-right'>運費</td>";
            formHtml += "<td id='shipFee' class='align-right' freeFee='"+_datas['free_price']+"' shipFee='"+shipFee+"'>"+shipFee+"</td>";
            formHtml += "</tr>";               

            // 計算稅金
            if( _datas['ein'] =='' ||  _datas['ein'] == null ){

                tax = 0;

            }else{
                
                tax = Math.round( (allTotal+shipFee) * 0.05 );
            }
            formHtml += "<tr>";
            formHtml += "<td colspan='6'></td>";
            formHtml += "<td class='align-right'>稅金</td>";
            formHtml += "<td id='tax' class='align-right' >"+tax+"</td>";
            formHtml += "</tr>";            
            
            
            // 應付金額

            needPay = allTotal+shipFee+tax;

            formHtml += "<tr>";
            formHtml += "<td colspan='6'></td>";
            formHtml += "<td class='align-right'>應付金額</td>";
            formHtml += "<td id='needPay' class='align-right' >"+needPay+"</td>";
            formHtml += "</tr>"; 

            $('#purchaseDetail').empty();
            
            // 將取回資料產生form
            $('#purchaseDetail').append( formHtml );            
        } 

        // 填寫預設配送資訊
        $("#shipName").val( $.trim(_datas['ship']['ship_name']) );
        $("#shipTel").val( $.trim(_datas['ship']['ship_tel']) );
        $("#shipPhone").val( $.trim(_datas['ship']['ship_phone']) );
        $("#shipAddress").val( $.trim(_datas['ship']['ship_address']) );
        $("#purchaseDealerId").val( $.trim(_datas['dealerId']) );
        
        $("#company").val( $.trim(_datas['company']) );
        $("#ein").val( $.trim(_datas['ein']) );

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

                        calculateOrder();

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
     | 添加庫存0商品
     |----------------------------------------------------------------
     |
     */
    $("#addZero").click(function(){
        
        //  確認已經產生需補貨數
        if( !$("#purchaseDealerId").val() ){

            estimateErr('請先產生需補貨數');
            return;
        }

        $.ajax({
                type: "POST",
                url: "{{url('/purchaseAjaxAddZero')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
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

                        $.each( returnData['datas'] , function(index,detail){

                            if( $.inArray(detail['goodsData']['id'],existGoods) < 0 ){

                                addRow( detail['goodsData'] );
                            
                            }
                        });

                        calculateOrder();

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
        
        $("#noRecord").remove();

        rowCount = $('#purchaseFormTable tr').length;

        var formHtml  = '';

        formHtml += "<tr>";
        formHtml += "<input type='hidden' value='"+_datas['id']+"' name='goodsId[]' class='goodsId' />";
        formHtml += "<td>"+_datas['goods_sn']+"</td>";
        formHtml += "<td>"+_datas['name']+"</td>";
        formHtml += "<td class='align-center'>"+_datas['allSalesNum']+"</td>";
        formHtml += "<td class='align-center'>"+0+"</td>";
        formHtml += "<td class='align-center'>0</td>";
        formHtml += "<td class='align-center'>"+_datas['w_price']+"</td>";
        formHtml += "<td><input type='number' name='needNum[]' value='"+_datas['addNum']+"' class='form-control changeNum' w_price='"+_datas['w_price']+"' changet='total"+rowCount+"' /></td>";
        formHtml += "<td class='align-right subtotal' id='total"+rowCount+"'>"+ _datas['addNum'] * _datas['w_price']+"</td>";
        formHtml += "</tr>";

        $('#purchaseFormTable').append( formHtml );
    }




    /*----------------------------------------------------------------
     | 動態改變數值
     |----------------------------------------------------------------
     |
     */
    $('body').on('click keyup', '.changeNum', function() {
        //console.log($(this).attr('w_price'));
        //console.log($(this).attr('changet'));
        $("#"+$(this).attr('changet')).html( $(this).val() * $(this).attr('w_price') );

        calculateOrder();
    });

    /*----------------------------------------------------------------
     | 調整統編時影響進貨單總和
     |----------------------------------------------------------------
     |
     */
    $('body').on('click keyup', '#ein', function() {
        calculateOrder();
    });
     
    $(".focused").removeClass('focused');
})

/*----------------------------------------------------------------
 | 重新統計
 |----------------------------------------------------------------
 |
 */
function calculateOrder(){
    var calAllTotal = 0;

    $(".subtotal").each(function() {

        calAllTotal += parseInt($(this).html()) ;

    });    
    
    // 變更總價
    $("#allTotal").html('');
    $("#allTotal").html(calAllTotal);

    // 運費
    var calFreeFee = $("#shipFee").attr('freefee');
    var calShipFee = $("#shipFee").attr('shipFee');

    if( calAllTotal >= calFreeFee){

        calShipFee = 0;

    }
    
    var calDiffFee = calFreeFee - calAllTotal;

    if( calDiffFee < 0){

        calDiffFee = 0;
    }
    // 變更運費
    $("#shipFee").html('');
    $("#shipFee").html(calShipFee);

    $("#taxTip").html('');
    $("#taxTip").html( "免運門檻:"+calFreeFee+"元,再"+calDiffFee+"元即可免運");

    // 變更稅金
    if( $("#ein").val() == '' ){

        calTax = 0;

    }else{

        calTax = Math.round( ( parseInt(calAllTotal)+parseInt(calShipFee) ) * 0.05 );
    }

    $("#tax").html('');
    $("#tax").html(calTax);

    // 變更總金額
    $("#needPay").html('');
    $("#needPay").html( parseInt(calAllTotal)+parseInt(calShipFee)+parseInt(calTax) );
}

</script>
<!-- /專屬js -->

@endsection
