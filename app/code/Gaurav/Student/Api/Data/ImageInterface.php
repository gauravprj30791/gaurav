<?php
namespace Gaurav\Student\Api\Data;
interface ImageInterface
{
    const IMAGE_ID          = 'student_id';
    const IMAGE             = 'image';
    public function getId();
    public function getImage();
    public function setId($id);
    public function setImage($image);
}
