@extends('layouts.app')
<style type="text/css">
  
.main-container {
    margin:0 !important;
    width: 100% !important;
    padding: 10px;
}

</style>
@section('content')



<div class="page-content page-loading" id="page_loading" style="display: none;">

<!--start header row -->
<div class="row top-subnav navbar-fixed-top"> 
<div class="col-sm-12 col-md-4 subnav-left "> 
   <ul class="nav nav-tabs subnav__nav" role="tablist">
    <li class="nav-item subnav__nav-item">
      <a class="nav-link subnav__nav-link {{(Request::segment(1)=='users')?'active':''}}"  href="{{url('users')}}">Users</a>
    </li>     
  </ul>
</div>
<div class="col-sm-12 col-md-8 float-left subnav-right for-custom-grid-dates">
<div class="subnav-one text-right "> </div>

<div class="subnav-two cst-subnav-two">  
        
</div>

<div class="subnav-three cst-subnav-three">  
    
</div>

</div>
</div >

<!--end header row -->




<!--START WIDGETS SECTION-->

<!-- <div class="container-fluid stats-widgets-sections">
  <div class="customer-result"> 
    <div class="d-flex justify-content-center my-3 width100 pull-left">
      <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>  
  </div>
</div> -->

<!--end WIDGETS SECTION-->




<!-- end hr tag diversion--->

<!-- start rxport button diversion--->
<div id="customer_export_grid"></div>
<div class="container-fluid export-search-section">
  <div class="row">
    <div class="col-sm-12 col-md-7"> 
      <?php 
        $btn_permission = checkPermission(Auth::user()->role->slug, 'action_btn'); 
        
        if(empty(in_array('add_user', $btn_permission))){
      ?>
      <button class="btn btn-info mb-2 mr-3 pull-left" type="button" id="add_btn">Add User</button>      
    <?php } ?>
      <div class="form-group customer-srch-dw 123">
        <input id="search_user" type="text" placeholder="Search Users" class="form-control" value="{{!empty(session('whereValUsers'))?session('whereValUsers'):''}}">
      </div>
    </div>

    <div class="col-sm-12 col-md-5 text-right pull-right">
      <div class="mobile-show-btn-grid">
        <div class="btn-group btn-collection btn-icon-group">
          <button class="btn btn-outline-info customer_grid_view" type="button"><span class="btn-icon ua-icon-grid"></span></button>
          <button class="btn btn-outline-info customer_list_view" type="button"><span class="btn-icon ua-icon-list"></span></button>
        </div>
      </div>
      <!--end mobile-show-btn-grid -->

      <div class="orderby-label">{{ __('messages.customers.Order_By') }}</div>       

      <span class="pull-right filter-icons">
        <?php 
           
            $up = 'fa-arrow-down text-danger';
            $value = 'desc';

            if(!empty(session('orderbyUser') && session('orderbyUser') == "asc")){
             
               $up ='fa-arrow-up text-primary';
             $value = 'asc';

            }
          ?>
        <i class="fa {{$up}}" aria-hidden="true" id="up" value={{$value}}></i>         
      </span>
      
      <div class="form-group orderby-select customer-orderby-select">
        <select class="form-control select2-hidden-accessible" data-placeholder=" {{ __('messages.customers.Order_By') }} "  id="orderby">
         <option value="users.created_at" {{!empty(session('orderbyValUser') && session('orderbyValUser') == "users.created_at")?'selected':''}}> {{ __('messages.customers.Join_Date') }} </option>
          <option value="name" {{!empty(session('orderbyValUser') && session('orderbyValUser') == "name")?'selected':''}}> Name </option>
          <option value="email" {{!empty(session('orderbyValUser') && session('orderbyValUser') == "email")?'selected':''}}> Email </option>
          <option value="title" {{!empty(session('orderbyValUser') && session('orderbyValUser') == "title")?'selected':''}}> Role </option>          
        </select>
      </div>
    </div>
  </div>
</div>

<!-- end rxport button diversion--->


    
<div class="container-fluid"> 
  <div class="main-container order-custom-container">       
    <div class="row">
      <div class="col-sm-12 col-md-12 userTable-section" id="customerTable">
         <div class="d-flex justify-content-center my-3 width100 pull-left">
            <div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
       </div>  
    </div>
  </div> 
</div>
<!--end container-fluid-->

<!-- Modal for export -->
<div id="modal-add_user" class="modal fade custom-modal-tabs  custom-modal show" data-backdrop="static" aria-modal="true">
@include('users.ajax.addUserModal')
</div>

<!-- Modal for change password -->
<div id="modal-chage_password" class="modal fade custom-modal-tabs custom-modal show" data-backdrop="static" aria-modal="true">              
  <!-- Modal body -->
  <div class="modal-dialog ausrmdle" role="document">
    <div class="modal-content">
      <div class="modal-header has-border custom-modal__image">  
        <img class="" src="https://cdn0.iconfinder.com/data/icons/business-startup-10/50/76-512.png" alt="">           
        <button type="button" class="close custom-modal__close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="ua-icon-modal-close"></span>
        </button>
      </div>
      <form id="change_password" name="change_password" class="el-form el-form--label-top">
        <div class="modal-body">   
          <h4 class="custom-modal__body-heading mb-3 add_update_user" >{{__("messages.users.change_password")}}</h4>
          @csrf
          <div class="row mb-4">
            <div class="col-md-6">
              <input data-original="" value="" type="hidden" autocomplete="off" name="change_pass_user_id" id="change_pass_user_id" class="el-input__inner form-control"/>  
              <label for="password" class="el-form-item__label"><strong>{{__("messages.users.new_password")}}</strong></label>           
              <div class="mr-3">
                <input data-original="" value="" type="password" autocomplete="off" name="password" id="password" class="el-input__inner form-control"/>  
              </div>   
            </div> 
            <div class="col-md-6">
              <label for="user_name" class="el-form-item__label"><strong>{{__("messages.users.confirm_password")}}</strong></label>           
              <div class="mr-3">
                <input data-original="" value="" type="password" autocomplete="off" name="con_password" id="con_password" class="el-input__inner form-control"/>  
              </div>   
            </div>                           
          </div>                        
        </div>
        <div class="modal-footer pb-0"> 
          <div class="custom-modal__buttons">
            <button type="button" class="btn btn-outline-primary" data-dismiss="modal" aria-label="Close">
             <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-success" id="submit_cp_btn">
              <span>Save</span>
            </button>
          </div>
        </div>
      </form> 
    </div>
  </div>
</div>  

<!-- Modal for delete segment -->
<div id="modal-delete-user" class="modal fade custom-modal custom-modal-tabs show " aria-modal="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header custom-modal__image">   
           <img class="deliveryicon mt-0" src="{!! asset('images/Error.png') !!}" alt="" style="display: block;z-index:1">             
              <button type="button" class="close custom-modal__close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="ua-icon-modal-close"></span>
              </button>
          </div>
          <div class="modal-body text-center">
             
              <div class="order-delivery-content">
                  <h2 class="order-delivery-title">{{__("messages.customer_detail.Are you sure")}}</h2>
              </div>
          </div>
          <div class="modal-footer" style="border:none">
             <div class="custom-modal__buttons">
              <button type="button" class="btn btn-danger" data-id=""  id="delete_user_btn">{{__("messages.customer_detail.Confirm")}}</button>
              <button type="button" class="btn btn-secondary " data-dismiss="modal" aria-label="Close">
                  {{__("messages.customers.Cancel")}}
              </button>
            </div>
          </div>
      </div>
  </div>
</div>
<!-- End modal for delete segment -->





</div>

@stop

