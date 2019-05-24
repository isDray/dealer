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

                    <a href="{{url('/accountNew')}}">
                        <span type="button" class="btn btn-primary waves-effect">
                        新增帳號
                        </span>
                    </a>
                </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable roleTable">
                                    <thead>
                                        <tr>
                                            <th>帳號名稱</th>
                                            <th>信箱</th>
                                            <th>管理平台</th>
                                            <th>編修時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>帳號名稱</th>
                                            <th>信箱</th>
                                            <th>管理平台</th>
                                            <th>編修時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                        @foreach( $users as $user) 
                                        <tr>
                                            <td>{{$user['name']}}</td>
                                            <td>{{$user['email']}}</td>
                                            <td>
                                                @if( $user['rootRole'] == 'Admin')
                                                    <span class="label bg-blue">總站</span>
                                                @endif

                                                @if( $user['rootRole'] == 'Dealer')
                                                    <span class="label bg-green">經銷</span>
                                                @endif                                                
                                            </td>
                                            <td>{{$user['updated_at']}}</td>
                                            <td>
                                                <a href="{{url('/accountEdit')}}/{{$user['id']}}">
                                                <button type="button" class="btn btn-success waves-effect">
                                                <i class="material-icons">settings</i>
                                                <span>編輯</span>
                                                </button>
                                                </a>

                                                
                                                <button type="button" class="btn btn-danger waves-effect accountDelete" rid="{{$user['id']}}" rname="{{$user['name']}}">
                                                <i class="material-icons">cancel</i>
                                                <span>刪除</span>
                                                </button>
                                            </td>

                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{url('/accountDeleteDo')}}" method="POST" id='deleteForm'>
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

    $('.roleTable').DataTable({
        responsive: true,
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
        }
    });

    
    $('body').on('click', '.accountDelete', function() {
        
        Swal.fire({
            title: '刪除確認',
            text: "即將刪除帳號:"+$(this).attr('rname')+",確定要刪除?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.value) {

                $("#deleteInput").val($(this).attr('rid'));
                $("#deleteForm").submit();
            }
        })

    });

})


</script>
<!-- /script -->
@endsection
