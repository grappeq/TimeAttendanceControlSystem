@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.jobslist') }}
@endsection

@section('contentheader_title')
<i class='fa fa-user-md'></i>
{{ trans('message.jobslist') }}
@endsection

@section('contentheader_levels')
<li>{{ trans('message.jobs') }}</li>
<li class="active">{{ trans('message.list') }}</li>
@endsection

@section('footer_scripts')
<!-- DataTables -->
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
$(function () {
    $('.table').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "language": {
            "url": "{{ asset('/plugins/datatables/extensions/locales/pl.json') }}"
        }
    });
});
</script>
@endsection

@section('main-content')

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ trans('message.jobslist') }}</h3>

                <div class="box-tools">
                    <a class="btn btn-default" href="{{ url('jobs/new') }}"><i class="fa fa-user-plus"></i> {{ trans('message.jobnew') }}</a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('message.jobname') }}</th>
                            <th>{{ trans('message.wageperhour') }}</th>
                            <th>{{ trans('message.jobisactiveheader') }}</th>
                            <th style="width: 200px;">{{ trans('message.actions') }}</th>
                        </tr>     
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job) 
                        <tr>
                            <td>{{ $job->name }}</td>
                            <td>{{ $job->wagePerHour }}</td>
                            <td>
                                @if($job->isActive)
                                <span class="label label-success">{{ trans('message.jobactive') }}</span>
                                @else
                                <span class="label label-danger">{{ trans('message.jobnotactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('jobs/edit/'.$job->id) }}" class="btn btn-warning">
                                    <i class="fa fa-edit"></i> 
                                    {{ trans('message.edit') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection
