@extends('layouts.app')

@section('content')
    <main class="main-spacing">
        <!-- User list and add user form start -->
        <section class="admin-userlist">
            <div class="container-lg">
                <div class="row">
                    <div class="col-xl-7 left-block">
                        <div class="user-list-table-wrapper">
                            <div class="heading">
                                <h1> <span class="highlight">User</span> List </h1>
                            </div>
                            <div class="table-responsive">
                                <table class="user-list-table table table-borderless">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td class="user-info-column">User Information</td>
                                            <td>Social Media</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($userdata as $user)
                                        <tr>
                                            <td>{{ $user['id'] }}</td>
                                            <td class="user-col user-info-column">
                                                <div class="user-info-block">
                                                    <div class="image">
                                                        <img src="{{ asset('images/user-dp.png') }}" alt="User profile image" height="50px" width="50px">
                                                    </div>
                                                    <div class="content">
                                                        <div class="user-name">
                                                            <span>{{ $user['project_name'] }}</span>
                                                        </div>
                                                        <div class="user-email mb-2">
                                                            <span>{{ $user['email'] }}</span>
                                                        </div>
                                                        <div class="bold-14">
                                                            <a class="text-white" href="{{ route('user-detail',[$user['id']]) }}">
                                                                <i class="fas fa-external-link-alt"></i> Visit now
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="user-number text-white">
                                                @if($user['twitter_link'] != '')
                                                    <a class="twitter" href="{{ $user['twitter_link'] }}" target="_blank">
                                                        <i class="fab fa-twitter"></i>
                                                    </a>
                                                @endif
                                                @if($user['discord_link'] != '')
                                                    <a class="discord" href="{{ $user['discord_link'] }}" target="_blank">
                                                        <i class="fab fa-discord"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="action-col">
                                                <div class="action-btns">
                                                    <a href="{{ route('userlist',[$user['id']]) }}">
                                                        <button type="button" class="edit">
                                                            <i class="far fa-edit"></i> Edit
                                                        </button>
                                                    </a>
                                                    
                                                    <button type="button" class="delete" rel="{{ $user['id'] }}">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" align="center">
                                                Empty user lists!
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 right-block">
                        <div class="add-user-form-wrapper">
                            <div class="heading">
                                <h1 id="form-heading"> <span class="highlight">Add</span> User </h1>
                            </div>
                            <form action="{{ route('add-user') }}" method="post" id="add-user-form">
                                @csrf
                                @if(isset($selectedUser))
                                    <input type="hidden" name="id" value="{{ $selectedUser->id }}" />
                                @endif
                                <div class="box-modal">
                                    <div class="title">
                                        <h3>User Information</h3>
                                    </div>
                                    <div class="first-name form-group">
                                        <input type="text" name="project_name" id="project_name" class="form-control" placeholder="Project Name" value="{{ $selectedUser->project_name ?? old('project_name') }}">
                                        <div class="error-message">{{$errors->first('project_name')}}</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ $selectedUser->email ?? old('email') }}">
                                        <div class="error-message">{{$errors->first('email')}}</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="opensea_link" id="opensea_link" class="form-control" placeholder="Collection Link (Important)" value="{{ $selectedUser->opensea_link ?? old('opensea_link') }}">
                                        <div class="error-message">{{ $errors->first('opensea_link') }}</div>
                                     </div>
                                     {{-- <div class="form-group">
                                        <input type="text" name="wallet_address" id="wallet_address" class="form-control" placeholder="Wallet address (sent ETH from)" value="{{ $selectedUser->wallet_address ?? old('wallet_address') }}">
                                        <div class="error-message">{{ $errors->first('wallet_address') }}</div>
                                     </div> --}}
                                     <div class="form-group">
                                        <input type="text" name="twitter_link" id="twitter_link" class="form-control" placeholder="Project's Official Twitter" value="{{ $selectedUser->twitter_link ?? old('twitter_link') }}">
                                        <div class="error-message">{{ $errors->first('twitter_link') }}</div>
                                     </div>
                                     <div class="form-group">
                                        <input type="text" name="discord_link" id="discord_link" class="form-control" placeholder="Project's Official Discord" value="{{ $selectedUser->discord_link ?? old('discord_link') }}">
                                        <div class="error-message">{{ $errors->first('discord_link') }}</div>
                                     </div>
                                     <div class="form-group border-dashed mb-2"></div>
                                     <div class="form-group">
                                        <label for="">What is the maximum number of items in your collection?</label>
                                        <input type="number" min="1" name="maximum_number_in_collection" id="maximum_number_in_collection" class="form-control" value="{{ $selectedUser->maximum_number_in_collection ?? old('maximum_number_in_collection') }}">
                                        <div class="error-message">{{ $errors->first('maximum_number_in_collection') }}</div>
                                     </div>
                                     <div class="form-group">
                                        <label for="">How much have you sold items have you sold from your collection?</label>
                                        <input type="text" name="item_sold" id="item_sold" class="form-control" value="{{ $selectedUser->item_sold ?? old('item_sold') }}">
                                        <div class="error-message">{{ $errors->first('item_sold') }}</div>
                                     </div>
                                     <div class="form-group">
                                        <label for="">What is your collection's blockchain?</label>
                                        <input type="text" name="collection_blockchain" id="collection_blockchain" class="form-control" value="{{ $selectedUser->collection_blockchain ?? old('collection_blockchain') }}">
                                        <div class="error-message">{{ $errors->first('collection_blockchain') }}</div>
                                     </div>
                                     <div class="form-group">
                                        <label for="">What is your collection's contract address(es)? (If available)</label>
                                        <input type="text" name="collection_contract_address" id="collection_contract_address" class="form-control" value="{{ $selectedUser->collection_contract_address ?? old('collection_contract_address') }}">
                                        <div class="error-message">{{ $errors->first('collection_contract_address') }}</div>
                                     </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-btn add-user-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- User list and add user form end -->
    </main>
    <script>
        $(document).ready(function(){
            $(".delete").click(function(){
                var id = $(this).attr('rel');
                Swal.fire({
                    title: 'Are you sure for delete this user?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('delete-user') }}?id="+id;
                    }
                });
            });
        });
        @isset($selectedUser->id)
            $('html, body').animate({
                scrollTop: $("#form-heading").offset().top - 100
            }, 500);
        @endisset
    </script>


@endsection