@extends('layouts.app')
@section('content')
<div class="row justify-content-center align-items-center" style="height: 30vh;">
  <div class="col-md-8">
    <div class="cart">
      <div class="card-body w-100">
        <form class="form-inline" action="{{ route('json-data.sorting') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input class="form-control mr-sm-2 w-75 pl-0 pt-1" name="json-file" type="file">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        @if ($errors->has('json-file'))
        <p class="text-danger">
          <small>{{ $errors->first('json-file') }}</small>
        </p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection