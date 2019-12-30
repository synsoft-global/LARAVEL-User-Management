@extends('layouts.app')
@section('content')
<div class="page-content">  
<div class="container-fluid container-fh">
  <div class="main-container container-fh__content p-contact">
    <form action="#" class="p-contact__form">
      <h3 class="p-contact__heading">Add User</h3>
      <h4 class="p-contact__form-heading">Lets Start a Project!</h4>

      <div class="form-group">
        <label for="contact-name">Name</label>
        <input type="text" class="form-control" id="user_name" name="user_name">
      </div>
      <div class="form-group">
        <label for="contact-email">Email address</label>
        <input type="email" class="form-control" id="user_email" name="user_email">
      </div>
      <div class="form-group">
        <label for="contact-subject">Subject</label>
        <input type="text" class="form-control" id="user_subject" name="user_subject">
      </div>
      <div class="form-group">
        <label for="contact-message">Message</label>
        <textarea class="form-control" id="user_message" name="user_message" rows="4"></textarea>
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-info p-contact__form-submit">Send Now</button>
      </div>
    </form>

  </div>
</div>
</div>
@endsection
