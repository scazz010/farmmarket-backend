<?php namespace App\GraphQL\Mutation;

use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadMutation implements MutationInterface, AliasedInterface
{
    public function uploadImage(UploadedFile $file)
    {
        return $file->getBasename() . $file->getFilename() . $file->getMimeType() . $file->getClientOriginalExtension();

    }

    public static function getAliases()
    {
        return [
            // `create_ship` is the name of the mutation that you SHOULD use inside of your types definition
            // `createShip` is the method that will be executed when you call `@=resolver('create_ship')`
            'uploadImage' => 'upload_image'
        ];
    }
}