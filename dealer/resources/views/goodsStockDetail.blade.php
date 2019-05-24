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
        <div class="header bg-red">
            <h2>
            {{$goodsSn}} 庫存明細

            </h2>
        </div>

        <div class="body"> 
            <div class="row clearfix">
            <div class='col-md-4'>
            <table class="table table-striped table-bordered" >
                <thead>
                    <tr class='bg-grey'>
                        <th>經銷商</th>
                        <th>庫存</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $stockDetails as $stockDetail)
                    <tr>
                        <th>{{$stockDetail['name']}}</th>
                        <th>{{$stockDetail['num']}}</th>                 
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>   
            </div>   
        </div>

        </div>
    </div>
</div>

<!-- 上傳圖片用script -->
<script type="text/javascript">
$(function(){

})
</script>


@endsection
