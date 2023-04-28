<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="{{ url('/') }}">DATA SCRAPER</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item @if (request()->is('/')) active @endif">
                <a class="nav-link" href="{{ url('/') }}">Data Scrap</a>
            </li>
            <li class="nav-item @if (request()->is('json-data-form')) active @endif"">
                <a class=" nav-link" href="{{ route('json-data.form') }}">Json data</a>
            </li>
        </ul>
    </div>
</nav>