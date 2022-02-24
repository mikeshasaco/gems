<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Exception;
use App\Models\Nftuser;
use App\Models\Nftdetail;
use Mail;
use Validator;
use Session;
use Redirect;

class NftuserController extends Controller{
    
    public function api_trendingnft(){
        try {
            $userdata = Nftdetail::join('nftusers','nftdetails.user_id','=','nftusers.id')
                                      ->select('nftdetails.*','nftusers.*')
                                      ->where('verify',1)
                                      ->orderBy('total_likes','DESC')
                                      ->limit(5)
                                      ->get()
                                      ->toArray();
            return Response::json(['status'=>'success', 'data'=>$userdata],200);
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
    }

    public function api_userlists(){
        try {
            $userdata = Nftdetail::join('nftusers','nftdetails.user_id','=','nftusers.id')
                                      ->select('nftdetails.*','nftusers.*','nftdetails.id as id')
                                      ->where('verify',1)
                                      ->get()
                                      ->toArray();
            return Response::json(['status'=>'success', 'data'=>$userdata],200);
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
    }

    // filter NftList
    public function filter_nftlists(Request $request)
    {
        try {
            $userdata = Nftdetail::join('nftusers','nftdetails.user_id','=','nftusers.id')
                                      ->select('nftdetails.*','nftusers.*','nftdetails.id as id')
                                      ->selectRaw('(
                                          (nftdetails.popularity + 
                                          nftdetails.community + 
                                          nftdetails.originality + 
                                          IFNULL(nftdetails.growth_evaluation, 0) + 
                                          IFNULL(nftdetails.resell_evaluation, 0) + 
                                          (IFNULL(nftdetails.potential_blue_chip, 0) * 10)) / 6)

                                          as average')
                                      ->where('verify',1);
            if($request->name && $request->name != ''){
                $name = clean($request->name);
                $userdata->where(function($query) use ($name){
                    $query->where('nft_name', 'like', '%'.$name.'%');
                    $query->orWhere('nft_link', 'like', '%'.$name.'%');
                });
            }
            // return Response::json(['status'=>'success', 'data'=>$request->utility],200);

            if($request->utility && $request->utility != '' && is_array($request->utility)){
                $userdata->where(function($query) use ($request){
                    $query->whereIn('utility', $request->utility);
                });
            }
            
            if($request->average && !empty($request->average)){
                $userdata->havingRaw('average BETWEEN ? AND ?',  $request->average);
            }

            if($request->average_sort){
                if($request->average_sort == 'high_to_low'){
                    $userdata->orderBy('average', 'DESC');
                }
                else{
                    $userdata->orderBy('average', 'ASC');
                }
            }
            else{
                $userdata->orderBy('nftdetails.id', 'ASC');
            }


            $userdata =   $userdata->get()->toArray();
            return Response::json(['status'=>'success', 'data'=>$userdata],200);
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
    }

    public function api_checkemail(Request $request){
        if(!isset($request->email) || $request->email == ''){
            return Response::json(['status'=>'error', 'message'=>'please enter email.'],422);
        }
        try {
            $userdata = Nftuser::where('email',$request->email)->first();
            if($userdata){
                return Response::json(['status'=>'success', 'message'=>'Email already exists. Your detail will be override.'],200);
            }else{
                return Response::json(['status'=>'success', 'message'=>''],200);
            }
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
    }

    public function api_adduser(Request $request){
       $validator = Validator::make($request->all(), [
            'twitter_link' => 'required|url',
            'collection_blockchain' => 'required',
            'maximum_number_in_collection' => 'required|numeric',
            'nft_image' => 'required',
            'nft_image.*' => 'required|mimes:jpg,jpeg,png|max:51200',
            'nft_name' => 'required',
            'nft_link' => 'required',
            // 'wallet_address' => 'required',
        ])
        ->setAttributeNames(
            ['nft_image.*' => 'Nft image'],
        );

        if ($validator->fails()) {
            return Response::json(['status'=>'validation_error', 'message'=>$validator->errors()], 422);
        }
        

        try {

            $data['email'] = $request->email ?? null;
            $data['project_name'] = $request->project_name ?? null;
            $data['opensea_link'] = $request->opensea_link ?? null;
            // $data['wallet_address'] = $request->wallet_address ?? null;
            $data['twitter_link'] = $request->twitter_link;
            $data['discord_link'] = $request->discord_link ?? null;
            $data['maximum_number_in_collection'] = $request->maximum_number_in_collection;
            $data['collection_blockchain'] = $request->collection_blockchain;
            $data['collection_contract_address'] = $request->collection_contract_address ?? null;
            $data['item_sold'] = $request->item_sold ?? null;


            $userdata = Nftuser::where('email',$request->email)->first();
            if($userdata && $request->email != ""){
                $userId = $userdata->id;
                Nftuser::where('id',$userId)->update($data);
            }else{
                $userId = Nftuser::insertGetId($data);
            }


            // send thankyou mail to customer
            if($request->email){
                $to = $request->email;
                // $name = ucfirst($request->fname).' '.ucfirst($request->lname);
                $name = "User";
                $maildata = ['name'=>$name];
                
                Mail::send('mail-templates.thankyou', $maildata, function($mail) use ($to){
                    $mail->to($to)
                        ->subject('GemsTools | Grading NFT Request')
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                });
            }



            // upload Nft image 

            $files = $request->file('nft_image');
            
            if($request->hasFile('nft_image'))
            {
                foreach ($files as $file) {
                    $name = time().'-'.$file->getClientOriginalName();
                    $file->storeAs('public/images/nftimages/',$name);
                    $data['images'][] = $name;
                }
            }
            if(isset($data['images']) && count($data['images']) > 0){
                $data['images'] = json_encode($data['images']);
            }
            
            
            //nft detail
            $nftdata['user_id'] = $userId;
            $nftdata['nft_name'] = $request->nft_name;
            $nftdata['nft_link'] = $request->nft_link;
            $nftdata['image'] = $data['images'];
            
            $randomString = generate_random_string(30);
            $nftdata['verify_token'] = $randomString;
            Nftdetail::insert($nftdata);
            
            //send submit nft mail to admin
            $to = env('ADMIN_EMAIL_ADDRESS');
            $link = "<a href='".route('verify-nft',[$randomString])."'>Verify</a>";
            $project_name = $request->project_name ?? 'NaN';
            $email = $request->email ?? 'NaN';
            $twitter_link = $request->twitter_link;
            $maximum_number_in_collection = $request->maximum_number_in_collection;
            $collection_blockchain = $request->collection_blockchain;
            $nft_name = $request->nft_name;
            $nft_link = $request->nft_link;
            
            $maildata = ['link'=>$link,'project_name'=>$project_name,'email'=>$email,'twitter_link'=> $twitter_link, 'maximum_number_in_collection' => $maximum_number_in_collection ,'collection_blockchain'=> $collection_blockchain ,'nft_name'=>$nft_name,'nft_link'=>$nft_link];
            
            Mail::send('mail-templates.submitnft', $maildata, function($mail) use ($to){
                $mail->to($to)
                    ->subject('Grading NFT')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });

            
            return Response::json(['status'=>'success', 'message'=>'NFT detail submitted successfully', 'data'=>$data],200);
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
    }

    public function ccc(){
        $maildata = ['link'=>'<a href="">google.co.in</a>','release_date'=>'2021-01-14','release_time'=>'03:16','project_name'=>'Gems Tools','website'=>'khabarnath.com'];
        // $maildata = ['name'=>'Kasimali','email'=>'kasimali.dhuka@qalbit.com','phone'=>'9852364170','nft_name'=>'Gems Tools','nft_link'=>'khabarnath.com', 'link'=>'https://google.com'];
        // return view('mail-templates.submitupcomingnft', $maildata);
        return view('mail-templates.submitupcomingnft', $maildata);
    }

    public function userlist(Request $request){
        if(isset($request->id) && $request->id != ''){
            $data['selectedUser'] = Nftuser::where('id',$request->id)->first();
            if(!$data['selectedUser']){
                return redirect()->back()->with('error', 'Sorry! user not found.');
            }
        }
        $data['userdata'] = Nftuser::get()->toArray();
        return view('userlist',$data);
    }

    public function adduser(Request $request){
        
        if(isset($request->id) && $request->id != ''){
            $validator = Validator::make($request->all(), [
                'twitter_link' => 'required|url',
                'discord_link' => 'nullable|url',
                'maximum_number_in_collection' => 'required',
                'email' => 'email|unique:nftusers,email,'.$request->id,
                'collection_blockchain' => 'required',
                // 'wallet_address' => 'required',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'twitter_link' => 'required|url',
                'discord_link' => 'nullable|url',
                'maximum_number_in_collection' => 'required',
                'email' => 'nullable|email|unique:nftusers',
                'collection_blockchain' => 'required',
                // 'wallet_address' => 'required',
            ]);
        }
        

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        //user detail
        $data['project_name'] = $request->project_name;
        $data['email'] = $request->email;
        $data['opensea_link'] = $request->opensea_link;
        // $data['wallet_address'] = $request->wallet_address;
        $data['twitter_link'] = $request->twitter_link;
        $data['discord_link'] = $request->discord_link;
        $data['maximum_number_in_collection'] = $request->maximum_number_in_collection;
        $data['item_sold'] = $request->item_sold;
        $data['collection_blockchain'] = $request->collection_blockchain;
        $data['collection_contract_address'] = $request->collection_contract_address;

        if(isset($request->id) && $request->id != ''){
            if(Nftuser::where('id',$request->id)->update($data)){
                Session::flash('success', 'user updated Successfully.');
            }else{
                Session::flash('error', 'Error! while updating user.');
            }
        }else{
            if(Nftuser::insert($data)){
                Session::flash('success', 'New user Added Successfully.');
            }else{
                Session::flash('error', 'Error! while adding user.');
            }
        }
        return Redirect::route('userlist');
    }

    public function deleteuser(Request $request){
        Nftdetail::where('user_id',$request->id)->delete();
        if(Nftuser::where('id',$request->id)->delete()){
            return redirect()->back()->with('success', 'User Deleted Successfully.');
        }else{
            return redirect()->back()->with('error', 'Error! while delete user.');
        }
    }

    public function userdetail(Request $request){
        $data['userdata'] = Nftuser::where('id',$request->id)->first();
        if(!$data['userdata']){
            return redirect()->back()->with('error', 'Sorry! user not found.');
        }
        $totalLikes = 0;
        $data['nftdata'] = Nftdetail::where('user_id',$request->id)->get();
        foreach($data['nftdata'] as $nft){
            $totalLikes += $nft->total_likes;
        }
        $data['totalLikes'] = $totalLikes;
        return view('userdetail',$data);
    }
}
