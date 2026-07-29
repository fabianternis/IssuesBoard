<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Media extends Model 
{
    protected $table = 'media';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'parent_id', 'parent_type', 'cdn_id', 'cdn_filename', 'cdn_url', 'description'];    public $timestamps = false;


    // protected static function boot()
    public function uploadAndAssign(string $base64Data, string $originalFilename) {
        // parent::boot();

        // static::creating(function ($model) {
            // POST to HackClub-CDN here?


            /* ----------------------------------------------- "Credits" (and otehr Links) ---------------------------------------- */
            // API-Docs: https://cdn.hackclub.com/docs/api
            // https://github.com/guzzle/guzzle/issues/2296
            // ...
            /* -------------------------------------------------------------------------------------------------------------------- */

            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://cdn.hackclub.com/api/v1/upload', [
                'headers' => [
                    'Authorization' => 'Bearer '.env('HACKCLUB_CDN_API_KEY'),
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen('data://text/plain;base64,' . $base64Data, 'r'),
                        'filename' => $originalFilename,
                    ],
                ],
                
            ]);

            $response_data = json_decode((string) $response->getBody(), true);

            $this->cdn_id = $response_data['id'] ?? null;
            // $this->cdn_filename = $originalFilename;
            $this->cdn_filename = $response_data['filename'] ?? null;
            $this->cdn_url = $response_data['url'] ?? null;

            return $this->save();

        // });
    }

    public function parent()
    {
        // return $this->belongsTo(, 'parent_id');
        return $this->morphTo();
    }


    
    // public function isDeleted(): bool
    // {
    //     return !is_null($this->deleted_at);
    // }

    // public function is_deleted(): bool
    // {
    //     return $this->isDeleted();
    // }

    // public function soft_delete()
    // {
    //     if(!$this->isDeleted()) {
    //         $this->deleted_at = $this->freshTimestamp();
    //         return $this->save();
    //     }
    // }

    // public function delete()
    // {
    //     return $this->soft_delete();
    // }
}