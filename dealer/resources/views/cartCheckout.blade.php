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
                                    <img class="media-object" src="{{url('images')}}/{{$cart['thumbnail']}}" alt="">
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
                        <td colspan='4'></td>
                        <td >總價:{{$checkAmount}}</td>
                    </tr>
                </tbody>
                <tbody style="border-top:1px solid #ddd">
                    <tr>
                        <td colspan='5' class='text-center' >房號<input type='text' class='form-control' name="room" style="margin:0 auto;width:30%" ></td>
                    </tr>        
                    <tr>
                        <td colspan='5' class='text-center'>

                            <input type="submit" class="btn btn-primary" value='送出訂單'>

                        </td>
                    </tr>             
                </body>
            </table>
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
