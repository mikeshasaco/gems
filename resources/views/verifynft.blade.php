@extends('layouts.app')

@section('content')
<main class="main-spacing">
    <!-- Submit NFTs form start -->
    <section class="verify-nft">
        <div class="nft-container">
            <div class="container-lg">
                <div class="row">
                    <div class="col-12 heading">
                        <h1> <span class="highlight">Verify</span> NFTs </h1>
                    </div>
                    <div class="col-xl-9 col-lg-10 col-md-12 left-block">
                        <div class="verify-nft-form">
                            <form action="{{ route('verifysubmitnft') }}" method="post" id="nft-verification-form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $nfeDetail->id }}">
                                <input type="hidden" name="userid" value="{{ $nfeDetail->user_id }}">
                                <div class="row">
                                    <div class="col-md-6 left-box">
                                        <div class="box-modal">
                                            <div class="title">
                                                <h3>User Information</h3>
                                            </div>
                                            <div class="first-name form-group">
                                                <input type="text" readonly name="project_name" id="project_name" class="form-control" placeholder="Project Name" value="{{ old('project_name') != '' ? old('project_name') : $nfeDetail->project_name }}">
                                                <div class="error-message">{{$errors->first('project_name')}}</div>
                                            </div>
                                            <div class="form-group">
                                                <input type="email" readonly name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') != '' ? old('email') : $nfeDetail->email }}">
                                                <div class="error-message">{{$errors->first('email')}}</div>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" readonly name="opensea_link" id="opensea_link" class="form-control" placeholder="Collection Link (Important)" value="{{ old('opensea_link') != '' ? old('opensea_link') : $nfeDetail->opensea_link }}">
                                                <div class="error-message">{{ $errors->first('opensea_link') }}</div>
                                             </div>
                                             {{-- <div class="form-group">
                                                <input type="text" readonly name="wallet_address" id="wallet_address" class="form-control" placeholder="Wallet address (sent ETH from)" value="{{ old('wallet_address') != '' ? old('wallet_address') : $nfeDetail->wallet_address }}">
                                                <div class="error-message">{{ $errors->first('wallet_address') }}</div>
                                             </div> --}}
                                             <div class="form-group">
                                                <input type="text" readonly name="twitter_link" id="twitter_link" class="form-control" placeholder="Project's Official Twitter" value="{{ old('twitter_link') != '' ? old('twitter_link') : $nfeDetail->twitter_link }}">
                                                <div class="error-message">{{ $errors->first('twitter_link') }}</div>
                                             </div>
                                             <div class="form-group">
                                                <input type="url" readonly name="discord_link" id="discord_link" class="form-control" placeholder="Project's Official Discord" value="{{ old('discord_link') != '' ? old('discord_link') : $nfeDetail->discord_link }}">
                                                <div class="error-message">{{ $errors->first('discord_link') }}</div>
                                             </div>
                                             <div class="form-group border-dashed mb-2"></div>
                                             <div class="form-group">
                                                <label for="">What is the maximum number of items in your collection?</label>
                                                <input type="number" min="1" readonly name="maximum_number_in_collection" id="maximum_number_in_collection" class="form-control" value="{{ old('maximum_number_in_collection') != '' ? old('maximum_number_in_collection') : $nfeDetail->maximum_number_in_collection }}">
                                                <div class="error-message">{{ $errors->first('maximum_number_in_collection') }}</div>
                                             </div>
                                             <div class="form-group">
                                                <label for="">How much have you sold items have you sold from your collection?</label>
                                                <input type="text" readonly name="item_sold" id="item_sold" class="form-control" value="{{ old('item_sold') != '' ? old('item_sold') : $nfeDetail->item_sold }}">
                                                <div class="error-message">{{ $errors->first('item_sold') }}</div>
                                             </div>
                                             <div class="form-group">
                                                <label for="">What is your collection's blockchain?</label>
                                                <input type="text" readonly name="collection_blockchain" id="collection_blockchain" class="form-control" value="{{ old('collection_blockchain') != '' ? old('collection_blockchain') : $nfeDetail->collection_blockchain }}">
                                                <div class="error-message">{{ $errors->first('collection_blockchain') }}</div>
                                             </div>
                                             <div class="form-group">
                                                <label for="">What is your collection's contract address(es)? (If available)</label>
                                                <input type="text" readonly name="collection_contract_address" id="collection_contract_address" class="form-control" value="{{ old('collection_contract_address') != '' ? old('collection_contract_address') : $nfeDetail->collection_contract_address }}">
                                                <div class="error-message">{{ $errors->first('collection_contract_address') }}</div>
                                             </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 right-box">
                                        <div class="box-modal">
                                            <div class="title">
                                                <h3>About your NFT</h3>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="nft_name" id="nft_name" class="form-control" placeholder="Name"  value="{{ old('nft_name') != '' ? old('nft_name') : $nfeDetail->nft_name }}" >
                                                <div class="error-message">{{$errors->first('nft_name')}}</div>
                                            </div>
                                            <div class="form-group">
                                                <input type="url" name="nft_link" id="nft_link" class="form-control" placeholder="Link"  value="{{ old('nft_link') != '' ? old('nft_link') : $nfeDetail->nft_link }}" >
                                                <div class="error-message">{{$errors->first('nft_link')}}</div>
                                            </div>
                                            <div class="form-collection">
                                                <div class="form-group">
                                                    <input min="1" max="100" type="tel" name="popularity" id="popularity" class="form-control" placeholder="Popularity" value="{{ old('popularity') }}">
                                                    <div class="error-message">{{$errors->first('popularity')}}</div>
                                                </div>
                                                <div class="form-group">
                                                    <input min="1" max="100" type="tel" name="community" id="community" class="form-control" placeholder="Community"  value="{{ old('community') }}">
                                                    <div class="error-message">{{$errors->first('community')}}</div>
                                                </div>
                                                <div class="form-group">
                                                    <input min="1" max="100" type="tel" name="originality" id="originality" class="form-control" placeholder="Originality"  value="{{ old('originality') }}">
                                                    <div class="error-message">{{$errors->first('originality')}}</div>
                                                </div>

                                                <div class="form-group">
                                                    <input min="1" max="100" type="tel" name="growth_evaluation" id="growth_evaluation" class="form-control" placeholder="Growth Evaluation"  value="{{ old('growth_evaluation') }}">
                                                    <div class="error-message">{{$errors->first('growth_evaluation')}}</div>
                                                </div>
                                                <div class="form-group">
                                                    <input min="1" max="100" type="tel" name="resell_evaluation" id="resell_evaluation" class="form-control" placeholder="Resell Evaluation"  value="{{ old('resell_evaluation') }}">
                                                    <div class="error-message">{{$errors->first('resell_evaluation')}}</div>
                                                </div>
                                                <div class="form-group">
                                                    <input min="1" max="10" type="tel" name="potential_blue_chip" id="potential_blue_chip" class="form-control" placeholder="Potential blue chip"  value="{{ old('potential_blue_chip') }}">
                                                    <div class="error-message">{{$errors->first('potential_blue_chip')}}</div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="tel" name="total" id="total" class="form-control" placeholder="total" readonly  value="{{ old('total') }}">
                                                    <div class="error-message" >{{$errors->first('total')}}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="utility" id="nft_utility" class="form-control" placeholder="Utility" >
                                                <div class="error-message">{{$errors->first('utility')}}</div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <div class="d-flex align-items-center">
                                                    <label for="approve_switch" class="mb-1 mr-3 bold-18">Reject</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="approve_switch" name="verify" value="1">
                                                        <label class="custom-control-label" for="approve_switch"></label>
                                                    </div>
                                                    <label for="approve_switch" class="mb-1 ml-1 bold-18">Approve</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="image">
                                                    @php
                                                        $images = json_decode($nfeDetail->image,true);
                                                    @endphp
                                                    @foreach ($images as $image)
                                                        <img style="border:2px solid #006eff;" class="rounded-circle" src="{{ STORAGE_IMAGE_URL.$image }}" alt="NFT image" width="130px" height="130px">
                                                    @endforeach
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-btn nft-submit-btn">Verify</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Submit NFTs form end -->
</main>
<script>
    $(document).ready(function () {
        $('#popularity,#community,#originality,#growth_evaluation,#resell_evaluation,#potential_blue_chip').on('change, blur, keyup', function () {
            var popularity = parseInt($("#popularity").val() != '' ? $("#popularity").val() : 0);
            var community = parseInt($("#community").val() != '' ? $("#community").val() : 0);
            var originality = parseInt($("#originality").val() != '' ? $("#originality").val() : 0);
            var growth_evaluation = parseInt($("#growth_evaluation").val() != '' ? $("#growth_evaluation").val() : 0);
            var resell_evaluation = parseInt($("#resell_evaluation").val() != '' ? $("#resell_evaluation").val() : 0);
            var potential_blue_chip = parseInt($("#potential_blue_chip").val() != '' ? $("#potential_blue_chip").val() : 0);
             var total = popularity + community + originality + growth_evaluation + resell_evaluation + potential_blue_chip;
            $("#total").val(total);
        });
    });
</script>
@endsection

