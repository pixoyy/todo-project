<?php

namespace App\Helper;

use App\Models\AuthorizationType;
use App\Models\Module;
use App\Models\ModuleGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Helper
{
    public static function getModuleRoute()
    {
        return explode('_', Route::currentRouteName())[0];
    }

    public static function getModuleName()
    {
        $route = self::getModuleRoute();
        $module = Module::where('route', $route)->first();
        return isset($module) ? $module->name : null;
    }

    public static function getModules()
    {
        $type = AuthorizationType::where('name', 'read')->first();
        $typeId = $type->id;
        $roleId = Auth::user()->role_id;

        $moduleGroups = ModuleGroup::withWhereHas('modules', function ($query1) use ($typeId, $roleId) {
            $query1->where('is_shown', 1)
            ->whereHas('authorizations', function ($query2) use ($typeId, $roleId) {
                $query2->where('authorization_type_id', $typeId)->where('role_id', $roleId);
            })->orderBy('order');
        })->orderBy('order')->get();

        $modules = Module::whereNull('module_group_id')
            ->where('is_shown', 1)
            ->whereHas('authorizations', function ($query) use ($typeId, $roleId) {
            $query->where('authorization_type_id', $typeId)->where('role_id', $roleId);
            })->orderBy('order')->get();    

        $merged = $moduleGroups->concat($modules)->sortBy('order');
        return $merged;
    }

    public static function getIndonesianDateFormat($date)
    {
        return Carbon::parse($date)->locale('id_ID')->translatedFormat('d M Y');
    }

    public static function serveImage($path, $options = array())
    {
        if ($path == NULL || $path == '') {
            return null;
        }
        if ($options == NULL) {
            $options = array();
        }
        unset($options['s']);
        $options['b'] = env('AWS_BUCKET');
        ksort($options);
        $baseUrl = env('IMAGE_URL');

        $signKey = 'v-LK4WCdhcfcc%jt*VC2cj%nVpu+xQKvLUA%H86kRVk_4bgG8&CWM#k*b_7MUJpmTc=4GFmKFp7=K%67je-skxC5vz+r#xT?62tT?Aw%FtQ4Y3gvnwHTwqhxUh89wCa_';

        $path = ltrim($path, '/');
        $signature = md5($signKey . ':' . $path . '?' . http_build_query($options));
        $options['s'] = $signature;

        $baseUrl = rtrim($baseUrl, '/') . '/' . rtrim($path, '/') . '?' . http_build_query($options);
        return $baseUrl;
    }

    public static function staticPath($path, $options = array())
    {
        if ($path == NULL || $path == '') {
            return null;
        }
        if ($options == NULL) {
            $options = array();
        }
        unset($options['s']);
        ksort($options);
        $baseUrl = env('STATIC_PATH');

        $signKey = 'v-LK4WCdhcfcc%jt*VC2cj%nVpu+xQKvLUA%H86kRVk_4bgG8&CWM#k*b_7MUJpmTc=4GFmKFp7=K%67je-skxC5vz+r#xT?62tT?Aw%FtQ4Y3gvnwHTwqhxUh89wCa_';

        $path = ltrim($path, '/');
        $signature = md5($signKey . ':' . $path . '?' . http_build_query($options));
        $options['s'] = $signature;

        $baseUrl = rtrim($baseUrl, '/') . '/' . rtrim($path, '/') . '?' . http_build_query($options);
        return $baseUrl;
    }

    public static function readFiles(array $urls)
    {
        $files_info = [];
        foreach ($urls as $r) {
            $path = $r['path'];
            $url = $r['url'];
            $file_storage_id = $r['file_storage_id'];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_NOBODY, TRUE);
            $data = curl_exec($ch);
            $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            $fileName = pathinfo($url, PATHINFO_FILENAME) . '.' . pathinfo($path, PATHINFO_EXTENSION);
            $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $files_info[] = array(
                "file_storage_id" => $file_storage_id,
                "name" => $fileName,
                "size" => $fileSize,
                "link" => $url,
                "path" => $path
            );
        }

        return $files_info;
    }
}
