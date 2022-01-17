<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Exception;
use App\Models\Nftuser;
use App\Models\Nftdetail;
use App\Models\Upcomingnft;
use Mail;
use Validator;
use Session;
use Redirect;

class UpcomingnftController extends Controller{

    public function api_upcomingnft(){
        try {
            $nftdata = Upcomingnft::where('verify',1)
                                    ->get()
                                    ->toArray();
            return Response::json(['status'=>'success', 'data'=>$nftdata],200);
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
    }

    public function api_addupcomingnft(Request $request){
         $validator = Validator::make($request->all(), [
            'project_name' => 'required',
            'website' => 'required|url',
            'release_date' => 'required',
            'release_time' => 'required',
            'timeZoneSelect' => 'required',
            'network' => 'required',
            'upcoming_nft_images' => 'required',
            'upcoming_nft_images.*' => 'required|file|mimes:jpg,jpeg,png|max:51200',
            'briefdescription' => 'required',
            'socialMediaData' => 'required',
        ])
        ->setAttributeNames(
            ['upcoming_nft_images.*' => 'Upcoming nft image'], // Your field name and alias
        );
 
         if ($validator->fails()) {
             return Response::json(['status'=>'validation_error', 'message'=>$validator->errors()], 422);
         }

         
         try {
            $data['project_name'] = $request->project_name;
            $data['website'] = $request->website;
            $data['timeZoneSelect'] = $request->timeZoneSelect;
            $data['network'] = $request->network;
            $data['release_date'] = date('Y-m-d',strtotime($request->release_date));
            $data['release_time'] = date('H:i:s',strtotime($request->release_time));
            $data['socialmedia'] = $request->socialMediaData;
            $data['briefdescription'] = $request->briefdescription;
            $data['network'] = $request->network;
            $data['max_number_collection'] = $request->max_number_collection ?? '';
            $data['unit_price_eth'] = $request->unit_price_eth ?? '';


            $files = $request->file('upcoming_nft_images');
            
            if($request->hasFile('upcoming_nft_images'))
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
            

             /*$name = time().'-'.$request->file('nft_image')->getClientOriginalName();
             if($request->file('nft_image')->storeAs('public/images/nftimages/',$name)){
                 $data['image'] = $name;
             }else{
                 $data['image'] = Null;
             }*/
            
            
            
             // send thankyou mail to customer
             /*$to = $request->email;
             $name = ucfirst($request->fname).' '.ucfirst($request->lname);
             $maildata = ['name'=>$name];
             
             Mail::send('mail-templates.thankyou', $maildata, function($mail) use ($to){
                 $mail->to($to)
                     ->subject('NFT Submitted')
                     ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
             });*/
             
             $randomString = generate_random_string(30);
             $data['verify_token'] = $randomString;
             $id = Upcomingnft::insertGetId($data);
             
             //send submit nft mail to admin
             $to = env('ADMIN_EMAIL_ADDRESS');
             $link = "<a href='".route('verify-upcoming-nft',[$randomString])."'>Verify</a>";

             $project_name = $request->project_name;
             $website = $request->website;
             $release_date = $request->release_date;
             $release_time = $request->release_time;
             
             $maildata = ['link'=>$link,'release_date'=>$release_date,'release_time'=>$release_time,'project_name'=>$project_name,'website'=>$website, 'timezone'=> $request->timeZoneSelect];
             
             Mail::send('mail-templates.submitupcomingnft', $maildata, function($mail) use ($to){
                 $mail->to($to)
                     ->subject('Gems Tools | Upcoming NFT Request')
                     ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
             });
             
             return Response::json(['status'=>'success', 'message'=>'Upcoming NFT detail submitted successfully', 'data'=>$data],200);
         } catch (Exception $e) {
             return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
         }
    }
    
    public function verifyupcomingnft(Request $request){
        $checkToken = Upcomingnft::where('verify_token',$request->token)
                                ->where('verify',0)
                                ->first();
        if(!$checkToken){
            Session::flash('error','Sorry! Upcoming NFT not fount, Or link Expired.');
            return Redirect::route('upcoming-nft');
        }
        $data['nfeDetail'] = $checkToken;
        // pre($data['nfeDetail']);
        return view('verifyupcomingnft',$data);
    }

    public function verifysubmitupcomingnft(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'project_name' => 'required',
            'website' => 'required|url',
            'release_date' => 'required',
            'release_time' => 'required',
            'timeZoneSelect' => 'required',
            'unit_price_eth' => 'required',
            'max_number_collection' => 'required',
            'briefdescription' => 'required',
            'network' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
       
        $status = Upcomingnft::where('id', $request->id)->update([
            "project_name" => $request->project_name,
            "website" => $request->website,
            "release_date" => date('Y-m-d',strtotime($request->release_date)),
            "release_time" => date('H:i:s',strtotime($request->release_time)),
            "timeZoneSelect" => $request->timeZoneSelect,
            "briefdescription" => $request->briefdescription,
            "network" => $request->network,
            "unit_price_eth" => $request->unit_price_eth,
            "max_number_collection" => $request->max_number_collection,
            "verify" => ($request->verify ?? false),
            "verify_token"=> null
        ]);
        
        /* $to = $request->user_email;
        $name = ucfirst($request->user_first_name).' '.ucfirst($request->user_last_name);
        $state = ($request->verify ?? false);
        $maildata = ['status'=>$state, 'name'=>$name];

        Mail::send('mail-templates.nftstatus', $maildata, function($mail) use ($to){
            $mail->to($to)
                ->subject('NFT Status')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });*/

        if($status){
            return Redirect::route('upcoming-nft')->with('success', 'Nft updated successfully');
        }
        return redirect()->back()->with('error', 'Nft while updating Nft');
    }

    public function upcomingnft(Request $request){
        if(isset($request->id) && $request->id != ''){
            $data['selectedNft'] = Upcomingnft::where('id',$request->id)->first();
            if(!$data['selectedNft']){
                return redirect()->back()->with('error', 'Sorry! NFT not found.');
            }
        }
        $data['upcomingnftdata'] = Upcomingnft::where('verify',1)->get()->toArray();
        return view('upcomingnft',$data);
    }

    public function addupcomingnft(Request $request){
        
        if(isset($request->id) && $request->id != ''){
            $validator = Validator::make($request->all(), [
                'project_name' => 'required',
                'website' => 'required|url',
                'release_date' => 'required',
                'release_time' => 'required',
                'social_media_array' => 'required',
                'briefdescription' => 'required',
                'timeZoneSelect' => 'required',
                'network' => 'required',
                'unit_price_eth'=> 'nullable|numeric|min:0'
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'project_name' => 'required',
                'website' => 'required|url',
                'release_date' => 'required',
                'release_time' => 'required',
                'social_media' => 'required',
                'briefdescription' => 'required',
                'timeZoneSelect' => 'required',
                'network' => 'required',
                'social_media_link' => 'required',
                'upcoming_nft_image' => 'required',
                'upcoming_nft_image.*' => 'required|file',
                'unit_price_eth'=> 'nullable|numeric|min:0'
            ]);

        }

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        $data['project_name'] = $request->project_name;
        $data['website'] = $request->website;
        $data['release_date'] = date('Y-m-d',strtotime($request->release_date));
        $data['release_time'] = date('H:i:s',strtotime($request->release_time));
        $data['socialmedia'] = $request->social_media;
        $data['briefdescription'] = $request->briefdescription;
        $data['timeZoneSelect'] = $request->timeZoneSelect;
        $data['network'] = $request->network;
        $data['verify'] = 1;
        $data['max_number_collection'] = $request->max_number_collection;
        $data['unit_price_eth'] = $request->unit_price_eth * 1;

        

        $newimages = [];
        if($request->file()){
            foreach($request->file('upcoming_nft_image') as $image){
                $name = time().'-'.$image->getClientOriginalName();
                if($image->storeAs('public/images/nftimages/',$name)){
                    $newimages[] = $name;
                }
            }
        }
        
        
        if(isset($request->id) && $request->id != ''){
            
            $newimages = array_merge($newimages, $request->old_images ?? []);
            $newimages = array_values($newimages);
            $data['images'] = json_encode($newimages);


            if($request->social_media != null && $request->social_media_link != null ){
                $socialMediaAssoc = [
                    ["media" => $request->social_media, "media_link" => $request->social_media_link]
                ];
                $socialMediaAssoc = array_values(array_merge($socialMediaAssoc, json_decode($request->social_media_array, true)));
                $data['socialmedia'] = json_encode($socialMediaAssoc);
            }
            else{
                $data['socialmedia'] = $request->social_media_array;
            }
            

            if(Upcomingnft::where('id',$request->id)->update($data)){
                Session::flash('success', 'Upcoming NFT updated Successfully.');
            }else{
                Session::flash('error', 'Error! while updating Upcoming NFT.');
            }
        }else{
            $data['images'] = json_encode($newimages);
            $socialMediaAssoc = [
                ["media" => $request->social_media, "media_link" => $request->social_media_link]
            ];
            $data['socialmedia'] = json_encode($socialMediaAssoc);

            if(Upcomingnft::insert($data)){
                Session::flash('success', 'New Upcoming NFT Added Successfully.');
            }else{
                Session::flash('error', 'Error! while adding Upcoming NFT.');
            }
        }
        return Redirect::route('upcoming-nft');
    }

    public function deleteupcomingnft(Request $request){
        if(Upcomingnft::where('id',$request->id)->delete()){
            return redirect()->back()->with('success', 'NFT Deleted Successfully.');
        }else{
            return redirect()->back()->with('error', 'Error! while delete NFT.');
        }
    }

}
