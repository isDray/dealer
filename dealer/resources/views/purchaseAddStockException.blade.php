+@extends('layouts.admin')

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
                <h2>新增商品</h2>
            </div>

            <div class='body'>
                <div class="row clearfix">
                    <form action="{{url('/ajaxAddStockGoods')}}" method='POST' id='ajaxAddStockGoods'>                
                    {{ csrf_field() }}
                   
                    <div class='col-md-4 col-xs-12 col-sm-12'>
                        <div class="input-group">
                            
                        <span class="input-group-addon">
                        貨號:
                        </span>
                        
                        <div class="form-line">
                            <input type="text" class="form-control align-center myborder"  name='addStockGoodsSn' value="">
                        </div>
                                
                        <span class="input-group-addon">
                            <input type="submit" class="btn btn-primary waves-effect" value='需補貨數'>
                        </span>
                           
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- /進貨單基本資料 -->

        </div>


        <div class='card'>

            <!-- 進貨單基本資料 -->
            <div class="header bg-red">
                <h2>入庫商品明細</h2>
            </div>

            <div class='body'>
                <form action="{{url('/addToStock')}}" method='POST' class='align-center'>

                    {{ csrf_field() }}

                    <input type='hidden' name='purchaseId' value='{{$puchaseId}}'>

                    <table class="table table-bordered align-left">
                        <thead>
                            <tr>
                                <th class='bg-grey'>商品貨號</th>
                                <th class='bg-grey'>商品名稱</th>
                                <th class='bg-grey'>入庫數量</th>
                                <th class='bg-grey'>操作</th>
                            </tr>
                        </thead>
                        <tbody id='stockList'>
                            @foreach( $puchaseGoods as $puchaseGood )
                            <tr id="{{$puchaseGood['goods_sn']}}">
                                <input type='hidden' name='goodsId[]' value="{{$puchaseGood['goods_id']}}">
                                <td>{{ $puchaseGood['goods_sn'] }}</td>
                                <td>{{ $puchaseGood['goods_name'] }}</td>
                                <td>
                                    <input type='text' name='stockNum[]' value='{{ $puchaseGood['num'] }}' class="form-control">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger waves-effect rmGoods" goodsSn="{{$puchaseGood['goods_sn']}}">移除</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        


                    </table>
                    <input type='submit' class="btn btn-primary waves-effect" value="加入庫存" class="form-control">
                </form>
            </div>

        </div>


</div>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
/*----------------------------------------------------------------
 | ajax 新增至庫存表成功
 |----------------------------------------------------------------
 |
 */
function stockSuccess( _msg ){
    swal.fire({
        
        title: "成功",
        html: _msg,
        type:'success'
    });
}



/*----------------------------------------------------------------
 | ajax 新增至庫存表失誤
 |----------------------------------------------------------------
 |
 */
 function stockErr( _msg ){
    swal.fire({
        
        title: "執行失誤",
        html: _msg,
        type:'error'
    });
 }




/*----------------------------------------------------------------
 | 新增表單
 |----------------------------------------------------------------
 |
 */
function stockAddForm( _datas ){
    
    htmlcode = '';
    htmlcode += "<tr id='"+ _datas['goods_sn'] +"'>";
    htmlcode += "<input type='hidden' name='goodsId[]' value='"+ _datas['id'] +"'>";
    htmlcode += "<td>"+ _datas['goods_sn']+"</td>";
    htmlcode += "<td>"+ _datas['name'] +"</td>";
    htmlcode += "<td><input type='text' name='stockNum[]' value='0' class='form-control'></td>";
    htmlcode += "<td><button type='button' class='btn btn-danger waves-effect rmGoods' goodsSn='"+_datas['goods_sn']+"'>移除</button></td>";
    htmlcode += '</tr>';

    if($("#" + _datas['goods_sn']).length == 0) {

        $("#stockList").append( htmlcode );
        stockSuccess("庫存表單新增商品成功");

    }else{
        stockSuccess("商品已經存在庫存表單中 , 無須新增");
    }
    
}

$(function(){





/*----------------------------------------------------------------
 | 移除入庫商品
 |----------------------------------------------------------------
 |
 */
$(document).on('click', '.rmGoods', function(){

    Swal.fire({
        title: "移除入庫商品",
        text: "確定要將貨號"+$(this).attr('goodsSn')+"由入庫商品中移除?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '確定移除',
        cancelButtonText: '取消'
    }).then((result) => {

        if (result.value) {

            /*$(this).parent("tr:first").remove();*/
            $("#"+$(this).attr('goodsSn')).remove();
            //console.log( $(this).parent("td:first") );
        }
    })

});




/*----------------------------------------------------------------
 | 貨號查詢
 |----------------------------------------------------------------
 |
 */
$("#ajaxAddStockGoods").submit(function(e) {

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
                    
            console.log( returnData );
            if( returnData['res'] == true ){

                stockAddForm( returnData['datas'] );
                //stockSuccess( returnData['msg'] );
                        
    
            }else if( returnData['res'] == false ){
                   
                stockErr( returnData['msg'] );
            
            }
                    
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 

            console.log("Status: " + textStatus); 

            console.log("Error: " + errorThrown); 
        }            
    });  
              
    }); 
})
</script>
<!-- /專屬js -->

@endsection
