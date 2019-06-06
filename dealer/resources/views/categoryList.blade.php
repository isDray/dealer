@extends('layouts.admin')

@section('content')

<!-- Basic Examples -->
<style type="text/css">
a{
     text-decoration: none!important;
}
</style>
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

                    <a href="{{url('/categoryNew')}}">
                        <span type="button" class="btn btn-primary waves-effect">
                        新增類別
                        </span>
                    </a>
                </ul>
                        </div>
                        <div class="body">

                            <div class="row clearfix mysearchbox">
                                <div class='col-xs-12 col-sm-12 col-md-12 bg-grey'>
                                    <p><b>進階搜尋</b></p>
                                </div>

                                <div class="col-sm-2">
                                    <p>分類名稱:</p>
                                    <div class="input-group">
                                        <input type="text" class="form-control myborder" placeholder="" id='myKeyword' value=''>
                                    </div>
                                </div>

                            </div>                             

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable roleTable">
                                    <thead>
                                        <tr>
                                            <th>類別名稱</th>
                                            <th>排序</th>
                                            <th>是否啟用</th>
                                            <th>編修時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
<!--                                     <tfoot>
                                        <tr>
                                            <th  style='display:none;'>id</th>
                                            <th>類別名稱</th>
                                            <th>商品描述</th>
                                            <th>是否啟用</th>
                                            <th>編修時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                        
<!--                                         @foreach( $categorys as $ckey=>$category ) 
                                        <tr><td  style='display:none;'>{{$ckey}}</td>
                                            <td>{!!$category['level']!!}{!!$category['levelIcon']!!}{{$category['name']}}</td>
                                            <th>{{$category['sort']}}</th>
                                            <td style='display:none;'>{{$category['desc']}}</td>
                                            <td> 
                                                @if($category['status'] == 1)
                                                <i class="material-icons col-light-green">done</i>
                                                @else
                                                <i class="material-icons col-red">clear</i>
                                                @endif
                                            </td>
                                            <td>{{$category['updated_at']}}</td>
                                            <td>
                                                <a href="{{url('/categoryEdit')}}/{{$category['id']}}">
                                                <button type="button" class="btn btn-success waves-effect">
                                                <i class="material-icons">settings</i>
                                                <span>編輯</span>
                                                </button>
                                                </a>

                                                
                                                <button type="button" class="btn btn-danger waves-effect categoryDelete" cid="{{$category['id']}}" cname="{{$category['name']}}">
                                                <i class="material-icons">cancel</i>
                                                <span>刪除</span>
                                                </button>
                                            </td>

                                        </tr>

                                        @endforeach -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{url('/categoryDeleteDo')}}" method="POST" id='deleteForm'>
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
        order:[[1,'asc']],
        responsive: true,
        stateSave: true, 
        searching:false,        
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
            "url" :"{{url('/categoryQuery')}}",
            "type": "POST",
            "data": function ( d ) { 
                d._token = "{{csrf_token()}}";
                d.myKeyword  = $("#myKeyword").val();                
            }
        },    
        "columnDefs" : [
            {   
                "targets" : 0 ,
                "data": 1,
                "orderable": false,
            }, 
            {   
                "targets" : 1 ,
                "data": 3,
            }, 
            {   
                "targets" : 2 ,
                "data": 2,
                "render" : function ( url, type, full) {
                    if( full[2] == 1 ){

                        return '<i class="material-icons col-light-green">done</i>';

                    }else if( full[2] == 0 ){
                        
                        return '<i class="material-icons col-red">clear</i>';
                    }
                }                   
            }, 
            {   
                "targets" : 3 ,
                "data": 4,
             
            }, 
            {   
                "targets" : 4,
                "data": 4,
                "orderable": false,
                "render" : function ( url, type, full) {
                    return  '<a href="'+"{{url('/categoryEdit')}}/"+full[0]+'">'+
                            '<button type="button" class="btn btn-success waves-effect">'+
                            '<i class="material-icons">settings</i>'+
                            '<span>編輯  </span>'+
                            '</button>'+
                            '</a>&nbsp;'+
                            '<button type="button" class="btn btn-danger waves-effect categoryDelete" cid="'+full[0]+'" cname="'+full[1]+'">'+
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
    $("#myKeyword").bind("keyup change", function(e) {
        
        mytable.ajax.reload();
    }); 
    
    $('body').on('click', '.categoryDelete', function() {

        var delId = $(this).attr('cid');

        Swal.fire({
            title: '刪除確認',
            text: "即將刪除商品分類:"+$(this).attr('cname')+",確定要刪除?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'
        }).then(function(result){
            if (result.value) {

                $("#deleteInput").val(delId);
                $("#deleteForm").submit();
            }
        })

    });

})


</script>
<!-- /script -->
@endsection
