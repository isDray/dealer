@extends('layouts.cart')

@section('content')

<!-- Basic Examples -->
<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />
<link href="{{asset('/adminbsb-materialdesign/css/cartGoods.css')}}" rel="stylesheet">
<script src="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/ckeditor/ckeditor.js')}}"></script>

<div id='goodsBox' class="container-fluid _np">
    
    <div id='goodsInfo' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 '>
        
        <div id='goodsPic' class='col-md-4 col-sm-12 col-xs-12 _np ' >

            <img src="{{url('images')}}/{{$goods['main_pic']}}">  

        </div>

        <div id='goods' class='col-md-7 col-md-offset-1 col-sm-12 col-xs-12 _np' >

            <h3>{{$goods['name']}}</h3>

            <h4>售價: {{$goods['dealerPrice']}}</h4>
            <h4>編號: {{$goods['goods_sn']}}</h4>
        
            <form id="addCartForm" class="form-inline" action="{{url('/'.$dealerDetect.'/addToCart/')}}" method="post">

              <div class="form-group">

                {{ csrf_field() }} 
                <input type='hidden' name='goodsId' value="{{$goods['id']}}">

                <select class='form-control' id='goodsNum' name='goodsNum'>
                <option value='0'> 請選擇購買數量 </option>
                @for($i = 1; $i <= $goods['stock']; $i++)
                <option value="{{$i}}">{{$i}}</option>
                @endfor
                </select>
              </div>

              <button type="submit" class="btn btn-primary">加入購物車</button>

            </form>

        </div>        

    </div>

    <div class='goodsLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='商品描述'></div>
    <div id='goodsDesc' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 '>
        {!!$goods['desc']!!}
    </div>

</div>

<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){

$("#addCartForm").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');

    $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: form.serialize(), // serializes the form's elements.
            success: function(data){
                
                if( data['res'] == true){
                    refreshItem( data['cartDatas'] );
                    cusMsg( data['res'] , data['msg'] );

                }else{

                    cusMsg( data['res'] , data['msg'] );
                }
            }
    });


});


})

/*----------------------------------------------------------------
 | 加入購物車回饋訊息
 |----------------------------------------------------------------
 |
 */
function cusMsg( _res , _msg ){

    if( _res == true){

        var msgTile = '執行成功';
        var msgType ='success';

    }else{

        var msgTile = '執行失敗';
        var msgType ='error';
    }

    swal.fire({
        
        title: msgTile,
        html: _msg,
        type:msgType
    
    });
}




/*----------------------------------------------------------------
 | 刷新購物車清單
 |----------------------------------------------------------------
 |
 */
function refreshItem( _datas ){

    // 先將購物車清單清空
    $("#cartItem").empty();

    $.each( _datas , function( index , element ) {
               
        htmlCode = '';

        htmlCode += "<div class='media' style='border-bottom:1px solid #333;padding-bottom:4px;'><div class='media-left'>";
        htmlCode += "<img src='{{url('images')}}"+"/"+element['thumbnail']+"' style='width:60px;height:60px;'>";
        htmlCode += "</div><div class='media-body' >";
        htmlCode += "<div class='col-md-12 col-sm-12 col-xs-12'>"
        htmlCode += "<h5>"+element['name']+"</h5>";
        htmlCode += "<h6>"+element['subTotal']+"</h6>";
        htmlCode += "</div>";
        htmlCode += "<div class='col-md-12 col-sm-12 col-xs-12'>"
        htmlCode += "<button class='btn btn-danger cartDelete' style='width:100%' goodsID='"+element['id']+"'>移除</button>";
        htmlCode += "</div>"
        htmlCode += "</div></div>";

    });

    $("#cartItem").append(htmlCode);
}

</script>
<!-- /專屬js -->

@endsection
