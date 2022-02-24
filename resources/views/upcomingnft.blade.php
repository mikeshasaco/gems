@extends('layouts.app')

@section('content')
@php
    // dd()
@endphp
    <main class="main-spacing">
        <!-- User list and add user form start -->
        <section class="admin-userlist">
            <div class="container-lg">
                <div class="row">
                    <div class="col-lg-7 left-block">
                        <div class="user-list-table-wrapper">
                            <div class="heading">
                                <h1> <span class="highlight">Upcoming NFTs</span> List </h1>
                            </div>
                            <div class="table-responsive">
                                <table class="user-list-table table table-borderless">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Project Name</td>
                                            <td>Website</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($upcomingnftdata as $nft)
                                        <tr class="border-0">
                                            <td>{{ $nft['id'] }}</td>
                                            <td class="user-col">
                                                <div class="user-info-block">
                                                    <!-- <div class="image">
                                                        <img src="{{ asset('images/user-dp.png') }}" alt="User profile image" height="50px" width="50px">
                                                    </div> -->
                                                    <div class="content">
                                                        <div class="user-name">
                                                            <span>{{ $nft['project_name'] }}</span>
                                                        </div>
                                                        <div class="user-email mb-3">
                                                            <span>{{ $nft['release_date'].' '.$nft['release_time'] }}</span>
                                                        </div>
                                                        <span class="readmore highlight" rel="{{ $nft['id'] }}">Read more</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="user-number text-white">{{ $nft['website'] ?? 'NaN' }}</td>
                                            <td class="action-col">
                                                <div class="action-btns">
                                                    <a href="{{ route('upcoming-nft',[$nft['id']]) }}">
                                                        <button type="button" class="edit">
                                                            <i class="far fa-edit"></i> Edit
                                                        </button>
                                                    </a>
                                                    <button type="button" class="delete" rel="{{ $nft['id'] }}">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="py-0">
                                                <div class="readmore-list readmore-element-{{ $nft['id'] }}" style="display:none;">
                                                    <div class="network bold-16">
                                                        <span class="highlight"> Network </span>  : <span class="font-weight-normal"> {{ $nft['network'] }} </span>
                                                    </div>
                                                    {{-- <div class="social bold-16">
                                                        <span class="highlight"> Social media </span>  : <span class="font-weight-normal"> {{ $nft['socialmedia'] }} </span>
                                                    </div> --}}
                                                    <div class="social bold-16">
                                                        <span class="highlight"> Social media: </span>
                                                            @php
                                                                $socialMedia = json_decode($nft['socialmedia']);
                                                            @endphp
                                                            @foreach ($socialMedia as $key => $item)
                                                                <a href="{{$item->media_link}}" target="_blank" rel="noopener noreferrer" class="{{$item->media}}"><i class="fab fa-{{$item->media}}"></i></a>
                                                            @endforeach
                                                      </div>
                                                    <div class="description bold-16">
                                                        <span class="highlight"> Description </span>  : <span class="font-weight-normal"> {{ $nft['briefdescription'] }} </span>
                                                    </div>
                                                    <div class="description bold-16">
                                                        <span class="highlight"> Unit price eth: </span><span class="font-weight-normal"> {{ $nft['unit_price_eth'] ?? 'NaN' }} </span>
                                                    </div>
                                                    <div class='nft-images mb-2'>
                                                        @isset($nft['images'])
                                                            <span class='highlight'>Images: </span>
                                                            @php
                                                                $images = json_decode($nft['images'])
                                                            @endphp
                                                            <div>
                                                                @foreach ($images as $image)
                                                                        <a href={{STORAGE_IMAGE_URL.$image}} target={"_blank"}>
                                                                            <img src={{STORAGE_IMAGE_URL.$image}} alt="User profile image" width="50px" />
                                                                        </a>
                                                                @endforeach
                                                            </div>
                                                        @endisset
                                                  </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    @empty
                                        <tr>
                                            <td colspan="4" align="center">
                                                Empty Upcoming NFTs lists!
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 right-block">
                        <div class="add-user-form-wrapper">
                            <div class="heading">
                                <h1 id="form-heading"> <span class="highlight">Add</span> Upcoming NFT </h1>
                            </div>
                            <form action="{{ route('add-upcomingnft') }}" method="post" id="add-user-form" enctype="multipart/form-data">
                                @csrf
                                @if(isset($selectedNft))
                                    <input type="hidden" name="id" value="{{ $selectedNft->id }}" />
                                @endif
                                <div class="box-modal">
                                    <div class="title">
                                        <h3>User Information</h3>
                                    </div>
                                    <div class="first-name form-group">
                                        <input type="text" name="project_name" id="project_name" class="form-control" placeholder="Project Name" value="{{ $selectedNft->project_name ?? old('project_name') }}">
                                        <div class="error-message">{{ $errors->first('project_name') }}</div>
                                    </div>
                                    <div class="last-name form-group">
                                        <input type="url" name="website" id="website" class="form-control" placeholder="Website" value="{{ $selectedNft->website ?? old('website') }}">
                                        <div class="error-message">{{ $errors->first('website') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="release_date" id="release_date" class="form-control" placeholder="Launch Date" value="{{ $selectedNft->release_date ?? old('release_date') }}">
                                        <div class="error-message">{{ $errors->first('release_date') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="time" name="release_time" id="release_time" class="form-control" placeholder="Launch Time" value="{{ $selectedNft->release_time ?? old('release_time') }}">
                                        <div class="error-message">{{ $errors->first('release_time') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <select name="timeZoneSelect" id="timeZoneSelect" class="form-control">
                                            
                                        </select>
                                        <div class="error-message">{{ $errors->first('timeZoneSelect') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" min="0" step="any" name="unit_price_eth" id="unit_price_eth" class="form-control" placeholder="Unit price in ETH" value="{{$selectedNft->unit_price_eth ?? old('unit_price_eth')}}">
                                        <div class="error-message">{{ $errors->first('unit_price_eth') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="max_number_collection" id="max_number_collection" class="form-control" placeholder="Max number in the collection" value="{{$selectedNft->max_number_collection ?? old('max_number_collection')}}">
                                        <div class="error-message"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="network" id="network" class="form-control" placeholder="Network" value="{{ $selectedNft->network ?? old('network') }}">
                                        <div class="error-message">{{ $errors->first('network') }}</div>
                                    </div>
                                    
                                    <div class="col-12 form-group border-dashed mb-3"></div>

                                    <div class="form-group">
                                        <select name="social_media" id="social_media" class="form-control">
                                            <option disabled="" value="" selected="">Social media</option>
                                            <option value="twitter">Twitter</option>
                                            <option value="discord">Discord</option>
                                            <option value="facebook">Facebook</option>
                                        </select>
                                        <div class="error-message"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="social_media_link" id="social_media_link" class="form-control" placeholder="Social media link" value="{{ old('social_media_link') }}">
                                        <div class="error-message">{{ $errors->first('social_media_link') }}</div>
                                    </div>
                                    <div class="form-group">
                                        @isset($selectedNft->socialmedia)
                                            @if ($selectedNft->socialmedia)
                                                @php
                                                    $existing_social_media = json_decode($selectedNft->socialmedia, true);
                                                @endphp
                                                
                                                <ul class="socialmedia-list">
                                                @foreach ($existing_social_media as $key => $item)
                                                        <li><span class="highlight">{{$item['media']}}: </span> {{$item['media_link']}}  &nbsp; &nbsp;
                                                        <span class="float-right highlight imp delete-socialmedia" index="{{$key}}" ><i class="fas fa-times"></i></span></li>
                                                @endforeach
                                                </ul>
                                            @endif
                                        @endisset
                                    </div>
                                    <div class="col-12 form-group border-dashed mb-3"></div>

                                    <div class="form-group">
                                        <div class="form-control file-upload">
                                            <div class="selected-file-wrapper">
                                                <span class="selected-file label"> No file selected</span>
                                            </div>
                                            <input type="file" name="upcoming_nft_image[]" id="upcoming_nft_image" multiple="multiple">
                                            <label for="upcoming_nft_image" class="file-label">
                                                <a class="form-control form-btn">Choose File</a>
                                            </label>
                                        </div>
                                        <div class="error-message">{{ $errors->first('upcoming_nft_image') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="briefdescription" id="briefdescription" class="form-control textarea" placeholder="Brief Description" >{{ $selectedNft->briefdescription ?? old('briefdescription') }}</textarea>
                                        <div class="error-message">{{ $errors->first('briefdescription') }}</div>
                                    </div>

                                    <div>
                                        @isset($selectedNft->images)
                                            <label class="highlight d-block">
                                                Images :
                                            </label>
                                            @php
                                                $images = json_decode($selectedNft->images);
                                            @endphp
                                            {{-- @foreach ($images as $image)
                                            <div class="d-flex flex-wrap">
                                                <div>
                                                    <img src="{{ STORAGE_IMAGE_URL.$image }}" style="width: 80px"alt="">
                                                    <button type="button" nftId="{{ $selectedNft->id }}" image-name="{{ $image }}" class="image-delete btn btn-sm btn-danger mt-3"><i class="fa fa-trash"></i></button>
                                                </div>

                                            </div>
                                            @endforeach --}}
                                            <div class="image text-center row">
                                                @foreach($images as $image)
                                                        <div class="d-flex flex-column col-6 justify-content-center align-items-center mt-3">
                                                            <img src="{{ STORAGE_IMAGE_URL.$image }}" style="border:2px solid #006eff;" class="rounded-circle"  alt="NFT image" width="130px" height="130px">
                                                            {{-- <span image-name="1641884779-logo.png" class="image-delete btn btn-sm btn-danger mt-3"><i class="fa fa-trash"></i></span> --}}
                                                            <input type="hidden" name="old_images[]" value="{{ $image }}">
                                                            <button type="button" nftId="{{ $selectedNft->id }}" image-name="{{ $image }}" class="image-delete btn btn-sm btn-danger mt-3"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                @endforeach
                                            </div> 
                                        @endisset
                                    </div>



                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-btn add-user-btn">Save</button>
                                </div>
                                <input type="hidden" name="deletedimages" id="deletedimages" value="" />
                                <input type="hidden" name="social_media_array" id="social_media_array" value="" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- User list and add user form end -->
    </main>

    <script>
        var deletedImages = [];
        var socialmedia = JSON.parse('<?php echo $selectedNft->socialmedia ?? "[]"; ?>');
        
        $(document).ready(function(){
            $("#release_date, #release_time").attr("type", "text");
            $("#social_media_array").val(JSON.stringify(socialmedia));

            $('#release_date, #release_time').focus(function (e) { 
                if($(this).attr('id') == "release_date"){
                    $(this).attr('type', 'date')
                }
                else{
                    $(this).attr('type', 'time')
                }
            });
            $('#release_date, #release_time').blur(function (e) { 
                if(e.target.value == "" || e.target.value == null || typeof e.target.value === "undefined"){
                    $(this).attr('type', 'text')
                }
            });

            $('#release_date, #release_time').hover(function (e) {
                    if($(this).attr('id') == "release_date"){
                        $(this).attr('type', 'date')
                    }
                    else{
                        $(this).attr('type', 'time')
                    }
                }, function (e) {
                    if(e.target.value == "" || e.target.value == null || typeof e.target.value === "undefined"){
                        $(this).attr('type', 'text')
                    }
                }
            );

            $('input[type="file"]').change(function(e) {
                var allImages = e.target.files;
                var selectedImages = [];
                $.each( allImages, function( key, value ) {
                    selectedImages.push(value.name);
                });
                $(".selected-file.label").html(selectedImages.join(', '));
            });

            $(".delete").click(function(){
                var id = $(this).attr('rel');
                Swal.fire({
                    title: 'Are you sure for delete this Upcoming NFTs?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('delete-upcomingnft') }}?id="+id;
                    }
                });
            });

            $(".readmore").click(function (){
                var listId = $(this).attr('rel');
                if($(this).html() == "Read more"){
                    $(this).html("Read less");
                    $(".readmore-element-"+listId).slideDown('fast');
                }
                else{
                    
                    $(this).html("Read more");
                    $(".readmore-element-"+listId).slideUp('fast');
                }
            });
            // $('#upcoming_nft_image').change(function (e) { 
            //     e.preventDefault();
            //     let path = $(this).val().split( '\\');
            //     let val = path[path.length-1];
            //     if(val == ""){
            //         $('.selected-file.label').html("No file selected");
            //     }
            //     else{
            //         $('.selected-file.label').html(path[path.length-1]);
            //     }
            // });

            $(".image-delete").click(function() {
                var selector = $(this);
                var imageName = $(this).attr('image-name');
                Swal.fire({
                    title: 'Are you sure for delete this Image?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deletedImages.push(imageName);
                        $("#deletedimages").val(deletedImages.join());
                        selector.closest("div").remove();
                    }
                });
            });

            $(".delete-socialmedia").click(function() {
                var selector = $(this);
                var social_index = $(this).attr('index');
                Swal.fire({
                    title: 'Are you sure for delete this Social media link?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // deletedImages.push(imageName);
                        selector.closest("li").remove();
                        
                        socialmedia = socialmedia.filter((item, index)=>{
                            return social_index != index
                        })
                        console.log(socialmedia);
                        $("#social_media_array").val(JSON.stringify(socialmedia));
                    }
                });
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

            var timezonOptions = '<option selected disabled>Select timezone</option>';
            timezoneSelectorOptions.forEach(element => {
                timezonOptions += "<option value='"+element.name+"'>"+element.option+"</option>";
            });
            $("#timeZoneSelect").append(timezonOptions);

            <?php 
                if(isset($selectedNft->timeZoneSelect)){ ?>
                    $("#timeZoneSelect").val('{{ $selectedNft->timeZoneSelect ?? '' }}');
                <?php }
            ?>

            @if (old('timeZoneSelect') != "")
                $("#timeZoneSelect").val('{{ old("timeZoneSelect") }}');
            @endif
            @if (old('social_media') != "")
                $("#social_media").val('{{ old("social_media") }}');
            @endif
        });
        @isset($selectedNft->id)
            $('html, body').animate({
                scrollTop: $("#form-heading").offset().top - 100
            }, 500);
        @endisset
    </script>
@endsection

@php
    // dd($selectedNft)
@endphp

