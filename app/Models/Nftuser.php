<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nftuser extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'project_name', 'opensea_link', 'wallet_address', 'twitter_link', 'discord_link', 'maximum_number_in_collection', 'collection_blockchain', 'collection_contract_address', 'item_sold'];
}
