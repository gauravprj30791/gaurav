<?php
namespace Digital\Productlabel\Api;

use Digital\Productlabel\Api\Data\ImageInterface;
interface ImageRepositoryInterface
{    
    public function save(ImageInterface $image);   
    public function getById($imageId);
    public function delete(ImageInterface $image);
    public function deleteById($imageId);
}
