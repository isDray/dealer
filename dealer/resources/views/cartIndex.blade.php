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


<div id='fastBox' class="container-fluid">

<!-- 輪播 -->
<!-- <div id='carouselBox' class='col-md-12 col-sm-12 col-xs-12 _np'>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>


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


  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div> -->
<!-- /輪播 -->
    <div class='boxLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12 fastBar' label='快速分類'>
        <!-- <span class='btn btn-primary fastBtn' data-toggle="collapse" data-target="#allCate">全部分類</span> -->
    </div>
    <div id='fastInner' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 _np' >
<!--         <div class='col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-1 col-xs-2 col-xs-offset-1 fastItem '><div>滾珠</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>伸縮</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>震動</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>逼真</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>AV</div></div>
        <div class='col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-1 col-xs-2 col-xs-offset-1 fastItem'><div>跳蛋</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>猛男</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>男女</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>特殊</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>內衣</div></div>
        <div class='col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-1 col-xs-2 col-xs-offset-1 fastItem'><div>網襪</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>丁字褲</div></div>
        <div class='col-md-2 col-sm-2 col-xs-2  fastItem'><div>三角褲</div></div> -->
        @foreach($categorys as $categoryk =>$category)
        <a href="{{url('')}}/{{$dealerDetect}}/cartCategory/{{$category['id']}}">
            <div class="fastItem">
                <div>
                <img src="{{url('')}}/{{$category['category_pic']}}" width="100%" height="100%">
                </div>

                <div class='_w'>
                {{$category['name']}}
                </div>

                <div class='_p'>
                {{$category['sortname']}}
                </div>
            </div>
        </a>           
        @endforeach

<!--         <div id="allCate" class="collapse col-md-12 col-sm-12 col-xs-12 _np">
        @foreach($categorys as $categoryk =>$category)
        @if( $categoryk > 7)
        <a href="{{url('')}}/{{$dealerDetect}}/cartCategory/{{$category['id']}}">
            <div class="fastItem">
                <div>
                <img src="{{url('')}}/{{$category['category_pic']}}" width="100%" height="100%">
                </div>
                <div>
                {{$category['sortname']}}
                </div>
            </div>
        </a>
        @endif            
        @endforeach          
        </div> -->
    </div>
</div>

<!-- 最新商品 -->
<!-- <div class="container-fluid">
    <div class='boxLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='最新商品'></div>
    
    <div id='newGoods' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 _np'>
        @if( $displayWay == 1)

        @foreach($newGoods as $newGood)
        <div class="col-md-3 col-sm-4 col-xs-6 _psp">
            <div class="thumbnail">
                <a href="{{url('')}}/{{$dealerDetect}}/goods/{{$newGood['id']}}" target="_blank">
                <img src="{{url('images')}}/{{$newGood['thumbnail']}}" alt="...">
                </a>

                <div class="caption">
            
                <h5> {{ $newGood['name'] }} </h5>
                <h4>價格:{{$newGood['goodsPrice'] }}</h4>

                <p class='itemBtn'>

                    @if( $newGood['stock'] > 0)
                    <button class="btn btn-primary addone" role="button" goodsId="{{$newGood['id']}}"><span class="glyphicon glyphicon-shopping-cart"></span>加入購物車</button>
                    @else
                    <button class="btn btn-danger" role="button" goodsId="{{$newGood['id']}}" disabled><span class="glyphicon glyphicon-retweet"></span>補貨中</button>
                    @endif
                </p>
                </div>
            </div>
        </div>
        @endforeach

        @else

        @foreach($newGoods as $good)
        <div class="media goodlist" style='background-color:#e3e3e3'>
          <div class="media-left">
            <a href="{{url('')}}/{{$dealerDetect}}/goods/{{$good['id']}}" target="_blank">
              <img src="{{url('images')}}/{{$good['thumbnail']}}" alt="...">
            </a>
          </div>
          <div class="media-body">
                <h5> {{ $good['name'] }}</h5>
                <h4>價格:{{$good['goodsPrice'] }}</h4>
                <p class='itemBtn'>

                   
                    @if( $good['stock'] > 0)
                    <button class="btn btn-primary addone" role="button" goodsId="{{$good['id']}}"><span class="glyphicon glyphicon-shopping-cart"></span>加入購物車</button>
                    @else
                    <button class="btn btn-danger" role="button" goodsId="{{$good['id']}}" disabled><span class="glyphicon glyphicon-retweet"></span>補貨中</button>
                    @endif                   
                </p>                
          </div>
        </div>
        @endforeach
        
        @endif        
    </div>
    
</div> -->
<!-- /最新商品 -->

<!-- 熱銷商品 -->
<!-- <div class="container-fluid">
    <div class='boxLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='熱銷商品'></div>
    
    <div id='hotGoods' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 _np'>
        @foreach($hots as $newGood)
        <div class="col-md-3 col-sm-4 col-xs-6 ">
            <div class="thumbnail">
                <a href="{{url('')}}/{{$dealerDetect}}/goods/{{$newGood['id']}}" target="_blank">
                <img src="{{url('images')}}/{{$newGood['thumbnail']}}" alt="...">
                </a>
                
                <div class="caption">
            
                <h5> {{ $newGood['name'] }} </h5>
                <h4>價格:{{$newGood['goodsPrice'] }}</h4>
                <p class='itemBtn'>

                   <button class="btn btn-primary addone" role="button" goodsId="{{$newGood['id']}}"><span class="glyphicon glyphicon-shopping-cart"></span>加入購物車</button>
                </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    </div>
</div> -->
<!-- /熱銷商品 -->

<!-- 推薦商品 -->
<!-- <div class="container-fluid">
    <div class='boxLabel _np col-md-8 col-md-offset-2 col-sm-12 col-xs-12' label='推薦商品'></div>
    
    <div id='recommendGoods' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 _np'>
        @if( $displayWay == 1)
        @foreach($recommendGoods as $newGood)
        <div class="col-md-3 col-sm-4 col-xs-6 _psp">
            <div class="thumbnail">
                <a href="{{url('')}}/{{$dealerDetect}}/goods/{{$newGood['id']}}" target="_blank">
                <img src="{{url('images')}}/{{$newGood['thumbnail']}}" alt="...">
                </a>
                
                <div class="caption">
            
                <h5> {{ $newGood['name'] }} </h5>
                <h4>價格:{{$newGood['goodsPrice'] }}</h4>
                <p class='itemBtn'>

                   <button class="btn btn-primary addone" role="button" goodsId="{{$newGood['id']}}"><span class="glyphicon glyphicon-shopping-cart"></span>加入購物車</button>
                </p>
                </div>
            </div>
        </div>
        @endforeach

        @else

        @foreach($newGoods as $good)
        <div class="media goodlist" style='background-color:#e3e3e3'>
          <div class="media-left">
            <a href="{{url('')}}/{{$dealerDetect}}/goods/{{$good['id']}}" target="_blank">
              <img src="{{url('images')}}/{{$good['thumbnail']}}" alt="...">
            </a>
          </div>
          <div class="media-body">
                <h5> {{ $good['name'] }}</h5>
                <h4>價格:{{$good['goodsPrice'] }}</h4>
                <p class='itemBtn'>
                   
                    @if( $good['stock'] > 0)
                    <button class="btn btn-primary addone" role="button" goodsId="{{$good['id']}}"><span class="glyphicon glyphicon-shopping-cart"></span>加入購物車</button>
                    @else
                    <button class="btn btn-danger" role="button" goodsId="{{$good['id']}}" disabled><span class="glyphicon glyphicon-retweet"></span>補貨中</button>
                    @endif                   
                </p>                
          </div>
        </div>
        @endforeach
        
        @endif         
    </div>
    
</div> -->
<!-- /推薦商品 -->

<script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- 專屬js -->
<script type="text/javascript">
$(function(){

    adjHeight = $("#fixBox").outerHeight();
    footerHeight =  $("#footer").outerHeight();
    
    reduceHeight = adjHeight + footerHeight +1 ;
    
    fastBoxHeight= $("#fastBox").innerHeight();
    // 計算高度
    $("#fastBox").css("margin-top",adjHeight);

    console.log( fastBoxHeight);
    console.log( $(window).height() - reduceHeight);
    if( fastBoxHeight < $(window).height() - reduceHeight){

        $("#fastBox").css("height","calc(100vh - "+reduceHeight+"px)");
    }
     //$("#fastBox").css("height","100vh");
    console.log( "calc(100vh - "+adjHeight+"px -"+footerHeight+"px)");
})
</script>
<!-- /專屬js -->

@endsection
