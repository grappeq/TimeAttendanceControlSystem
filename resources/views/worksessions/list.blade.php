@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.worksessionslist') }}
@endsection

@section('contentheader_title')
<i class='fa fa-users'></i>
{{ trans('message.worksessionslist') }}
@endsection

@section('contentheader_levels')
<li>{{ trans('message.worksessions') }}</li>
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
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "language": {
            "url": "{{ asset('/plugins/datatables/extensions/locales/pl.json') }}"
        },
        "order": [[ 2, "desc" ]]
    });
});
</script>
@endsection

@section('main-content')

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ trans('message.worksessions') }}</h3>

                <div class="box-tools">
                    <a class="btn btn-default" href="{{ url('work_sessions/new') }}">
                        <i class="fa fa-user-plus"></i> 
                        {{ trans('message.worksessionnew') }}
                    </a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('message.employee') }}</th>
                            <th>{{ trans('message.worksessionstart') }}</th>
                            <th>{{ trans('message.worksessionend') }}</th>
                            <th>{{ trans('message.job') }}</th>
                            <th>{{ trans('message.timeworked') }}</th>
                            <th>{{ trans('message.earnedamount') }}</th>
                            <th>{{ trans('message.paidheader') }}</th>
                            <th style="width: 200px;">{{ trans('message.actions') }}</th>
                        </tr>     
                    </thead>
                    <tbody>
                        @foreach ($workSessions as $workSession) 
                        <tr>
                            <td>
                                <a href='{{url('/employees/details/'.$workSession->employee->id)}}'>
                                    {{$workSession->employee->firstName}} {{$workSession->employee->lastName}}
                                </a>
                            </td>
                            <td>{{$workSession->startDatetime}}</td>
                            <td>{{$workSession->endDatetime}}</td>
                            <td>
                                {{$workSession->job->name.' ('
                                            .$workSession->wagePerHour.' '.
                                            trans('message.currencyperhour').')' }}
                            </td>
                            <td>{{$workSession->timeWorked > 0? $workSession->getTimeWorkedFormatted() : '' }}</td>
                            <td>{{$workSession->earnedAmount > 0? $workSession->earnedAmount: ''}}</td>
                            <td>
                                @if($workSession->paycheckId != 0)
                                <span class="label label-success">{{ trans('message.paid') }}</span>
                                @else
                                <span class="label label-danger">{{ trans('message.unpaid') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('work_sessions/edit/'.$workSession->id) }}" class="btn btn-warning">
                                    <i class="fa fa-edit"></i> 
                                    {{ trans('message.edit') }}
                                </a>
                                <a href="{{ url('work_sessions/delete/'.$workSession->id) }}" class="btn btn-danger">
                                    <i class="fa fa-trash-o"></i> 
                                    {{ trans('message.delete') }}
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
