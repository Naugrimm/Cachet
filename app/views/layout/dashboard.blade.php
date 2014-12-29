<!DOCTYPE html>
<html>
@include('layout._head')

<body class="dashboard">
    @include('partials.dashboard.nav')
    <div class="wrapper">
        @include('partials.dashboard.sidebar')
        <div class="content">
        @yield('content')
        </div>
    </div>
</body>
</html>
