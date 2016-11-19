@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.paychecksummary') }}
@endsection

@section('contentheader_title')
<i class='fa fa-money'></i>
{{ trans('message.paychecksummary') }}
@endsection

@section('contentheader_levels')
<li>{{ trans('message.paychecks') }}</li>
<li class="active">{{ trans('message.paychecksummary') }}</li>
@endsection

@section('footer_scripts')
@endsection

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <form action="" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="box-header">
                    <h3 class="box-title">{{ trans('message.paycheckchosenrange') }}</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('message.employee') }}</label>
                                <div class="input-group">
                                    <a href="{{ url('/employees/details/'.$employee->id) }}">
                                        {{ $employee->fullName }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('message.paycheckperiodstart') }}</label>
                                <div class="input-group">
                                    {{ $startPeriod }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('message.paycheckperiodend') }}</label>
                                <div class="input-group">
                                    {{ $endPeriod }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a  class="btn btn-default" href="{{ url('paychecks/new/range_choice') }}">{{ trans('message.change') }}</a>
                    <button type="submit" class="btn btn-info pull-right">{{ trans('message.savepaycheck') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ trans('message.earningsdays') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table">
                    <tr>
                        <th style="width: 90px;">{{ trans('message.date') }}</th>
                        <th>{{ trans('message.earnedamountnocurrency') }}</th>
                        <th>{{ trans('message.timeworked') }}</th>
                        <th style="width: 50px;"></th>
                    </tr>
                    <tr class="bg-gray-light">
                        <td>{{ trans('message.avg') }}</td>
                        <td>{{ $avgEarnedAmount }}</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-striped" style="width: {{ $avgTimePercentage }}%"></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-blue">{{ $avgTimeWorked }}</span>
                        </td>                    </tr>
                    @foreach ($paycheckDataByDates as $date => $dateInfo)
                    <tr>
                        <td>
                            <a href="#">
                                {{ $date }}
                            </a>
                        </td>
                        <td>{{ number_format($dateInfo['earnedAmount'],2) }} {{ trans('message.currency') }}</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-success" style="width: {{ $dateInfo['timeWorkedPercentage'] }}%"></div>
                            </div>
                        </td>
                        <td>
                            <a href="#">
                                <span class="badge bg-success">{{ $dateInfo['timeWorkedPretty'] }}</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-gray"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Łączny zarobek</span>
                <span class="info-box-number">{{ $totalEarnedAmount }} zł</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-gray"><i class="fa fa-clock-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Łączny przepracowany czas</span>
                <span class="info-box-number">{{ $totalTimeWorked }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
@endsection
