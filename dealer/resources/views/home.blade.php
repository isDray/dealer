@extends('layouts.admin')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                {{$title}}
                </div>

                <div class="body">
                    <div class="row clearfix">
                    
                    <a href="{{url('order')}}/?status=2">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-red hover-zoom-effect">
                        <div class="icon ">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="content">
                            <div class="text">待處理訂單</div>
                            <div class="number count-to" data-from="0" data-to="{{$pendingNum}}" data-speed="1000" data-fresh-interval="1">{{$pendingNum}}</div>
                        </div>
                    </div>
                    </div>
                    </a>
                    
                    <a href="{{url('order')}}/?status=3">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-red hover-zoom-effect">
                        <div class="icon ">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">已確認未出貨</div>
                            <div class="number count-to" data-from="0" data-to="{{$checkedNum}}" data-speed="1000" data-fresh-interval="1">{{$checkedNum}}</div>
                        </div>
                    </div>
                    </div>
                    </a>

                    <a href="{{url('order')}}/?status=4">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-red hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">assignment_turned_in</i>
                        </div>
                        <div class="content">
                            <div class="text">已完成訂單</div>
                            <div class="number count-to" data-from="0" data-to="{{$doneNum}}" data-speed="1000" data-fresh-interval="1">{{$doneNum}}</div>
                        </div>
                    </div>
                    </div>
                    </a>


                    </div>

                    <!-- 商品相關 -->
                    <div class="row clearfix">

                    @role('Admin')
                    <a href="{{url('goods')}}/?status=1">                    
                    @endrole

                    @role('Dealer')
                    <a href="{{url('price')}}/?stock=1">
                    @endrole

                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-indigo hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">view_quilt</i>
                        </div>
                        <div class="content">
                            @role('Admin')
                            <div class="text">上架商品數</div>
                            @endrole
                            @role('Dealer')
                            <div class="text">可用商品數</div>
                            @endrole
                            <div class="number count-to" data-from="0" data-to="{{$useGoodsNum}}" data-speed="1000" data-fresh-interval="1">{{$useGoodsNum}}</div>
                        </div>
                    </div>
                    </div>
                    </a>

                    @role('Admin')

                    <a href="{{url('goods')}}/?status=2">                    
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-indigo hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">check_box_outline_blank</i>
                        </div>
                        <div class="content">
                            <div class="text">停用商品數</div>
                            <div class="number count-to" data-from="0" data-to="{{$stopGoodsNum}}" data-speed="1000" data-fresh-interval="20">{{$stopGoodsNum}}</div>
                        </div>
                    </div>
                    </div>
                    </a>
            
                    @endrole

                    @role('Dealer')
                    <a href="{{url('price')}}/?stock=3"> 
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-indigo hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">check_box_outline_blank</i>
                        </div>
                        <div class="content">
                            <div class="text">無庫存商品數</div>
                            <div class="number count-to" data-from="0" data-to="{{$noStockGoodsNum}}" data-speed="1000" data-fresh-interval="20">{{$noStockGoodsNum}}</div>
                        </div>
                    </div>
                    </div>                    
                    </a>
                    @endrole
                                       
                    @role('Dealer')
                    <a href="{{url('price')}}/?stock=2">
                    @endrole
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-indigo hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">warning</i>
                        </div>
                        <div class="content">
                            <div class="text">低庫存商品數</div>
                            <div class="number count-to" data-from="0" data-to="{{$lowStaockNum}}" data-speed="1000" data-fresh-interval="20">{{$lowStaockNum}}</div>
                        </div>
                    </div>
                    </div>
                    </a>

                    </div>                    
                    <!--/商品相關 -->

                </div>
            </div>
    </div>
</div>

<script src="{{asset('adminbsb-materialdesign/plugins/jquery-countto/jquery.countTo.js')}}"></script>

<script type="text/javascript">
$(function () {
    initCounters();
});
function initCounters() {
    $('.count-to').countTo();
}
</script>
@endsection
