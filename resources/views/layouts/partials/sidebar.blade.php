<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{Auth::user()->getAvatar()}}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('adminlte_lang::message.online') }}</a>
            </div>
        </div>
        @endif

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('message.header') }}</li>
            <!-- Optionally, you can add icons to the links -->
            <li {!! (Request::is('home') ? 'class="active"' : '') !!}>
                <a href="{{ url('home') }}">
                    <i class='fa fa-home'></i> <span>{{ trans('message.home') }}</span>
                </a>
            </li>
            <li {!! (Request::is('work_sessions/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/work_sessions/list') }}">
                    <i class='fa fa-clock-o'></i> <span>{{ trans('message.worksessions') }}</span>
                </a>
            </li>
            <li {!! (Request::is('employees/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/employees/list') }}">
                    <i class='fa fa-users'></i> <span>{{ trans('message.employees') }}</span>
                </a>
            </li>
            <li {!! (Request::is('jobs/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/jobs/list') }}">
                    <i class='fa fa-user-md'></i> <span>{{ trans('message.jobs') }}</span>
                </a>
            </li>
            <li {!! (Request::is('users/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/users/list') }}">
                    <i class='fa fa-male'></i> <span>{{ trans('message.users') }}</span>
                </a>
            </li>
            <li class="treeview{!! (Request::is('paychecks/*') ? ' active' : '') !!}">
                <a href="#">
                    <i class="fa fa-dollar"></i> <span>{{ trans('message.paychecksmenu') }}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li {!! (Request::is('paychecks/new/*') ? 'class="active"' : '') !!}>
                        <a href="{{ url('/paychecks/new/range_choice') }}">
                            <i class="fa fa-money"></i>
                            <span>{{ trans('message.paychecknew') }}</span>
                        </a>
                    </li>
                    <li {!! (Request::is('paychecks/list') ? 'class="active"' : '') !!}>
                        <a href="{{ url('/paychecks/list') }}">
                            <i class='fa fa-calendar'></i>
                            <span>{{ trans('message.paycheckhistory') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
