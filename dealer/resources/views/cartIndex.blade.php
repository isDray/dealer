@extends('layouts.cart')

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
<div class="container-fluid _np" style='margin-bottom:40px;'>

<!-- 輪播 -->
<div class='col-md-12 col-sm-12 col-xs-12 _np'>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="https://***REMOVED***.com/***REMOVED***/data/afficheimg/20181108siafyj.jpg" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
    <div class="item">
      <img src="https://***REMOVED***.com/***REMOVED***/data/afficheimg/20181108siafyj.jpg" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
    <div class="item">
      <img src="https://***REMOVED***.com/***REMOVED***/data/afficheimg/20181108siafyj.jpg" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div>
<!-- /輪播 -->

</div>

<!-- 最新商品 -->
<div class="container-fluid">
    <div class='boxLabel _np col-md-10 col-md-offset-1 col-sm-12 col-xs-12' label='最新商品'></div>
    
    <div id='newGoods' class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 _np'>
        @for( $i=0; $i<8 ; $i++)
        <div class="col-md-3 col-sm-4 col-xs-6 ">
            <div class="thumbnail">
                <img src="https://***REMOVED***.com/***REMOVED***/images/201505/thumb_img/9407_thumb_G_1432062363451.gif" alt="...">
                <div class="caption">
            
                <h3>商品名稱</h3>
                <p>$560</p>
                <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                </div>
            </div>
        </div>
        @endfor
    </div>
    
    </div>
</div>
<!-- /最新商品 -->

<!-- 熱銷商品 -->
<div class="container-fluid">
    <div class='boxLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='熱銷商品'></div>
    
    <div id='hotGoods' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12'>
        @for( $i=0; $i<8 ; $i++)
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="thumbnail">
                <img src="https://***REMOVED***.com/***REMOVED***/images/201505/thumb_img/9407_thumb_G_1432062363451.gif" alt="...">
                <div class="caption">
            
                <h3>商品名稱</h3>
                <p>$560</p>
                <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                </div>
            </div>
        </div>
        @endfor
    </div>
    
    </div>
</div>
<!-- /熱銷商品 -->

<!-- 推薦商品 -->
<div class="container-fluid">
    <div class='boxLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='推薦商品'></div>
    
    <div id='recommendGoods' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12'>
        @for( $i=0; $i<8 ; $i++)
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="thumbnail">
                <img src="https://***REMOVED***.com/***REMOVED***/images/201505/thumb_img/9407_thumb_G_1432062363451.gif" alt="...">
                <div class="caption">
            
                <h3>商品名稱</h3>
                <p>$560</p>
                <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                </div>
            </div>
        </div>
        @endfor
    </div>
    
    </div>
</div>
<!-- /推薦商品 -->

<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){

})
</script>
<!-- /專屬js -->

@endsection
