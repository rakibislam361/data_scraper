@extends('layouts.app')
@section('content')
<div class="row justify-content-center align-items-center" style="height: 30vh;">
  <div class="col-md-8">
    <div class="cart">
      <div class="card-body w-100">
        <form class="form-inline" action="{{ route('scrap-url.store') }}" method="POST">
          @csrf
          <input class="form-control mr-sm-2 w-75" name="url" type="text" placeholder="https://www.example.com" aria-label="url">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        @if ($errors->has('url'))
        <p class="text-danger">
          <small>{{ $errors->first('url') }}</small>
        </p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection