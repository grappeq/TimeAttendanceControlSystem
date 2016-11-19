@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.employeeedit') }}
@endsection

@section('contentheader_title')
<i class='fa fa-user-plus'></i>
{{ trans('message.employeeedit') }}
@endsection

@section('header_styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('/plugins/select2/select2.min.css')}}">    
@endsection

@section('footer_scripts')
<!-- Select2 -->
<script src="{{asset('/plugins/select2/select2.full.min.js')}}"></script>
<script>
$(".select2").select2();
</script>
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
    <form action="{{ url('employees/edit/'.$employee->id) }}" method="post" class="form-horizontal">
        <div class="box-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.id') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="{{ trans('message.id') }}" value="{{ $employee->id }}" disabled="disabled"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.firstname') }}</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-font input-group-addon"></span>
                        <input type="text" class="form-control" placeholder="{{ trans('message.firstname') }}" name="firstName" value="{{ old('firstName', $employee->firstName) }}" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.lastname') }}</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-font input-group-addon"></span>
                        <input type="text" class="form-control" placeholder="{{ trans('message.lastname') }}" name="lastName" value="{{ old('lastName', $employee->lastName) }}" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.pesel') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="{{ trans('message.pesel') }}" name="PESEL" value="{{ old('PESEL', $employee->PESEL) }}" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">{{ trans('message.job') }}</label>
                <div class="col-md-10">
                    {{ Form::select(
                                'jobId',
                                App\Job::where('isActive', 1)->lists('name', 'id'),
                                old('jobId', $employee->job->id),
                                ['class' => 'form-control select2']
                                )}}
                </div>
            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a  class="btn btn-default" href="#" onClick="window.history.go(-1); return false;">{{ trans('message.back') }}</a>
            <button type="submit" class="btn btn-info pull-right">{{ trans('message.save') }}</button>
        </div>
        <!-- /.box-footer -->
    </form>

</div>

@endsection
