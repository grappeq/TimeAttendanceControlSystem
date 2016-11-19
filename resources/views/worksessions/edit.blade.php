@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.worksessionedit') }}
@endsection

@section('contentheader_title')
<i class='fa fa-clock-o'></i>
{{ trans('message.worksessionedit') }}
@endsection

@section('footer_scripts')
<!-- date-range-picker -->
<script src="{{asset('/plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
$('#startDatetime, #endDatetime').daterangepicker({
    timePicker: true,
    timePickerIncrement: 10,
    format: 'YYYY-MM-DD hh:mm:00',
    singleDatePicker: true,
    timePicker12Hour: false,
    "locale": {
        "applyLabel": "Wybierz",
        "cancelLabel": "Anuluj",
        "daysOfWeek": [
            "Nd",
            "Pn",
            "Wt",
            "Śr",
            "Cz",
            "Pt",
            "So"
        ],
        "monthNames": [
            "Styczeń",
            "Luty",
            "Marzec",
            "Kwiecień",
            "Maj",
            "Czerwiec",
            "Lipiec",
            "Sierpień",
            "Wrzesień",
            "Październik",
            "Listopad",
            "Grudzień"
        ],
        "firstDay": 1
    }
});
</script>
@endsection

@section('header_styles')
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('/plugins/daterangepicker/daterangepicker-bs3.css')}}">
@endsection

@section('main-content')
@if (count($errors) > 0)
<div class="alert alert-danger">
    {{ trans('message.someproblems') }}<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="box">
    <!--    <div class="box-header with-border">
            <h3 class="box-title">Horizontal Form</h3>
        </div>-->
    <!-- /.box-header -->
    <!-- form start -->
    <form action="" method="post" role="form">
        <div class="box-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('message.employee') }}</label>
                        <div class="input-group">
                            <a href="{{ url('/employees/details/'.$workSession->employee->id) }}">
                                {{ $workSession->employee->fullName }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('message.job') }}</label>
                        <div class="input-group">
                            {{ $workSession->job->name }} ( {{ $workSession->job->wagePerHour.trans('message.currencyperhour') }} )
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('message.worksessionstart') }}</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class='fa fa-clock-o'></i>
                            </span>
                            <input type="text" class="form-control" placeholder="{{ trans('message.worksessionstart') }}" 
                                   name="startDatetime" value="{{ old('startDatetime', $workSession->startDatetime) }}" 
                                   id="startDatetime" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label>{{ trans('message.worksessionend') }}</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class='fa fa-clock-o'></i>
                            </span>
                            <input type="text" class="form-control" placeholder="{{ trans('message.worksessionend') }}" 
                                   name="endDatetime" value="{{ old('endDatetime', $workSession->endDatetime) }}" 
                                   id="endDatetime" required="required"/>
                        </div>
                    </div>
                </div>
            </div>

            <h6>
                {{ trans('message.createdat').$workSession->created_at->format('d.m.Y - H:i:s') }}<br />
                {{ trans('message.updatedat').$workSession->updated_at->format('d.m.Y - H:i:s') }}
            </h6>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a  class="btn btn-default" href="{{ url('work_sessions/list') }}">{{ trans('message.back') }}</a>
            <button type="submit" class="btn btn-info pull-right">{{ trans('message.save') }}</button>
        </div>
        <!-- /.box-footer -->
    </form>

</div>

@endsection
