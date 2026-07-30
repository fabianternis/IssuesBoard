<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Media extends Model 
{
    protected $table = 'media';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'parent_id', 'parent_type', 'remote_id', 'filename', 'url', 'description'];
    public $timestamps = false;


    // protected static function boot()
    // public function uploadAndAssign(string $base64Data, string $originalFilename)
    // public static function createFromBase64(string $base64Data, string $originalFilename, array $attributes = []): self
    public static function createFromUpload(array $file, array $attributes = []): self
    {
        // parent::boot();

        // static::creating(function ($model) {
            // POST to HackClub-CDN here?


            /* ----------------------------------------------- "Credits" (and otehr Links) ---------------------------------------- */
            // API-Docs: https://cdn.hackclub.com/docs/api
            // https://github.com/guzzle/guzzle/issues/2296
            // ...
            /* -------------------------------------------------------------------------------------------------------------------- */

            $client = new \GuzzleHttp\Client();

            // $response = $client->post('https://cdn.hackclub.com/api/v1/upload', [
            // BRUH
            $response = $client->post('https://cdn.hackclub.com/api/v4/upload', [
                'headers' => [
                    'Authorization' => 'Bearer '.env('HACKCLUB_CDN_API_KEY'),
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        // 'contents' => base64_decode($base64Data),
                        'contents' => fopen($file['tmp_name'], 'r'),
                        // 'filename' => $originalFilename,
                        'filename' => $file['name'],
                    ],
                ],
            ]);

            $response_data = json_decode((string) $response->getBody(), true);

            // $this->remote_id = $response_data['id'] ?? null;
            // // $this->cdn_filename = $originalFilename;
            // $this->filename = $response_data['filename'] ?? $originalFilename;
            // $this->url = $response_data['url'] ?? null;

            // // Align local primary key with remote_id and persist model
            // return $this->syncWithRemoteId($this->remote_id);

            $remote_id = $response_data['id'] ?? null;

            if (!$remote_id) {
                // ToDO: Error Stuff (Hackclub CDN Error)
            }

            return static::create(array_merge($attributes,
            [
                'id' => $remote_id,
                'remote_id' => $remote_id,
                // 'filename' => $response_data['filename'] ?? $originalFilename,
                'filename' => $response_data['filename'] ?? $file['name'],
                'url'  => $response_data['url'] ?? null,
            ]));

        // });
    }

    public function parent()
    {
        // return $this->belongsTo(, 'parent_id');
        return $this->morphTo();
    }


    public function syncWithRemoteId(?string $remote_id = null)
    {
        $id = $remote_id ?? $this->remote_id;

        if (!empty($id)) {
            $this->setAttribute($this->getKeyName(), $id);
        }

        return $this->save();
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