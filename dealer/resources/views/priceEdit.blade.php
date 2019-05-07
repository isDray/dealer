@extends('layouts.admin')

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


        <div class="card">

        <!-- form 表格 -->
        <div class="header">
            <h2>
            {{$title}}表格
            <small>填寫以下表格以編輯商品售價</small>
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
                
                    <!-- 基本資料-->


                    <div role="tabpanel" class="tab-pane fade in active" id="basic">
                        
                        <form action="{{url('/priceEditDo')}}" method="POST" enctype="multipart/form-data">       
                        <div class='col-sm-12'>
                            
                            {{ csrf_field() }}
                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <b>商品名稱</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="name" placeholder="" value="{{$goodsData['name']}}" disabled/>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="col-sm-3">
                                        <b>貨號</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="goods_sn" placeholder="" value="{{$goodsData['goods_sn']}}" disabled/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             
<!--                                 <div class="row clearfix">
                
                                    <div class="col-sm-6">
                                        <p><b>商品類別</b></p>
                                        <select class="form-control show-tick" name='category'>
                                            @foreach( $categorys as $category)
                                            <option value="{{$category['id']}}"
                                                @if( $goodsData['cid'] == $category['id'] )
                                                selected
                                                @endif
                                            > {{$category['level']}}{{$category['name']}} </option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>

                                
                                <div class="row clearfix">
                                    
                                    <div class="col-md-6">
                                        <p><b>擴展類別</b></p>
                                        
                                        <select class="form-control show-tick" multiple name='multiplCategory[]'>
                                            @foreach( $categorys as $category)
                                            <option value="{{$category['id']}}" 
                                                @if( in_array( $category['id'] , $goodsCats ) )
                                                selected
                                                @endif
                                            >{{$category['name']}} </option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div> -->
                                <!-- /擴展類別 -->
                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <b>批發價</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="wprice" placeholder="" value="{{$goodsData['w_price']}}" disabled/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <b>售價</b>
                                        <div class="form-group">
                                        @foreach( $multiples as $multiple)
                                            <input type="radio" class="with-gap" id="ig_radio{{$multiple['id']}}" name='multiple' value="{{$multiple['id']}}"
                                            
                                            @if( count($goodsPrice) > 0 && $goodsPrice['multiple_id'] == $multiple['id'])
                                                checked
                                            @elseif( count($goodsPrice) <= 0 && $dealer['multiple'] == $multiple['multiple'] )
                                                checked
                                            @endif
                                            >
                                            <label for="ig_radio{{$multiple['id']}}">{{$multiple['multiple']}}倍 </label>
                                            <br>
                                        @endforeach
                                        <div class="input-group input-group-xs">
                                            <span class="input-group-addon">
                                                <input type="radio" class="with-gap" id="ig_radio" name='multiple' value='999'
                                                @if( count($goodsPrice) > 0 && $goodsPrice['multiple_id'] == 999 )
                                                    checked
                                                @endif
                                                >
                                                <label for="ig_radio">自訂售價</label>
                                            </span>
                                            <div class="form-line">
                                                <input type="number" min='0' class="form-control" name='custome'
                                                @if( count($goodsPrice) > 0 && $goodsPrice['multiple_id'] == 999 )
                                                    value="{{$goodsPrice['price']}}"
                                                @endif
                                                >
                                            </div>
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

                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <b>庫存</b>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="stock" placeholder="" value="{{$stock}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>                                

                                <div class='row clearfix'>
                                    <div class="col-sm-3">
                                        <b>商品主圖</b>
                                        <div class="form-group">
                                            <div class="">
                                                <!-- <input type="file" class="form-control imageupload" name="mainpic" id="mainpic" placeholder="" disabled/> -->
                                            </div>
                                            <div id="mainDisplay">
                                                <img src="{{url('/images')}}/{{$goodsData['main_pic']}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <b>商品縮圖</b>
                                        <div class="form-group">
                                            <div class="">
                                                <!-- <input type="file" class="form-control imageupload" name="thumbnail" id="thumbnail" placeholder="" disabled/> -->
                                            </div>
                                            <div id="thumbDisplay">
                                                <img src="{{url('/images')}}/{{$goodsData['thumbnail']}}">
                                            </div>
                                        </div>
                                    </div>                                     
                                </div>

<!--                                 <div class="row clearfix">
                                    <div class="col-sm-3">
                                       <p><b>上架</b></p>
                                        <div class="form-group">
                                            <div class="demo-checkbox">
                                                <input type="checkbox" id="status" name='status'
                                                @if( $goodsData['status'] == 1)
                                                    checked
                                                @endif
                                                />
                                                <label for="status">勾選上架</label>
                                            </div>              
                                        </div>                  
                                    </div> 
                                </div>    -->                                                
                                
                                
                            

                        </div>
                        <input type='hidden' name='id' value="{{$goodsData['id']}}">
                        <button class="btn btn-primary waves-effect" type="submit">編輯</button>
                        </form>
                    </div>

                    <!-- / 基本資料 -->


            </div>


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

    /*----------------------------------------------------------------
     | 拖曳上傳檔案
     |----------------------------------------------------------------
     | AJAX 上傳檔案 , 如果成功則會在下方立即產生排序元素
     |
     |
     */

    Dropzone.options.frmFileUpload = {
        
        init: function() {
            
            this.on("success", function(file, responseText) {

                this.removeFile(file);

                if(responseText != false){
                    ajaxPath = jQuery.parseJSON(responseText);
                    $("#goodsPicSort").append('<li class="ui-state-default goodsPicSortItem">'+
                                        '<i class="material-icons sortTips">format_line_spacing</i> '+
                                        '<img src="{{url('')}}/'+ajaxPath+'">'+
                                        '<input type="hidden" name="picSort[]"" value="'+ajaxPath+'">'+
                                        '<button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float goodsPicDelete" pic="'+ajaxPath+'" >'+
                                        '<i class="material-icons">cancel</i>'+
                                        '</button>'+
                                        '</li>'
                    );

                }

            });
        }
    };
    // 拖曳上傳成功結束

    /*----------------------------------------------------------------
     | AJAX 移除圖片
     |----------------------------------------------------------------
     |
     |
     */

    $('body').on('click', '.goodsPicDelete', function() {
        
        picPath = $(this).attr('pic');
        gid     = "{{$goodsData['id']}}";
        delLi   = $(this).parent();

        // 刪除確認
        Swal.fire({
            
            title: '即將刪除圖片',
            text:  "圖片一經刪除後無法回復,是否確定刪除?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '確定',
            cancelButtonText: '取消'
        
        }).then((result) => {
             
            if (result.value) {
            
                // 送出刪除AJAX
                
                var request = $.ajax({
                    
                    url: "{{url('/goodsAjaxPicDelete')}}",
                    method: "POST",
                    data: { gid : gid ,
                            picPath : picPath ,
                            _token: "{{ csrf_token() }}",
                    },
                    dataType: "JSON"
                });
 
                request.done(function( msg ) {
                    
                    if( msg[0] == true ){

                        delLi.remove();

                    }
                });
 
                request.fail(function( jqXHR, textStatus ) {
                    
                    
                });

            // 送出刪除AJAX結束
            } 
        
        })
        // 刪除確認結束

    });

    // AJAX 移除圖片結束

    /*----------------------------------------------------------------
     | 記憶排序
     |----------------------------------------------------------------
     |
     |
     */
    $("#saveSort").click(function(){
        
        var sortArr = [];
        var sortGid = "{{$goodsData['id']}}";
        $( ".goodsPicSortItem" ).each(function( index ) {

            sortArr.push($(this).children('input').val());

        });

        // AJAX 排序
        var request = $.ajax({
                    
            url: "{{url('/goodsAjaxPicSort')}}",
            method: "POST",
            data: { gid : sortGid ,
                    sort : sortArr ,
                    _token: "{{ csrf_token() }}",
            },
            dataType: "JSON"
        });
 
        request.done(function( msg ) {
                    
            if( msg[0] == true ){
                Swal.fire(
                    '排序完成',
                    '商品圖片已調整排序',
                    'success'
                )

            }else{
                Swal.fire(
                    '排序失敗',
                    '調整排序過程失敗',
                    'error'
                )                
            }
        });
 
        request.fail(function( jqXHR, textStatus ) {
                    
                    
        });

        // AJAX 排序結束
    })
    // 記憶排序結束
})


</script>

<script>
$(function(){
    
    $( "#goodsPicSort" ).sortable();
    $( "#goodsPicSort" ).disableSelection();

    // 一開始先將全部被focus的元素解除focus
    $(".focused:not(.error)").removeClass('focused');

    /*
    CKEDITOR.replace('ckeditor',options); 
    CKEDITOR.config.height = 300;
    */

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
/* 消除列li的樣式 */
#goodsPicSort > li{

    list-style-type:none;
}
#goodsPicSort > li > img {
    obj-fit:cover;
    height: 120px;
    max-width: 50%;
}
.sortTips{
    margin-left:  10px;
    margin-right: 20px;
}
.goodsPicSortItem{

    position: relative;
}
.goodsPicSortItem > button{
    position: absolute;
    right: 2px;
    top: 2px;
}
</style>

@endsection
