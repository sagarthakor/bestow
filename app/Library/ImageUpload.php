<?php

namespace App\Library;
use Aws\S3\S3Client;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageUpload
{
    public $image;
    protected $client;

    CONST DOCUMENT_TYPE = 1, PROFILE_IMAGE_TYPE = 2;

    function __construct()
    {
//        $this->client = S3Client::factory([
//            'credentials' => [
//                'key'    => env('S3_KEY'),
//                'secret' => env('S3_SECRET'),
//            ],
//            'region' => env('S3_REGION'),
//            'version' => 'latest',
//        ]);
    }


    public function process($file, $type, $image = null)
    {
        if ($type == self::DOCUMENT_TYPE) {
            $image_width = env('DOCUMENT_IMAGE_MAIN_WIDTH');
            $image_height = env('DOCUMENT_IMAGE_MAIN_HEIGHT');
        }
        else {
            $image_width = env('PROFILE_IMAGE_THUMB_WIDTH');
            $image_height = env('PROFILE_IMAGE_THUMB_HEIGHT');
        }

        if($image){
            $this->image = (new ImageManager())->make($image);
        }
        else
        {
            try {
                $this->image = (new ImageManager())->make(file_get_contents($file->path()));
            }
            catch(NotReadableException $e){
                return false;
            }
        }

        if($this->image->height() < env('IMAGE_CONSTRAINT_HEIGHT') && $this->image->width() < env('IMAGE_CONSTRAINT_WIDTH'))
            return false;


        $this->image->resize($image_width, $image_height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $canvas = (new ImageManager)->canvas($image_width+5, $image_height+5, '#ffffff');
        $this->image = $canvas->insert($this->image, 'center');

        return $this;
    }

    public function store($path, $disk = 'S3')
    {
        $imageName =  Str::uuid()->toString().'.jpg';

        if ($disk == 'S3') {

            $adapter = new AwsS3Adapter($this->client, env('S3_BUCKET'), $path, ['CacheControl' => 2592000]);
            $filesystem = new Filesystem($adapter);

            if(!$filesystem->put($imageName, $this->image->stream('jpg')->__toString()))
                return false;

        }
        else {

            $disk = \Storage::disk('public');
            $disk->put($path . $imageName, $this->image->stream('jpg')->__toString());
        }


        return $imageName;
    }

    /**
     * @param $file
     * @param $path
     * @param string $disk
     * @return bool
     */
    public function fileStore($file, $path, $disk = 'S3')
    {
        if ($file->getClientOriginalExtension() != 'pdf' && $file->getClientOriginalExtension() != 'jpg')
            return false;

        $filename =  Uuid::generate(4).'.' . $file->getClientOriginalExtension();

        if (!$file->storeAs($path, $filename, strtolower($disk)))
            return false;

        return $filename;
    }

    public function delete($path, $image, $disk = 'S3')
    {
        if ($disk == 'S3') {

            $adapter = new AwsS3Adapter($this->client, env('S3_BUCKET'), $path, ['CacheControl' => 2592000]);
            $filesystem = new Filesystem($adapter);
            if($filesystem->delete($image))
                return true;
            else
                return false;

        }
        else {
            $disk = \Storage::disk('spaces');
            $disk->delete($path . $image);
        }
    }

}
