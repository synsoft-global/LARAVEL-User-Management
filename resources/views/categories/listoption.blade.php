@if($children && count($children) > 0)
  <!-- <input type="checkbox" name="select_all" class="select_all">Select All -->

  <ul  id="{{isset($menu_id)?$menu_id:''}}" class="{{isset($class)?$class:''}}"> 
    @foreach($children as $key => $category_val)
      @if(isset($category_val['children']) && count($category_val['children']) > 0)
      
      <li class="select_all_li">
        <!-- <input type="checkbox" name="select_all_name" class="select_all"> -->
        <a href="javascript:void(0);" class="select_text"> Select All</a>
      </li>
      @endif
      <li class="level{{ $category_val['depth']+1}}" id="key{{$key}}">
        <!-- <a href="{{url('categories')}}/{{isset($category_val['id'])?$category_val['id']:''}}" class=""> -->
          

           
              @if(($checkbox))

              
               <div class="checkbox">
                <?php $selected=(!empty($selected)) ? $selected : []; ?>
                  <div class="custom-control custom-checkbox">
                   <input value="{{$category_val["id"]}}" {{(in_array($category_val["id"],$selected)) ? "checked=checked" : ""}}   id="in-product_cat-{{$category_val["id"]}}" name="product_cat[]" type="checkbox" class="custom-control-input form-control  checkbox style-2 ml-2">

                  <label class="custom-control-label el-checkbox__label" for="in-product_cat-{{$category_val["id"]}}">{{$category_val['name']}}</label>                

                  </div>
                </div>

             @endif

        <!-- </a>  -->
        @if(isset($category_val['children']) && count($category_val['children']) > 0)
          {!!view("categories/listoption",array_merge($category_val,array('class'=>'children','selected'=>$selected)))->render()!!}
        @endif
      </li>
    @endforeach
  </ul>
@endif