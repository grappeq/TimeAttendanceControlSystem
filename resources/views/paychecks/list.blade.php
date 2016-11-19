@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.paycheckhistory') }}
@endsection

@section('contentheader_title')
<i class='fa fa-calendar'></i>
{{ trans('message.paycheckhistory') }}
@endsection

@section('contentheader_levels')
<li>{{ trans('message.paychecks') }}</li>
<li class="active">{{ trans('message.list') }}</li>
@endsection

@section('footer_scripts')
<!-- DataTables -->
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
$(function () {
    $('.table').DataTable({
        "order": [[ 3, "desc" ]],
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
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
                <h3 class="box-title">{{ trans('message.paycheckhistory') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('message.employee') }}</th>
                            <th>{{ trans('message.timerange') }}</th>
                            <th>{{ trans('message.timeworked') }}</th>
                            <th>{{ trans('message.amount') }}</th>
                            <th>{{ trans('message.personresponsible') }}</th>
                            <th style="width: 300px;">{{ trans('message.actions') }}</th>
                        </tr>     
                    </thead>
                    <tbody>
                        @foreach ($paychecks as $paycheck) 
                        <tr>
                            <td>
                                <a href="{{ url('/employees/details/').$paycheck->employee->id }}">
                                {{ $paycheck->employee->firstName.' '.$paycheck->employee->lastName }}
                                </a>
                            </td>
                            <td>{{ $paycheck->periodStartDatetime }} - {{ $paycheck->periodEndDatetime }}</td>
                            <td>{{ $paycheck->getTimeWorkedFormatted() }}</td>
                            <td>{{ $paycheck->earnings }}</td>
                            <td>{{ $paycheck->user->name }}</td>
                            <td>
                                <a href="{{ url('paychecks/delete/'.$paycheck->id) }}" class="btn btn-danger">
                                    <i class="fa fa-undo"></i> 
                                    {{ trans('message.undo') }}
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
