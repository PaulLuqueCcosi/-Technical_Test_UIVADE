<!-- resources/views/partials/header.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Mi Aplicación</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Módulo Básico</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('ventas') ? 'active' : '' }}" href="{{ url('/ventas') }}">Módulo de Ventas</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
