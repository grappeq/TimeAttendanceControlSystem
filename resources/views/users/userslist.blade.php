@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.userslist') }}
@endsection

@section('contentheader_title')
<i class='fa fa-male'></i>
{{ trans('message.userslist') }}
@endsection



@section('main-content')
@if (Session::has('message'))
   <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ trans('message.userslist') }}</h3>

                <div class="box-tools">
                    <a class="btn btn-default" href="{{ url('users/new') }}"><i class="fa fa-male"></i> {{ trans('message.newuser') }}</a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>{{ trans('message.login') }}</th>
                        <th>{{ trans('message.fullname') }}</th>
                        <th>{{ trans('message.createdat') }}</th>
                    </tr>
                    @foreach ($users as $user) 
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection
