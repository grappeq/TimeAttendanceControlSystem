@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.jobedit') }}
@endsection

@section('contentheader_title')
<i class='fa fa-user-md'></i>
{{ trans('message.jobedit') }}
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

            <div class="form-group">
                <label>{{ trans('message.jobname') }}</label>
                <div class="input-group">
                    <span class="glyphicon glyphicon-font input-group-addon"></span>
                    <input type="text" class="form-control" placeholder="{{ trans('message.jobname') }}" name="name" value="{{ old('name', $job->name) }}" required="required"/>
                </div>
            </div>

            <div class="form-group">
                <label>{{ trans('message.wageperhour') }}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input type="number" min="0.01" step="0.01" class="form-control" 
                           placeholder="{{ trans('message.wageperhour') }}" name="wagePerHour" value="{{ old('wagePerHour', $job->wagePerHour) }}" required="required"/>
                </div>
            </div>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="isActive" 
                           @if((old('isActive', $job->isActive))) checked="checked" @endif /> {{ trans('message.jobactive') }}
                </label>
            </div>
            
            <h6>
                {{ trans('message.createdat').$job->created_at->format('d.m.Y - H:i:s') }}<br />
                {{ trans('message.updatedat').$job->updated_at->format('d.m.Y - H:i:s') }}
            </h6>
            
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a  class="btn btn-default" href="{{ url('jobs/list') }}">{{ trans('message.back') }}</a>
            <button type="submit" class="btn btn-info pull-right">{{ trans('message.save') }}</button>
        </div>
        <!-- /.box-footer -->
    </form>

</div>

@endsection
