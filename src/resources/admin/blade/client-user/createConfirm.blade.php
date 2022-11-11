@extends('main')

@php $agency = Renderer::get('agency') @endphp

@section('title', Renderer::getPageTitle())

@section('CONTENTS')
    @include('include.msg.error-msg')

    <h3>Client create confirmation</h3>
    <p>Name: {{request()->input('name')}}</p>
    <p>Email: {{request()->input('email')}}</p>
    <p>Tel: {{request()->input('tel')}}</p>
    <p>Status: {{\App\Common\Database\Definition\AvailableStatus::getName(request()->input('is_available'))}}</p>
    <p>Agency: {{$agency->name}}</p>
    <form id="confirm-form" method="POST" action="{{ route('admin.client-user.store') }}">
        @csrf
        <input type="hidden" name="name" value="{{ request()->input('name') }}"></br>
        <input type="hidden" name="email" value="{{ request()->input('email') }}"></br>
        <input type="hidden" name="tel" value="{{ request()->input('tel') }}"></br>
        <input type="hidden" name="is_available" value="{{ request()->input('is_available') }}"></br>
        <input type="hidden" name="agency_id" value="{{ request()->input('agency_id') }}"></br>
        
        <input type="button" value="back" data-post-url="{{ route('admin.client-user.create') }}">
        <input type="submit" value="submit">
    </form>
@stop

@section('JAVASCRIPT')
    <script>
        $("input[type=button]").on('click', function(e) {
            e.preventDefault();

            let form = $('#confirm-form');
            form.attr('action', $(this).data('post-url'));
            form.submit();
        });
    </script>
@stop
