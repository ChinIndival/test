@extends('main')

@php 
    $customerUser = Renderer::get('customerUser');
@endphp

@section('CONTENTS')
    @include('include.msg.status-msg')
    <h3>Client show</h3>
    <p>Id: {{ $customerUser->id }}</p>
    <p>Name: {{ $customerUser->getName() }}</p>
    <p>Tel: {{ $customerUser->getTel() }}</p>
    <p>Email: {{ $customerUser->email }}</p>
    <p>Address: {{ $customerUser->getAddress() }}</p>
    <p>Birthday: {{ $customerUser->getBirthday() }}</p>
    <p>Gender: {{ $customerUser->getGender() }}</p>
    <a href="{{ route('admin.customerUser.edit', $customerUser->id) }}">Edit</a>
    <a href="{{ route('admin.customerUser.index', $customerUser->id) }}">Home</a>
@stop
