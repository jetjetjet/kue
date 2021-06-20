@extends('Layout.index')

@section('css-body')
  <link href="{{ url('/') }}/assets/css/pages/privacy/privacy.css" rel="stylesheet" type="text/css" />
  <link href="{{ url('/') }}/assets/css/apps/notes.css" rel="stylesheet" type="text/css" />
  <style>
    .imgrespo {
    height: 300px;
    width: 55vw;
    max-width: 755px;
    }
  </style>
@endsection

@section('content-breadcumb')
  <div class="page-header">
    <nav class="breadcrumb-one" aria-label="breadcrumb">
      @yield('breadcumb')
    </nav>
  </div>
@endsection

@section('content-body')
  @yield('content-table')
@endsection


@section('js-body')

  <script src="{{ url('/') }}/assets/js/apps/notes.js"></script>
  @yield('js-table')
@endsection