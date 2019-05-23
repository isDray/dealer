@extends('layouts.admin')

@section('content')

<!-- Basic Examples -->
<style type="text/css">
a{
     text-decoration: none!important;
}
</style>

<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-red">
                <h2>
                &nbsp;商品列表
                </h2>
                
                <ul class="header-dropdown m-r--5">
                    <!--
                    <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">settings</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">操作</a></li>                                        <!--
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    
                                    </ul>
                                </li>
                    -->
                    
                    <!--
                    <a href="{{url('/dealerNew')}}">
                        <span type="button" class="btn btn-primary waves-effect">
                        新增經銷商
                        </span>
                    </a>
                    -->

                </ul>
                        </div>
                        <div class="body">



                            <!-- 進階搜尋框 -->
                            <div class="row clearfix" style="border:1px solid #d4d4d4; margin-bottom:50px;">
                                <div class='col-xs-12 col-sm-12 col-md-12 bg-grey' style="">
                                    <p><b>進階搜尋</b></p>
                                </div>
                                @role('Admin')
                                <div class="col-sm-2">
                                    <p>經銷商</p>
                                    <select class="form-control show-tick" id='dealer'>
                                    <option value='0' >-選擇-</option>
                                    @foreach( $dealers as $dealer)
                                    <option value="{{$dealer['id']}}">{{$dealer['name']}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                @endrole
                                <div class="col-sm-2">
                                    <p>商品類別</p>
                                    <select class="form-control show-tick myborder" id='category'>
                                        <option value='0' >-選擇-</option>
                                        @foreach( $categorys as $category)
                                        <option value="{{$category['id']}}"> {{$category['level']}}{{$category['name']}} </option>
                                        @endforeach
                                    </select>
                                </div> 
                                <div class="col-sm-1">
                                    <p>商品貨號:</p>
                                    <div class="input-group">
                                        <input type="text" class="form-control myborder" placeholder="" id='myKeyword' value=''>
                                    </div>
                                </div>                                 
                                
                                <div class="col-sm-1">
                                    <p>商品名稱:</p>
                                    <div class="input-group">
                                        <input type="text" class="form-control myborder" placeholder="" id='nameKeyword' value=''>
                                    </div>
                                </div>  


                                <div class="col-sm-1">
                                    <p>商品數量</p>
                                    <select class="form-control show-tick myborder" id='stock'>
                                        <option value='0' >-選擇-</option>
                                        <option value='1' @if($dfStock==1) selected @endif>充足</option>
                                        <option value='2' @if($dfStock==2) selected @endif>低庫存</option>
                                        <option value='3' @if($dfStock==3) selected @endif>無庫存</option>
                                    </select>
                                </div> 

                                <!--
                                <div class="col-sm-2">
                                    <p>價格</p>
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" placeholder="" id='min_price'>
                                        </div>
                                        <span class="input-group-addon">~</span>
                                        <div class="form-line">
                                            <input type="text" class="form-control" placeholder="" id='max_price'>
                                        </div>                                        
                                    </div>
                                </div>
                                -->
                                <!-- 訂單時間選擇 -->

                                <!--
                                <div class="col-xs-3">
                                    
                                    <p>進貨單時間</p>
                                    
                                    <div class="input-group">

                                        
                                        <div class="form-line" id='orderSatrtBox'>
                                        
                                            <input type="text" class="form-control" placeholder="開始日期" id='orderSatrt'>
                                        
                                        </div>
                                        

                                        <span class="input-group-addon">~</span>
                                        
                                        
                                        <div class="form-line" id='orderEndBox'>
                                            
                                            <input type="text" class="form-control" placeholder="結束日期" id='orderEnd'>
                                        
                                        </div>
                                        

                                    </div>



                                </div>-->

                                <!-- /訂單時間選擇 -->
                                

                            </div>
                            <!-- /進階搜尋框 -->

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable orderTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>商品貨號</th>
                                            <th>商品名稱</th>
                                            <th>批發價</th>
                                            <th>售價</th>
                                            <th>庫存</th>   
                                            <th>編修時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
<!--                                     <tfoot>
                                        <tr>
                                            <th>商品貨號</th>
                                            <th>商品名稱</th>
                                            <th>批發價</th>
                                            <th>售價</th>
                                            <th>庫存</th>  
                                            <th>編修時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{url('/dealerDeleteDo')}}" method="POST" id='deleteDealerForm'>
                    {{ csrf_field() }}
                    <input type='hidden' id='deleteInput' name='id'>
                </form>

<!-- script -->
<link href="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-datepicker/js/datepicker-zh-TW.js')}}"></script>
<script type="text/javascript">
$(function(){

    orderTable = $('.orderTable').DataTable({
        order:[[0,'desc']],
        responsive: true,
        searching: false,
        lengthMenu: [ 20, 50, 100 ],
        pageLength: 20,          
        dom: '<"top"<"col-md-6"<"inlinebox"li>><"col-md-6"f>>rt<"bottom"p><"clear">',         
        language:{
            "processing":   "處理中...",
            "loadingRecords": "載入中...",
            "lengthMenu":   "顯示 _MENU_ 項結果",
            "zeroRecords":  "沒有符合的結果",
            "info":         "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "infoEmpty":    "顯示第 0 至 0 項結果，共 0 項",
            "infoFiltered": "(從 _MAX_ 項結果中過濾)",
            "infoPostFix":  "",
            "search":       "搜尋:",
            "paginate": {
                "first":    "第一頁",
                "previous": "上一頁",
                "next":     "下一頁",
                "last":     "最後一頁"
            },
            "aria": {
                "sortAscending":  ": 升冪排列",
                "sortDescending": ": 降冪排列"
            }
        },
        "processing": true,
        "serverSide": true,

        "ajax": {
            "url" :"{{url('/priceQuery')}}",
            "type": "POST",
            "data": function ( d ) { 
                d._token = "{{csrf_token()}}";
                /*d.min_price  = $("#min_price").val();
                d.max_price  = $("#max_price").val();
                d.orderSatrt = $("#orderSatrt").val();
                d.orderEnd   = $("#orderEnd").val();
                d.status     = $("#status").val();
                d.dealer     = $("#dealer").val();*/
                d.myKeyword  = $("#myKeyword").val();
                d.nameKeyword = $("#nameKeyword").val();
                d.stock      = $("#stock").val();
                d.category  = $("#category").val();
            }
        },
        "columnDefs" : [
            {   "targets" : 0 ,
                "data":6
            },        
            {   "targets" : 1 ,
                "orderable": false,
                "data":0
            },
            {   "targets" : 2 ,
                "orderable": false,               
                "data":1
            },     
            {   "targets" : 3 ,
                "data":2

            }, 
            {   "targets" : 4,
                "data":3
            },
            {   "targets" : 5,

                "data":4
            },            
            {   "targets" : 6,
                "data":5
            },            
            /*                            
            {   "targets" : 7 ,
                "orderable": false,
                "render" : function ( url, type, full) {
                    if( full[7] == 1 ){

                        return '<span class="label bg-grey">待處理</span>';

                    }else if( full[7] == 2 ){
                        
                        return '<span class="label bg-teal">已確認</span>';
                    
                    }else if( full[7] == 3 ){
                        
                        return '<span class="label bg-teal">已出貨</span>';

                    }else if( full[7] == 4 ){

                        return '<span class="label bg-teal">取消</span>';
                    }
                    else if( full[7] == 5 ){

                        return '<span class="label bg-teal">已出貨且加入庫存</span>';
                    }
                }
        
            },
     
            {   "targets" : 8 ,
                "orderable": true,
            },  
            {   "targets" : 9 ,
                "orderable": true,
            },*/                          
            {   "targets" : 7 ,
                "data": "edit",
                "orderable": false,
                "render" : function ( url, type, full) {
                    //console.log( full );
                    return  '<a href="'+"{{url('/priceEdit')}}/"+full[6]+'">'+

                            @role('Dealer')

                            '<button type="button" class="btn btn-success waves-effect">'+
                            '<i class="material-icons">settings</i>'+
                            '<span>管理</span>'+
                            '</button>'
                            
                            @endrole
                            
                            /*
                            @role('Dealer')
                            '<button type="button" class="btn btn-success waves-effect">'+
                            '<i class="material-icons">settings</i>'+
                            '<span>管理</span>'+
                            '</button>'+                            
                            @endrole
                            
                            '</a>&nbsp;'+
                            '<button type="button" class="btn btn-danger waves-effect deleteDealer" dealerId="'+full[5]+'" orderName="'+full[0]+'">'+
                            '<i class="material-icons">cancel</i>'+
                            '<span>刪除</span>'+
                            '</button>'
                            */
                }
        
            },                     

        ]

    });




    /*----------------------------------------------------------------
     | 觸發查詢
     |----------------------------------------------------------------
     |
     */
    $("#myKeyword").bind("keyup change", function(e) {
        
        orderTable.ajax.reload();
    });
    $("#nameKeyword").bind("keyup change", function(e) {
        
        orderTable.ajax.reload();

    });
    $("#stock").bind("change", function(e) {
        
        orderTable.ajax.reload();
    });
    $("#category").bind("change" , function(e){

        orderTable.ajax.reload();

    });    
    /*
    $("#max_price").bind("keyup change", function(e) {
        
        orderTable.ajax.reload();
    });
    $("#dealer").bind("change" , function(e){

        orderTable.ajax.reload();

    });    
    $("#status").bind("change" , function(e){

        orderTable.ajax.reload();

    });
    $("#orderSatrt").bind("change" , function(e){

        orderTable.ajax.reload();

    });
    $("#orderEnd").bind("change" , function(e){

        orderTable.ajax.reload();

    });


    $('body').on('click', '.deleteDealer', function() {
        
        Swal.fire({

            title: '刪除確認',
            text: "即將刪除經銷商:"+$(this).attr('orderName')+",確定要刪除?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'

        }).then((result) => {
            if (result.value) {

                $("#deleteInput").val( $(this).attr('dealerId') );
                $("#deleteDealerForm").submit();
            }
        })

    });
    */


   /*----------------------------------------------------------------
    | 時間選擇器
    |----------------------------------------------------------------
    | 
    |
    */

    $('#orderSatrt').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        container : "#orderSatrtBox",
        language: 'zh-TW',
    });
    

    $('#orderEnd').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        container : "#orderEndBox",
        language: 'zh-TW',
    });    

    /*
    $('#bs_datepicker_component_container').datepicker({
        autoclose: true,
        container: '#bs_datepicker_component_container'
    });*/
    //

})


</script>
<!-- /script -->
@endsection
