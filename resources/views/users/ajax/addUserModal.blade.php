
<!-- Modal body -->
<div class="modal-dialog ausrmdle" role="document">
  <div class="modal-content">
    <div class="modal-header has-border custom-modal__image">  
      <img class="" src="https://cdn0.iconfinder.com/data/icons/business-startup-10/50/76-512.png" alt="">           
      <button type="button" class="close custom-modal__close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" class="ua-icon-modal-close"></span>
      </button>
    </div>
    <form id="add_user" name="add_user" class="el-form el-form--label-top">
      <div class="modal-body">   
        <h4 class="custom-modal__body-heading mb-3 add_update_user" >{{!empty($user)?'Update':'Add'}} User</h4>
        @csrf
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="user_name" class="el-form-item__label"><strong>First Name</strong></label>           
            <div class="mr-3">
              <input data-original="" value="{{!empty($user->first_name)?$user->first_name:''}}" type="text" autocomplete="off" name="first_name" id="first_name" class="el-input__inner form-control"/>  
            </div>   
          </div> 
          <div class="col-md-6">
            <label for="user_name" class="el-form-item__label"><strong>Last Name</strong></label>           
            <div class="mr-3">
              <input data-original="" value="{{!empty($user->last_name)?$user->last_name:''}}" type="text" autocomplete="off" name="last_name" id="last_name" class="el-input__inner form-control"/>  
            </div>   
          </div> 
          
        </div>
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="user_phone" class="el-form-item__label"><strong>Phone</strong></label>           
            <div class="mr-3">
              <input  value="{{!empty($user->phone)?$user->phone:''}}" data-original="" type="text" name="user_phone" id="user_phone" autocomplete="off" class="el-input__inner form-control">                          
            </div> 
          </div>
          <div class="col-md-6">
            <label for="user_email" class="el-form-item__label"><strong>Email</strong></label>           
            <div class="mr-3">
              <input value="{{!empty($user->email)?$user->email:''}}" data-original="" type="email" name="user_email" id="user_email" autocomplete="off" class="el-input__inner form-control">                        
              <input value="{{!empty($user->id)?$user->id:''}}" data-original="" type="hidden" name="id" id="id" autocomplete="off" class="el-input__inner form-control">                        
            </div>   
          </div>
          
        </div>
       
        <div class="row mb-4" >
          <div class="col-md-6 user_role_id">
            <label for="user_role_id" class="el-form-item__label"><strong>Role</strong></label>           
            <div class="mr-3">                   
              <select data-placeholder="Select Role" class="form-control select2-hidden-accessible dataset__header-search-input customer_option_dropdown_input" name="user_role_id" id="user_role_id">
               
                {!!returnUserRoleList(!empty($user->user_role_id)?$user->user_role_id:'')!!}
              </select>             
            </div>
            <label for="user_role_id" class="error"></label>                   
          </div> 
        </div>
        <div class="row mb-4" >
          <div class="col-md-12 category_div" style="{{empty($user->users_categories)?'display: none;':'display: block;'}}">
            <label for="category_id" class="el-form-item__label"><strong>Categories</strong></label>           
            <div class="mr-3">          
              {!! returnProductCategoryList(!empty($user->users_categories)?json_decode($user->users_categories):[],1) !!}
            </div>      
          </div>

        </div>
      </div>
      <div class="modal-footer pb-0"> 
        <div class="custom-modal__buttons">
          <button type="button" class="btn btn-outline-primary" data-dismiss="modal" aria-label="Close">
           <span>Cancel</span>
          </button>
          <button type="submit" class="btn btn-success" id="submit_btn">
            <span>Submit</span>
          </button>
        </div>
      </div>
    </form> 
  </div>
</div>