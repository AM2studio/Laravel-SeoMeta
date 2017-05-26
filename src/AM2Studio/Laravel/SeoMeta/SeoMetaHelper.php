<?php

namespace AM2Studio\Laravel\SeoMeta;

use Form;

class SeoMetaHelper
{
    protected static $seoMeta = [];

    public static $seoMetaTypes = [
        'title'                  => ['type' => 'string', 'template' => '<title>%s</title>'],
        'description'            => ['type' => 'string', 'template' => '<meta name="description" itemprop="description" content="%s" />'],
        'keywords'               => ['type' => 'string', 'template' => '<meta name="keywords" content="%s" />'],
        'canonical'              => ['type' => 'string', 'template' => '<link rel="canonical" href="%s" />'],
        'article:published_time' => ['type' => 'string', 'template' => '<meta property="article:published_time" content="%s" />'],
        'article:section'        => ['type' => 'string', 'template' => '<meta property="article:section" content="%s" />'],

        'og:description'         => ['type' => 'string', 'template' => '<meta property="og:description" content="%s" />'],
        'og:title'               => ['type' => 'string', 'template' => '<meta property="og:title" content="%s" />'],
        'og:url'                 => ['type' => 'string', 'template' => '<meta property="og:url" content="%s" />'],
        'og:type'                => ['type' => 'string', 'template' => '<meta property="og:type" content="%s" />'],
        'og:locale'              => ['type' => 'string', 'template' => '<meta property="og:locale" content="%s" />'],
        'og:locale:alternate'    => ['type' => 'array',  'template' => '<meta property="og:locale:alternate" content="%s" />'],
        'og:site_name'           => ['type' => 'string', 'template' => '<meta property="og:site_name" content="%s" />'],
        'og:image'               => ['type' => 'array',  'template' => '<meta property="og:image" content="%s" />'],
        'og:image:url'           => ['type' => 'array',  'template' => '<meta property="og:image:url" content="%s" />'],
        'og:image:size'          => ['type' => 'string', 'template' => '<meta property="og:image:size" content="%s" />'],

        'twitter:card'           => ['type' => 'string', 'template' => '<meta name="twitter:card" content="%s" />'],
        'twitter:title'          => ['type' => 'string', 'template' => '<meta name="twitter:title" content="%s" />'],
        'twitter:site'           => ['type' => 'string', 'template' => '<meta name="twitter:site" content="%s" />'],
    ];

    public static function setSeoMeta($seoMeta, $model = null, $variant = '')
    {
        foreach ($seoMeta as $key => $value) {
            self::$seoMeta[$key] = $value;
        }
    }
    
    public static function setSeoMetaModel($model, $variant, $data = [])
    {
        $function = 'getSeo' . ucfirst($variant);

        $metasGenerator = $model->$function($data);
        $metasDatabase  = $model->getSeoMeta($variant);
        
        foreach ($metasGenerator as $key => $value) {
            if($value != ''){
                self::$seoMeta[$key] = $value;
            }
        }

        foreach ($metasDatabase as $key => $value) {
            if($value != ''){
                $config = $model::$seoMeta;
                foreach($config['variables'] as $variable){
                    $value = str_replace($variable[1], $variable[2], $value);
                }
                $value = view(['template' => $value], ['model' => $model]);

                self::$seoMeta[$key] = $value;
            }
        }
    }

    public static function render($default)
    {
        $seoMeta = self::$seoMeta;
        foreach ($default as $key => $value) {
            $seoMeta[$key] = $default[$value];
        }

        $seoMetasRoute = SeoMeta::where(['route' => \Request::route()->getName()])->get();
        foreach($seoMetasRoute as $seoMetaRoute){
            $seoMeta[$seoMetaRoute->key] = $seoMetaRoute->value;
        }

        $string = '';
        foreach ($seoMeta as $key => $value) {
            if (self::$seoMetaTypes[$key]['type'] == 'string') {
                if ($seoMeta[$key] != '') {
                    $seoMeta[$key] = trim(preg_replace('!\s+!', ' ', strip_tags($seoMeta[$key])));
                    $string .= sprintf(self::$seoMetaTypes[$key]['template'], $seoMeta[$key])."\n";
                }
            } else {
                $seoMeta[$key] = explode("\n", self::$seoMeta[$key]);
                foreach ($seoMeta[$key] as $row) {
                    if ($row != '') {
                        $row = trim(preg_replace('!\s+!', ' ', strip_tags($row)));
                        $string .= sprintf(self::$seoMetaTypes[$key]['template'], $row)."\n";
                    }
                }
            }
        }
        echo $string;
    }

    public static function formData($model)
    {
        $seoMetas = $model->seoMetas()->lists('value', 'key');
        
        $config          = $model::$seoMeta;
        $configMetas     = $config['metas'];
        $configVariants  = $config['variants'];
        
        $formData = [];
        foreach ($configVariants as $variant) {
            foreach ($configMetas as $meta) {
                $key   = $variant . '.' . $meta;
                $name  = 'seoMeta[' . $key . ']';
                $value = empty($seoMetas[$key]) ? '' : $seoMetas[$key];
                $type  = self::$seoMetaTypes[$meta]['type'] == 'string' ? 'text' : 'textarea';
                $label = ucwords($meta);
                
                $formData[$variant][$meta] = ['key' => $key, 'name' => $name, 'value' => $value, 'type' => $type, 'label' => $label];
            }
        }

        return $formData;
    }
}
