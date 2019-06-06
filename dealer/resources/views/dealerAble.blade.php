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
<link href="{{asset('adminbsb-materialdesign/plugins/multi-select/css/multi-select.css')}}" rel="stylesheet" />

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
            <small>填寫以下表格以編輯可用分類及商品</small>
            </h2>
        </div>

        <div class="body">
        
            <form action="{{url('/newdealerCategoryAndGoodsDo')}}" method="POST" enctype="multipart/form-data">
                <input type='hidden' name="dealerId" value="{{ $dealer['uid'] }}" >
                {{ csrf_field() }} 
                <div class='col-sm-12'>
                    
                    @role('Admin')
                    <p>可用分類及商品設定</p>

                    <div class="row clearfix">
                       
                        <!-- 可用分類選項 -->
                        <div  class="col-md-12 col-sm-12 col-xs-12">
                            
                            <!-- 快速選擇 -->
                            <div class="col-md-3 col-sm-12 col-xs-12" style="margin-bottom:0px;">
                                <b>前台呈現分類</b>
                                <div class="form-group" style="margin-bottom:0px;">
                                    <div>

                                        <input name="quickCategory" type="radio" id="allCategory" class="with-gap" value='1'/>
                                        <label for="allCategory">全分類</label>

                                        <input name="quickCategory" type="radio" id="underAndNight" class="with-gap" value='2'/>
                                        <label for="underAndNight">內睡衣</label>        
                                                                        
                                    </div>
                                </div>                                
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12" style="border-top:1px solid grey;padding-top:15px;">
                                @foreach($categorys as $category)
                                <div class="col-md-3 col-sm-12 col-xs-12 _np">

                                    <input type="checkbox" class="filled-in" id="category{{$category['id']}}" name='allCategory[]' value="{{$category['id']}}" @if( in_array($category['id'] ,$ablecategorys) ) checked @endif >
                                    <label for="category{{$category['id']}}">{{$category['name']}}</label>                                    

                                </div>
                                @endforeach
                            </div>                           
                            <!-- /快速選擇 -->
                        </div>
                        <!-- /可用分類選項 -->

                        <!-- 可用商品 -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-3 col-sm-12 col-xs-12" style="margin-bottom:0px;">
                            <b>可用商品</b>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:15px;">
                             
                            <input name="ableWay" type="radio" id="ableWay1" class="with-gap" value='1' checked />
                            <label for="ableWay1">選單選取</label>
                            <br>

                            <select id="optgroup" class="ms" multiple="multiple" name='ableGoods[]'>
                                @foreach( $selectGoods as $selectGoodk => $selectGood)
                                <optgroup label="{{$selectGoodk}}">
                                    @foreach($selectGood as $goodsItem)
                                    <option value="{{$goodsItem['id']}}" @if(in_array($goodsItem['id'],$ableGoods)) SELECTED @endif >{{$goodsItem['goods_sn']}}_{{$goodsItem['name']}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>

                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:15px;">

                            <input name="ableWay" type="radio" id="ableWay2" class="with-gap" value='2'/>
                            <label for="ableWay2">輸入貨號</label>
                            <br>

                            <textarea rows='8' class="form-control no-resize" name='ableGoodsText' ></textarea>
                            
                            </div>                            
                    
                        </div>                       
                        <!-- /可用商品 -->

                    </div>
                        

                    @endrole

                </div>

                <button class="btn btn-primary waves-effect" type="submit">確定</button>

            </form>


        </div>

        </div>
    </div>
</div>


<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-datepicker/js/datepicker-zh-TW.js')}}"></script>
<script src="{{asset('/js/jquery.quicksearch.js')}}"></script>


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
    @role('Admin')
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
    



    /*----------------------------------------------------------------
     | 快速選擇分類
     |----------------------------------------------------------------
     |
     */
    $("input[name='quickCategory']").change(function(){
        
        var nowchoose = $('input[name=quickCategory]:checked').val();
        
        if( nowchoose == 1){

            $("input[name='allCategory[]']").each(function() {
                $(this).prop("checked", true);
            });
        }
        if( nowchoose == 2){

            $("input[name='allCategory[]']").each(function() {
                $(this).prop("checked", false);
            });

            $("#category10").prop("checked", true);
            $("#category11").prop("checked", true);
            $("#category12").prop("checked", true);
            $("#category13").prop("checked", true);
        }

    });
    /*$('#optgroup').multiSelect({ selectableHeader:"22556",dblClick:true});*/
    $('#optgroup').multiSelect({
      selectableOptgroup: true ,
      selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='快速搜尋'>",
      selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='快速搜尋'>",
      afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
    
        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });
    
        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
      },
      afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
      },
      afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
      }
    });

    @endrole  

    

});
</script>

@if(session()->has('successMsg') && session('successMsg') =='經銷商密碼修改成功 , 系統即將自動登出 , 請稍後自行登入')
<script type="text/javascript">

$(function(){
setTimeout(function(){
document.getElementById('logout-form').submit();
},3000);

});

</script>
@endif

<!-- /上傳圖片用script -->

<style type="text/css">
#mainDisplay > img , #thumbDisplay > img {
    object-fit:contain;
    max-width: 100%;
}

.defaultDisplay{
    overflow: hidden;
}
.defaultDisplay > img{
    object-fit:cover!important;
    
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
