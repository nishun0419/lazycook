@extends('layouts.app')

@section('content')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">
<nav-component></nav-component>
<transition mode="out-in">
<router-view></router-view>
</transition>
@endsection