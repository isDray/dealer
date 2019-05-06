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
                    
                    
<!--                     <a href="{{url('/dealerNew')}}">
                        <span type="button" class="btn btn-primary waves-effect">
                        新增經銷商
                        </span>
                    </a> -->

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
                                    <select class="form-control show-tick" id='dealer' name='dealer'>
                                    <option value='0' >-選擇-</option>
                                    @foreach( $dealers as $dealer)
                                    <option value="{{$dealer['id']}}">{{$dealer['name']}}</option>
                                    @endforeach
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

                                
                                <div class="col-xs-3">
                                    
                                    <p>進貨單時間</p>
                                    
                                    <div class="input-group">

                                        
                                        <div class="form-line" id='orderSatrtBox'>
                                        
                                            <input type="text" class="form-control" placeholder="開始日期" id='orderSatrt' name='start'>
                                        
                                        </div>
                                        

                                        <span class="input-group-addon">~</span>
                                        
                                        
                                        <div class="form-line" id='orderEndBox'>
                                            
                                            <input type="text" class="form-control" placeholder="結束日期" id='orderEnd' name='end'>
                                        
                                        </div>
                                        

                                    </div>



                                </div>

                                <!-- /訂單時間選擇 -->
                                

                            </div>
                            <!-- /進階搜尋框 -->


                        </div>
                    </div>
                </div>


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
