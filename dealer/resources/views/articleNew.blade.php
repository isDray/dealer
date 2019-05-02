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
    //filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    //filebrowserUploadUrl: '"laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
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
        </div>

        <div class="body">
        
            <form action="{{url('/articleNewDo')}}" method="POST" enctype="multipart/form-data">

                {{ csrf_field() }} 
                <div class='col-sm-12'>
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>文章名稱:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="name" placeholder="" />
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>是否啟用:</b>
                            <div class="form-group">
                            <div>
                                <input name="status" type="checkbox" id="status" class="with-gap radio-col-teal adminChildRole" value="" >
                                <label for="status">勾選啟用</label>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>排序:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="number" class="form-control myborder" min='0' name="sort" placeholder="" value='5' />
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                       <div class="col-md-12 col-sm-12 col-xs-12">
                            <p><b>文章內容</b></p>
                            <div class="form-group">
                            <div class="demo-checkbox">
                                                                   
                                <textarea id="ckeditor" name='content'></textarea>

                            </div>              
                            </div>                  
                        </div> 
                    </div>
                </div>

                <button class="btn btn-primary waves-effect" type="submit">新增</button>

            </form>


        </div>
        </div>
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
