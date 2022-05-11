<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Response;
use Illuminate\Support\Facades\Log;


class NftApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        
    }

    // get nft list by name (search)
    public function get_nft_list_by_name(Request $request)
    {
        // $request->name= 'Stuff';
        $yesterdayDate = date('c', strtotime('-1 day', time()));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => ICY_GRAPHQL_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"query":"query BAYCStats($query : String, $first : Int) {\\r\\n    contracts(filter: { name: { \\r\\n      istartswith: $query\\r\\n      }} \\r\\n      orderBy: VOLUME \\r\\n      orderDirection: DESC\\r\\n     first: $first\\r\\n    ) {\\r\\n      edges {\\r\\n        node {\\r\\n          ... on ERC721Contract {\\r\\n            address\\r\\n            name\\r\\n            symbol\\r\\n            unsafeOpenseaImageUrl\\r\\n            \\r\\n            stats(timeRange: { gte: \\"'.$yesterdayDate.'\\"}) {\\r\\n              totalSales\\r\\n              volume\\r\\n            }\\r\\n          }\\r\\n        }\\r\\n      }\\r\\n    }\\r\\n }","variables":{"query":"'.$request->name.'","first":10}}',
            // CURLOPT_POSTFIELDS =>'{"query":"query SearchCollections($query: String!, $first: Int) {\\r\\n  contracts(filter: { \\r\\n    name: {\\r\\n      istartswith: $query, \\r\\n    },\\r\\n   }\\r\\n  first: $first\\r\\n  \\r\\n  ) {\\r\\n    edges {\\r\\n      node {\\r\\n        address\\r\\n        ... on ERC721Contract {\\r\\n          name\\r\\n          symbol\\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n}","variables":{"query":"'.$request->name.'","first":10}}',
            // CURLOPT_POSTFIELDS =>'{"query":"query SearchCollections($query: String!, $first: Int) {\\r\\n  contracts(filter: { \\r\\n    name: {\\r\\n      istartswith: $query, \\r\\n    },\\r\\n   }\\r\\n  first: $first\\r\\n  \\r\\n  ) {\\r\\n    edges {\\r\\n      node {\\r\\n        address\\r\\n        ... on ERC721Contract {\\r\\n          name\\r\\n          symbol\\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n}","variables":{"query":"'.$request->name.'","first":10}}',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'x-api-key: '.ICY_API_KEY,
                'Host: graphql.icy.tools',
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $res = json_decode($response);


        // search from name
        if(isset($res->data->contracts->edges) && count($res->data->contracts->edges) < 10){
            $dataRequired = 10 - count($res->data->contracts->edges);
            $anotherData = $this->get_nftlist_by_param($request->name, $dataRequired, 'name');
            
            if($anotherData->data->contracts->edges && count($anotherData->data->contracts->edges) > 0){
                array_push($res->data->contracts->edges, ...$anotherData->data->contracts->edges);
            }            
        }
        

        // filter data
        $res->data->contracts->edges = $this->filter_redudent_data_from_nft_search($res->data->contracts->edges);

        
        
        // search from symbol
        if(isset($res->data->contracts->edges) && count($res->data->contracts->edges) < 10){
            $dataRequired = 10 - count($res->data->contracts->edges);
            $anotherData = $this->get_nftlist_by_param($request->name, $dataRequired, 'symbol');
            if($anotherData->data->contracts->edges && count($anotherData->data->contracts->edges) > 0){
                array_push($res->data->contracts->edges, ...$anotherData->data->contracts->edges);
            }            
        }

        // filter data
        $res->data->contracts->edges = $this->filter_redudent_data_from_nft_search($res->data->contracts->edges);

        $res->data->contracts->edges = array_values($res->data->contracts->edges);

        // remove redunent variable
        // if($res->data->contracts->edges && count($res->data->contracts->edges) > 0){
        //     $collect_address = [];
        //     $collect_name = [];
        //     $collect_code = [];
        //     $res->data->contracts->edges = array_filter($res->data->contracts->edges, function($elm) use (&$collect_address, &$collect_name, &$collect_code){
        //         if((in_array($elm->node->name, $collect_name) && in_array($elm->node->symbol, $collect_code)) || (in_array($elm->node->address, $collect_address) )){
        //             return false;
        //         }
        //         else {
        //             array_push($collect_address, $elm->node->address);
        //             array_push($collect_name, $elm->node->name);
        //             array_push($collect_code, $elm->node->symbol);
        //             return true;
        //         }
        //     });
        // }
        
        

        if($info['http_code'] == 200){
            return Response::json(['status'=>'success', 'data'=> $res],200);
        }
        return Response::json(['status'=>'error', 'data'=> 'Error while fatching data'],200);
    }

    // get nftlist by both name and code contains query
    public function get_nftlist_by_param($name, $first, $param)
    {
        $curl = curl_init();
        $yesterdayData = date('c', strtotime('-1 day', time()));
        curl_setopt_array($curl, array(
            CURLOPT_URL => ICY_GRAPHQL_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"query":"query BAYCStats($query : String, $first : Int) {\\r\\n    contracts(filter: { '.$param.': { \\r\\n      icontains: $query\\r\\n      }} \\r\\n      orderBy: VOLUME \\r\\n      orderDirection: DESC\\r\\n      first: $first\\r\\n    ) {\\r\\n      edges {\\r\\n        node {\\r\\n          ... on ERC721Contract {\\r\\n            address\\r\\n            name\\r\\n            symbol\\r\\n            unsafeOpenseaImageUrl\\r\\n            \\r\\n            stats(timeRange: { gte: \\"'.$yesterdayData.'\\"}) {\\r\\n              totalSales\\r\\n              volume\\r\\n            }\\r\\n          }\\r\\n        }\\r\\n      }\\r\\n    }\\r\\n }","variables":{"query":"'.$name.'","first":'.$first.'}}',
            // CURLOPT_POSTFIELDS =>'{"query":"query SearchCollections($query: String!, $first: Int) {\\r\\n  contracts(filter: { \\r\\n    '.$param.':{\\r\\n      icontains: $query\\r\\n    }\\r\\n  }\\r\\n  first: $first\\r\\n  \\r\\n  ) {\\r\\n    edges {\\r\\n      node {\\r\\n        address\\r\\n        ... on ERC721Contract {\\r\\n          name\\r\\n          symbol\\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n}","variables":{"query":"'.$name.'","first":'.$first.'}}',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'x-api-key: '.ICY_API_KEY,
                'Host: graphql.icy.tools',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $res = json_decode($response);
        return $res;
    }
    // filter redudent data
    public function filter_redudent_data_from_nft_search($list)
    {
        $collect_address = [];
        $collect_name = [];
        $collect_code = [];


        if($list && count($list) > 0){
            $list = array_filter($list, function($elm) use (&$collect_address, &$collect_name, &$collect_code){
                if((in_array($elm->node->name, $collect_name) && in_array($elm->node->symbol, $collect_code)) || (in_array($elm->node->address, $collect_address) )){
                    return false;
                }
                else {
                    array_push($collect_address, $elm->node->address);
                    array_push($collect_name, $elm->node->name);
                    array_push($collect_code, $elm->node->symbol);
                    return true;
                }
            });
        }
        return $list;
        // pre([$list, $collect_name, $collect_code]);
    }

    // get nft details by address
    public function get_nft_detail_by_address(Request $request)
    {
        try {
            //code...
            $curl = curl_init();
            $todayData = date('c');

            // dd($request->durationFilter);

            if($request->durationFilter == '1 day'){
                $yesterdayData = date('c', strtotime('-1 day', time()));
            }elseif($request->durationFilter == '7 days'){
                $yesterdayData = date('c', strtotime('-7 day', time()));
            }else{
                $yesterdayData = date('c', strtotime('-30 day', time()));
            }

            curl_setopt_array($curl, array(
                CURLOPT_URL => ICY_GRAPHQL_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{"query":"query BAYCStats($address: String!) {\\r\\n  contract(address: $address) {\\r\\n    ... on ERC721Contract {\\r\\n      unsafeOpenseaImageUrl\\r\\n      name\\r\\n      address\\r\\n      stats(timeRange: { gte: \\"'.$yesterdayData.'\\"}) {\\r\\n        average\\r\\n        ceiling\\r\\n        floor\\r\\n        totalSales\\r\\n        volume\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n }","variables":{"address":"'.$request->address.'"}}',
                // CURLOPT_POSTFIELDS =>'{"query":"query CollectionStats($address: String!) {\\r\\n    contract(address: $address) {\\r\\n      ... on ERC721Contract {\\r\\n  name \n      stats(\\r\\n          timeRange: {\\r\\n            gte: \\"'.$yesterdayData.'\\"\\r\\n            lt: \\"'.$todayData.'\\"\\r\\n          }\\r\\n        ) {\\r\\n          floor\\r\\n          volume\\r\\n          totalSales\\r\\n          average\\r\\n          ceiling\\r\\n          \\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n  ","variables":{"address":"'.($request->address ?? null).'"}}',
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'x-api-key: '.ICY_API_KEY,
                    'Host: graphql.icy.tools',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
    
            if($info['http_code'] == 200){
                $result = json_decode($response);
                if(isset($result->data->contract) && $result->data->contract != null){
                    // $result->data->contract->images = $this->get_nft_image($request->address);
                    return Response::json(['status'=>'success', 'data'=> $result->data->contract],200);
                }
            }
            return Response::json(['status'=>'error', 'data'=> 'Error while fatching data'],200);
        } catch (Throwable $th) {
            //throw $th;
            return Response::json(['status'=>'error', 'data'=> $th->getMessage()],200);
        }
    }

    // get Nft image from token
    public function get_nft_image($address = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => ICY_GRAPHQL_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"query":"  query TokenImages($contractAddress: String!, $tokenId: String!) {\\r\\n    token(\\r\\n      contractAddress: $contractAddress,\\r\\n      tokenId: $tokenId,\\r\\n    ) {\\r\\n      ... on ERC721Token {\\r\\n        images {\\r\\n          url\\r\\n          width\\r\\n          height\\r\\n          mimeType\\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n  ","variables":{"contractAddress":"'.$address.'","tokenId":"1"}}',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: '.ICY_API_KEY,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        if($info['http_code'] == 200){
            $result = json_decode($response);
            if(isset($result->data->token) && $result->data->token != null){
                if(isset($result->data->token->images) && !empty($result->data->token->images)){
                    return $result->data->token->images[0];       
                }
            }
        }
        return null;

        echo $response;

    }

    // get Nft history
    public function get_nft_history_by_address(Request $request)
    {
        // $request->address = "0xbc4ca0eda7647a8ab7c2061c2e118a18a936f13d";
        $todayData = date('c');
        $yesterdayData = date('c', strtotime('-1 day', time()));
        $average_array = [];
        $floor_array = [];
        $ceiling_array = [];
        $date_array = [];
        $tooltip_array = [];
        try {
        
            $result = $this->get_weekly_history($request->address, $date_array, $request->duration);
            if(isset($result) && isset($result->data) && !empty($result->data)){
                foreach ($result->data as $key => $collection) {
                    if($collection->stats){
                        $average_array[] = number_format($collection->stats->average, 3);
                        $floor_array[] = number_format($collection->stats->floor, 3);
                        $ceiling_array[] = number_format($collection->stats->ceiling, 3);
                        $tooltip_array[] = "Name: {$collection->name} <br/> 
                        Average: ".number_format($collection->stats->average, 3)." <br/> 
                        Floor: ".number_format($collection->stats->floor, 3)." <br/> 
                        Volume: ".number_format($collection->stats->volume, 3)." <br/> 
                        Total Sales: ".$collection->stats->totalSales." <br/> 
                        Ceiling: ".number_format($collection->stats->ceiling, 3);
                    }
                    else{
                        $average_array[] = 0;
                        $floor_array[] = 0;
                        $ceiling_array[] = 0;
                        $tooltip_array[] = "
                        Average: 0 <br/> 
                        Floor: 0 <br/> 
                        Volume: 0 <br/> 
                        Total Sales: 0 <br/> 
                        Ceiling: 0 ";
                    }
                }
            }
            else{
                throw new Exception("Error while fatching data", 1);
            }

            return Response::json(['status'=>'success', 'data'=> [
                'average' => $average_array,
                'floor' => $floor_array,
                'ceiling' => $ceiling_array,
                'x_axis' => $date_array,
                'tooltip' => $tooltip_array,
                'name'=> $collection->name
            ]],200);

        } catch (Exception $e) {
            return Response::json(['status'=>'error', 'data'=> $e->getMessage()],200);
        }
        
    }


    public function get_history_by_address($address, $fromDate, $toDate)
    {
        usleep(500000);
        $curl = curl_init();
        $todayData = date('c');
        $yesterdayData = date('c', strtotime('-1 day', time()));

        curl_setopt_array($curl, array(
            CURLOPT_URL => ICY_GRAPHQL_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"query":"query CollectionStats($address: String!) {\\r\\n    contract(address: $address) {\\r\\n      ... on ERC721Contract {\\r\\n  name \n      stats(\\r\\n          timeRange: {\\r\\n            gte: \\"'.$fromDate.'\\"\\r\\n            lt: \\"'.$toDate.'\\"\\r\\n          }\\r\\n        ) {\\r\\n          floor\\r\\n          volume\\r\\n          totalSales\\r\\n          average\\r\\n          ceiling\\r\\n          \\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n  ","variables":{"address":"'.($address ?? '').'"}}',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'x-api-key: '.ICY_API_KEY,
                'Host: graphql.icy.tools',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        if($info['http_code'] == 200){
            $result = json_decode($response);
            Log::emergency('@@@@@@'.json_encode($result));
            if(isset($result->data->contract) && $result->data->contract != null){
                return $result->data->contract;
            }
        }
        else{
            Log::emergency('******'.json_encode($info));
            Log::emergency('*************************');
        }
        return null;
    }

    public function get_weekly_history($address = null, &$x_axis, $duration)
    {

        $graphQuery = '{"query":"query BAYCStats($address: String!) {\\r\\n';  
            // {"query":"query BAYCStats($address: String!) {\\r\\n  day1:contract(address: $address) {\\r\\n    ... on ERC721Contract {\\r\\n      stats(timeRange: {gte: \\"2022-03-09T00:00:00.000Z\\", lt: \\"2022-03-10T00:00:00.000Z\\"}) {\\r\\n        volume\\r\\n        average\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n  day2:contract(address: $address) {\\r\\n    ... on ERC721Contract {\\r\\n      stats(timeRange: {gte: \\"2022-02-21T00:00:00.000Z\\", lt: \\"2022-02-28T00:00:00.000Z\\"}) {\\r\\n        volume\\r\\n        average\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n }","variables":{"address":"0xbc4ca0eda7647a8ab7c2061c2e118a18a936f13d"}}

        if($duration == "Month"){
            $duration_count = 30;
        }elseif($duration == "Week"){
            $duration_count = 7;
        }
        else{
            // Hourly
            $duration_count = 24;
        }

        for ($i=$duration_count; $i >= 0; $i--) { 
            if($duration == "Hourly"){
                $fromDate = date('c', strtotime('-'.($i+1).' hour', time()));
                $toDate = date('c', strtotime('-'.($i).' hour', time()));
                $x_axis[] = date('H:00', strtotime('-'.($i).' hour', time()));
            }
            else{
                $fromDate = date('c', strtotime('-'.($i+1).' day', time()));
                $toDate = date('c', strtotime('-'.($i).' day', time()));
                $x_axis[] = date('d-M', strtotime('-'.($i).' day', time()));
            }
            
            $graphQuery .= 'day'.$i.':contract(address: $address) {\\r\\n    ... on ERC721Contract {    name\\r\\n      \\r\\n      stats(timeRange: {gte: \\"'.$fromDate.'\\", lt: \\"'.$toDate.'\\"}) {\\r\\n        volume\\r\\n        average\\r\\n      floor\\r\\n   ceiling\\n\\r    totalSales\\n\\r    }\\r\\n    }\\r\\n  }\\r\\n';
            
            // $result = $this->get_history_by_address($request->address, $fromDate, $toDate);
            // Log::emergency(json_encode($result));

            // if($result && $result->stats){
            //     $average_array[] = number_format($result->stats->average, 3);
            //     $tooltip_array[] = "Name: {$result->name} <br/> 
            //     Average: ".number_format($result->stats->average, 3)." <br/> 
            //     Floor: ".number_format($result->stats->floor, 3)." <br/> 
            //     Volume: ".number_format($result->stats->volume, 3)." <br/> 
            //     Total Sales: ".$result->stats->totalSales." <br/> 
            //     Ceiling: ".number_format($result->stats->ceiling, 3);
            // }
            // else{
            //     $average_array[] = 0;
            //     $tooltip_array[] = "
            //     Average: 0 <br/> 
            //     Floor: 0 <br/> 
            //     Volume: 0 <br/> 
            //     Total Sales: 0 <br/> 
            //     Ceiling: 0 ";
            // }
        }
        $graphQuery .= '}","variables":{"address":"'.$address.'"}}';


        $curl = curl_init();
        $todayData = date('c');
        $yesterdayData = date('c', strtotime('-1 day', time()));

        curl_setopt_array($curl, array(
            CURLOPT_URL => ICY_GRAPHQL_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$graphQuery,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'x-api-key: '.ICY_API_KEY,
                'Host: graphql.icy.tools',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        return json_decode($response);
    }
}

// query Collection($address: String!) {\n  collection(address: $address) {\n    address\n    attributes {\n      count\n      displayType\n      name\n      value\n      __typename\n    }\n    circulatingSupply\n    createdAt\n    description\n    discordUrl\n    externalUrl\n    imageUrl\n    instagramUsername\n    name\n    slug\n    symbol\n    telegramUrl\n    twitterUsername\n    uuid\n    sellerRoyaltyBasisPoints\n    buyerRoyaltyBasisPoints\n    dailyStats {\n      averagePriceInEth\n      maxPriceInEth\n      minPriceInEth\n      numberOfMints\n      numberOfOrders\n      recentFloorPriceInEth\n      volumeInEth\n      topBuyers {\n        amountInEth\n        count\n        wallet {\n          address\n          ensName\n          tags\n          __typename\n        }\n        __typename\n      }\n      topSellers {\n        amountInEth\n        count\n        wallet {\n          address\n          ensName\n          tags\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n}\n
