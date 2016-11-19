@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.serviceunavailable') }}
@endsection

@section('contentheader_title')
{{ trans('message.503error') }}
@endsection

@section('$contentheader_description')
@endsection

@section('main-content')

<div class="error-page">
    <h2 class="headline text-red">503</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-red"></i> Ups! {{ trans('message.somethingwrong') }}</h3>
        <p>
            }}
            {{ trans('message.mainwhile') }} 
            <a href='{{ url('/home') }}'>
                {{ trans('message.returndashboard') }}
            </a>
        </p>
    </div>
</div><!-- /.error-page -->
@endsection