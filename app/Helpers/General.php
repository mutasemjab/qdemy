<?php
define('CURRENCY','JOD');
define('PGN',18);
define('FIELD_TYPE',[
    'scientific'=> 'scientific',
    'literary'  => 'literary',
    'general'   => 'general',
]);
define('OPTIONAL_FROM_FIELD_TYPE',[
    'scientific'  => 'scientific',
    'literary'    => 'literary',
    'general'     => 'general',
]);

if (!function_exists('BunnyHelper')) {
  function BunnyHelper()
  {
    return new \App\Services\Bunny;
  }
}

if (!function_exists('CategoryRepository')) {
  function CategoryRepository()
  {
    return new \App\Repositories\CategoryRepository;
  }
}

if (!function_exists('CourseRepository')) {
  function CourseRepository()
  {
    return new \App\Repositories\CourseRepository;
  }
}

if (!function_exists('CartRepository')) {
  function CartRepository()
  {
    return new \App\Repositories\CartRepository;
  }
}

function uploadImage($folder, $image)
{
    $extension = strtolower($image->extension());
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $image->move($folder, $filename);
    return $filename;
}



function uploadFile($file, $folder)
{
    $path = $file->store($folder);
    return $path;
}



