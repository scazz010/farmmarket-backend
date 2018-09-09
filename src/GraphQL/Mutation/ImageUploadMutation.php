<?php namespace App\GraphQL\Mutation;

use App\FarmMarket\Model\Image\Command\UploadImage;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Prooph\ServiceBus\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use App\Repository\ImageRepository;


class ImageUploadMutation implements MutationInterface, AliasedInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;
    /**
     * @var ImageRepository
     */
    private $imageRepository;

    public function __construct(ContainerInterface $container, UploaderHelper $uploaderHelper, ImageRepository $imageRepository)
    {
        $this->commandBus = $container->get('prooph_service_bus.farm_market_command_bus');
        $this->uploaderHelper = $uploaderHelper;
        $this->imageRepository = $imageRepository;
    }

    public function uploadImage(UploadedFile $file)
    {
        $uploadImageCommand = UploadImage::withData(
            Uuid::uuid4(),
            $file
        );

        $this->commandBus->dispatch($uploadImageCommand);

        $image = $this->imageRepository->find($uploadImageCommand->imageId());

        return [
            'id' => $image->id(),
            'url' => $this->uploaderHelper->asset($image, 'imageFile')
        ];
    }

    public static function getAliases()
    {
        return [
            'uploadImage' => 'upload_image'
        ];
    }
}