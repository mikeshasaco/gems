@extends('layouts.app')

@section('content')
<main class="main-spacing">
    <!-- Submit NFTs form start -->
    <section class="verify-nft">
        <div class="nft-container">
            <div class="container-lg">
                <div class="row">
                    <div class="col-12 heading">
                        <h1> <span class="highlight">Verify Upcoming</span> NFTs </h1>
                    </div>
                    <div class="col-12 left-block">
                        <div class="verify-nft-form">
                            <form action="{{ route('verifysubmitupcomingnft') }}" method="post" id="nft-verification-form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $nfeDetail->id }}">
                                <div class="row">
                                    <div class="col-12 left-box">
                                        <div class="box-modal">
                                            <div class="title">
                                                <h3>User Information</h3>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <input type="text" required name="project_name" id="project_name" class="form-control" placeholder="Project Name" value="{{ old('project_name') != '' ? old('project_name') : $nfeDetail->project_name }}">
                                                            <div class="error-message">{{ $errors->first('project_name') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group">
                                                        <input type="url" required name="website" id="website" class="form-control" placeholder="Website" value="{{ old('website') != '' ? old('website') : $nfeDetail->website }}">
                                                        <div class="error-message">{{ $errors->first('website') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group">
                                                        <input type="date" required name="release_date" id="release_date" class="form-control" placeholder="Release Date (YYYY-mm-dd)" value="{{ old('release_date') != '' ? old('release_date') : $nfeDetail->release_date }}">
                                                        <div class="error-message">{{ $errors->first('release_date') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group">
                                                        <input type="time" required name="release_time" id="release_time" class="form-control" placeholder="Release Time (HH:ii:ss)" value="{{ old('release_time') != '' ? old('release_time') : $nfeDetail->release_time }}">
                                                        <div class="error-message">{{ $errors->first('release_time') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group">
                                                        <select class="form-control" name="timeZoneSelect" id="timeZoneSelect">
                                                            <option value="">Select TimeZone</option>
                                                        </select>
                                                        <div class="error-message">{{ $errors->first('timeZoneSelect') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" required name="network" id="network" class="form-control" placeholder="Network" value="{{ old('network') != '' ? old('network') : $nfeDetail->network }}">
                                                        <div class="error-message">{{ $errors->first('network') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12 pb-1">
                                                    <div class="form-group">
                                                        <input type="number" required name="unit_price_eth" id="unit_price_eth" class="form-control" placeholder="Unit price in ETH" value="{{ old('unit_price_eth') != '' ? old('unit_price_eth') : $nfeDetail->unit_price_eth }}">
                                                        <div class="error-message">{{ $errors->first('unit_price_eth') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-12 pb-1">
                                                    <div class="form-group">
                                                        <input type="number" required name="max_number_collection" id="max_number_collection" class="form-control" placeholder="Max number in the collection" value="{{ old('max_number_collection') != '' ? old('max_number_collection') : $nfeDetail->max_number_collection }}">
                                                        <div class="error-message">{{ $errors->first('max_number_collection') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-12"></div>
                                                <div class="col-md-4 col-12">
                                                    <div class="title">
                                                        <h3>Social Media</h3>
                                                    </div>
                                                    <div class="form-group">
                                                        @php 
                                                            $socialMedia = json_decode($nfeDetail->socialmedia,true);
                                                        @endphp

                                                        @foreach($socialMedia as $detail)
                                                            <div class="row mb-3">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="form-control"><i class="fab fa-{{ $detail['media'] }}"></i> : {{ $detail['media_link'] }}</div>                                                                  
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="title">
                                                        <h3>NFT's Images</h3>
                                                    </div>
                                                    <div class="form-group">
                                                        @php 
                                                            $images = json_decode($nfeDetail->images,true);
                                                        @endphp

                                                        @foreach($images as $detail)
                                                            <img width="150" src="{{ STORAGE_IMAGE_URL }}{{ $detail }}" alt="NFT's Image">
                                                        @endforeach
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            {{-- <div class="full-name form-group">
                                                
                                                <div class="last-name form-group">
                                                    <input type="url" required name="website" id="website" class="form-control" placeholder="Website" value="{{ old('website') != '' ? old('website') : $nfeDetail->website }}">
                                                    <div class="error-message">{{ $errors->first('website') }}</div>
                                                </div>
                                            </div> --}}
                                            {{-- <div class="full-name form-group">
                                                <div class="first-name form-group">
                                                    <input type="date" required name="release_date" id="release_date" class="form-control" placeholder="Release Date (YYYY-mm-dd)" value="{{ old('release_date') != '' ? old('release_date') : $nfeDetail->release_date }}">
                                                    <div class="error-message">{{ $errors->first('release_date') }}</div>
                                                </div>
                                                <div class="last-name form-group">
                                                    <input type="time" required name="release_time" id="release_time" class="form-control" placeholder="Release Time (HH:ii:ss)" value="{{ old('release_time') != '' ? old('release_time') : $nfeDetail->release_time }}">
                                                    <div class="error-message">{{ $errors->first('release_time') }}</div>
                                                </div>
                                            </div> --}}
                                            {{-- <div class="form-group">
                                                <input type="text" required name="socialmedia" id="socialmedia" class="form-control" placeholder="Social Media" value="{{ old('socialmedia') != '' ? old('socialmedia') : $nfeDetail->socialmedia }}">
                                                <div class="error-message">{{ $errors->first('socialmedia') }}</div>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" required name="network" id="network" class="form-control" placeholder="Network" value="{{ old('network') != '' ? old('network') : $nfeDetail->network }}">
                                                <div class="error-message">{{ $errors->first('network') }}</div>
                                            </div> --}}
                                            <div class="form-group">
                                                <textarea required name="briefdescription" id="briefdescription" class="form-control textarea" placeholder="Brief Description" >{{ old('briefdescription') != '' ? old('briefdescription') : $nfeDetail->briefdescription }}</textarea>
                                                <div class="error-message">{{ $errors->first('briefdescription') }}</div>
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
        $('#popularity,#community,#originality').on('change, blur, keyup', function () {
            var popularity = parseInt($("#popularity").val() != '' ? $("#popularity").val() : 0);
            var community = parseInt($("#community").val() != '' ? $("#community").val() : 0);
            var originality = parseInt($("#originality").val() != '' ? $("#originality").val() : 0);
            var total = popularity + community + originality;
            $("#total").val(total);
        });
        const timezoneSelectorOptions = [];
        moment.tz.names()
            .reduce((memo, tz) => {
                memo.push({
                name: tz,
                offset: moment.tz(tz).utcOffset()
                });
                
                return memo;
            }, [])
            .sort((a, b) => {
                return a.offset - b.offset
            })
            .reduce((memo, tz) => {
                const timezone = tz.offset ? moment.tz(tz.name).format('Z') : '';
                timezoneSelectorOptions.push({
                name: tz.name,
                option: `(GMT${timezone}) ${tz.name}`
                })
                return null;
            }, "");

        var timezonOptions = '';
        timezoneSelectorOptions.forEach(element => {
            timezonOptions += "<option value='"+element.name+"'>"+element.option+"</option>";
        });
        $("#timeZoneSelect").append(timezonOptions);
        $("#timeZoneSelect").val('{{ $nfeDetail->timeZoneSelect }}');
    });
</script>
@endsection

