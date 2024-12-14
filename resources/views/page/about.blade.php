@extends('welcome')

@section('page')
<div class="container mt-4">
  <div class="row">
      <div class="col-12">
          <h1>About Us</h1>
          <div class="card">
              <div class="card-body">
                  <h5>Our Story</h5>
                  <p>
                      Panti Digital is a platform dedicated to supporting orphanages 
                      by providing a digital marketplace for their products and 
                      facilitating donations.
                  </p>
              </div>
          </div>

          <!-- Social Media Links -->
          <div class="text-center mt-4">
              <h3>Connect With Us</h3>
              <div class="social-icons">
                  <a href="#" class="btn btn-primary mx-2">
                      <i class="fab fa-facebook"></i> Facebook
                  </a>
                  <a href="#" class="btn btn-info mx-2">
                      <i class="fab fa-twitter"></i> Twitter
                  </a>
                  <a href="#" class="btn btn-danger mx-2">
                      <i class="fab fa-instagram"></i> Instagram
                  </a>
                  <a href="#" class="btn btn-dark mx-2">
                      <i class="fab fa-linkedin"></i> LinkedIn
                  </a>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection