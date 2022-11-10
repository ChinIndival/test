@extends('main')

@section('CONTENTS')
    @include('include.msg.status-msg')

    <div class="page-title">
        <h3>
            Agency List
        </h3>
    </div>
    <div class="search-form card">
        <div class="card-header">Search</div>
        <div class="card-body">
            <form class="mb-2" method="GET" action="{{ route('admin.agency.index') }}">
                <div class="row g-2">
                    <div class="mb-3 col-md-6">
                        <div class="d-flex">
                            <label for="inputPassword fw-bold" class="col-form-label fw-bold">ID: </label>
                            <div class="flex-fill">
                                <input type="text" name="id" value="{{ Renderer::oldWithRequest('id') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <div class="d-flex">
                            <label for="inputPassword fw-bold" class="col-form-label ps-3 fw-bold">Name: </label>
                            <div class="flex-fill">
                                <input type="text" name="name" value="{{ Renderer::oldWithRequest('name') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <div class="d-flex">
                            <label for="inputPassword fw-bold" class="col-form-label fw-bold">Tel: </label>
                            <div class="flex-fill">
                                <input type="text" name="tel" class="form-control" value="{{ Renderer::oldWithRequest('tel') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <div class="d-flex">
                            <label for="inputPassword fw-bold" class="col-form-label ps-3 fw-bold">Address: </label>
                            <div class="flex-fill">
                                <input type="text" name="address" value="{{ Renderer::oldWithRequest('address') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </div>  
                </div>
            </form>
        </div>
        
    </div>
    <div class="card mt-5">
        <div class="card-header">
            <a href="{{ route('admin.agency.create') }}" class="btn btn-outline-primary float-end"><i class="fas fa-plus-circle"></i> Add</a>
        </div>
        <div class="card-body">
            {!! Renderer::renderPaginator('include.pager') !!}
            <div class="table-responsive"><table width="100%" class="table table-striped" id="dataTables-example">
                <thead>
                    <tr>
                        <th scope="col" width="150">Id</th>
                        <th scope="col" width="150">Name</th>
                        <th scope="col" width="150">Tel</th>
                        <th scope="col" width="150">Address</th>
                    </tr>
                </thead>
                <tbody>
                @forelse(Renderer::getPaginator() ?? [] as $val)
                    <tr>
                        <td><a href="{{ route('admin.agency.show', $val->id) }}" class="text-link">{{ $val->id }}</a></td>
                        <td>{{ $val->name }}</td>
                        <td>{{ $val->tel }}</td>
                        <td>{{ $val->address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="4"><p>No results </p></td>
                    </tr>
                @endforelse
                </tbody>
            </table></div>
            
            {!! Renderer::renderPaginator('include.pager') !!}
        </div>
    </div>
@stop
