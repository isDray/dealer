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
            <div class="header">
                <h2>
                &nbsp;
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

                    <a href="{{url('/orderNew')}}">
                        <span type="button" class="btn btn-primary waves-effect">
                        新增訂單
                        </span>
                    </a>
                </ul>
                        </div>
                        <div class="body">



                            <!-- 進階搜尋框 -->
                            <div class="row clearfix mysearchbox">
                                <div class='col-xs-12 col-sm-12 col-md-12 bg-grey'>
                                    <p><b>進階搜尋</b></p>
                                </div>
                               
                                <div class="col-sm-2">
                                    <p>價格</p>
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control myborder" placeholder="" id='min_price'>
                                        </div>
                                        <span class="input-group-addon">~</span>
                                        <div class="form-line">
                                            <input type="text" class="form-control myborder" placeholder="" id='max_price'>
                                        </div>                                        
                                    </div>
                                </div>

                                
                                <div class="col-sm-2">
                                    <p>訂單狀態</p>
                                    <select class="form-control show-tick myborder" id='status'>
                                        <option value='0' >-選擇-</option>
                                        <option value='1' @if($dfStatus == '1') selected @endif>未新增完成</option>
                                        <option value='2' @if($dfStatus == '2') selected @endif >待處理</option>
                                        <option value='3' @if($dfStatus == '3') selected @endif >已出貨</option>
                                        <option value='4' @if($dfStatus == '4') selected @endif >取消</option>
                                    </select>
                                </div> 
                                
                                <!-- 訂單時間選擇 -->
                                <div class="col-xs-3">
                                    
                                    <p>訂單時間</p>
                                    
                                    <div class="input-group">

                                        
                                        <div class="form-line" id='orderSatrtBox'>
                                        
                                            <input type="text" class="form-control myborder align-center" placeholder="開始日期" id='orderSatrt'>
                                        
                                        </div>
                                        

                                        <span class="input-group-addon">~</span>
                                        
                                        
                                        <div class="form-line" id='orderEndBox'>
                                            
                                            <input type="text" class="form-control myborder align-center" placeholder="結束日期" id='orderEnd'>
                                        
                                        </div>
                                        

                                    </div>



                                </div>

                                <!-- /訂單時間選擇 -->
                                

                            </div>
                            <!-- /進階搜尋框 -->

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable orderTable">
                                    <thead>
                                        <tr>
                                            <th>訂單編號</th>
                                            <th>經銷商</th>
                                            <th>總價</th>
                                            <th>狀態</th>
                                            <th>出貨時間</th>
                                            <th>編修日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>訂單編號</th>
                                            <th>經銷商</th>
                                            <th>總價</th>
                                            <th>狀態</th>
                                            <th>出貨時間</th>
                                            <th>編修日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </tfoot>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{url('/orderDeleteDo')}}" method="POST" id='deleteOrderForm'>
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
        order:[[5,'desc']],
        responsive: true,
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
            "url" :"{{url('/orderQuery')}}",
            "type": "POST",
            "data": function ( d ) { 
                d._token = "{{csrf_token()}}";
                d.min_price  = $("#min_price").val();
                d.max_price  = $("#max_price").val();
                d.orderSatrt = $("#orderSatrt").val();
                d.orderEnd   = $("#orderEnd").val();
                d.status     = $("#status").val();
            }
        },
        "columnDefs" : [
            {   "targets" : 0 ,
                "orderable": false,
            },
            {   "targets" : 1 ,
                "orderable": false,
                @role('Dealer')
                "visible": false
                @endrole
            },     
            {   "targets" : 2 ,
                "orderable": false,
            },                     
            {   "targets" : 3 ,
                "orderable": false,
                "render" : function ( url, type, full) {
                    if( full[3] == 1 ){

                        return '<span class="label bg-grey">尚未新增完成</span>';

                    }else if( full[3] == 2 ){
                        
                        return '<span class="label bg-teal">待處理</span>';
                    
                    }else if( full[3] == 3 ){
                        
                        return '<span class="label bg-teal">已出貨</span>';

                    }else if( full[3] == 4 ){

                        return '<span class="label bg-teal">取消</span>';
                    }
                }
        
            },
            {   "targets" : 4 ,
                "orderable": false,
            },     
            {   "targets" : 5 ,
                "orderable": true,
            },                     
            {   "targets" : 6 ,
                "data": "edit",
                "orderable": false,
                "render" : function ( url, type, full) {
                    //console.log( full );
                    return  '<a href="'+"{{url('/orderInfo')}}/"+full[8]+'">'+
                            @role('Admin')
                            @permission('orderList')
                            '<button type="button" class="btn btn-success waves-effect">'+
                            '<i class="material-icons">settings</i>'+
                            '<span>查看</span>'+
                            '</button>'+
                            @endpermission
                            @endrole
                            @role('Dealer')
                            '<button type="button" class="btn btn-success waves-effect">'+
                            '<i class="material-icons">settings</i>'+
                            '<span>查看</span>'+
                            '</button>'+                            
                            @endrole

                            '</a>&nbsp;'+
                            '<button type="button" class="btn btn-danger waves-effect deleteOrder" orderId="'+full[8]+'" orderName="'+full[0]+'">'+
                            '<i class="material-icons">cancel</i>'+
                            '<span>刪除</span>'+
                            '</button>'
                }
        
            },                     

        ]

    });




    /*----------------------------------------------------------------
     | 觸發查詢
     |----------------------------------------------------------------
     |
     */
    $("#min_price").bind("keyup change", function(e) {
        
        orderTable.ajax.reload();
    });
    $("#max_price").bind("keyup change", function(e) {
        
        orderTable.ajax.reload();
    });  
    $("#status").bind("change" , function(e){

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


    $('body').on('click', '.deleteOrder', function() {
        
        Swal.fire({

            title: '刪除確認',
            text: "即將刪除訂單編號:"+$(this).attr('orderName')+"之訂單,確定要刪除?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'

        }).then((result) => {
            if (result.value) {

                $("#deleteInput").val( $(this).attr('orderId') );
                $("#deleteOrderForm").submit();
            }
        })

    });



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
