<?php
namespace Digital\Productlabel\Api\Data;
interface ImageInterface
{
    const IMAGE_ID          = 'label_id';
    const IMAGE             = 'image';
    public function getId();
    public function getImage();
    public function setId($id);
    public function setImage($image);
}
