@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.home') }}
@endsection

@section('contentheader_title')
<i class='fa fa-home'></i>
{{ trans('message.home') }}
@endsection

@section('footer_scripts')
<!-- ChartJS 1.0.1 -->
<script src="{{url('/plugins/chartjs/Chart.min.js')}}"></script>
<script>
////-------------
////- LINE CHART -
////--------------
//var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
//var lineChart = new Chart(lineChartCanvas);
//var lineChartOptions = areaChartOptions;
//lineChartOptions.datasetFill = false;
//lineChart.Line(areaChartData, lineChartOptions);

//-------------
//- PIE CHART -
//-------------
// Get context with jQuery - using jQuery's .get() method.
var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
var pieChart = new Chart(pieChartCanvas);
        var PieData = {!!$currentSessionsJobsJSON!!};
var pieOptions = {
    onAnimationComplete: function ()
    {
        this.showTooltip(this.segments, true);
    },
    tooltipEvents: [],
    showTooltips: true,
//Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
//String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
//Number - The width of each segment stroke
    segmentStrokeWidth: 2,
//Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
//Number - Amount of animation steps
    animationSteps: 100,
//String - Animation easing effect
    animationEasing: "easeOutBounce",
//Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
//Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
//Boolean - whether to make the chart responsive to window resizing
    responsive: true,
// Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: true,
//String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
};
//Create pie or douhnut chart
// You can switch between pie and douhnut using the method below.
pieChart.Doughnut(PieData, pieOptions);
</script>
@endsection

@section('main-content')
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{$currentlyWorkingEmployeesNumber}}</h3>

                <p>{{ trans('message.currentlyworkingemployeesnumber') }}</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a href="{{ url('/employees/list') }}" class="small-box-footer">
                {{ trans('message.list') }}
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{$todayWorkedEmployeesNumber}}</h3>

                <p>{{ trans('message.todayworkedemployeesnumber') }}</p>
            </div>
            <div class="icon">
                <i class="fa fa-calendar"></i>
            </div>
            <a href="{{ url('/work_sessions/list') }}" class="small-box-footer">
                {{ trans('message.more') }}
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{$todayWorkedHours}} h</h3>

                <p>{{ trans('message.timeworkedtoday') }}</p>
            </div>
            <div class="icon">
                <i class="fa fa-clock-o"></i>
            </div>
            <a href="{{ url('/work_sessions/list') }}" class="small-box-footer">
                {{ trans('message.more') }}
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{$todayEarnedMoney}} {{trans('message.currency')}}</h3>

                <p>{{ trans('message.moneyearnedtoday') }}</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
            <a href="#" class="small-box-footer" style="height: 25px;">

            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">{{trans('message.currentemployeesjobs')}}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="pieChart" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

@endsection
