<?php
/**
 * Description: Order helper for parse request data and insert related database.
 * Version: 1.0.0
 * Author: Synsoft Global
 * Author URI: https://www.synsoftglobal.com/
 *
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB,Auth;
Use App\Helpers\ProductHelper;
use Carbon\Carbon;

class Category extends Model
{
    public function product_category()
    {       
        return $this->hasMany('App\Model\ProductsCategory');
    }



    /**
     * get Data for exporting category list.  
     *
     * @param $request
     * @return JSON Object      
     */
    public function getExportData($postData){
      try{ 
        
        // select query from Category table
        $query = Category::query();
        DB::enableQueryLog();
        $query->select(          
            //DB::raw('IF(order_items.total IS NULL, 0, SUM(order_items.total)) AS net_revenue'),
            DB::raw('IF(product_categories.id IS NULL, 0, count(distinct product_categories.id)) as products_count'),    
            DB::raw('IF(product_categories.id IS NULL, 0, count(distinct product_categories.id)) as products'),    
           // DB::raw('IF(order_items.quantity IS NULL, 0, SUM(order_items.quantity)) as net_sold'),         
           // DB::raw('IF(order_items.quantity IS NULL, 0, SUM(order_items.quantity)) + IF(refund_items.quantity IS NULL, 0, refund_items.quantity) as items_sold'),  
            DB::raw('IF(order_items.quantity IS NULL, 0, SUM(order_items.quantity)+IF(refund_items.quantity IS NULL, 0, SUM(refund_items.quantity))) as items_sold')   ,    
            //DB::raw('IF(order_items.total IS NULL, 0, SUM(order_items.total)) as net_sales'),         
            DB::raw('IF(order_items.total_tax IS NULL, 0, SUM(order_items.total_tax)) as total_tax'),         
            DB::raw('max(order_items.date_created) as last_order_date'),
            'order_items.date_created' ,
            'product_categories.product_id',      
            'categories.name',       
            'categories.slug',       
            'categories.description',       
            'categories.id',
            'categories.id as category_id',
            //DB::raw('products.parent_id as parent_category_id'),
            //DB::raw('products.images as image'),
            DB::raw('IF(order_items.id IS NULL, 0, count(order_items.id)) as net_orders'),
            //DB::raw('IF(refund_items.id IS NULL, 0, SUM(refund_items.total)) as total_refunds12'),
            DB::raw('REPLACE(IF(refund_items.id IS NULL, 0, refund_items.total), "-", "") as total_refunds'),
          
            DB::raw('IF(order_items.quantity IS NULL, 0, SUM(order_items.quantity)) as gross_items_sold'),
            
            DB::raw('IF(order_items.total IS NULL, 0, SUM(order_items.total)) as gross_sales'),
            DB::raw('REPLACE(IF(refund_items.quantity IS NULL, 0, SUM(refund_items.quantity)), "-", "") as refunded_items'),


            DB::raw('IF(order_items.total IS NULL, 0, SUM(order_items.total)+IF(refund_items.total IS NULL, 0, SUM(refund_items.total))) AS net_revenue'),
            DB::raw('IF(order_items.total IS NULL, 0, SUM(order_items.total)+IF(refund_items.total IS NULL, 0, SUM(refund_items.total))) AS net_sales'),

            DB::raw('IF(order_items.quantity IS NULL, 0, SUM(order_items.quantity)+IF(refund_items.quantity IS NULL, 0, SUM(refund_items.quantity))) as net_sold')
            
        );

                   
        global $start_date,$end_date;
          if (!empty($postData['range_picker'])) {
              $date_exp = explode('-', $postData['range_picker']);

              $start_date=date('Y-m-d',strtotime($date_exp[0]));
              $end_date=date('Y-m-d',strtotime($date_exp[1]));         
             
           }else{
              $start_date=$postData['startdate'];
              $end_date=$postData['enddate'];
         
          }   

        $query->join('product_categories', function($join){
          global $start_date,$end_date;
          $current_date = date('Y-m-d');
          $join->on('product_categories.category_id', '=', 'categories.id');
        },'','','left');

       /* $query->join('products', function($join){
          global $start_date,$end_date;
          $current_date = date('Y-m-d');
          $join->on('products.id', '=', 'product_categories.product_id');
        },'','','left');*/

         $start_date=$start_date." 00:00:00";
        $end_date=$end_date." 23:59:59";     
         $query->join(DB::raw("(SELECT order_items.* FROM order_items inner join orders on orders.id=order_items.order_id and status in  ('completed','processing','on-hold')) as order_items"),function($join){
        global $start_date,$end_date;      
            $join->on("product_categories.product_id","=","order_items.product_id")
            ->whereBetween('order_items.date_created',[$start_date, $end_date]);

      },'','','left');

        $query->join('refund_items', function($join){
            global $start_date,$end_date;
            $current_date = date('Y-m-d');
            $join->on( 'refund_items.product_id', '=','product_categories.product_id')               
           //->whereBetween('refund_items.date_created',[$start_date, $end_date])
           ->on(DB::raw('refund_items.order_id'),'=', DB::raw('order_items.order_id'));
         },'','','left');

         $postData['filter_type'] = (isset($postData['filter_type'])) ? $postData['filter_type'] : 'all';

        if(!empty($postData['segments'])){
            $query=ProductHelper::parse_product_segment($query,$postData['segments'],$postData['filter_type']);
        }
        if(!empty(Auth::user())){
          $user=Auth::user();
        }else{
          $user=User::find($postData['user_id']);
        }
        $current_role=$user->role->slug;
        

        $cat=[];
        if($user){
          if($current_role=='producer'){
            $users_categories=Auth::user()->users_categories;
            if(!empty($users_categories)){
              $cat=json_decode($users_categories,true);
            }
          }
        
        }

        if($current_role=='producer'){
         // $query->join('product_categories', 'product_categories.product_id', '=', 'products.id','left');
          $query->whereIn('product_categories.category_id',$cat);
        }

        $query->groupBy('categories.id');


        $query->orderBy($postData['orderbyVal'],$postData['orderby']);



        $query->when(!empty($postData['whereVal']), function ($q) use($postData) {
          $q->where(function ($q) {
            $q->where('categories.name', 'like', '%' . $postData['whereVal'] . '%');
            $q->where('categories.id', 'like', '%' . $postData['whereVal'] . '%');

            return $q->orWhere('categories.description', 'like', '%' . $postData['whereVal'] . '%');
          });
        });

       	$data['categories']['categories'] = $query->get();

        if(isset($postData['freq_to'])){        	
  	        $query->whereBetween('order_items.date_created', [$postData['startdate'], $postData['enddate']]);
  	   	}

        $subQuery_total=DB::table( DB::raw("({$query->toSql()}) as sub ") )
            ->mergeBindings($query->getQuery());   

        $data['categories']['net_total_revenue'] =  $subQuery_total->sum('net_revenue');   
       
        $data['categories']['total_net_sold'] =  $subQuery_total->sum('net_sold');

      //dd(DB::getQueryLog());
       $data['categories']['total'] =  $data['categories']['categories']->count();
       $data['categories']['settings'] =  $postData['settings'];
        return $data;
      }catch(Exception $e){
          return redirect('/');
          die();
      }
    }

    /**
  * Get searched, segment and orderby filtered data to export
  *
  *@param $postData(Post data)
  *
  *@return Data to export
  */
  public function getSearchData($searh,$limit=3){
  
        if($searh)
         // select query from Category table
        $query = Category::query();
        DB::enableQueryLog();
        $query->select(         
          
           
            'categories.name',       
            'categories.slug',       
            'categories.description',       
            'categories.id',
            'categories.id as category_id'        
            
        );

        if (!empty($searh)) {

           $query->where(function($q) use($searh){
             $q->where('categories.name', 'like', '%' . $searh . '%');
             $q->orWhere('categories.id', 'like', '%' . $searh . '%');
            return $q->orWhere('categories.description', 'like', '%' . $searh . '%');
          });

           // $query->when(!empty($searh), function ($q) use($searh) {
      
        }   

        $current_role=Auth::user()->role->slug;
        $cat=[];
        if($current_role=='producer'){
          $users_categories=Auth::user()->users_categories;
          if(!empty($users_categories)){
            $cat=json_decode($users_categories,true);
          }
        }

        if($current_role=='producer'){
         // $query->join('product_categories', 'product_categories.product_id', '=', 'products.id','left');
          $query->whereIn('id',$cat);
        }      

      //  DB::enableQueryLog();
        $data = $query->get()->take($limit);     
//dd(DB::getQueryLog());
         return $data;
    }

    /**
     * get Data for exporting category list.  
     *
     * @param $request
     * @return JSON Object      
     */
    public function checkPermission($url_id){
      try{ 
        $status=false;

        if(Auth::user()->role->slug=='producer'){
          $users_categories=Auth::user()->users_categories;
          if(!empty($users_categories)){
            $cat=json_decode($users_categories,true);            
          }

          if(!in_array($url_id,$cat)){
            $status=true;
          }
         
        }
        return $status;
       }catch(Exception $e){
          return redirect('/');
          die();
      }
    }
}
