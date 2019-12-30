<?php
/**
 * Description: Handle User module functions.
 * Version: 1.0.0
 * Author: Synsoft Global
 * Author URI: https://www.synsoftglobal.com/
 *
 */
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\UserRole;
use Auth, Session, Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{
	public function __construct(){
    	//$this->middleware('auth');		
	}
	

	/**
     * User's Profile page.
     *
     * @param Request $request     
     */  
    public function edit_profile(Request $request){
    	return view('profile.edit_profile');
    }

    /**
     * Update User's Profile.
     *
     * @param Request $request     
     */ 
    public function updateProfile(Request $request){

        if($request->ajax()){
            $name = '';
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/profile_img');
                $imagePath = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);                
            }
            $data = User::where('id', Auth::user()->id)->update(['profile_image' => $name]);
            return response()->json(['message'=> __('messages.users.profile_image_has_saved'), 'img' => $name]);
        }else{

            $email_exists=User::where('id','!=', Auth::user()->id)->where('email',$request->email)->count();
           
            if($email_exists > 0){
                Session::flash('message', __('messages.users.email_already_exists'));
                Session::flash('alert-class', 'alert-danger');
            }else{
                Session::flash('message',  __('messages.users.profile_updated_successfully'));
                Session::flash('alert-class', 'alert-success');
                $data = User::where('id', Auth::user()->id)->update(['first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email]);
            }
            

           
            return redirect()->back();
        }
    }

     /**
     * Update logged in User's Pasword.
     *
     * @param Request $request     
     */ 
    public function updatePassword(Request $request){

        //echo '<pre>'; print_r($request->all()); echo '</pre>';
        $user =User::where('id', Auth::user()->id)->first();
        $messages = [
            'new_password.regex' => __('messages.users.password_must_contain_rules'),
            'new_password.required_with' =>  __('messages.users.password_and_con_password_must_be_same'),
            'new_password.same' => __('messages.users.password_and_con_password_must_be_same'),
          ];
        Validator::make($request->all(), [
           // 'new_password' => 'min:7|required_with:con_password|same:con_password|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[@!$#%]).*$/|',
            'new_password' => 'min:7|required_with:con_password|same:con_password|',
        ], $messages)->validate();
        


        if (Hash::check($request->old_password, $user->password)) {         
            $user->password = Hash::make($request->new_password);
            $user->save();
            Session::flash('message', __('messages.users.password_updated_successfully'));
            Session::flash('alert-class', 'alert-success');
        }else{

            Session::flash('message', __('messages.users.current_password_did_not_match'));
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->back();
    }

    /**
    * Add user in user management
    *
    *@param $request
    *
    * @return status
    */
    public function add_user_create(Request $request){
        try{

            //echo '<pre>'; print_r($request->product_cat); echo '</pre>';die();

            $pass =randomPassword();
            $data_to_add = array(
                'first_name' => $request->first_name, 
                'last_name' => $request->last_name, 
                'email' => $request->user_email, 
                'phone' => $request->user_phone, 
                'user_role_id' => $request->user_role_id, 
                'users_categories' => !empty($request->product_cat)?json_encode($request->product_cat):'', 
            );
            if(!$request->input('id')){ $data_to_add['password'] = Hash::make($pass); }
            
            User::updateorcreate(['id'=>$request->input('id')],$data_to_add);
            $maildata = array(
                            'view'      => 'email.user_account_info',
                            'email'     => $request->user_email,
                            'subject'   => __('messages.users.mail_subject'),
                            'name'      => $request->first_name.' '.$request->last_name,
                            'phone'     => $request->user_phone,
                            'password'  => $pass,
                            );
            if($request->input('id')){
                return response()->json(['status'=>1, 'msg'=>__('messages.users.user_updated_successfully')]);

            }else{
                sendmail($maildata);
                return response()->json(['status'=>1, 'msg'=>__('messages.users.user_added_successfully')]);
                
            }
        }catch(Exception $e){
            return response()->json(['status'=>0, 'msg'=>__('messages.common.something_went_wrong')]);
        }
    }
    
    /**
    * User list ( user management)
    *
    *@param $request
    *
    * @return json object
    */
    public function list_users(Request $request){
        return view('users.users');
    }

     /**
     * get all user paginated list.  
     *
     * @param $request
     * @return  JSON Object    
     */
    public function getPaginatedUser(Request $request){

        try{
            if(!empty($request->input('orderby'))){
                session(['orderbyUser' =>  $request->input('orderby')]);            
            }else{
                session(['orderbyUser' =>  'desc']); 
            }  

         
            if($request->input('orderbyVal')){                  
                session(['orderbyValUser' =>  $request->input('orderbyVal')]);
            }else{
                session(['orderbyValUser' =>  'users.created_at']); 
            }          

            
            if(!empty($request->input('whereVal'))){                        
                session(['whereValUsers' =>  $request->input('whereVal')]);
            }else{
                session(['whereValUsers' =>  '']);
            }
            
            if($request && !empty($request->input('limit'))){                           
                session(['limit' =>  $request->input('limit')]);
            }

            $limit = !empty(session('limit'))?session('limit'):10;
            DB::enableQueryLog();
            $query = User::query();
            $query->select( 'users.*',DB::raw('CONCAT (first_name," ",last_name) as name'));
            $query->join('user_roles', 'users.user_role_id', '=', 'user_roles.id');   
            if (!empty(session('whereValUsers'))) {
                $query->when(session('whereValUsers'), function ($q) { 
                   // $q->where( DB::raw('CONCAT (first_name," ",last_name)'), 'like', '%' . session('whereValUsers') . '%');

                    $q->where( DB::raw('concat(first_name," ",last_name)'), 'like', '%' . session('whereValUsers') . '%');

                   // $q->orWhere('last_name', 'like', '%' . session('whereVal') . '%');
                    $q->orWhere('email', 'like', '%' . session('whereValUsers') . '%');
                    $q->orWhere('phone', 'like', '%' . session('whereValUsers') . '%');
                    $q->orWhere('users.id', 'like', '%' . session('whereValUsers') . '%');
                    $q->orWhere('user_roles.title', 'like', '%' . session('whereValUsers') . '%');
                    return $q->orWhere('phone', 'like', '%' . session('whereValUsers') . '%');
                });
            }                      
           
         
            $query->orderBy(session('orderbyValUser'), session('orderbyUser'));

            $data['total']= 0;
            $data['users']='';
            $data['users'] = $query->paginate($limit)->onEachSide(1);
            
            $data['total']=$data['users']->total();        
            
        

          
            $viewType = (!empty($request->input('gridview')) && $request->input('gridview')==='true') ? 'users.ajax.ajaxusers-cart' : 'users.ajax.ajaxusers' ;
            if (count($data['users']) > 0) {         
                if($request->ajax()){
                    $view = view($viewType,$data)->render();
                     //$result = view("users.ajax.ajaxresult",$data)->render();
                    return response()->json(['html'=>$view, 'total' => $data['total']/*,'result'=>$result*/]);
                }else{
                    return $data;
                }
            } else {
                if($request->ajax()){
                    $view = view($viewType,$data)->render();
                    //$result = view("users.ajax.ajaxresult",$data)->render();
                    return response()->json(['html'=>$view, 'total' => $data['total']/*,'result'=>$result*/]);
                }else{
                    unset($data['users']);
                    $data['no_data'] = 'No results could be displayed.';
                    return $data;
                }           
            }
        }catch(Exception $e){
            return redirect('/');
        }
    }


     /**
     * Check unique email   
     *
     * @param $request
     * @return  boolean true/false
     */
    public function getValidateEmail(Request $request){
        if ($request->input('email') !== '') {
            $validator = Validator::make($request->all(), [
               
                 'email' => 'unique:users,email,'.$request->id, 
            ]);

            if (!$validator->fails()) {
                die('true');
            }
        }
        die('false');
    }   

    /**
     * Delete user
     *
     * @param $request
     * @return  json object
     */
    public function delete_user(Request $request){
        try{
            User::where('id', $request->id)->delete();
            return response()->json(['status'=>1, 'msg'=>__('messages.users.user_deleted_successfully')]);
        }catch(Exception $e){
            return response()->json(['status'=>0, 'msg'=>__('messages.common.something_went_wrong')]);
        }

    }   

     /**
     * Edit user
     *
     * @param $request
     * @return  json object
     */
    public function edit_user(Request $request){
        try{
            $data['user'] = User::findOrfail($request->id);
            $html = view('users.ajax.addUserModal', $data)->render();
            return response()->json(['status'=>1, 'html'=>$html]);
        }catch(Exception $e){
            return response()->json(['status'=>0, 'msg'=>__('messages.common.something_went_wrong')]);
        }
    }

       /* @param $request post requst object
    * @return customer html filter
    */
    public function dropdownUser(Request $request){
      try{
        $search = $request->input('q');
         $productids = $request->input('productids');

        $query = User::query()->where(DB::raw('CONCAT (first_name," ",last_name)'), 'like', '%' . $search . '%');
        if(isset($productids) && !empty($productids) && count($productids)>0){
           $query->whereNotIn('id',$productids);
        }

        $query->select('id',DB::raw('CONCAT (first_name," ",last_name) as text'))             
        ->orderBy('text','asc');

        $data= $query->take(50)->get();
        return response()->json($data);
      }catch(Exception $e){
        return redirect('/');
        die();
      }            
    }


    /**
     * Update User's Pasword by admin.
     *
     * @param Request $request     
     */ 
    public function update_user_password(Request $request){

        //echo '<pre>'; print_r($request->all()); echo '</pre>';
        $user =User::where('id', $request->id)->first();
        
        if ($user) {   

            $user->password = Hash::make($request->password);

            if($user->save()){
                $maildata = array(
                            'view'      => 'email.password_update',
                            'email'     => $user->email,
                            'subject'   => __('messages.users.mail_subject'),
                            'name'      => $user->first_name.' '.$user->last_name,
                            'password'  => $request->password,
                            );           
                sendmail($maildata);
                $msg = __('messages.users.password_updated_successfully');
                return response()->json(['status' =>1, 'msg'=>$msg]);
            }
        }else{
            $msg =  __('messages.users.no_user_found');
            return response()->json(['status' =>0, 'msg'=>$msg]);
        }
    }  
}
