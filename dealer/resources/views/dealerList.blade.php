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
                    
                    
                    <a href="{{url('/newdealerNew')}}">
                        <span type="button" class="btn btn-primary waves-effect">
                        新增經銷商
                        </span>
                    </a>

                </ul>
                        </div>
                        <div class="body">



                            <!-- 進階搜尋框 -->
                            <div class="row clearfix" style="border:1px solid #d4d4d4; margin-bottom:50px;">
                                <div class='col-xs-12 col-sm-12 col-md-12 bg-grey' style="">
                                    <p><b>進階搜尋</b></p>
                                </div>
                                @role('Admin')
                                <div class="col-sm-1">
                                    <p>經銷商</p>
                                    <select class="form-control show-tick myborder" id='dealer'>
                                    <option value='0' >-選擇-</option>
                                    @foreach( $dealers as $dealer)
                                    <option value="{{$dealer['id']}}">{{$dealer['name']}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-1">
                                    <p>狀態</p>
                                    <select class="form-control show-tick myborder" id='status'>
                                    <option value='0' >-選擇-</option>

                                        <option value="1">啟用</option>
                                        <option value="2">停用</option>

                                    </select>
                                </div>                                
                                @endrole
                                <!--
                                <div class="col-sm-2">
                                    <p>進貨單狀態</p>
                                    <select class="form-control show-tick" id='status'>
                                        <option value='0' >-選擇-</option>
                                        <option value='1' >待處理</option>
                                        <option value='2' >已確認</option>
                                        <option value='3' >已出貨</option>
                                        <option value='4' >取消</option>
                                    </select>
                                </div> 

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
                                            <th>經銷商ID</th>
                                            <th>旅館名稱</th>
                                            <th>聯絡人</th>
                                            <th>email</th>
                                            <th>手機</th>
                                            <th>啟用</th>
                                            <th>開始合作時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
<!--                                     <tfoot>
                                        <tr>
                                            <th>經銷商名稱</th>
                                            <th>聯絡人</th>
                                            <th>email</th>
                                            <th>手機</th>
                                            <th>加入時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{url('/newdealerDeleteDo')}}" method="POST" id='deleteDealerForm'>
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
        lengthMenu: [ 20, 50, 100 ],
        pageLength:20,        
        responsive: true,
        searching: false,
        stateSave: true, 
        dom: '<"top"<"col-md-6"<"inlinebox"li>><"col-md-6"f>>rt<"bottom"p><"clear">',                   
        language:{
            "processing":   "處理中...",
            "loadingRecords": "載入中...",
            "lengthMenu":   "顯示 _MENU_ 項結果",
            "zeroRecords":  "沒有符合的結果",
            "info":         "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "infoEmpty":    "顯示第 0 至 0 項結果，共 0 項",
            // "infoFiltered": "(從 _MAX_ 項結果中過濾)",
            "infoFiltered":"",            
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
            "url" :"{{url('/newdealerQuery')}}",
            "type": "POST",
            "data": function ( d ) { 
                d._token = "{{csrf_token()}}";
                d.min_price  = $("#min_price").val();
                d.max_price  = $("#max_price").val();
                d.orderSatrt = $("#orderSatrt").val();
                d.orderEnd   = $("#orderEnd").val();
                d.status     = $("#status").val();
                d.dealer     = $("#dealer").val();
            }
        },
        "columnDefs" : [
            {   "targets" : 0 ,
            },        
            {   "targets" : 1 ,
                "orderable": false,
                "render" : function ( url, type, full) {
                    return  '<a href="'+"{{url('')}}/"+full[6]+'" target="_blank">'+full[1]+'</a>';
                }
            },
            {   "targets" : 2 ,
                "orderable": false,
                "visible": false                 
            },     
            {   "targets" : 3 ,
                "orderable": false,

            }, 
            {   "targets" : 4,
                "orderable": false,
            },
            {   "targets" : 5,
                "orderable": false,
                "render" : function ( url, type, full) {
                    if( full[7] == 0 ){

                        return '<i class="material-icons col-red">clear</i>';

                    }else if( full[7] == 1 ){
                        
                        return '<i class="material-icons col-light-green">done</i>';
                    
                    }
                }

            },             
            {   "targets" : 6,
                "orderable": false,
                "data":5,
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
                    return  '<a href="'+"{{url('/newdealerEdit')}}/"+full[0]+'">'+
                            @role('Admin')
                            @permission('purchaseEdit')
                            '<button type="button" class="btn btn-success waves-effect">'+
                            '<i class="material-icons">settings</i>'+
                            '<span>管理</span>'+
                            '</button>'+
                            @endpermission
                            @endrole
                            @role('Dealer')
                            '<button type="button" class="btn btn-success waves-effect">'+
                            '<i class="material-icons">settings</i>'+
                            '<span>管理</span>'+
                            '</button>'+                            
                            @endrole
                            
                            '</a>&nbsp;'+
                            '<a href="{{url('')}}/newdealerPassword/'+full[0]+'">'+
                            '<button type="button" class="btn bg-blue waves-effect">'+
                            '<i class="material-icons">edit</i>'+
                            '<span>修改密碼</span>'+
                            '</button>'+
                            '</a>&nbsp;'+                          

                            '<a href="{{url('')}}/newdealerCategoryAndGoods/'+full[0]+'">'+
                            '<button type="button" class="btn bg-amber waves-effect">'+
                            '<i class="material-icons">unarchive</i>'+
                            '<span>分類及商品</span>'+
                            '</button>'+
                            '</a>&nbsp;'+

                            '<a href="{{url('')}}/newdealerQr/'+full[0]+'">'+
                            '<button type="button" class="btn bg-grey waves-effect">'+
                            '<i class="material-icons">file_download</i>'+
                            '<span>QR-code</span>'+
                            '</button>'+
                            '</a>&nbsp;'+


                            '<button type="button" class="btn btn-danger waves-effect deleteDealer" dealerId="'+full[0]+'" orderName="'+full[1]+'">'+
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

        var delId = $(this).attr('dealerId');

        Swal.fire({

            title: '刪除確認',
            text: "即將刪除經銷商:"+$(this).attr('orderName')+",確定要刪除?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'

        }).then(function(result){
            if (result.value) {

                $("#deleteInput").val( delId );
                $("#deleteDealerForm").submit();
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
