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

        
        <!-- 訂單明細 -->
        <div class='card'>
            <div class="header bg-red">
                <h2>訂單明細</h2>
            </div>
            <div class='body' id='orderGoodsList'>
            @if( count($orderGoods) > 0)
                
                <form method="post" action="{{url('/orderEditGoods')}}">
                {{ csrf_field() }}
                
                <input type="hidden" value="{{ $orderId }}" name="orderid"  id='orderid_input' class="form-control" >
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>商品縮圖</th>
                            <th>商品貨號</th>
                            <th>商品名稱</th>
                            <th>商品數量</th>
                            <th>商品價格</th>
                            <th>商品小計</th>
                            <th>移除</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                        $tmpAmount = 0;
                        @endphp

                        @foreach ($orderGoods as $orderGood)

                        @php
                        $tmpAmount += $orderGood['subtotal'];
                        @endphp
                        <tr>
                            <!--<input type="hidden" value="{{ $orderGood['oid'] }}" name="orderid" class="form-control" >-->
                            <input type="hidden" value="{{ $orderGood['gid'] }}" name="id[]" class="form-control" style="width:auto;">
                            <td><img src="{{url('/images/'.$orderGood['thumbnail'])}}" width="64px;"></td>
                            <td>{{ $orderGood['goods_sn'] }}</td>
                            <td>{{ $orderGood['name'] }}</td>
                            <td><input type="text" value="{{ $orderGood['num'] }}" name="num[]" class="form-control" style="width:auto;"></td>
                            <td><input type="text" value="{{ $orderGood['price'] }}" name="price[]" class="form-control" style="width:auto;"></td>
                            <td>{{ $orderGood['subtotal'] }}</td>
                            <td><p class='btn btn-danger waves-effect delItem' oid="{{ $orderGood['oid'] }}" gid="{{ $orderGood['gid'] }}">移除</p></td>
                        </tr> 
                        @endforeach    
                        <tr>
                            <td colspan='5' class='align-right'>合計:</td>
                            <td>{{$tmpAmount}}</td>
                            <td><input type='submit' id='orderUpdate' class='btn btn-primary waves-effect' value='更新商品'></td>
                        </tr> 
                    </tbody>


                </table>
                </form>
            @else
                <input type="hidden" value="{{ $orderId }}" name="orderid"  id='orderid_input' class="form-control" >
                暫無商品
            @endif

            </div>

            <!-- 刪除訂單中商品表單 -->
            <form action="{{url('/orderDeleteGoods')}}" method="POST" id='deleteOrderItem'>
                {{ csrf_field() }}
                <input type='hidden' id='deleteOid' name='oid'>
                <input type='hidden' id='deleteGid' name='gid'>
            </form>
            <!-- /刪除訂單中商品表單 -->

        </div>
        <!-- /訂單明細 -->
        <div class="card">

        <!-- form 表格 -->
        <div class="header bg-red">
            <h2>
            {{$title}}表格
            <small>填寫以下表格以新增一組訂單</small>
            </h2>

            <!-- 功能列表(暫時用不到)
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);">Action</a></li>
                        <li><a href="javascript:void(0);">Another action</a></li>
                        <li><a href="javascript:void(0);">Something else here</a></li>
                    </ul>
                </li>
            </ul>
            -->
        </div>

        <div class="body" id='addBox'>

            <div class="row clearfix">
                <!-- 訂單商品 -->
                <div class='col-md-12 col-sm-12 col-xs-12' >
                                 

                </div>    
                <!-- / 訂單商品 -->

                <!-- 選取訂單商品 -->
                <div class='col-md-12 col-sm-12 col-xs-12' >
                    
                    <div class="col-sm-2">
                        <small>請輸入貨號或商品名稱</small>
                        
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" placeholder="" id='goodsKeyWord' />
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-1">
                        <small>&nbsp;</small>
                        
                        <div class="form-group">
                            <div>
                                <button id='goodsSearch' class='btn btn-primary waves-effect'>搜尋</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-9">
                        <small>&nbsp;</small>
                        
                        <div class="form-group" style="width:auto;">
                            
                            <div style="width:auto;">
                                <select id='goodsres' class="form-control show-tick myborder autoSelect"> 
                                    <option value="">請先搜尋關鍵字</option>
                                </select>
                            </div>
                        </div>
                    </div> 

                </div> 
                <!-- 選取訂單商品 -->

                <!-- 確認商品 -->
                <div class='col-md-12 col-sm-12 col-xs-12' id='chkGoodsDiv'>
                    
                    <div class='col-md-12 col-sm-12 col-xs-12' id='goodsTables'>
                
                    </div>

                </div>
                <!-- /確認商品 -->
            </div>    


        </div><!-- /body -->

        </div><!-- /card -->


        <div class='card'>
            <div class='body align-center'>
                
                @if( $isNew == True )
                    <!-- <a href="{{url('/orderEditBasic/new/'.$orderId)}}"> -->
                    <a href="{{url('/orderInfo/'.$orderId)}}">
                        <span class='btn btn-primary waves-effect'>確定</span>
                    </a>
                @else
                    <a href="{{url('/orderInfo/'.$orderId)}}">
                        <span class='btn btn-primary waves-effect'>確定</span>
                    </a>
                @endif

            </div>
        </div>

    </div>
</div>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<style type="text/css">
.btn-group.autoSelect{
   width: auto!important;  
   border-bottom:2px solid #c4c4c4 !important;
}

.btn-group.autoSelect > button >.bs-caret > .caret{
    right: 0px!important;
}


</style>
<!-- 專屬js -->
<script type="text/javascript">
$(function(){
    
    /*----------------------------------------------------------------
     | 查詢符合訂單
     |----------------------------------------------------------------
     |
     */

    $("#goodsSearch").click(function(){

        // 如果搜尋框內有值才接著做搜尋動作
        if( $("#goodsKeyWord").val() ){

            var request = $.ajax({
                url: "{{url('/orderSearchGoods')}}",
                method: "POST",
                data: { goodsKeyWord : $("#goodsKeyWord").val(),
                        dealerId:"{{$dealerId}}",
                        _token: "{{ csrf_token() }}",
                      },
                dataType: "json"
            });
 
            request.done(function( res ) {
                
               
                // 如果ajax回傳的陣列長度大於1(表示資料庫有對應資料) , 則重新整理
                if( res.length > 0){
                    
                    // 先清空下拉選單
                    $("#goodsres").empty();
                    
                    //
                    //$("#goodsres").append("<option value='0'>請選擇商品</option>");
                    
                    $.each( res , function( index , goods ){
                        
                        //console.log( goods['name'] );
                        $("#goodsres").append("<option value='"+goods['id']+"'>"+goods['name']+' | 貨號:'+goods['goods_sn']+"</option>");
                        
                    });
                    
                    // ajax產生商品下拉選單後 , 直接觸發一次選單改變事件
                    $("#goodsres").trigger( "change" );
                    

                    // 重新整理下拉選單
                    $('#goodsres').selectpicker('refresh');
                
                }else{
                    
                    // 清空下拉選單
                    //$('#goodsNumber').val();

                    // 還原初始化呈現
                    //$("#goodsres").append("<option value='"+goods['id']+"'>"+goods['name']+'訂單編號:'+goods['goods_sn']+"</option>");
                }

            });
 
            request.fail(function( jqXHR, textStatus ) {

                console.log( "Request failed: " + textStatus );

            });         
    
        }
    });
    


    /*----------------------------------------------------------------
     | 選取商品後顯示對應資料
     |----------------------------------------------------------------
     | 
     |
     */
    $("#goodsres").change(function( e ){
        
        // 預存商品id
        chooseId = $(this).val();
        
        // 如果商品id不為0,就撈取該商品相關資料
        if( chooseId != 0 ){
            
            var request = $.ajax({
                url: "{{url('/orderGetGoods')}}",
                method: "POST",
                data: { _token: "{{ csrf_token() }}", 
                        chooseId : chooseId,
                        dealerId :"{{$dealerId}}"
                },
                dataType: "json"
            });
             
            request.done(function( res ) {
                

                //console.log( res );
                // 如果有取得商品資訊
                if( res !== false ){
                    
                    // 清空要呈現選項的區塊
                    $("#goodsTables").empty();

                    tableDatas  = "<table class='table table-hover'>";
                    tableDatas += "<thead><tr><th></th><th></th></tr></thead>";
                    tableDatas += "<tbody>";
                    tableDatas += "<tr><td>商品名稱</td><td>"+res['name']+"</td></tr>";
                    tableDatas += "<tr><td>商品貨號</td><td>"+res['goods_sn']+"</td></tr>";
                    tableDatas += "<tr><td>商品類別</td><td>"+res['cname']+"</td></tr>";
                    tableDatas += "<tr><td>商品價格</td><td><input name='goodsPrice' type='radio' id='radio_1' checked value='"+res['price']+"' /><label for='radio_1'>售價:"+res['price']+"</label><br><input name='goodsPrice' type='radio' id='radio_3' value='"+res['w_price']+"' /><label for='radio_3'>自訂售價:</label><input type='text' class='form-control' style='display:inline;width:auto;' id='setPrice' ></td></tr>";
                    tableDatas += "<tr><td>商品縮圖</td><td><img src='{{url('/images')}}/"+res['thumbnail']+"' width='64px'></td></tr>";
                    tableDatas += "<tr><td>商品數量</td><td><div class='col-md-2' style='padding-left:0px;'><input type='number' class='form-control' min='1' value='1' id='goodsNumber'/></div></td></tr>";
                    tableDatas += "<tr><td colspan='2'><button id='addToOrder' class='btn btn-primary waves-effect'>添加商品</button></td></tr>";
                    tableDatas += "</tbody></table>";
                    tableDatas += "<input type='hidden' id='goodsId' value='"+res['id']+"'>";
                    //<input name='goodsPrice' type='radio' id='radio_2' value='"+res['w_price']+"' /><label for='radio_2'>批發價:"+res['w_price']+"</label><br>            
                    $("#goodsTables").append( tableDatas );


                }else{

                }

            });
             
            request.fail(function( jqXHR, textStatus ) {
                //alert( "Request failed: " + textStatus );
            });

        }

    });



    /*----------------------------------------------------------------
     | 添加商品至訂單
     |----------------------------------------------------------------
     | 填寫相關資料後,將商品轉換成form表單資料
     |
     */
    $('body').on('click',"#addToOrder", function() {
        
        // 取得商品id
        goodsId     = $('#goodsId').val();

        // 判斷價格
        if( $('input[name=goodsPrice]:checked').attr('id') != 'radio_3' ){
            
            goodsPrice  = $('input[name=goodsPrice]:checked').val();

        }else{

            goodsPrice  = $("#setPrice").val();
        } 
        
        // 取得數量
        goodsNumber = $('#goodsNumber').val();
        
        /* ajax 將商品資料寫入訂單
         *----------------------------------------------------------------
         *
         */

        var requestAddGoods = $.ajax({
            url: "{{url('/orderAddGoods')}}",
            method: "POST",
            data: {

                goodsId     : goodsId,
                goodsPrice  : goodsPrice,
                goodsNumber : goodsNumber,
                _token      : "{{ csrf_token() }}",
                orderId     : '{{$orderId}}'

            },
            dataType: "JSON"
        });
 
        requestAddGoods.done(function( res ) {

            if( res['status'] === false){
                
                errMsg = [];

                $.each( res , function( ind , val ){
                    
                    if( ind != 'status'){
                        
                        errMsg.push(val);
                        
                    }

                });
                
                // 呈現錯誤訊息
                cusShowAlert( "addBox" , errMsg );

            }else{

                //console.log( res );
                orderIdInput = $("#orderid_input").val();
                // 清空本來的列表
                $("#orderGoodsList").empty();
                
                // 初始化暫存table字串
                tmpTable = '';
                tmpAmount = 0;
               
                $.each( res , function( ind , val ){
                    tmpTable += '<tr>';
                    //tmpTable += "<input type='hidden' value='"+val['oid']+"' name='orderid' class='form-control' >";
                    tmpTable += "<input type='hidden' value='"+val['gid']+"' name='id[]' class='form-control' style='width:auto;' >";
                    tmpTable += "<td><img src='{{url('/images')}}/"+val['thumbnail']+"' width='64px;'></td>";
                    tmpTable += "<td>"+val['goods_sn']+"</td>";
                    tmpTable += "<td>"+val['name']+"</td>";
                    tmpTable += "<td><input type='text' value='"+val['num']+"' name='num[]' class='form-control' style='width:auto;'></td>";
                    tmpTable += "<td><input type='text' value='"+val['price']+"' name='price[]' class='form-control' style='width:auto;'></td>";
                    tmpTable += "<td>"+val['subtotal']+"</td>";
                    tmpTable += "<td><p class='btn btn-danger waves-effect delItem' oid='"+val['oid']+"' gid='"+val['gid']+"'>移除</p></td>";
                    tmpTable += "</tr>";

                    tmpAmount += val['subtotal'];
                                        
                });
                // 重新產生編輯商品列表
                $("#orderGoodsList").append("<form method='post' action='{{url('/orderEditGoods')}}'>"+
                                            '{{ csrf_field() }}'+
                                            "<input type='hidden' value='"+orderIdInput+"' name='orderid' class='form-control'>"+
                                            "<table class='table table-hover'>"+
                                            "<thead>"+
                                                "<tr>"+
                                                    "<th>商品縮圖</th>"+
                                                    "<th>商品貨號</th>"+
                                                    "<th>商品名稱</th>"+
                                                    "<th>商品數量</th>"+
                                                    "<th>商品價格</th>"+
                                                    "<th>商品小計</th>"+
                                                    "<th>移除</th>"+
                                                "</tr>"+
                                            "</thead>"+
                                            "<tbody>"+
                                            tmpTable+
                                            "<tr>"+
                                            "<td colspan='5' class='align-right'>合計:</td>"+
                                            "<td>"+tmpAmount+"</td>"+
                                            "<td><input type='submit' id='orderUpdate' class='btn btn-primary waves-effect' value='更新商品'></td>"+
                                            "</tr>"+
                                            "</tbody>"+
                                            "</form>"
                                           );

                
            }
            

        });
 
        requestAddGoods.fail(function( jqXHR, textStatus ) {
            //alert( "Request failed: " + textStatus );
        });

    });



    /*----------------------------------------------------------------
     | 跳出提示
     |----------------------------------------------------------------
     |
     |
     */
    function cusShowAlert( id , data ){
        
        console.log(data);
        liText = '';

        // 組合字串
        $.each( data , function( ind , val ){
            liText += '<li>'+val+'</li>';
        });

        $("#"+id).prepend("<div class='alert alert-warning alert-dismissible' role='alert' id='addAlert'>"+
                              "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+
                              liText+
                           "</div>");
    }



    /*----------------------------------------------------------------
     | 移除訂單細項
     |----------------------------------------------------------------
     | 當操作
     |
     */

    $('body').on('click',".delItem", function() {
        
        Swal.fire({
            title: '移除確認',
            text:  "即將刪除商品,確定要刪除?",
            type:  'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.value) {

                $("#deleteOid").val( $(this).attr('oid') );

                $("#deleteGid").val( $(this).attr('gid') );
                //$("#deleteInput").val( $(this).attr('cid') );
                $("#deleteOrderItem").submit();
            }
        })
    });

     

})
</script>
<!-- /專屬js -->

@endsection
