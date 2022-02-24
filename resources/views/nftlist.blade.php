@extends('layouts.app')

@section('content')
    <main class="main-spacing">
        <!-- NFT list and add NFT form start -->
        <section class="admin-userlist nft-group-section">
            <div class="container-lg">
                <div class="row">
                    <!-- NFT list start -->
                    <div class="col-xl-8 left-block">
                        <div class="user-list-table-wrapper new-nft">
                            <div class="heading">
                                <h1> <span class="highlight">New</span> NFTs </h1>
                            </div>
                            <div class="table-responsive nft-table-wrapper">
                                <table class="user-list-table text-left table table-borderless nft-table" id="nft-list-table">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>NFTs Details</td>
                                            <td>Utility</td>
                                            <td class="nft-detail-column">Detail</td>
                                            <td>Average</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($nftdata as $nft)
                                            @php
                                                $images = json_decode($nft['image'],true);
                                            @endphp
                                            <tr class="border-0">
                                                <td>{{ $nft['id'] }}</td>
                                                <td class="user-col">
                                                    <div class="user-info-block nft-block">
                                                        <div class="wrapper">
                                                            
                                                            <div class="image">
                                                                <img style="border:2px solid #006eff;"
                                                                    class="rounded-circle"
                                                                    src="{{ STORAGE_IMAGE_URL . $images[0] }}"
                                                                    alt="NFTs image" height="50px" width="50px">
                                                            </div>
                                                            <div class="content">
                                                                <div class="user-name">
                                                                    <span>{{ $nft['nft_name'] }}</span>
                                                                </div>
                                                                <div class="user-email">
                                                                    <span>{{ $nft['email'] }}</span>
                                                                </div>
                                                                <div class="view-now-btn">
                                                                    <a href="{{ $nft['nft_link'] }}" target="_blank">
                                                                        <i class="fas fa-external-link-alt"></i> Visit now
                                                                    </a>
                                                                </div>
                                                                <div>
                                                                    <a class="highlight imp bold-14 mt-2">Read more</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </td>
                                                <td class="pcuot">{{ $nft['utility'] }}</td>
                                                <td>
                                                    <div>
                                                        <div class="table-detail-column-data">
                                                            <span class="text-left">% NFT sold</span>
                                                            <span class="color-orange">{{calculate_grade($nft['popularity'] ?? 0) }}</span>
                                                        </div>
                                                        <div class="table-detail-column-data">
                                                            <span class="text-left">Social media</span>
                                                            <span class="color-orange">{{ calculate_grade($nft['community'] ?? 0) }}</span>
                                                        </div>
                                                        <div class="table-detail-column-data">
                                                            <span class="text-left">Design</span>
                                                            <span class="color-orange">{{ calculate_grade($nft['originality'] ?? 0) }}</span>
                                                        </div>

                                                        <div class="table-detail-column-data">
                                                            <span class="text-left">NFTs Growth Evaluation</span>
                                                            <span class="color-orange">{{ $nft['growth_evaluation'] ?? 0 }}</span>
                                                        </div>
                                                        <div class="table-detail-column-data">
                                                            <span class="text-left">NFTs Resell Evaluation</span>
                                                            <span class="color-orange">{{ $nft['resell_evaluation'] ?? 0 }}</span>
                                                        </div>
                                                        <div class="table-detail-column-data d-block">
                                                            <div class="text-left">Potential Blue Chip</div>
                                                            {{-- <span class="color-orange">{{ $nft['potential_blue_chip'] ?? 0 }}</span> --}}
                                                            <div class="potential-blue-chip-graph text-left">
                                                                <div class="dot-container">
                                                                    @php
                                                                        $score = $nft['potential_blue_chip'] ?? 0;
                                                                        $color = 'grey';
                                                                        $item_fill = 0;
                                                                        if($score > 0 && $score <= 3){
                                                                            $color = 'red';
                                                                            $item_fill = 3;
                                                                        }
                                                                        else if($score > 3 && $score  <= 7){
                                                                            $color = 'yellow';
                                                                            $item_fill = 3;
                                                                        }
                                                                        else if($score > 7 && $score <= 10){
                                                                            $color = 'green';
                                                                            $item_fill = 5;
                                                                        }
                                                                    @endphp
                                                                    @for ($i = 0; $i < 5; $i++)
                                                                        @if ($i < $item_fill)
                                                                            <span class="{{$color." dot"}}"></span>
                                                                        @else
                                                                            <span class="std dot"></span>
                                                                        @endif
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="pcuot">{{ number_format((float)((($nft['popularity']??0) + ($nft['community']??0) + ($nft['originality']??0) + ($nft['growth_evaluation'] ?? 0) + ($nft['resell_evaluation'] ?? 0) + ($nft['potential_blue_chip']*10)) / 6), 2, '.', '') }}</td>
                                                <td class="action-col">
                                                    <div class="action-btns">
                                                        <a href="{{ route('nftlist', [$nft['id']]) }}">
                                                            <button type="button" class="edit">
                                                                <i class="far fa-edit"></i> Edit
                                                            </button>
                                                        </a>

                                                        <button type="button" class="delete"
                                                            rel="{{ $nft['id'] }}">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="text-left">
                                                <td colspan="6" class="p-0">
                                                   <div class="more-detail" style="display: none;">
                                                      <div class="nft-images">
                                                         <span class="highlight">Images: </span>
                                                         <div>
                                                             @foreach ($images as $image)
                                                                <img src="{{ STORAGE_IMAGE_URL.$image }}" alt="NFTs Image" width="90px">
                                                             @endforeach
                                                        </div>
                                                      </div>
                                                   </div>
                                                </td>
                                             </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" align="center">
                                                    Empty NFT lists!
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- NFT list end -->

                    <!-- NFT form start -->
                    <div class="col-xl-4 right-block">
                        <div class="add-user-form-wrapper">
                            <div class="heading">
                                <h1 id="form-heading"> <span class="highlight">Add</span> NFTs </h1>
                            </div>
                            <form action="{{ route('add-nft') }}" method="post" id="add-nft-form"
                                enctype="multipart/form-data">
                                @csrf
                                @if (isset($selectedNFT))
                                    <input type="hidden" name="id" value="{{ $selectedNFT->id }}" />
                                @endif
                                <div class="box-modal">
                                    <div class="title">
                                        <h3>NFTs Information</h3>
                                    </div>
                                    <div class="form-group select-group">
                                        <select name="user_id" id="user_select" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($users as $user)
                                                @php
                                                    $selected = '';
                                                    if (isset($selectedNFT->user_id) && $selectedNFT->user_id == $user['id']) {
                                                        $selected = 'selected';
                                                    }
                                                @endphp
                                                <option value="{{ $user['id'] }}" {{ $selected }}>
                                                    {{ $user['project_name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="error-message">{{ $errors->first('user_id') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="nft_name" id="nft_name" class="form-control"
                                            placeholder="Name" value="{{ $selectedNFT->nft_name ?? old('nft_name') }}">
                                        <div class="error-message">{{ $errors->first('nft_name') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="url" name="nft_link" id="nft_link" class="form-control"
                                            placeholder="Link" value="{{ $selectedNFT->nft_link ?? old('nft_link') }}">
                                        <div class="error-message">{{ $errors->first('nft_link') }}</div>
                                    </div>
                                    <div class="form-collection">
                                        <div class="form-group">
                                            <input type="tel" min="1" max="100" name="popularity" id="popularity" class="form-control"
                                                placeholder="Popularity" value="{{ $selectedNFT->popularity ?? old('popularity') }}">
                                            <div class="error-message">{{ $errors->first('popularity') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" min="1" max="100" name="community" id="community" class="form-control"
                                                placeholder="Community" value="{{ $selectedNFT->community ?? old('community') }}">
                                            <div class="error-message">{{ $errors->first('community') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" min="1" max="100" name="originality" id="originality" class="form-control"
                                                placeholder="Originality" value="{{ $selectedNFT->originality ?? old('originality') }}">
                                            <div class="error-message">{{ $errors->first('originality') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <input type="tel" min="1" max="100" name="growth_evaluation" id="growth_evaluation" class="form-control"
                                                placeholder="Growth Evaluation" value="{{ $selectedNFT->growth_evaluation ?? old('growth_evaluation') }}">
                                            <div class="error-message">{{ $errors->first('growth_evaluation') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" min="1" max="100" name="resell_evaluation" id="resell_evaluation" class="form-control"
                                                placeholder="Resell Evaluation" value="{{ $selectedNFT->resell_evaluation ?? old('resell_evaluation') }}">
                                            <div class="error-message">{{ $errors->first('resell_evaluation') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" min="1" max="10" name="potential_blue_chip" id="potential_blue_chip" class="form-control"
                                                placeholder="Potential blue chip" value="{{ $selectedNFT->potential_blue_chip ?? old('potential_blue_chip') }}">
                                            <div class="error-message">{{ $errors->first('potential_blue_chip') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" name="total" id="total" class="form-control"
                                                placeholder="total" value="{{ $selectedNFT->total ?? old('total') }}" readonly>
                                            <div class="error-message">{{ $errors->first('total') }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="utility" id="utility" class="form-control"
                                            placeholder="Utility" value="{{ $selectedNFT->utility ?? old('utility') }}">
                                        <div class="error-message">{{ $errors->first('utility') }}</div>
                                    </div>
                                    {{-- <div class="form-group">
                                        <input type="file" name="nft_image[]" id="nft_image" multiple="multiple" class="d-none">
                                        <label for="nft_image" class="file-label form-control">Image</label>
                                        <div class="error-message">{{ $errors->first('nft_image') }}</div>

                                    </div> --}}
                                    <div class="form-group">
                                        <div class="form-control file-upload">
                                            <div class="selected-file-wrapper">
                                                <span class="selected-file label"> No file selected</span>
                                            </div>
                                            <input type="file" name="nft_image[]" id="nft_image" multiple="multiple">
                                            <label for="nft_image" class="file-label">
                                                <a class="form-control form-btn">Choose File</a>
                                            </label>
                                        </div>
                                        <div class="error-message">{{ $errors->first('nft_image') }}</div>
                                    </div>

                                    @if (isset($selectedNFT->image) && $selectedNFT->image != '')
                                        @php
                                            $images = json_decode($selectedNFT->image,true);
                                        @endphp
                                        <div class="form-group mt-3">
                                            <div class="image text-center row">
                                                @foreach ($images as $image)
                                                    <div class="d-flex flex-column col-6 justify-content-center align-items-center mt-3">
                                                        <input type="hidden" name="old_images[]" value="{{ $image }}">
                                                        <img style="border:2px solid #006eff;" class="rounded-circle"
                                                        src="{{ STORAGE_IMAGE_URL . $image }}" alt="NFT image"
                                                        width="130px" height="130px">
                                                        <span class="image-delete btn btn-sm btn-danger mt-3"><i class="fa fa-trash"></i></span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-btn add-user-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- NFT form end -->
                </div>
            </div>
        </section>
        <!-- NFT list and add NFT form end -->
    </main>
    <script>
        $(document).ready(function() {
            $('#popularity,#community,#originality,#growth_evaluation,#resell_evaluation,#potential_blue_chip').on('change, blur, keyup', function() {
                var popularity = parseInt($("#popularity").val() != '' ? $("#popularity").val() : 0);
                var community = parseInt($("#community").val() != '' ? $("#community").val() : 0);
                var originality = parseInt($("#originality").val() != '' ? $("#originality").val() : 0);
                var growth_evaluation = parseInt($("#growth_evaluation").val() != '' ? $("#growth_evaluation").val() : 0);
                var resell_evaluation = parseInt($("#resell_evaluation").val() != '' ? $("#resell_evaluation").val() : 0);
                var potential_blue_chip = parseInt($("#potential_blue_chip").val() != '' ? $("#potential_blue_chip").val() : 0);
                var total = popularity + community + originality + growth_evaluation + resell_evaluation + potential_blue_chip;
                $("#total").val(total);
            });
            $(".delete").click(function() {
                var id = $(this).attr('rel');
                Swal.fire({
                    title: 'Are you sure for delete this NFTs?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('delete-nft') }}?id=" + id;
                    }
                });
            });
            $(".image-delete").click(function() {
                var selector = $(this);
                Swal.fire({
                    title: 'Are you sure for delete this Image?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        selector.closest("div").remove();
                    }
                });
            });

            $('input[type="file"]').change(function(e) {
                var allImages = e.target.files;
                var selectedImages = [];
                $.each( allImages, function( key, value ) {
                    selectedImages.push(value.name);
                });
                $(".selected-file.label").text(selectedImages.join(', '));
            });

            @if (old('user_id') != "")
                $("#user_select").val('{{ old("user_id") }}');
            @endif

        });
    </script>
    <style>
        main .nft-group-section .new-nft .nft-table-wrapper {
            display: block!important;
        }
        .nft-table{
            min-width: 700px!important;
        }
    </style>
    <script>
        $(document).ready(function () {
            $(".highlight").click(function (){
                
                if($(this).html() == "Read more"){
                    $(this).html("Read less");
                    $(this).closest('tr').next('tr').find('.more-detail').slideDown('fast');
                }
                else{
                    $(this).html("Read more");
                    $(this).closest('tr').next('tr').find('.more-detail').slideUp('fast');
                }
            });
        });
        @isset($selectedNFT->id)
            $('html, body').animate({
                scrollTop: $("#form-heading").offset().top - 100
            }, 500);
        @endisset
    </script>
@endsection
