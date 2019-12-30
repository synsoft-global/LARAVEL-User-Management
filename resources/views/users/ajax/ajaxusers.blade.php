<div class="scrollable-table">
  <table class="table dataset__table table__actions table table-responsive table-hover table-striped customer-table">
    <?php 
      $btn_permission = checkPermission(Auth::user()->role->slug, 'action_btn'); 
    ?>
    <thead>
      <tr>  
        <th>Name</th>
        <th>Email</th>
        <th>Contact No.</th>
        <th>Role</th> 
        @if(empty(in_array('edit_user', $btn_permission)) || empty(in_array('delete_user', $btn_permission)))
        <th>Action</th>
        @endif      
      </tr>
    </thead>
    <tbody>
     @if(isset($users) && count($users)>0)
      @foreach($users as $key => $val)
        <?php 
          $spread = isset($val->id)?$val->id:'';
          $css = "background-color:".get_random_background_color()." !important;color:white;";
        ?>
        <tr>
          <td class="cst-customer-col">
           <div class="widget-todo__item">
          
              <span class="textavatar widget-todo__item-avatar" data-width="40" data-height="40" data-name="{{isset($val->first_name)?$val->first_name:''}}"  style="width: 40px; height: 40px;  font-size: 14px; {{$css}}"><abbr title="{{isset($val->first_name)?$val->first_name:'' }} {{ isset($val->last_name)?$val->last_name:''}} ">{{isset($val->first_name[0])?$val->first_name[0]:'' }} {{isset($val->last_name[0])?$val->last_name[0]:''}}</abbr></span>
              <div class="widget-todo__item-info">
               <div class="bd-widget-latest-reservations__item-title">
                  <span class="widget-contacts__item-name my-0">{{ (empty($val->first_name) && $val->last_name) ? __('messages.customers.No_name_provided')  :$val->name }}
                  </span>
                </div> 
                <span class="widget-todo__item-date">{{date('F j, Y', strtotime(isset($val->created_at)?$val->created_at:''))}}</span>
              </div>
            </div>
          </td> 
          <td class="cst-contact-detail-col">
            @if(!empty($val->email))
            <a href="mailto:{{isset($val->email)?$val->email:''}}" class="bd-widget-latest-reservations__item-meta-icon my-0"><span class="ua-icon-envelope"> </span> {{isset($val->email)?$val->email:''}}  
            </a>
            @endif
          </td>
          <td>   
            @if(!empty($val->phone))
              <div class="widget-contacts__item-email">
                <a href="tel:{{isset($val->phone)?$val->phone:''}}" class="bd-widget-latest-reservations__item-meta-icon my-0"><span class="ua-icon-phone"> </span>{{isset($val->phone)?$val->phone:''}}</a>
              </div>
            @endif
          </td>
          <td>   
            @if(!empty($val->role->title))
              <div class="widget-contacts__item-email">
                <span class="fa fa-users mr-1"> </span>{{isset($val->role->title)?$val->role->title:''}}
              </div>
            @endif
          </td>
          @if(empty(in_array('edit_user', $btn_permission)) || empty(in_array('delete_user', $btn_permission)))
          <td>   
            @if(empty(in_array('edit_user', $btn_permission)))
            <div class="btn-group btn-collection mr-3" >
              <button data-id="{{$val->id}}"  data-placement="top" data-toggle="popover" data-trigger="hover" data-content="Edit the user" data-tippy-placement="top" class="edit_user btn btn-secondary" type="button"> <i class="fa fa-pencil" aria-hidden="true" ></i>
              </button> 
            @endif      
              @if(empty(in_array('change_password', $btn_permission)))
              <button  data-placement="top" data-id="{{$val->id}}" data-toggle="modal" data-target="#chage_password_modal_" data-trigger="hover" data-content="Change Password" data-tippy-placement="top"  class="change_password btn btn-secondary" type="button"><i class="fa fa-key" aria-hidden="true" id="d_hide_icon_{{$val->id}}"></i>
              </button>                        
            @endif         
            @if(empty(in_array('delete_user', $btn_permission)))
              <button data-name="{{$val->name}}" data-id="{{$val->id}}" data-placement="top" data-toggle="popover" data-trigger="hover" data-content="Delete the user" data-tippy-placement="top"  class="delete_user btn btn-secondary" type="button"><i class="fa fa-trash" aria-hidden="true" id="d_hide_icon_{{$val->id}}"></i>
              </button>
            @endif
          
            </div> 
          </td>
          @endif  
        </tr>
      @endforeach      
      @else
      <tr>
        <td colspan="4">
          <p>{{ __('messages.customers.no_matching_customers_were_found') }}</p>
        </td>
      </tr>
      @endif                        
    </tbody>
  </table>
</div>
<div class="dataset__footer my-4"> 
  @if(isset($users) && count($users) > 0)
    <div class="pagination">
      <?php   
        $links = $users->onEachSide(1)->links();      
        $patterns = array();
        $patterns[] = '/'. Request::segment(1).'\?page=/';
        $replacements = array();
        $replacements[] = 'users?page=';
        echo preg_replace($patterns, $replacements, $links); 
      ?>
    </div>
    <div class="dataset__pages">
      <div class="dropdown dataset__pages-dropdown">
        <select class="changelimit select_custom" id="changelimit">             
          <option value="10" {{!empty(session('limit') && session('limit') == "10")?'selected':''}}>10</option>
          <option value="50" {{!empty(session('limit') && session('limit') == "50")?'selected':''}}>50</option>
          <option value="100" {{!empty(session('limit') && session('limit') == "100")?'selected':''}}>100</option>
        </select>
      </div>
    </div>   
  @endif    
</div>   
