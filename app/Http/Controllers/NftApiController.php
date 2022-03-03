<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Response;


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
            CURLOPT_POSTFIELDS =>'{"query":"\\r\\n  query SearchCollections($query: String!, $first: Int) {\\r\\n    contracts(filter: { name: { icontains: $query, contains: $query }}, first: $first) {\\r\\n      edges {\\r\\n        node {\\r\\n          address\\r\\n          ... on ERC721Contract {\\r\\n            name\\r\\n            symbol\\r\\n          }\\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n  ","variables":{"query":"'.$request->name.'","first":10}}',
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
            return Response::json(['status'=>'success', 'data'=> json_decode($response)],200);
        }
        return Response::json(['status'=>'error', 'data'=> 'Error while fatching data'],200);
    }

    // get nft details by address
    public function get_nft_detail_by_address(Request $request)
    {
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
            CURLOPT_POSTFIELDS =>'{"query":"query CollectionStats($address: String!) {\\r\\n    contract(address: $address) {\\r\\n      ... on ERC721Contract {\\r\\n  name \n      stats(\\r\\n          timeRange: {\\r\\n            gte: \\"'.$yesterdayData.'\\"\\r\\n            lt: \\"'.$todayData.'\\"\\r\\n          }\\r\\n        ) {\\r\\n          floor\\r\\n          volume\\r\\n          totalSales\\r\\n          average\\r\\n          ceiling\\r\\n          \\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n  ","variables":{"address":"'.($request->address ?? null).'"}}',
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
                $result->data->contract->images = $this->get_nft_image($request->address);
                return Response::json(['status'=>'success', 'data'=> $result->data->contract],200);
            }
        }
        return Response::json(['status'=>'error', 'data'=> 'Error while fatching data'],200);
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
        $todayData = date('c');
        $yesterdayData = date('c', strtotime('-1 day', time()));
        $average_array = [];
        $date_array = [];
        $tooltip_array = [];
        for ($i=7; $i >= 0; $i--) { 
            $fromDate = date('c', strtotime('-'.($i+1).' day', time()));
            $toData = date('c', strtotime('-'.($i).' day', time()));
            $result = $this->get_history_by_address($request->address, $fromDate, $toData);
            $date_array[] = date('d-M', strtotime('-'.($i).' day', time()));
            if($result->stats){
                $average_array[] = number_format($result->stats->average, 3);
                $tooltip_array[] = "Name: {$result->name} <br/> 
                Average: ".number_format($result->stats->average, 3)." <br/> 
                Floor: ".number_format($result->stats->floor, 3)." <br/> 
                Volume: ".number_format($result->stats->volume, 3)." <br/> 
                Total Sales: ".$result->stats->totalSales." <br/> 
                Ceiling: ".number_format($result->stats->ceiling, 3);
            }
            else{
                $average_array[] = 0;
                $tooltip_array[] = "Name: {$result->name} <br/> 
                Average: 0 <br/> 
                Floor: 0 <br/> 
                Volume: 0 <br/> 
                Total Sales: 0 <br/> 
                Ceiling: 0 ";
            }
        }
        return Response::json(['status'=>'success', 'data'=> [
            'average' => $average_array,
            'x_axis' => $date_array,
            'tooltip' => $tooltip_array
        ]],200);

    }


    public function get_history_by_address($address, $fromDate, $toData)
    {
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
            CURLOPT_POSTFIELDS =>'{"query":"query CollectionStats($address: String!) {\\r\\n    contract(address: $address) {\\r\\n      ... on ERC721Contract {\\r\\n  name \n      stats(\\r\\n          timeRange: {\\r\\n            gte: \\"'.$fromDate.'\\"\\r\\n            lt: \\"'.$toData.'\\"\\r\\n          }\\r\\n        ) {\\r\\n          floor\\r\\n          volume\\r\\n          totalSales\\r\\n          average\\r\\n          ceiling\\r\\n          \\r\\n        }\\r\\n      }\\r\\n    }\\r\\n  }\\r\\n  ","variables":{"address":"'.($address ?? null).'"}}',
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
                return $result->data->contract;
            }
        }
        return null;
    }
}

// query Collection($address: String!) {\n  collection(address: $address) {\n    address\n    attributes {\n      count\n      displayType\n      name\n      value\n      __typename\n    }\n    circulatingSupply\n    createdAt\n    description\n    discordUrl\n    externalUrl\n    imageUrl\n    instagramUsername\n    name\n    slug\n    symbol\n    telegramUrl\n    twitterUsername\n    uuid\n    sellerRoyaltyBasisPoints\n    buyerRoyaltyBasisPoints\n    dailyStats {\n      averagePriceInEth\n      maxPriceInEth\n      minPriceInEth\n      numberOfMints\n      numberOfOrders\n      recentFloorPriceInEth\n      volumeInEth\n      topBuyers {\n        amountInEth\n        count\n        wallet {\n          address\n          ensName\n          tags\n          __typename\n        }\n        __typename\n      }\n      topSellers {\n        amountInEth\n        count\n        wallet {\n          address\n          ensName\n          tags\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n}\n
