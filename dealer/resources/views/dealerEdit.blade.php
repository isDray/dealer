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

<link href="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
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
        <div class="header bg-red">
            <h2>
            {{$title}}表格
            <small>填寫以下表格以新增經銷商</small>
            </h2>
        </div>

        <div class="body">
        
            <form action="{{url('/dealerEditDo')}}" method="POST" enctype="multipart/form-data">
                <input type='hidden' name="dealerId" value="{{ $dealer['uid'] }}" >
                {{ csrf_field() }} 
                <div class='col-sm-12'>
                    
                    <p>帳號資料(ID:{{$dealer['uid']}})</p>
                    
                    <div class="row clearfix">

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>帳號<span style='color:red;'>(必填)</span>:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="account" id='account' placeholder="" value="{{ $dealer['name'] }}"/>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>&nbsp;</b>
                            <div class="form-group">
                            <div class="">
                            <button class="btn bg-cyan waves-effect m-b-15" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false"
                                    aria-controls="collapseExample">
                                修改密碼
                            </button>
                            </div>
                            </div>                            
                        </div>

                    </div>

                    <div class="collapse row clearfix" id="collapseExample">

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>舊密碼:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="password" class="form-control myborder" name="oldpassword" placeholder="" value=""/>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>新密碼:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="password" class="form-control myborder" name="password1" placeholder="" value=""/>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>新密碼確認:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="password" class="form-control myborder" name="password2" placeholder="" value=""/>
                            </div>
                            </div>
                        </div>                        
                       
                    </div>                    
                    
                   
                    <!-- 辨別代碼選擇 -->
                    @role('Admin')
                    <p> 網站資料 </p>

                    
                    <div class="row clearfix">

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>網站代碼<span style='color:red;'>(必填)</span>:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="accessWay" id="accessWay" placeholder="" value="{{$accessWay}}" />
                            </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>合作日期:</b>
                            <div class="form-group">
                            <div class="form-line" id='enableDateBox'>
                                <input type="text" class="form-control myborder align-center" placeholder="合作日期" id='enable_date' name="enable_date" value="{{$dealer['enable_date']}}">
                            </div>
                            </div>
                        </div>


                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>&nbsp;</b>
                            <div class="form-group">
                            <div>
                                <input type="checkbox" class="filled-in" id="status" name='status'>
                                <label for="status">啟用</label>
                            </div>
                            </div>
                        </div>




                         
                    </div>
                    @endrole
                    <!-- 辨別代碼選擇 -->
                    <p> 價格資料 </p>
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>價格預設倍數<span style='color:red;'>(必填)</span>:</b>
                            <div class="form-group">
                            @foreach( $multiples as $multiple)
                                <input type="radio" class="with-gap" id="ig_radio{{$multiple['id']}}" name='multiple' value="{{$multiple['multiple']}}"
                                @if( $dealer['multiple'] == $multiple['multiple'] )
                                checked
                                @endif
                                >
                                <label for="ig_radio{{$multiple['id']}}">{{$multiple['multiple']}}倍 </label>
                                <br>
                            @endforeach
                            </div>
                        </div>
                    </div>   
                    @role('Admin')
                    <p>旅館資料</p>
                    <!-- 收貨資訊 -->
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>公司抬頭:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="company" placeholder="" value="{{ $dealer['company'] }}" />
                            </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>統一編號:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="ein" placeholder="" value="{{ $dealer['ein'] }}" />
                            </div>
                            </div>
                        </div>                        
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>旅館名稱:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="hotel_name" placeholder="" value="{{ $dealer['hotel_name'] }}" />
                            </div>
                            </div>
                        </div>
   
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>旅館電話:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="hotel_tel" placeholder="" value="{{  $dealer['hotel_tel']  }}" />
                            </div>
                            </div>
                        </div>  
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>旅館手機:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="hotel_phone" placeholder="" value="{{  $dealer['hotel_phone']  }}" />
                            </div>
                            </div>
                        </div> 
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>旅館信箱:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="hotel_email" id="hotel_email" placeholder="" value="{{  $dealer['hotel_email']  }}" />
                            </div>
                            </div>
                        </div>                         
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <b>旅館地址:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="hotel_address" id="hotel_address" placeholder="" value="{{  $dealer['hotel_address']  }}" />
                            </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>旅館網址:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="hotel_url" placeholder="" value="{{ $dealer['web_url'] }}"/>
                            </div>
                            </div>
                        </div>                        
                        <div class="col-sm-3">
                            <b>網頁版LOGO <span style="color:red">建議尺寸(400*300)</span></b>
                            <div class="form-group">
                                <div class="">
                                    <input type="file" class="form-control imageupload" name="mainpic" id="mainpic" placeholder="" />
                                </div>
                                <div id="mainDisplay">
                                    @if( !empty($dealer['logo1']) )
                                    <img src="{{url('')}}/logo/{{$dealer['uid']}}/{{$dealer['logo1']}}">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <b>手機板LOGO <span style="color:red">建議尺寸(300*200)</span></b>
                            <div class="form-group">
                                <div class="">
                                    <input type="file" class="form-control imageupload" name="thumbnail" id="thumbnail" placeholder="" />
                                </div>
                                <div id="thumbDisplay">
                                    @if( !empty($dealer['logo2']) )
                                    <img src="{{url('')}}/logo/{{$dealer['uid']}}/{{$dealer['logo2']}}">
                                    @endif
                                </div>
                            </div>
                        </div>                                                                                                                
                    </div>                                     
                    <p>聯絡人資料</p>
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>聯絡人<span style='color:red;'>(必填)</span>:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="user_name" id="user_name" placeholder="" value="{{ $dealer['user_name'] }}" />
                            </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>職稱:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="user_position" placeholder="" value="{{ $dealer['user_position'] }}" />
                            </div>
                            </div>
                        </div>                         
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>信箱:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="email" class="form-control myborder" name="user_email" placeholder="" value="{{ $dealer['email'] }}" />
                            </div>
                            </div>
                        </div>                          
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>聯絡人手機:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="user_phone" id="user_phone" placeholder="" value="{{ $dealer['user_phone'] }}"/>
                            </div>
                            </div>
                        </div>   
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>聯絡人電話:</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="user_tel" id="user_tel" placeholder="" value="{{ $dealer['user_tel'] }}"/>
                            </div>
                            </div>
                        </div>                                             
                    </div>                    
                    
                    <!-- 顏色設定 -->
                    <p>色彩設定</p>
                    <!-- 收貨資訊 -->
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>logo背景色1</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="color" class="form-control myborder" name="logocolor1" placeholder="" value="{{$dealer['logo_color1']}}"/>
                            </div>
                            </div>
                        </div> 
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>logo背景色2</b>
                            <div class="form-group">
                            <div class="form-line">
                                <input type="color" class="form-control myborder" name="logocolor2" placeholder="" value="{{$dealer['logo_color2']}}"/>
                            </div>
                            </div>
                        </div>                                               
                    </div>
                    <!-- /顏色設定 -->
                    @endrole
                    <p>收貨資料</p>
                    <!-- 收貨資訊 -->
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>預設收貨人:</b>
                            <div class="input-group">
                            
                                <span class="input-group-addon">
                                    <input type="checkbox" class="filled-in" id="sameName">
                                    <label for="sameName">同聯絡人</label>
                                </span>
                                
                                <div class="form-line">
                                    <input type="text" class="form-control myborder" name="ship_name" id='ship_name' placeholder="" value="{{  $dealer['ship_name'] }}"/>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>預設收貨手機:</b>
                            <div class="input-group">
                            <span class="input-group-addon">
                                <input type="checkbox" class="filled-in" id="samePhone">
                                <label for="samePhone">同聯絡人</label>
                            </span>                            
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="ship_phone" id="ship_phone" placeholder="" value="{{ $dealer['ship_phone'] }}" />
                            </div>
                            </div>
                        </div>   
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <b>預設收貨電話:</b>
                            <div class="input-group">
                            <span class="input-group-addon">
                                <input type="checkbox" class="filled-in" id="sameTel">
                                <label for="sameTel">同聯絡人</label>
                            </span>                                 
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="ship_tel" id='ship_tel' placeholder="" value="{{ $dealer['ship_tel'] }}" />
                            </div>
                            </div>
                        </div>  
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <b>預設收貨地址:</b>
                            <div class="input-group">
                            <span class="input-group-addon">
                                <input type="checkbox" class="filled-in" id="sameAddress">
                                <label for="sameAddress">同旅館地址</label>
                            </span>                                 
                            <div class="form-line">
                                <input type="text" class="form-control myborder" name="ship_address" id="ship_address" placeholder="" value="{{ $dealer['ship_address']}}" />
                            </div>
                            </div>
                        </div>                                                                    
                    </div>  
                    <!-- /收貨資訊 -->                  

                      

                </div>

                <button class="btn btn-primary waves-effect" type="submit">確定</button>

            </form>


        </div>
        </div>
    </div>
</div>


<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-datepicker/js/datepicker-zh-TW.js')}}"></script>

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
    
    $(".form-line").removeClass('focused');

    $( "#fileArea" ).sortable();
    $( "#fileArea" ).disableSelection();

    /*CKEDITOR.replace('ckeditor',options);
    CKEDITOR.config.height = 300;*/
    
    /*----------------------------------------------------------------
     | 收貨人同步聯絡人
     |
     */
    $("#sameName").click(function(){

        if( $('#sameName').is(":checked") ){
        
            $('#ship_name').val( $("#user_name").val() );

        }else{
            
            $('#ship_name').val( '' );
        }
    });

    /*----------------------------------------------------------------
     | 收貨手機同步聯絡人
     |
     */
    $("#samePhone").click(function(){
        
        if( $('#samePhone').is(":checked") ){

            $('#ship_phone').val( $("#user_phone").val() );

        }else{

            $('#ship_phone').val( '' );
        }
    });

    /*----------------------------------------------------------------
     | 收貨電話同步聯絡人
     |
     */
    $("#sameTel").click(function(){
        
        if( $('#sameTel').is(":checked") ){

            $('#ship_tel').val( $("#user_tel").val() );

        }else{

            $('#ship_tel').val( '' );
        }
    });     

    /*----------------------------------------------------------------
     | 收貨地址同旅館地址
     |
     */
    $("#sameAddress").click(function(){
        
        if( $('#sameAddress').is(":checked") ){

            $('#ship_address').val( $("#hotel_address").val() );

        }else{

            $('#ship_address').val( '' );
        }
    });

    $('#enable_date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        container : "#enableDateBox",
        language: 'zh-TW',
    });   
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
