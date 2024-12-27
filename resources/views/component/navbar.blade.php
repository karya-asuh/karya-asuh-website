<nav class="col-12 navbar navbar-expand-lg navbar-light bg-light bg-gradient">
  <a class="navbar-brand mx-5" href="#">Karya Asuh</a>
  <button class="navbar-toggler mx-5" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse mx-5" id="navbarNav">
    <ul class="navbar-nav ms-auto gap-3">
      <li class="nav-item {{ request()->is('/') ? 'active' : '' }} ml-3">
        <a class="nav-link" href="/">Home</a>
      </li>
      <li class="nav-item {{ request()->is('catalog') ? 'active' : '' }} ml-3">
        <a class="nav-link" href="/catalog">Catalog</a>
      </li>
      <li class="nav-item {{ request()->is('panti') ? 'active' : '' }} ml-3">
        <a class="nav-link" href="/panti">Panti</a>
      </li>
      <li class="nav-item {{ request()->is('about') ? 'active' : '' }} ml-3">
        <a class="nav-link" href="/about">About</a>
      </li>
      <li class="nav-item {{ request()->is('profile') ? 'active' : '' }} ml-3">
        <a class="nav-link" href="/profile">{{ Auth::user()->name }}</a>
      </li>
    </ul>
  </div>
  </div>
</nav>