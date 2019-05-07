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
                &nbsp;出貨統計報表
                </h2>    
            </div>
            
            <div class="body">
                <!-- 進階搜尋框 -->
                <div class="row clearfix" style="border:1px solid #d4d4d4; margin-bottom:50px;">
                    
                    <div class='col-xs-12 col-sm-12 col-md-12 bg-grey' style="">
                        <p><b>進階搜尋</b></p>
                    </div>
                    <form action="{{url('/reportPurchase')}}" method="get" autocomplete="off" >
                    {{ csrf_field() }}
                    @role('Admin')
                    <div class="col-sm-2">
                        <p>經銷商</p>
                        <select class="form-control show-tick myborder" id='dealerId' name='dealerId'>
                        <option value='0' >-全部-</option>
                        @foreach( $dealers as $dealer)
                        <option value="{{$dealer['id']}}"
                        @if( $dealer_id == $dealer['id'])
                        SELECTED
                        @endif
                        >{{$dealer['name']}}</option>
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
                            
                                <input type="text" class="form-control myborder align-center" placeholder="開始日期" id='orderSatrt' name='start' value="{{$dateStart}}">
                                        
                            </div>
                            
                            <span class="input-group-addon" style="padding-right:10px;" >~</span>
                                        
                                        
                            <div class="form-line" id='orderEndBox'>
                                            
                                <input type="text" class="form-control myborder align-center" placeholder="結束日期" id='orderEnd' name='end' value="{{$dateEnd}}">
                                        
                            </div>
                        </div>
                    </div>
                    <!-- /訂單時間選擇 -->
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <input type='submit' class='btn btn-primary waves-effect' value='查詢'>
                    </div>
                    
                    </form>  
                </div>
                <!-- /進階搜尋框 -->
                            
                <!-- 統計報表 -->
                <div class="row clearfix">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan='2' class='bg-grey'> 統計表 </th>
                            </tr>
                        </thead>
                        <tbody>

<!--                             <tr>
                                <td class='bg-grey' width='10%'> 進貨單數 </td>
                                <td> </td>
                                                               
                            </tr> -->
                            <tr>
                                <td class='bg-grey' width='10%'> 未銷售商品數量</td>
                                <td>  </td>
                            </tr>
<!--                             <tr>
                                <td class='bg-grey' width='10%'> 總金額</td>
                                <td> </td>
                            </tr> -->
<!--                            <tr>
                                <td class='bg-grey' width='10%'> 總批發金額</td>
                                <td> </td> 
                            </tr> -->

                        </tbody>

                    </table>
                    
                    @if( count($details) > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan='3' class='bg-grey'> 明細 </th>
                            </tr>
                            <tr>
                                <th>貨號</th>
                                <th>商品名稱</th>
                                <th>進貨量</th>
                            </tr>                            
                        </thead> 
                        <tbody>

                            @foreach( $details as $detailk => $detail)
                            <tr>
                                <td> {{ $detailk }} </td>
                                <td> {{ $detail['name'] }}</td>
                                <td> {{ $detail['num'] }}</td>
                            </tr>                            
                            @endforeach

                        </tbody>
                    </table>
                    @endif 

                </div>
                <!-- /統計報表 -->

                <!-- 統計圖表 -->
<!--                 <div class="row clearfix">
                    
                    <div class="col-md-8 col-sm-12 col-xs-12 " style="height:300px;border:1px solid #d3d3d3;">
                        <canvas id="perDayChart">
                        </canvas>  
                    </div>                    

                    <div class="col-md-4 col-sm-12 col-xs-12 " style="height:300px;border:1px solid #d3d3d3;">
                        <!-- <div style='width:100%;height:300px;position:relative;display:inline-block;margin-bottom:30px; border:1px solid gray;'>
                        <canvas id="shipPie" style='height:100%!important;width:100%;!important'>
                        </canvas>                            
                    </div>


                </div> -->
                <!--/統計圖表 -->

            </div>
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
<script src="{{asset('adminbsb-materialdesign/plugins/chartjs/Chart.bundle.js')}}"></script>


<script type="text/javascript">
$(function(){




$(".form-line").removeClass('focused');
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




/*----------------------------------------------------------------
 | 繪製有效訂單數比例圖
 |----------------------------------------------------------------
 |
 */
// var ctx = $("#shipPie");

// totalData = {
  
//   labels: ['未出貨訂單','已出貨訂單'] , 
//   datasets: [{
      
//       data: [],
      
//       backgroundColor: [
//           'rgba(255, 99, 132, 0.6)',
//           'rgba(54, 162, 235, 0.6)',
//           'rgba(255, 206, 86, 0.6)',
//           'rgba(75, 192, 192, 0.6)',
//           'rgba(153, 102, 255, 0.6)',
//           'rgba(255, 159, 64, 0.6)'
//       ],
//   }],
        
// };

// var myPieChart = new Chart(ctx, {
    
//     type: 'pie',
//     data: totalData,
//     options: { 
//         responsive: true ,
//         maintainAspectRatio: false,
//         title: {
//             display: true,
//             text: '訂單比例圖'
//         },
//         tooltips: {
//             callbacks: {
//                 label: function(tooltipItem, data) {
                  
//                 allData = data['datasets'][0]['data'];
//                 ctxAll = 0;
                    
//                 $.each(allData,function(aDk,aDv){
                    
//                     ctxAll += parseInt(aDv);
                
//                 })
//                 return data['labels'][tooltipItem['index']] + ': '+data['datasets'][0]['data'][tooltipItem['index']]+'筆' + ((data['datasets'][0]['data'][tooltipItem['index']]/ctxAll)*100).toFixed(2) + '%';
//                 }
//             }
//         }
//     }
  
// });




/*----------------------------------------------------------------
 | 繪製搜尋時間內每日訂單數
 |----------------------------------------------------------------
 |
 */
// var perDayCtx = $("#perDayChart"); 

// totalData = {
    
//     labels: [],      
//     datasets: 
//     [  
//       { fill:false,
//         label:'完成訂單',
//         borderColor:'rgba(255, 99, 132, 0.6)',
//         data: [],
//         backgroundColor: 'rgba(255, 99, 132, 0.6)'
//       },
      
//       { fill:false,
//         type: "line",
//         label:'本站訂單',
//         borderColor:'rgba(54, 162, 235, 0.6)',
//         data: [{$perOtherDateStr}],
//         backgroundColor: 'rgba(54, 162, 235, 0.6)'
//       },
//       { fill:false,
//         type: "line",
//         label:'平台訂單',
//         borderColor:'rgba(255, 206, 86, 0.6)',
//         data: [{$perOutDateStr}],
//         backgroundColor: 'rgba(255, 206, 86, 0.6)'
//       }
           
//     ],
    

// };

// var myBarChart = new Chart(perDayCtx, {
    
//     type: 'bar',
//     data: totalData,
//     options: { 
//         responsive: true ,
//         maintainAspectRatio: false,
//         title: {
//             display: true,
//             text: '每日訂單'
//         },
//         scales: {
//             yAxes: [{
//                 ticks: {
//                     min: 0,
//                     stepSize: 1
//                 }
//             }]
//         }        
//     }    
// }); 

})


</script>
<!-- /script -->
@endsection
