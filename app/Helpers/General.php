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

// function to add new key langs to lang files
if (!function_exists('translate_lang')) {
    function translate_lang($key = null, $file = 'front')
    {
        if (empty($key)) {
            return $key;
        }

        // Get current locale
        $currentLocale = app()->getLocale();
        $anotherCurrentLocale = $currentLocale == 'ar' ? 'en' : 'ar';
        // Build file path
        $filePath        = resource_path("lang/{$currentLocale}/{$file}.php");
        $anotherFilePath = resource_path("lang/{$anotherCurrentLocale}/{$file}.php");

        // Load existing translations
        $translations = [];
        if (file_exists($filePath)) {
            $translations        = include $filePath;
            $anotherTranslations = include $anotherFilePath;
            if (!is_array($translations)) {
                $translations = [];
            }
        }

        // Check if key exists
        $value = $translations[$key] ?? null;

        if ($value === null) {
            // Key doesn't exist, add it directly to the main array
            $translations[$key] = $key;
            $anotherTranslations[$key] = $key;

            // Write updated translations back to file
            $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
            file_put_contents($filePath, $content);
            $anotherContent = "<?php\n\nreturn " . var_export($anotherTranslations, true) . ";\n";
            file_put_contents($anotherFilePath, $anotherContent);

            $value = $key; // Return the key as fallback
        }

        return $value ?? $key;
    }
}

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

if (!function_exists('SubjectRepository')) {
  function SubjectRepository()
  {
    return new \App\Repositories\SubjectRepository;
  }
}

if (!function_exists('CartRepository')) {
  function CartRepository()
  {
    return new \App\Repositories\CartRepository;
  }
}

// return auth user
// type sudent but now return any user
if (!function_exists('auth_student')) {
  function auth_student()
  {
    return auth('user')->user();
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



