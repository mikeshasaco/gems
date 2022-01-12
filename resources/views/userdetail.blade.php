@extends('layouts.app')

@section('content')
<main class="main-spacing">
    <!-- User NFT detail start -->
    <section class="user-nft-detail">
        <div class="container-lg">
            <div class="row grid-container">
                <div class="col-lg-4 col-md-5 left-block">
                    <div class="user-detail">
                        <div class="heading">
                            <h1> <span class="highlight"> User </span> Detail </h1>
                        </div>
                        <div class="user-detail-tab">
                            <div class="tab-wrapper">
                                <div class="content">
                                    <div class="image">
                                        <img src="{{ asset('images/user-dp.png') }}" alt="User profile image" width="90px" height="90px">
                                    </div>
                                    <div class="user-name">
                                        <span>{{ $userdata['fname'].' '.$userdata['lname'] }}</span>
                                    </div>
                                    <div class="user-email">
                                        <span>{{ $userdata['email'] }}</span>
                                    </div>
                                    <div class="user-phone">
                                        <a href="tel:{{ $userdata['phone'] }}">{{ $userdata['phone'] }}</a>
                                    </div>
                                    <div class="nft-status">
                                        <div class="nft-like">
                                            <span>{{ sprintf('%02d', $totalLikes) }}</span> <span class="icon"><i class="fas fa-thumbs-up"></i></span>
                                        </div>
                                        <div class="nft-rank">
                                            <span>NFTs</span> <span class="rank">{{ sprintf('%02d', count($nftdata)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-7 right-block">
                    <div class="user-nft-list">
                        <div class="list-wrapper">
                            <div class="heading">
                                <h2><span class="highlight">NFTs</span> List</h2>
                            </div>
                            <div class="detail-list table-responsive">
                            @forelse($nftdata as $nft)
                                <div class="detail-nft">
                                    <div class="nft-header">
                                        <h2>{{ $nft->nft_name }}</h2>
                                    </div>
                                    <div class="nft-content">
                                        <div class="nft-stats w-100 flex-wrap">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="popularity">
                                                            <div class="table-detail-column-data">
                                                                <span class="stat text-left">% NFTs Sold</span>
                                                                <span class="stat color-orange">{{ $nft->popularity ?? 0 }}%</span>
                                                            </div>
                                                            <div class="table-detail-column-data">
                                                                <span class="stat text-left">Social media</span>
                                                                <span class="stat color-orange">{{ $nft->community ?? 0 }}%</span>
                                                            </div>
                                                            <div class="table-detail-column-data border-0">
                                                                <span class="stat text-left">Design</span>
                                                                <span class="stat color-orange">{{ $nft->utility ?? '-' }}</span>
                                                            </div>
                                                            <div class="table-detail-column-data d-block d-sm-none" style="border-top: 1px dashed rgba(255, 255, 255, 0.13);">
                                                                @php
                                                                    $avaerage = ((int)$nft->popularity + (int)$nft->community + (int)$nft->originality ) / 3;   
                                                                @endphp
                                                                <span class="stat text-left highlight important">Average</span>
                                                                <span class="stat color-orange float-right">{{ $avaerage ? number_format((float)$avaerage, 2, '.', '') : 0 }}%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="total m-0 d-none d-sm-block">
                                                            <div class="title text-right average_text_in_user_detail" style="line-height:0px">
                                                               
                                                                <span class="std-18 highlight imp">Average: </span>
                                                                <span class="stat">
                                                                    <span>{{ $avaerage ? number_format((float)$avaerage, 2, '.', '') : 0 }}%</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="nft-likes text-right">
                                                            <span>{{ $nft->total_likes ?? 0 }}</span> <span class="icon"><i class="fas fa-thumbs-up"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="community">
                                                <div class="title">
                                                    <span>Social media</span>
                                                </div>
                                                <div class="stat">
                                                    <span>{{ $nft->community ?? 0 }}%</span>
                                                </div>
                                            </div>
                                            <div class="utility">
                                                <div class="title">
                                                    <span>Utility</span>
                                                </div>
                                                <div class="stat">
                                                    <span>{{ $nft->utility ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="originality">
                                                <div class="title">
                                                    <span>Design</span>
                                                </div>
                                                <div class="stat">
                                                    <span>{{ $nft->originality ?? 0 }}%</span>
                                                </div>
                                            </div>
                                            <div class="total">
                                                <div class="title">
                                                    <span>Average</span>
                                                </div>
                                                <div class="stat">
                                                    @php
                                                        $avaerage = ((int)$nft->popularity + (int)$nft->community + (int)$nft->originality ) / 3;   
                                                    @endphp
                                               <span>{{ $avaerage ?? 0 }}%</span>
                                                </div>
                                            </div> --}}
                                        </div>
                                        
                                    </div>
                                </div>
                            @empty
                                <h3>Empty NFTs Lists!.</h3>
                            @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- User NFT detail end -->
</main>
@endsection