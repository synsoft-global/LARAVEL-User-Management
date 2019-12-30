<div class="row margin_0">
<div class="col-sm-12 col-md-12 p-0">
  @if(isset($users) && count($users)>0)
    @foreach($users as $key => $val)
     <!--  start new table card -->
     <div class="card-widget cst-card-widget-new">
            <a href="{{url('users')}}/{{isset($val->id)?$val->id:''}}" class="single-card">
              <div class="info clearfix">
                <div class="pull-left">
                  <div class="order-cell ">                   
                  <div class="meta">                   
                   
                    <span  class="order-number"> {{ (empty($val->name)) ? __('messages.customers.No_name_provided'):$val->name }} </span>                   
                    <div class="date">                    
                        {{isset($val->email)?$val->email:''}}    
                              

                      </div>
                    </div>
                  </div>
                </div> 
                <div class="pull-right text-right totals">
                  <div class="total">

                    @if(!empty($val->phone))
                    <a href="tel:{{isset($val->phone)?$val->phone:''}}" class="bd-widget-latest-reservations__item-meta-icon my-0"><span class="ua-icon-phone"> </span>{{isset($val->phone)?$val->phone:''}}</a>
                     @endif
                  </div>
                  
                 </div>
               </div>

                <div class="footer customer">
                  <div class="resource-index-card-customer-name">
                   <span class="flag-icon flag-icon-{{strtolower($val->billing_country)}}"></span> 
                     <div class="details">
                      <span class="name">  
                        @if(!empty($val->role->title))</span>                  
                      <span class="city"> <span>{{isset($val->role->title)?$val->role->title:''}}</span> 
                       @endif
                    </div>
                  </div>
                </div>
              </a>

    </div>
    <!-- end new table card-->
     @endforeach      
    @else     
     <p>  {{ __('messages.customers.no_matching_customers_were_found') }} </p>     
       
     @endif     
 </div>
</div>
<div class="dataset__footer my-4 row"> 
    <div class="pagination col-sm-12 col-md-9">      
    @if(isset($users))
      <?php   
       $links = $users->onEachSide(1)->links();
      
        $patterns = array();
        $patterns[] = '/'. Request::segment(1).'\?page=/';
        $replacements = array();
        $replacements[] = 'users?page=';
        echo preg_replace($patterns, $replacements, $links); 
      ?>
       @endif
    </div>
    <div class=" col-sm-12 col-md-3 pagination-limit-max">     
        <select class="changelimit select_custom" id="changelimit">         
          <option value="10" {{!empty(session('limit') && session('limit') == "10")?'selected':''}}>10</option>
          <option value="50" {{!empty(session('limit') && session('limit') == "50")?'selected':''}}>50</option>
          <option value="100" {{!empty(session('limit') && session('limit') == "100")?'selected':''}}>100</option>
        </select>   
    </div>   
  </div>
