@extends('main')

@section('CONTENTS')
    @include('include.error-msg')

    <h3>Customer edit confirmation</h3>
    <p>Name: {{request()->input('name')}}</p>
    <p>Tel: {{request()->input('tel')}}</p>
    <p>Email: {{request()->input('email')}}</p>
    <p>Birthday: {{request()->input('birthday')}}</p>
    <p>Address: {{request()->input('address')}}</p>
    <form id="confirm-form" method="POST" action="{{ route('admin.customerUser.update', request()->input('id')) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ request()->input('id') }}">
        <input type="hidden" name="name" value="{{ request()->input('name') }}"></br>
        <input type="hidden" name="email" value="{{ request()->input('email') }}"></br>
        <input type="hidden" name="tel" value="{{ request()->input('tel') }}"></br>
        <input type="hidden" name="birthday" value="{{ request()->input('birthday') }}"></br>
        <input type="hidden" name="address" value="{{ request()->input('address') }}"></br>
        <input type="button" value="back" data-post-url="{{ route('admin.customerUser.edit', request()->input('id')) }}">
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