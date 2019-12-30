<?php
use App\Model\Product;
use App\Model\Category;
use App\Model\Customer;
use App\Model\Order;
use App\Model\Coupon;
use App\Model\Segment;
use App\Model\userRole;
use App\Model\User;
use App\Model\ProductCategory;
use App\Model\storeData;
use App\Model\ProductTag;
use Illuminate\Http\Request;
Use App\Helpers\OrderHelper;
Use App\Helpers\CustomerHelper;
use Carbon\Carbon;

if(!function_exists('checkPermission')){
/**
 * Get list of logged in user's not permitted menu  .
 *
 * @since 2.0.5
 *
 * @param $user_role_slug (Logged in user role), $permission_type  (type of permission is url, action button, sidebar menu or navigation menu)
 *
 * @return Array (All not permitted list)
 */
    function checkPermission($user_role_slug, $permission_type){
        $not_permitted = array(
                'admin' => array(
                                'url_param'     => array(),
                                'action_btn'    => array(),
                                'sidebar_menu'  => array(),
                                'nav_menu'      => array(),
                            ),
                'manager' => array(
                                'url_param'     => array('view_settings'),
                                'action_btn'    => array(),
                                'sidebar_menu'  => array(),
                                'nav_menu'      => array('view_settings'),
                            ),
                'sales_person' => array(
                                'url_param'=>array('view_settings'),
                                'action_btn'=>array('add_user', 'edit_user', 'delete_user'),
                                'sidebar_menu'=>array(),
                                'nav_menu'=>array('view_settings'),
                            ),
                'producer' => array(
                                'url_param'=>array('reports', 'users', 'view_settings'),
                                'action_btn'=>array(),
                                'sidebar_menu'=>array('reports'),
                                'nav_menu'=>array('users', 'view_settings'),
                            ),
            );  

        return $not_permitted[$user_role_slug][$permission_type];
    }
}

if(!function_exists('returnUserRoleList')){
/**
*Get list of User Role
*
*@param $selected
*
*@return String $options
*
*/
    function returnUserRoleList($selected= ''){
        $data = userRole::query()->select('title', 'id')->get();
        $options = '';
        foreach ($data as $key => $value) {
            $show_select = "";
            if(!empty($selected) && $selected == $value->id){
                $show_select = "selected";
            }
            $options .=  '<option value="'.$value->id.'"'.$show_select.'>'.ucfirst($value->title).'</option>';
        }
        return $options;
    }
}


if(!function_exists('returnProductCategoryList')){
/**
 * Get Product Category List .
 *
 * @since 2.0.5
 *
 * @param $selected,$display
 *
 * @return View $html; 
 */
function returnProductCategoryList($selected=[],$display=0){
    $categories=Category::get()->toArray();

    /*if(gettype($categories)!='array'){
        $categories=json_decode(json_encode($categories),true);
        
    }*/
    $data['checkbox'] = false;  
    $data['menu_id'] = '';    
    $data['class'] = 'cat-checklist';    
    $data['selected'] = $selected;    
    if($display==1){
        $data['checkbox'] = true;
    }else if($display==0){
        $data['menu_id'] = 'Decor';  
    }
    $data['children'] = buildTree($categories,0,0,$data['checkbox']);    
     
    $html = view("categories/listoption",$data)->render();

    return $html;
}


}
if(!function_exists('buildTree')){


/**
 * Get tree structure of categories .
 *
 * @since 2.0.5
 *
 * @param $elements, $parentId = 0,$depth=0,$checkbox
 *
 * @return Array (All category list)
 * 0 => Return ul li struture
 * 1 => Return list in parent child relation
 * 2 =>Return parent child with checkbox option  
 */
    function buildTree(array $elements, $parentId = 0,$depth=0,$checkbox) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $element['depth']=$depth;
                $element['checkbox']=$checkbox;
                $children = buildTree($elements, $element['id'],$depth+1,$checkbox);
                if ($children) {
                    $element['children'] = $children;
                }
               
                $branch[] = $element;
            }
        }
        return $branch;
    }
}

if (! function_exists('get_page_title')) {
    /**
     * Get Page Title
     *
     * @param $request1,$request2
     *
     * @return string $title
     *
     */
    function get_page_title($request1,$request2){
        $title='Reporting Tool';
          if(Auth::check()){
            if($request1 == 'users'){
                $title='Users | Reporting Tool';
            }
          }else{
            $title='Reporting Tool | Login';
          }
          
        return  $title;
    }
}

if (! function_exists('get_countries')) {
/**
 * Get all countries.
 *
 * @return array
 */
    function get_countries() {

        $countries = include 'countries.php';        
        return $countries;
    }
}

if (! function_exists('get_currency_symbol')) {
/**
 * Get Currency symbol.
 *
 * @param string $currency Currency. (default: '').
 * @return string
 */
    function get_currency_symbol( $currency = '' ) {
        if ( ! $currency ) {
            $currency = 'USD';
        }

        $symbols   = 
            
        array(
                'AED' => '&#x62f;.&#x625;',
                'AFN' => '&#x60b;',
                'ALL' => 'L',
                'AMD' => 'AMD',
                'ANG' => '&fnof;',
                'AOA' => 'Kz',
                'ARS' => '&#36;',
                'AUD' => '&#36;',
                'AWG' => 'Afl.',
                'AZN' => 'AZN',
                'BAM' => 'KM',
                'BBD' => '&#36;',
                'BDT' => '&#2547;&nbsp;',
                'BGN' => '&#1083;&#1074;.',
                'BHD' => '.&#x62f;.&#x628;',
                'BIF' => 'Fr',
                'BMD' => '&#36;',
                'BND' => '&#36;',
                'BOB' => 'Bs.',
                'BRL' => '&#82;&#36;',
                'BSD' => '&#36;',
                'BTC' => '&#3647;',
                'BTN' => 'Nu.',
                'BWP' => 'P',
                'BYR' => 'Br',
                'BYN' => 'Br',
                'BZD' => '&#36;',
                'CAD' => '&#36;',
                'CDF' => 'Fr',
                'CHF' => '&#67;&#72;&#70;',
                'CLP' => '&#36;',
                'CNY' => '&yen;',
                'COP' => '&#36;',
                'CRC' => '&#x20a1;',
                'CUC' => '&#36;',
                'CUP' => '&#36;',
                'CVE' => '&#36;',
                'CZK' => '&#75;&#269;',
                'DJF' => 'Fr',
                'DKK' => 'DKK',
                'DOP' => 'RD&#36;',
                'DZD' => '&#x62f;.&#x62c;',
                'EGP' => 'EGP',
                'ERN' => 'Nfk',
                'ETB' => 'Br',
                'EUR' => '&euro;',
                'FJD' => '&#36;',
                'FKP' => '&pound;',
                'GBP' => '&pound;',
                'GEL' => '&#x20be;',
                'GGP' => '&pound;',
                'GHS' => '&#x20b5;',
                'GIP' => '&pound;',
                'GMD' => 'D',
                'GNF' => 'Fr',
                'GTQ' => 'Q',
                'GYD' => '&#36;',
                'HKD' => '&#36;',
                'HNL' => 'L',
                'HRK' => 'kn',
                'HTG' => 'G',
                'HUF' => '&#70;&#116;',
                'IDR' => 'Rp',
                'ILS' => '&#8362;',
                'IMP' => '&pound;',
                'INR' => '&#8377;',
                'IQD' => '&#x639;.&#x62f;',
                'IRR' => '&#xfdfc;',
                'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
                'ISK' => 'kr.',
                'JEP' => '&pound;',
                'JMD' => '&#36;',
                'JOD' => '&#x62f;.&#x627;',
                'JPY' => '&yen;',
                'KES' => 'KSh',
                'KGS' => '&#x441;&#x43e;&#x43c;',
                'KHR' => '&#x17db;',
                'KMF' => 'Fr',
                'KPW' => '&#x20a9;',
                'KRW' => '&#8361;',
                'KWD' => '&#x62f;.&#x643;',
                'KYD' => '&#36;',
                'KZT' => 'KZT',
                'LAK' => '&#8365;',
                'LBP' => '&#x644;.&#x644;',
                'LKR' => '&#xdbb;&#xdd4;',
                'LRD' => '&#36;',
                'LSL' => 'L',
                'LYD' => '&#x644;.&#x62f;',
                'MAD' => '&#x62f;.&#x645;.',
                'MDL' => 'MDL',
                'MGA' => 'Ar',
                'MKD' => '&#x434;&#x435;&#x43d;',
                'MMK' => 'Ks',
                'MNT' => '&#x20ae;',
                'MOP' => 'P',
                'MRO' => 'UM',
                'MUR' => '&#x20a8;',
                'MVR' => '.&#x783;',
                'MWK' => 'MK',
                'MXN' => '&#36;',
                'MYR' => '&#82;&#77;',
                'MZN' => 'MT',
                'NAD' => '&#36;',
                'NGN' => '&#8358;',
                'NIO' => 'C&#36;',
                'NOK' => '&#107;&#114;',
                'NPR' => '&#8360;',
                'NZD' => '&#36;',
                'OMR' => '&#x631;.&#x639;.',
                'PAB' => 'B/.',
                'PEN' => 'S/',
                'PGK' => 'K',
                'PHP' => '&#8369;',
                'PKR' => '&#8360;',
                'PLN' => '&#122;&#322;',
                'PRB' => '&#x440;.',
                'PYG' => '&#8370;',
                'QAR' => '&#x631;.&#x642;',
                'RMB' => '&yen;',
                'RON' => 'lei',
                'RSD' => '&#x434;&#x438;&#x43d;.',
                'RUB' => '&#8381;',
                'RWF' => 'Fr',
                'SAR' => '&#x631;.&#x633;',
                'SBD' => '&#36;',
                'SCR' => '&#x20a8;',
                'SDG' => '&#x62c;.&#x633;.',
                'SEK' => '&#107;&#114;',
                'SGD' => '&#36;',
                'SHP' => '&pound;',
                'SLL' => 'Le',
                'SOS' => 'Sh',
                'SRD' => '&#36;',
                'SSP' => '&pound;',
                'STD' => 'Db',
                'SYP' => '&#x644;.&#x633;',
                'SZL' => 'L',
                'THB' => '&#3647;',
                'TJS' => '&#x405;&#x41c;',
                'TMT' => 'm',
                'TND' => '&#x62f;.&#x62a;',
                'TOP' => 'T&#36;',
                'TRY' => '&#8378;',
                'TTD' => '&#36;',
                'TWD' => '&#78;&#84;&#36;',
                'TZS' => 'Sh',
                'UAH' => '&#8372;',
                'UGX' => 'UGX',
                'USD' => '&#36;',
                'UYU' => '&#36;',
                'UZS' => 'UZS',
                'VEF' => 'Bs F',
                'VES' => 'Bs.S',
                'VND' => '&#8363;',
                'VUV' => 'Vt',
                'WST' => 'T',
                'XAF' => 'CFA',
                'XCD' => '&#36;',
                'XOF' => 'CFA',
                'XPF' => 'Fr',
                'YER' => '&#xfdfc;',
                'ZAR' => '&#82;',
                'ZMW' => 'ZK'        
        );
        $currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

        return $currency_symbol;
    }
}

 if (! function_exists('get_random_background_color')) {
    /**
     * Get random background color from array of 5 element
     *
     * 
     * @return string
     */
    function get_random_background_color()
    {
         $color_array= array(
            '0' => 'rgb(125,244,85)',
            '1' => 'rgb(33,108,215)',
            '2' => 'rgb(61,266,140)',
            '3' => 'rgb(-161,-88,-47)',
            '4' => 'rgb(163,162,246)',
         );
        

       return $color_array[array_rand($color_array,1)];
    }
}

if(!function_exists('randomPassword')){
/**
*Get random generated password 
*
*@param 
*
*@return String $pass
*
*/


function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

}


if (! function_exists('sendmail')) {

/**
 * Send mail.
 *
 * @since 2.0.5
 *
 * @param $data
 *
 *@return Sends mail
 */
    function sendmail($data){
        if(isset($data['filename'])){
            $data['files_with_size'] = Storage::disk()->size($data['filename']);
        }
        Mail::send($data['view'], $data , function($message) use($data) {
            $message->from('reports@connectbrands.co', 'Login');
            $message->to($data['email'])
                ->subject($data['subject']);
            if((isset($data['attach_csv']) && $data['attach_csv'] == 1 && $data['files_with_size']<=10240)){

                $message->attach(storage_path('app/'.$data['filename']), [
                            'as' => $data['export_name'].'-'.Carbon::now()->toDateString('YY-m-d').'_'.$data['module'].'.csv',
                            'mime' => 'application/csv',
                        ]);
            }
            $message->setContentType('application/csv');
            //$message->queue();
            });
        if(!Mail::failures()){
            return 1;
        }
        else{
            return 0;
        }
    }
}
