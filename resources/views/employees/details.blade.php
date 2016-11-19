@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.employeedetails') }}
@endsection

@section('contentheader_title')
<i class='fa fa-user-plus'></i>
{{ trans('message.employeedetails') }}
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
    <form class="form-horizontal">
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
                        <input type="text" class="form-control" placeholder="{{ trans('message.firstname') }}" name="firstName" value="{{ $employee->firstName }}" disabled="disabled"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.lastname') }}</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-font input-group-addon"></span>
                        <input type="text" class="form-control" placeholder="{{ trans('message.lastname') }}" name="lastName" value="{{ $employee->lastName }}" disabled="disabled"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.pesel') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="{{ trans('message.pesel') }}" name="PESEL" value="{{ $employee->PESEL }}" disabled="disabled"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.job') }}</label>
                <div class="col-sm-10">
                    {{ Form::select(
                                'jobId',
                                App\Job::where('isActive', 1)->lists('name', 'id'),
                                $employee->job->id,
                                ['class' => 'form-control', 'disabled' => 'disabled']
                                )}}
                </div>
            </div>

            <h6>
                {{ trans('message.createdat').$employee->created_at->format('d.m.Y - H:i:s') }}<br />
                {{ trans('message.updatedat').$employee->updated_at->format('d.m.Y - H:i:s') }}
            </h6>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a  class="btn btn-default"  href="#" onClick="window.history.go(-1); return false;">{{ trans('message.back') }}</a>
            <a  class="btn btn-info pull-right" href="{{ url('employees/edit/'.$employee->id) }}">{{ trans('message.edit') }}</a>
        </div>
        <!-- /.box-footer -->
    </form>

</div>

@endsection
