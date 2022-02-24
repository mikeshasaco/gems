<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nftuser;
use App\Models\Nftdetail;
use App\Models\Nftgraph;
use Validator;
use Session;
use Redirect;
use Response;
use DB;
use Mail;

class NftdetailController extends Controller{

    public function api_like(Request $request){
        if(!isset($request->id) || $request->id == ''){
            return Response::json(['status'=>'error', 'message'=>'NFT not found.'],422);
        }
        try {
            $nftdata = Nftdetail::where('id',$request->id)->first();
            if(!$nftdata){
                return Response::json(['status'=>'error', 'message'=>'NFT not found.'],422);
            }

            if(!Nftdetail::where('id',$request->id)->update(['total_likes' => ($nftdata->total_likes + 1)])){
                return Response::json(['status'=>'error', 'message'=>'Error while Nft Like.'],422);
            }

            //insert for graph
            $currentYear = date('Y');
            $currentMonth = date('m');
            
            $result = DB::select("SELECT * FROM nftgraphs WHERE YEAR(created_at) = $currentYear AND MONTH(created_at) = $currentMonth AND nft_id = $request->id");
            if($result){
                $countLike = $result[0]->likes ?? 0;
                DB::select("Update nftgraphs set likes = ".($countLike+1)." WHERE YEAR(created_at) = $currentYear AND MONTH(created_at) = $currentMonth AND nft_id = $request->id");
            }else{
                $dateTime  = date('Y-m-d');
                Nftgraph::insert(['nft_id' => $request->id, 'likes' => 1, 'created_at' => $dateTime]);
            }
           
            return Response::json(['status'=>'success', 'message'=>'You have liked successfully.'],200);
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
    }

    public function api_graphdata(Request $request){
        try{
            $finalResult = [];
            $tempresult = [];
            for ($i = 0; $i < 5; $i++) {
                if($i == 0){
                    $dtCollection = explode("-",date('m-M-Y'));
                }else{
                    $dtCollection = explode("-",date('m-M-Y', strtotime("-$i month")));
                }

                $finalResult['months'][] = $dtCollection[1];

                $results = DB::select("SELECT nftgraphs.*,nftusers.*, nftdetails.* FROM nftgraphs 
                                    join nftdetails on nftdetails.id = nftgraphs.nft_id
                                    join nftusers on nftdetails.user_id = nftusers.id
                                    WHERE YEAR(nftgraphs.created_at) = $dtCollection[2] AND MONTH(nftgraphs.created_at) = $dtCollection[0] order by likes DESC limit 5");
                
                for($j = 0; $j < 5; $j++){
                    $tempresult[$dtCollection[1]]['likes'][] =  $results[$j]->likes ?? 0;

                    if(isset($results[$j]->popularity) && $results[$j]->popularity != ''){
                        $average = ((int)$results[$j]->popularity + (int)$results[$j]->community + (int)$results[$j]->originality + 
                        (int)($results[$j]->growth_evaluation ?? 0) + (int)($results[$j]->resell_evaluation ?? 0) + ($results[$j]->potential_blue_chip ?  (int)$results[$j]->potential_blue_chip * 10 : 0)
                        )/6;
                        $tooltip_html = 'Name: '.$results[$j]->nft_name."<br/> Like: ".$results[$j]->likes."<br/> Average: ".number_format($average, 2);
                        $tempresult[$dtCollection[1]]['tooltip_html'][] =  $tooltip_html;
                    }else{
                        $tempresult[$dtCollection[1]]['tooltip_html'][] =  '';
                    }
                }
                
            }
            

            $months = array_keys($tempresult);
            foreach ($tempresult as $key => $value) {
                foreach ($value['likes'] as $k => $v) { 
                    $finalResult['data']['likes'][$k][] = $v;
                }

                foreach ($value['tooltip_html'] as $k => $v) { 
                    $finalResult['data']['tooltip'][$k][] = $v;
                }
            }
            // pre($finalResult);
            return Response::json(['status'=>'success', 'data'=>$finalResult],200);
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
        
    }

    // get utility for search
    public function api_getutility(Request $request)
    {
        try {
            $data = Nftdetail::distinct()->where('utility','!=', null)->get(['utility'])->toArray();
            $data = array_column($data, 'utility');
            return Response::json(['status'=>'success', 'data'=>$data],200);
        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'message'=>$e->getMessage()],422);
        }
    }

    public function verifynft(Request $request){
        $checkToken = Nftdetail::join('nftusers','nftdetails.user_id','=','nftusers.id')
                                ->select('nftdetails.*','project_name','email','opensea_link','twitter_link','discord_link','maximum_number_in_collection','collection_blockchain','collection_contract_address','item_sold')
                                ->where('verify_token',$request->token)
                                ->where('verify',0)
                                ->first();
        if(!$checkToken){
            Session::flash('error','Sorry! user not fount, Or link Expired.');
            return Redirect::route('userlist');
        }
        $data['nfeDetail'] = $checkToken;
        // pre($data['nfeDetail']);
        return view('verifynft',$data);
    }

    public function verifysubmitnft(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'userid' => 'required',
            // 'twitter_link' => 'required|url',
            // 'maximum_number_in_collection' => 'required',
            // 'collection_blockchain' => 'required',
            'nft_name' => 'required',
            'nft_link' => 'required|url',
            'popularity' => 'required|numeric|min:1|max:100',
            'community' => 'required|numeric|min:1|max:100',
            'originality' => 'required|numeric|min:1|max:100',
            'growth_evaluation' => 'required|numeric|min:1|max:100',
            'resell_evaluation' => 'required|numeric|min:1|max:100',
            'potential_blue_chip' => 'required|numeric|min:1|max:10',
            'utility' => 'required',
            'total' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $status = Nftdetail::where('id', $request->id)->update([
            "nft_name" => $request->nft_name,
            "nft_link" => $request->nft_link,
            "popularity" => $request->popularity,
            "community" => $request->community,
            "utility" => $request->utility,
            "originality" => $request->originality,
            "growth_evaluation" => $request->growth_evaluation,
            "resell_evaluation" => $request->resell_evaluation,
            "potential_blue_chip" => $request->potential_blue_chip,
            "total" => $request->total,
            "verify" => ($request->verify ?? false),
            "verify_token"=> null
        ]);

        if($request->email && $request->email != ''){
            $to = $request->email;
            $name = "User";
            $state = ($request->verify ?? false);
            $maildata = ['status'=>$state, 'name'=>$name];

            Mail::send('mail-templates.nftstatus', $maildata, function($mail) use ($to){
                $mail->to($to)
                    ->subject('NFT Status')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
        }

        if($status){
            return Redirect::route('userlist')->with('success', 'Nft updated successfully');
        }
        return redirect()->back()->with('error', 'Nft while updating Nft');
    }

    public function nftlist(Request $request){
        if(isset($request->id) && $request->id != ''){
            $data['selectedNFT'] = Nftdetail::where('id',$request->id)->first();
            if(!$data['selectedNFT']){
                return redirect()->back()->with('error', 'Sorry! NFT not found.');
            }
        }
        $data['nftdata'] = Nftdetail::join('nftusers','nftdetails.user_id','=','nftusers.id')
                                      ->select('nftdetails.*','project_name','email','opensea_link','twitter_link','discord_link','maximum_number_in_collection','collection_blockchain','collection_contract_address','item_sold')
                                      ->where('verify',1)
                                      ->get()
                                      ->toArray();
        $data['users'] = Nftuser::get()->toArray();                
        return view('nftlist',$data);
    }

    public function addnft(Request $request){
        if(isset($request->id) && $request->id != ''){
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'nft_name' => 'required',
                'nft_link' => 'required',
                'popularity' => 'required|numeric|min:1|max:100',
                'community' => 'required|numeric|min:1|max:100',
                'originality' => 'required|numeric|min:1|max:100',
                'growth_evaluation' => 'required|numeric|min:1|max:100',
                'resell_evaluation' => 'required|numeric|min:1|max:100',
                'potential_blue_chip' => 'required|numeric|min:1|max:10',
                'total' => 'required',
                'utility' => 'required',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'nft_name' => 'required',
                'nft_link' => 'required',
                'popularity' => 'required|numeric|min:1|max:100',
                'community' => 'required|numeric|min:1|max:100',
                'originality' => 'required|numeric|min:1|max:100',
                'growth_evaluation' => 'required|numeric|min:1|max:100',
                'resell_evaluation' => 'required|numeric|min:1|max:100',
                'potential_blue_chip' => 'required|numeric|min:1|max:10',
                'total' => 'required',
                'utility' => 'required',
                'nft_image' => 'required',
                'nft_image.*' => 'required|file'
            ]);
            
        }
       

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        //user detail
        $data['user_id'] = $request->user_id;
        $data['nft_name'] = $request->nft_name;
        $data['nft_link'] = $request->nft_link;
        $data['popularity'] = $request->popularity;
        $data['community'] = $request->community;
        $data['originality'] = $request->originality;
        $data['growth_evaluation'] = $request->growth_evaluation;
        $data['resell_evaluation'] = $request->resell_evaluation;
        $data['potential_blue_chip'] = $request->potential_blue_chip;
        $data['total'] = $request->total;
        $data['utility'] = $request->utility;
        $data['verify'] = 1;

        $newimages = [];

        if($request->file()){
            foreach($request->file('nft_image') as $image){
                $name = time().'-'.$image->getClientOriginalName();
                if($image->storeAs('public/images/nftimages/',$name)){
                    $newimages[] = $name;
                }
            }
        }
        
        if(isset($request->id) && $request->id != ''){
            $newimages = array_merge($newimages, $request->old_images ?? []);
            $newimages = array_values($newimages);

            $data['image'] = !empty($newimages) ? json_encode($newimages) : null;
            
            if(Nftdetail::where('id',$request->id)->update($data)){
                Session::flash('success', 'NFT updated Successfully.');
            }else{
                Session::flash('error', 'Error! while updating NFT.');
            }
        }else{
            $data['image'] = !empty($newimages) ? json_encode($newimages) : null;
            
            if(Nftdetail::insert($data)){
                Session::flash('success', 'New NFT Added Successfully.');
            }else{
                Session::flash('error', 'Error! while adding NFT.');
            }
        }
        return Redirect::route('nftlist');
    }

    public function deletenft(Request $request){
        if(Nftdetail::where('id',$request->id)->delete()){
            return redirect()->back()->with('success', 'NFT Deleted Successfully.');
        }else{
            return redirect()->back()->with('error', 'Error! while delete NFT.');
        }
    }
}
