@extends('layouts.app')
@section('content')
    <div class="row justify-content-center align-items-center mt-2" style="height: 30vh;">
        <div class="col-md-8">
            <a href="{{ asset('storage/scraped_data.json') }}" class="btn btn-outline-primary mb-2 ml-1 float-right"
                download>Fille
                Download</a>
            <a href="{{ route('scraped.file.download') }}" class="btn btn-outline-primary mb-2 ml-1 float-right">Images
                Download</a>
            <a href="{{ url('/') }}" class="btn btn-outline-danger mb-2 float-right">Back</a>
            <table class="table table-striped table-bordered">
                <thead class="thead-light">
                    <tr>
                        <td>SL</td>
                        <td>Image</td>
                        <td>Title</td>
                        <td>Author</td>
                        <td>price</td>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><img src="{{ $item['image'] ?? null }}" alt="{{ $item['title'] ?? null }}"
                                    style="height: 100px;"></td>
                            <td>{{ $item['title'] ?? null }}</td>
                            <td>{{ $item['author'] ?? null }}</td>
                            <td>{{ $item['price'] ?? null }}</td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
