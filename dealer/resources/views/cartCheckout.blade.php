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
    
    <h4 style='color:red;font-weight:900;text-align:center;padding-left:15px;padding-right:15px;'>訂單送出後，請可撥打分機，告知櫃檯人員已訂購商品，以便盡快處理您的訂單，謝謝!</h4>

    <div class='checkoutLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='購買清單'></div>

    <div id='checkoutList' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12'> 
        
        <form action="{{url('')}}/{{$dealerDetect}}/newOrder" method='POST'>
        {{ csrf_field() }}
        <div class="table">
            <table class="table">

                <thead>
                    <tr>
                        <th width='40%'>商品名稱</th>
                        <th>售價</th>
                        <th>數量</th>
                        <th>小計</th>
                        <th>操作</th>
                    </tr>
                </thead>
                
                <tbody id='checkoutItem'>
                @php
                    $checkAmount = 0;
                @endphp
                @foreach( $carts as $cart)
                    <tr>
                        <td>
                            <div class="media">

                                <div class="media-left checkoutImgBox">
                                    <img class="media-object _w" src="{{url('images')}}/{{$cart['thumbnail']}}" alt="" >
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
                                @for( $i = 1 ; $i<= $cart['stock'] ; $i++)
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
                            <span class='btn btn-danger deleteCheckoutItem ' goodsId="{{$cart['id']}}" >刪除</span>
                        </td>
                    </tr>
                    @php
                        $checkAmount += $cart['subTotal'];
                    @endphp
                @endforeach
                    <tr>
                        <td colspan='3'></td>
                        <td colspan='2'>總價:{{$checkAmount}}</td>
                    </tr>
                </tbody>
                       


                          
            

            </table>
            <div class="col-md-4 col-md-offset-4 col-sm-12 col-sm-12 text-center">
                 房號:<input type='text' class='form-control' name="room" style="margin-top:10px;" >
                付款方式: <select class='form-control' style="margin-top:10px;" name='payway'>
                            <option value="現金付款">現金付款</option>
                            <option value="櫃檯刷卡">櫃檯刷卡</option>
                          </select>    
                <textarea class="form-control" rows="3" placeholder="備註欄位" style="margin-top:10px;margin-bottom:10px;" name="note"></textarea>         
                
                <input type="submit" class="btn btn-primary" value="購買/訂單送出">                    
            </div>
            
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
    
        // ajax 修改數量
        
        var editCart = $.ajax({
            url: "{{url('')}}/{{$dealerDetect}}/addToCart",
            method: "POST",
            data: {
                goodsId:  $(this).attr('goodsId'),
                goodsNum: $(this).val(),
                complete: true,
                _token: "{{ csrf_token() }}",
            },
            dataType: "JSON"
        });
 
        editCart.done(function( data ) {

            if( data['res'] == true){
                
                refreshItem( data['cartDatas'] );
                refreshCheckout( data['cartDatas'] );
                cusMsg( data['res'] , data['msg'] );
            }
        });
 
        editCart.fail(function( jqXHR, textStatus ) {
            console.log( "Request failed: " + textStatus );
        });

        // ajax 修改數量結束
    }

});

});

/*----------------------------------------------------------------
 | 重新產生checkout 列表
 |----------------------------------------------------------------
 |  
 |
 */
function refreshCheckout( _datas ){
    
    // 清空原始列表
    $("#checkoutItem").empty();
    
    // 初始化html
    var checkoutHtml = "";
    var totalAmount  = 0;
    $.each( _datas , function( index , element ) {
        
        checkoutHtml += "<tr>";
        checkoutHtml += "<td><div class='media'><div class='media-left checkoutImgBox'>";
        checkoutHtml += "<img class='media-objec' src='"+"{{url('images')}}/"+ element['thumbnail'] +"' alt=''>";
        checkoutHtml += "</div><div class='media-body'>"+element['name']+"</div></div></td>";
        checkoutHtml += "<td>"+element['goodsPrice']+"</td>";
        checkoutHtml += "<td><select class='form-control checkoutNum' goodsId='"+element['id']+"' >";
        for (i = 1; i <= element['stock']; i++) { 
            
            checkoutHtml += "<option value='"+i+"'";
            
            if(i == element['num']){

                checkoutHtml += " SELECTED >";

            }else{
                checkoutHtml += " > " ;
            }

            checkoutHtml += i+"</option>";
        }
        checkoutHtml += "</select></td>";

        checkoutHtml += "<td>"+element['subTotal']+"</td>";
        checkoutHtml += "<td><span class='btn btn-danger deleteCheckoutItem ' goodsId='"+element['id']+"' >刪除</span></td>";
        checkoutHtml += "</tr>";

        totalAmount += element['subTotal'];

    });
    
    checkoutHtml += "<tr><td colspan='4'></td><td >總價:"+ totalAmount +"</td></tr>";                   
                    
                        


                 
    // 重新產生checkout列表
    $("#checkoutItem").append( checkoutHtml );            
                         
}




/*----------------------------------------------------------------
 | ajax 刪除checkout 項目
 |----------------------------------------------------------------
 |
 */

$(document).on( 'click', '.deleteCheckoutItem', function(e){
    
    if (e.handled !== true) {
        e.handled = true;
    
        // ajax 刪除checkout項目
        
        var editCart = $.ajax({
            url: "{{url('')}}/{{$dealerDetect}}/deleteItem",
            method: "POST",
            data: {
                goodsId:  $(this).attr('goodsId'),
                _token: "{{ csrf_token() }}",
            },
            dataType: "JSON"
        });
 
        editCart.done(function( data ) {

            if( data['res'] == true){
                
                refreshItem( data['cartDatas'] );
                refreshCheckout( data['cartDatas'] );
                cusMsg( data['res'] , data['msg'] );
            }
        });
 
        editCart.fail(function( jqXHR, textStatus ) {
            console.log( "Request failed: " + textStatus );
        });

        // ajax 刪除checkout項目結束
    }

}); 

</script>
<!-- /專屬js -->

@endsection
