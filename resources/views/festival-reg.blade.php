@extends('layouts.master')
@section('content')                    
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="products-box d-flex justify-content-center align-items-center my-3">
                <a href=""><img alt="" src="{{asset('')}}assets/img/panta-ilish.jpg" class="img-fluid rounded"></a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center my-2">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              Registration Form
            </div>
            <div class="card-body">
              @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
              <form method="post" action="{{ route('festival_regs.store') }}">
                @csrf
                {{-- <div class="form-group">
                  <label for="fullName">Full Name</label>
                  <input type="text" class="form-control" name="fullName" placeholder="Enter your full name">
                </div> --}}
                <div class="form-group">
                  <label for="mobileNumber">Mobile Number</label>
                  <input type="tel" class="form-control" name="mobile" placeholder="Enter your mobile number">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" name="email" placeholder="Enter your email address">
                </div>
                <div class="form-group">
                  <label for="ticketNumber">Ticket Number</label>
                  <input type="text" class="form-control" name="ticket_number" placeholder="Enter your ticket number">
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
              </form>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection