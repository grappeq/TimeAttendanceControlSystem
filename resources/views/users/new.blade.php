@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.newuser') }}
@endsection

@section('contentheader_title')
<i class='fa fa-user-plus'></i>
{{ trans('message.newuser') }}
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
    <form action="{{ url('/users/new') }}" method="post" class="form-horizontal">
        <div class="box-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.fullname') }}</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-font input-group-addon"></span>
                        <input type="text" class="form-control" placeholder="{{ trans('message.fullname') }}" name="name" value="{{ old('name') }}"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.login') }}</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-user input-group-addon"></span>
                        <input type="text" class="form-control" placeholder="{{ trans('message.login') }}" name="username" value="{{ old('username') }}"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.password') }}</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-lock input-group-addon"></span>
                        <input type="password" class="form-control" placeholder="{{ trans('message.password') }}" name="password"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('message.retrypepassword') }}</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-log-in input-group-addon"></span>
                        <input type="password" class="form-control" placeholder="{{ trans('message.retrypepassword') }}" name="password_confirmation"/>
                    </div>
                </div>
            </div>



        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a  class="btn btn-default" href="{{ url('users/list') }}">{{ trans('message.cancel') }}</a>
            <button type="submit" class="btn btn-info pull-right">{{ trans('message.save') }}</button>
        </div>
        <!-- /.box-footer -->
    </form>

</div>

@endsection
