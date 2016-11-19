@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.paycheckchoice') }}
@endsection

@section('contentheader_title')
<i class='fa fa-calendar'></i>
{{ trans('message.paycheckchoice') }}
@endsection

@section('footer_scripts')
<!-- date-range-picker -->
<script src="{{asset('/plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('/plugins/select2/select2.full.min.js')}}"></script>
<script>
$('#periodStartDatetime, #periodEndDatetime').daterangepicker({
    timePicker: true,
    timePickerIncrement: 10,
    format: 'YYYY-MM-DD HH:mm:00',
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

$("select[name='employeeId']").select2({
    ajax: {
        url: function (params) {
            var searchPhrase = "*";
            if(params.term != undefined) searchPhrase = params.term;
            return "{{ url('/employees/ajax/search/') }}/" + searchPhrase;        }
    }
});
</script>
@endsection

@section('header_styles')
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('/plugins/daterangepicker/daterangepicker-bs3.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('/plugins/select2/select2.min.css')}}">    
@endsection

@section('main-content')

<div class="alert alert-info">
    <h2>{{ trans('message.rememberexclamation') }}</h2>
    <ul>
        <li>{{ trans('message.paycheckrangechoiceinfo_1') }}</li>
        <li>{{ trans('message.paycheckrangechoiceinfo_2') }}</li>
    </ul>
</div>

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
                        <select class="form-control select2" 
                                name="employeeId"
                                style="width: 100%;"
                                required="required">
                            @if(!empty(old('employeeId')))
                            <option value="{{old('employeeId')}}">
                                {{App\Employee::find(old('employeeId'))->fullName}}
                            </option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('message.paycheckperiodstart') }}</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class='fa fa-clock-o'></i>
                            </span>
                            <input type="text" class="form-control" placeholder="{{ trans('message.paycheckperiodstart') }}" 
                                   name="periodStartDatetime" value="{{ old('periodStartDatetime', $defaultPeriodStart) }}" 
                                   id="periodStartDatetime" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label>{{ trans('message.paycheckperiodend') }}</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class='fa fa-clock-o'></i>
                            </span>
                            <input type="text" class="form-control" placeholder="{{ trans('message.paycheckperiodstart') }}" 
                                   name="periodEndDatetime" value="{{ old('periodEndDatetime', $defaultPeriodEnd) }}" 
                                   id="periodEndDatetime" required="required"/>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">{{ trans('message.forward') }}</button>
        </div>
        <!-- /.box-footer -->
    </form>

</div>

@endsection
