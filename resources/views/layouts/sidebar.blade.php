<div class="sidebar-section">
  <div class="sidebar-section__scroll">
    <?php
    /*
      Logged in user details
    */
    ?>
    <div class="simplebar-track vertical" style="visibility: visible;">
      <div class="simplebar-scrollbar" style="top: 2px; height: 62px;"></div>
    </div>
    <!--<div class="sidebar-user-a">
     
    </div>-->
    <?php
    /*
      Sidebar menu for logged-in user
    */
    ?>
    <?php 
      $sidebar_permission = checkPermission(Auth::user()->role->slug, 'sidebar_menu'); 
    ?>
    <div> 

      <ul class="sidebar-section-nav">
       
        @if(empty(in_array('dashboard', $sidebar_permission)))
        <li class="sidebar-section-nav__item {{(Request::segment(1)=='dashboard')?'is-active':''}}">
            <a class="sidebar-section-nav__link" href="{{url('dashboard')}}" >
              <span class="sidebar-section-nav__item-icon ua-icon-widget-users"></span>
              <span class="sidebar-section-nav__item-text">Users</span>
             
            </a>       
        </li>
        @endif
        <!-- @if(empty(in_array('reports', $sidebar_permission)))  
        <li class="sidebar-section-nav__item {{(Request::segment(1)=='reports')?'is-active':''}}">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#" >
            <span class="sidebar-section-nav__item-icon ua-icon-charts"></span>
            <span class="sidebar-section-nav__item-text">Reports</span>
          </a>
          <ul class="sidebar-section-subnav" style="{{(Request::segment(1)=='reports')?'display: block;':''}}">
            @if(empty(in_array('reports/revenue', $sidebar_permission)))  
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(2)=='revenue')?'is-active':''}}"  href="{{url('reports/revenue')}}">Revenue</a></li>
            @endif
            @if(empty(in_array('reports/orders', $sidebar_permission)))  
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(2)=='orders')?'is-active':''}}" href="{{url('reports/orders')}}">Orders</a></li>
            @endif
            @if(empty(in_array('reports/refunds', $sidebar_permission)))
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(2)=='refunds')?'is-active':''}}" href="{{url('reports/refunds')}}">Refunds</a></li>
            @endif
            @if(empty(in_array('reports/customers', $sidebar_permission)))
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(2)=='customers')?'is-active':''}}" href="{{url('reports/customers')}}">Customers</a></li>
            @endif
          </ul>
        </li>
        @endif
        @if(empty(in_array('orders', $sidebar_permission)) || empty(in_array('refunds', $sidebar_permission)) || empty(in_array('orders_segments', $sidebar_permission)))  
        <li class="sidebar-section-nav__item {{(Request::segment(1)=='orders' || Request::segment(1)=='orders_segments' || Request::segment(1)=='order' ||  Request::segment(1)=='refunds')?'is-active':''}}" >
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#" >
            <span class="sidebar-section-nav__item-icon ua-icon-shopping-bag"></span>
            <span class="sidebar-section-nav__item-text">Orders</span>
          </a>
          <ul class="sidebar-section-subnav" style="{{(Request::segment(1)=='orders' || Request::segment(1)=='orders_segments' ||  Request::segment(1)=='order' ||  Request::segment(1)=='refunds')?'display: block;':''}}">
            @if(empty(in_array('orders', $sidebar_permission)))
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(1)=='orders'  ||  Request::segment(1)=='order')?'is-active':''}}"  href="{{url('orders')}}">Orders</a></li>
            @endif
            @if(empty(in_array('orders_segments', $sidebar_permission)))
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(1)=='orders_segments' )?'is-active':''}}" href="{{url('orders_segments')}}">Segments</a></li>
            @endif
            @if(empty(in_array('refunds', $sidebar_permission)))
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(1)=='refunds')?'is-active':''}}" href="{{url('refunds')}}">Refunds</a></li>
            @endif
          </ul>
        </li>
        @endif
        @if(empty(in_array('customers', $sidebar_permission)) || empty(in_array('segments/customers', $sidebar_permission)))  
        <li class="sidebar-section-nav__item {{(Request::segment(1)=='customers' || Request::segment(1)=='segments' || Request::segment(1)=='customer')?'is-active':''}}">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#" >
            <span class="sidebar-section-nav__item-icon ua-icon-widget-users"></span>
            <span class="sidebar-section-nav__item-text">Customers</span>
           
          </a>
          <ul class="sidebar-section-subnav" style="{{(Request::segment(1)=='customers' || Request::segment(1)=='segments' ||  Request::segment(1)=='customer')?'display: block;':''}}">
            @if(empty(in_array('customers', $sidebar_permission)))
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(1)=='customers'  ||  Request::segment(1)=='customer')?'is-active':''}}" href="{{url('customers')}}">List</a></li>
            @endif
            @if(empty(in_array('segments/customers', $sidebar_permission)))
            <li class="sidebar-section-subnav__item "><a class="sidebar-section-subnav__link {{(Request::segment(1)=='segments')?'is-active':''}}" href="{{url('segments/customers')}}">Segments</a></li>
            @endif
          </ul>  
        </li>
        @endif
        @if(empty(in_array('products', $sidebar_permission)) || empty(in_array('categories', $sidebar_permission)) || empty(in_array('product-groups', $sidebar_permission)))  
        <li class="sidebar-section-nav__item {{(Request::segment(1)=='products' || Request::segment(1)=='variations' || Request::segment(1)=='product' || Request::segment(1)=='categories' || Request::segment(1)=='product-groups' || Request::segment(1)=='segments_products' || Request::segment(1)=='segments_variations')?'is-active':''}}"">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#" >
            <span class="sidebar-section-nav__item-icon ua-icon-folder"></span>
            <span class="sidebar-section-nav__item-text">Products</span>
          </a>
          <ul class="sidebar-section-subnav" style="{{(Request::segment(1)=='products' || Request::segment(1)=='variations' || Request::segment(1)=='product' || Request::segment(1)=='categories' || Request::segment(1)=='product-groups' || Request::segment(1)=='segments_products' || Request::segment(1)=='segments_variations')?'display: block;':''}}">
            @if(empty(in_array('products', $sidebar_permission)))
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(1)=='products'  ||  Request::segment(1)=='product')?'is-active':''}}" href="{{url('products')}}">Products</a></li>
            @endif
            @if(empty(in_array('categories', $sidebar_permission)))
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(1)=='categories'  ||  Request::segment(1)=='category')?'is-active':''}}" href="{{url('categories')}}">Categories</a></li>
            @endif
            @if(empty(in_array('product-groups', $sidebar_permission)))            
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link {{(Request::segment(1)=='product-groups'  ||  Request::segment(1)=='group')?'is-active':''}}" href="{{url('product-groups')}}">Groups</a></li>
            @endif
          </ul>
        </li>
        @endif	
        @if(empty(in_array('export_list', $sidebar_permission)))  
        <li class="sidebar-section-nav__item {{(Request::segment(1)=='export_list')?'is-active':''}}">
            <a class="sidebar-section-nav__link" href="{{url('export_list')}}" >
              <span class="btn-icon ua-icon-download"></span>
              <span class="sidebar-section-nav__item-text">Exports</span>
             
            </a>       
        </li>
        @endif
        @if(empty(in_array('file_manager', $sidebar_permission)))  
        <li class="sidebar-section-nav__item {{(Request::segment(1)=='file_manager')?'is-active':''}}">
            <a class="sidebar-section-nav__link" href="{{url('file_manager')}}" >
              <span class="mdi mdi-file sidebar-section-nav__item-icon"></span>
              <span class="sidebar-section-nav__item-text">File Manager</span>
            </a>       
        </li>
        @endif
        @if(empty(in_array('social_feed', $sidebar_permission)))  
        <li class="sidebar-section-nav__item {{(Request::segment(1)=='social_feed')?'is-active':''}}">
            <a class="sidebar-section-nav__link" href="{{url('social_feed')}}" >
              <span class="sidebar-section-nav__item-icon iconfont-activity"></span>
              <span class="sidebar-section-nav__item-text">Social Feed</span>
            </a>       
        </li>
        @endif -->

             
          
      </ul>
    </div>
  </div>
</div>

<!-- /.navbar-static-side -->