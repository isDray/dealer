@extends('layouts.admin')

@section('content')

<!-- Basic Examples -->
<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet" />
<link href="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />
<script src="{{asset('adminbsb-materialdesign/plugins/dropzone/dropzone.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script>
  var options = {
    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
  };
</script>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


        <div class="card">

        <!-- form 表格 -->
        <div class="header">
            <h2>
            {{$title}}表格
            <small>填寫以下表格以新增一組類別</small>
            </h2>

            <!-- 功能列表(暫時用不到)
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);">Action</a></li>
                        <li><a href="javascript:void(0);">Another action</a></li>
                        <li><a href="javascript:void(0);">Something else here</a></li>
                    </ul>
                </li>
            </ul>
            -->
        </div>

        <div class="body">
            
            <!-- 頁籤框 -->
            <div class="row clearfix">
                
                <!-- 頁籤選項 -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs tab-col-red" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#basic" data-toggle="tab">基本資料</a>
                        </li>
                        <li role="presentation">
                            <a href="#image" data-toggle="tab">圖片管理</a>
                        </li>

                     </ul>
                </div>
                <!-- /頁籤選項 -->

                <!-- 頁籤內容 -->
                <form action="{{url('/goodsNewDo')}}" method="POST" enctype="multipart/form-data">
                <div class="tab-content">
                    <!-- 基本資料-->
                    <div role="tabpanel" class="tab-pane fade in active" id="basic">
                        
                        <div class='col-sm-12'>
                            
                            
                            {{ csrf_field() }}
                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <b>商品名稱</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="name" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="col-sm-3">
                                        <b>貨號</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="goods_sn" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                
                                    <div class="col-sm-6">
                                        <p><b>商品類別</b></p>
                                        <select class="form-control show-tick" name='category'>
                                            @foreach( $categorys as $category)
                                            <option value="{{$category['id']}}"> {{$category['level']}}{{$category['name']}} </option>
                                            @endforeach
                                        </select>
                                    </div> 

                                </div>

                                <!-- 擴展類別 -->
                                <div class="row clearfix">
                                    
                                    <div class="col-md-6">
                                        <p><b>擴展類別</b></p>
                                        
                                        <select class="form-control show-tick" multiple name='multiplCategory[]'>
                                            @foreach( $categorys as $category)
                                            <option value="{{$category['id']}}" >{{$category['name']}} </option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                                <!-- /擴展類別 -->

                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <b>售價</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="price" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="col-sm-3">
                                        <b>批發價</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="wprice" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                                                                                           
                                    <!--
                                    <div class="col-sm-2">
                                        <b>庫存</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="storage" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                    -->
                                </div>                
                                
                                <div class='row clearfix'>
                                    <div class="col-sm-3">
                                        <b>商品主圖</b>
                                        <div class="form-group">
                                            <div class="">
                                                <input type="file" class="form-control imageupload" name="mainpic" id="mainpic" placeholder="" />
                                            </div>
                                            <div id="mainDisplay">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <b>商品縮圖</b>
                                        <div class="form-group">
                                            <div class="">
                                                <input type="file" class="form-control imageupload" name="thumbnail" id="thumbnail" placeholder="" />
                                            </div>
                                            <div id="thumbDisplay">

                                            </div>
                                        </div>
                                    </div>                                     
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                       <p><b>上架</b></p>
                                        <div class="form-group">
                                            <div class="demo-checkbox">
                                                <input type="checkbox" id="status" checked name='status'/>
                                                <label for="status">勾選上架</label>
                                            </div>              
                                        </div>                  
                                    </div> 
                                </div> 


                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                       <p><b>商品內容</b></p>
                                        <div class="form-group">
                                            <div class="demo-checkbox">
                                                                   
                                                <textarea id="ckeditor" name='desc'></textarea>

                                            </div>              
                                        </div>                  
                                    </div> 
                                </div>


                                           
                                
                                
                            

                        </div>

                    </div>
                    <!-- / 基本資料 -->

                    <!-- 圖片管理 -->
                    <div role="tabpanel" class="tab-pane fade" id="image">
                        <div class='col-sm-12'>
                            <div class="row clearfix">
                                    <div class="col-sm-10" >
                                       <p><b>上傳圖片</b></p>                                        



                                    <ul id='fileArea' class="connectedSortable">
                                        
                                        <li class="ui-state-default">
                                        
                                            <div class="form-group">
                                                <div class="demo-checkbox">
                                                    <input type="file" class="otherfile" name='file[]'/>

                                                </div>      
                                            </div>
                                        
                                        </li>
                                    
                                    </ul>


                                    </div> 
                                    <div class="col-sm-2">
                                        <button  id='addFile' type="button" class="btn btn-primary waves-effect">增加圖片數量</button>
                                    </div>
                            </div>                            
                        </div>
                    </div>
                    <!-- /圖片管理 -->
                               
                </div>

                               
                <!-- /頁籤內容 -->

            </div>

                <button class="btn btn-primary waves-effect" type="submit">新增</button>

            </form>
            <!-- /頁籤框 -->

        </div>

        </div>

                                          

        <!-- 權限區塊 -->

        <!-- /權限區塊 -->

    </div>
</div>

<!-- 上傳圖片用script -->
<script type="text/javascript">
$(function(){

    $("#addFile").click(function(){
        
        $("#fileArea").append("<li class='ui-state-default'><div class='form-group'>"+

                                    "<div class='demo-checkbox'>"+

                                        "<input type='file' class='otherfile' name='file[]'/>"+
                                        
                                    "</div>"+
                               "</div></li>"

        );

        $( "#fileArea" ).sortable({
            
            connectWith: ".connectedSortable"
        
        }).disableSelection();

    });

    $(".imageupload").change(function(e) {

    for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {

        var file = e.originalEvent.srcElement.files[i];

        var img = document.createElement("img");
        var reader = new FileReader();
        reader.onloadend = function() {
             img.src = reader.result;
        }
        reader.readAsDataURL(file);
        if( $(this).attr('id') == 'mainpic'){
            
            $("#mainDisplay").empty();
            $("#mainDisplay").append(img);

        }else if( $(this).attr('id') == 'thumbnail'){

            $("#thumbDisplay").empty();
            $("#thumbDisplay").append(img);
        }
    }
    
    });

    //otherfile
    $('body').on('change', '.otherfile', function(e) {

    for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {

        var file = e.originalEvent.srcElement.files[i];

        var img = document.createElement("img");
        var reader = new FileReader();
        reader.onloadend = function() {
             img.src = reader.result;
        }
        reader.readAsDataURL(file);
        $(this).after(img);
        /*
        if( $(this).attr('id') == 'mainpic'){
            
            $("#mainDisplay").empty();
            $("#mainDisplay").append(img);
        }else if( $(this).attr('id') == 'thumbnail'){
            $("#thumbDisplay").empty();
            $("#thumbDisplay").append(img);
        }
        */
    }
    
    });


})
</script>

<script>
$(function(){
    
    $( "#fileArea" ).sortable();
    $( "#fileArea" ).disableSelection();

    CKEDITOR.replace('ckeditor',options);
    CKEDITOR.config.height = 300;
});
</script>
<!-- /上傳圖片用script -->

<style type="text/css">
#mainDisplay > img , #thumbDisplay > img{

    object-fit:contain;
    max-width: 100%;
}
.demo-checkbox > img{
    object-fit:contain;
    height: 120px;
    max-width: 100px;
}
.ui-state-default{
    margin-top: 2px;
    border:1px solid #ddd;
    background-color: #f6f6f6;
}

</style>

@endsection
