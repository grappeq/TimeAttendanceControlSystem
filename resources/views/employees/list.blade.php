@extends('layouts.app')

@section('htmlheader_title')
{{ trans('message.employeelist') }}
@endsection

@section('contentheader_title')
<i class='fa fa-users'></i>
{{ trans('message.employeelist') }}
@endsection

@section('contentheader_levels')
<li>{{ trans('message.employees') }}</li>
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
                <h3 class="box-title">{{ trans('message.employeelist') }}</h3>

                <div class="box-tools">
                    <a class="btn btn-default" href="{{ url('employees/new') }}">
                        <i class="fa fa-user-plus"></i> 
                        {{ trans('message.newemployee') }}
                    </a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('message.id') }}</th>
                            <th>{{ trans('message.fullname') }}</th>
                            <th>{{ trans('message.job') }}</th>
                            <th>{{ trans('message.loggedheader') }}</th>
                            <th style="width: 300px;">{{ trans('message.actions') }}</th>
                        </tr>     
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee) 
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->firstName.' '.$employee->lastName }}</td>
                            <td>{{ $employee->job->name.' ('.
                                        $employee->job->wagePerHour.' '.trans('message.currencyperhour').')' }}</td>
                            <td>
                                @if($employee->isLogged())
                                <span class="label label-success">{{ trans('message.logged') }}</span>
                                @else
                                <span class="label label-danger">{{ trans('message.notlogged') }}</span>
                                @endif
                            </td>
                            <td>

                                @if(!$employee->isLogged())
                                <a href="{{ url('employees/list?action=login&employeeId='.$employee->id) }}" class="btn btn-success">
                                    <i class="fa fa-hourglass-start"></i> 
                                    {{ trans('message.buttonsign') }}
                                </a>
                                @else
                                <a href="{{ url('employees/list?action=logout&employeeId='.$employee->id) }}" class="btn btn-danger">
                                    <i class="fa fa-hourglass-end"></i> 
                                    {{ trans('message.signout') }}
                                </a>                                @endif
                                <a href="{{ url('employees/details/'.$employee->id) }}" class="btn btn-primary">
                                    <i class="fa fa-search"></i> 
                                    {{ trans('message.showdetails') }}
                                </a>
                                <a href="{{ url('employees/edit/'.$employee->id) }}" class="btn btn-warning">
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
