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
                    @permission('goodsNew')
                    <a href="{{url('/goodsNew')}}">
                        <span type="button" class="btn btn-primary waves-effect">
                        新增商品
                        </span>
                    </a>
                    @endpermission
                </ul>
            </div>
            
                        <div class="body">
                            
                            <div class="row clearfix mysearchbox">
                                <div class='col-xs-12 col-sm-12 col-md-12 bg-grey'>
                                    <p><b>進階搜尋</b></p>
                                </div>

                                <div class="col-sm-2">
                                    <p>商品類別</p>
                                    <select class="form-control show-tick myborder" id='category'>
                                        <option value='0' >-選擇-</option>
                                        @foreach( $categorys as $category)
                                        <option value="{{$category['id']}}"> {{$category['level']}}{{$category['name']}} </option>
                                        @endforeach
                                    </select>
                                </div>                                



<!--                                 <div class="col-sm-2">
                                    <p>批發價</p>
                                    <div class="input-group">
                                        <div class="form-line myborder">
                                            <input type="text" class="form-control" placeholder="" id='min_price'>
                                        </div>
                                        <span class="input-group-addon" style="padding-right:10px;">~</span>
                                        <div class="form-line myborder">
                                            <input type="text" class="form-control" placeholder="" id='max_price'>
                                        </div>                                        
                                    </div>
                                </div>
 -->
                                

                                
                                <div class="col-sm-1">
                                    <p>上架</p>
                                    <select class="form-control show-tick myborder" id='status'>
                                        <option value='0' >-選擇-</option>
                                        <option value="1" @if($dfStatus == 1) selected @endif>上架</option>
                                        <option value="2" @if($dfStatus == 2) selected @endif>下架</option>
                                    </select>
                                </div>        

                                <div class="col-sm-1">
                                    <p>推薦</p>
                                    <select class="form-control show-tick myborder" id='recommend'>
                                        <option value='0' >-選擇-</option>
                                        <option value="1" >推薦</option>
                                        <option value="2" >無推薦</option>
                                    </select>
                                </div> 

                                <div class="col-sm-2">
                                    <p>商品貨號:</p>
                                    <div class="input-group">
                                        <input type="text" class="form-control myborder" placeholder="" id='snKeyword' value=''>
                                    </div>
                                </div>  
                                
                                <div class="col-sm-2">
                                    <p>商品名稱:</p>
                                    <div class="input-group">
                                        <input type="text" class="form-control myborder" placeholder="" id='myKeyword' value=''>
                                    </div>
                                </div>
                              
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable roleTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>縮圖</th>
                                            <th>商品名稱</th>
                                            <th>商品貨號</th>
                                            <th>上架</th>
                                            <!-- <th class='never'>推薦</th> -->
                                            <th>售價</th>
                                            <th>批發價</th>
                                            <th>庫存</th>
                                            <th>編修時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
<!--                                     <tfoot>
                                        <tr>
                                            
                                            <th>縮圖</th>
                                            <th>商品名稱</th>
                                            <th>商品貨號</th>
                                            <th>是否啟用</th>
                                            <th>售價</th>
                                            <th>批發價</th>
                                            <th>編修時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                        

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id='stockContent' value=""></div>
                <form action="{{url('/goodsDeleteDo')}}" method="POST" id='deleteForm'>
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

<script type="text/javascript">
$(function(){

    mytable = $('.roleTable').DataTable({
        order:[[0,'desc']],
        responsive: true,
        stateSave: true,
        searching:false,
        
        lengthMenu: [ 20, 50, 100 ],
        pageLength:20,

        dom: '<"top"<"col-md-6"<"inlinebox"li>><"col-md-6"f>>rt<"bottom"p><"clear">',
        language:{
            "processing":   "處理中...",
            "loadingRecords": "載入中...",
            "lengthMenu":   "顯示 _MENU_ 項結果",
            "zeroRecords":  "沒有符合的結果",
            "info":         "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "infoEmpty":    "顯示第 0 至 0 項結果，共 0 項",
            /*"infoFiltered": "(從 _MAX_ 項結果中過濾)",*/
            "infoFiltered":"",
            "infoPostFix":  "",
            "search":       "關鍵字搜尋:",
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
            "url" :"{{url('/goodsQuery')}}",
            "type": "POST",
            "data": function ( d ) { 
                d._token = "{{csrf_token()}}";
                d.min_price = $("#min_price").val();
                d.max_price = $("#max_price").val();
                d.category  = $("#category").val();
                d.status    = $("#status").val();
                d.myKeyword  = $("#myKeyword").val(); 
                d.snKeyword  = $("#snKeyword").val();  
                d.recommend  = $("#recommend").val();
            }
        },

        "columnDefs" : [
            {   "targets" : 0 ,
                "data": 8,
            },        
            {   "targets" : 1 ,
                "orderable": false,
                "render" : function ( url, type, full) {
                    return '<img height="80px" width="80px" src="'+"{{url('/images')}}/"+full[0]+'"/>';
                }
            },   
            {   "targets" : 2 ,
                "orderable": false,
                "data": 1,
            },      
            {   "targets" : 3 ,
                "orderable": false,
                "data": 2,
            },                           
            {   "targets" : 4 ,
              
                "render" : function ( url, type, full) {
                    if( full[3] == 1 ){

                        return '<i class="material-icons col-light-green">done</i>';

                    }else if( full[3] == 0 ){
                        
                        return '<i class="material-icons col-red">clear</i>';
                    }
                }
        
            },
            // {
            //     "targets":5,


            //     "render" : function ( url, type, full) {
            //         if( full[10] == 1 ){

            //             return '<i class="material-icons col-light-green">done</i>';

            //         }else if( full[10] == 0 ){
                        
            //             return '<i class="material-icons col-red">clear</i>';
            //         }
            //     },
            //     // "data":10,
                              
            // },            
            {
                "targets":5,
                "data": 4,
            },
            {
                "targets":6,
                "data": 5,
            },            
            {
                "targets":7,
                //"data": 6,
                "render" : function ( url, type, full) {
                    return "<a target='_blank' href='"+"{{url('/goodsStockDetail')}}/"+full[8]+"'>"+full[6]+"</a>";
                    //return  "<div class='stockbox' gid='"+full[8]+"'>"+full[6]+"<div><table><tr><td>經銷商</td><td>庫存</td></tr>"+tmptable+"</table></div></div>";
                }
            },   
            {
                "targets":8,
                "data": 7,
            },                     
            {   "targets" : 9 ,
                "data": "edit",
                "orderable": false,
                "render" : function ( url, type, full) {
                    return  '<a href="'+"{{url('/goodsEdit')}}/"+full[8]+'">'+
                            '<button type="button" class="btn btn-success waves-effect">'+
                            '<i class="material-icons">settings</i>'+
                            '<span>編輯  </span>'+
                            '</button>'+
                            '</a>&nbsp;'+
                            '<button type="button" class="btn btn-danger waves-effect goodsDelete" cid="'+full[8]+'" cname="'+full[1]+'">'+
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
        
        mytable.ajax.reload();
    });
    $("#max_price").bind("keyup change", function(e) {
        
        mytable.ajax.reload();
    });  
    $("#category").bind("change" , function(e){

        mytable.ajax.reload();

    });
    $("#status").bind("change" , function(e){

        mytable.ajax.reload();

    });
    $("#recommend").bind("change" , function(e){

        mytable.ajax.reload();

    });    
    $("#myKeyword").bind("keyup change", function(e) {
        
        mytable.ajax.reload();
    });    
    $("#snKeyword").bind("keyup change", function(e) {
        
        mytable.ajax.reload();
    });
    /*----------------------------------------------------------------
     | 觸發刪除
     |----------------------------------------------------------------
     |
     */
    $('body').on('click', '.goodsDelete', function() {
        
        Swal.fire({
            title: '刪除確認',
            text: "即將刪除商品:"+$(this).attr('cname')+",確定要刪除?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.value) {

                $("#deleteInput").val( $(this).attr('cid') );
                $("#deleteForm").submit();
            }
        })

    });

    $('body').on('mouseover', '.stockbox', function() {

        //$(this).children().html( $("#stockContent").val() );
        $(this).children().show();
    });
    
    $('body').on('mouseleave', '.stockbox', function() {
        
        $(this).children().hide();
    });    
    function getajaxstock( _gid){
            
            
            var tooltipajax = $.ajax({
                url: "{{url('/goodsAjaxStock')}}",
                method: "POST",
                data: { gid : _gid ,
                     _token : "{{csrf_token()}}" },
                dataType: "json",

            });
 
            tooltipajax.done(function( returnDatas ) {
                
                if( returnDatas[0] == true ){
                if( returnDatas.length > 0){
                    var numHtml = '';
                    numHtml += "<table><tr><td>經銷商</td><td>庫存</td></tr>";
                    $.each( returnDatas[1], function( key, value ) {
                        numHtml += "<tr>";
                        numHtml += "<td>"+value['name']+"</td>";
                        numHtml += "<td>"+value['num']+"</td>";
                        numHtml += "</tr>";
                    });

                    numHtml += "</table>";

                    $("#stockContent").val( numHtml );
                } 
                }

            });
 
            tooltipajax.fail(function( jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );
            });     
            

    }
})

</script>
<!-- /script -->

<style type="text/css">
td >img {
    obj-fit:contain;
    width: 80px;
}
</style>
@endsection
