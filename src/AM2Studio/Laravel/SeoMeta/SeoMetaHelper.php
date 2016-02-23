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
		'canonical'        		 => ['type' => 'string', 'template' => '<link rel="canonical" href="%s" />'],
        'article:published_time' => ['type' => 'string', 'template' => '<meta property="article:published_time" content="%s" />'],
        'article:section'        => ['type' => 'string', 'template' => '<meta property="article:section" content="%s" />'],

        'og:description'         => ['type' => 'string', 'template' => '<meta property="og:description" content="%s" />'],
        'og:title'               => ['type' => 'string', 'template' => '<meta property="og:title" content="%s" />'],
        'og:url'                 => ['type' => 'string', 'template' => '<meta property="og:url" content="h%s" />'],
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

    public static function setSeoMeta($seoMeta)
    {
        if (is_array($seoMeta)) {
            foreach ($seoMeta as $key => $value) {
                self::$seoMeta[$key] = $value;
            }
        }
    }

    public static function render($default)
    {
        $seoMeta = self::$seoMeta;
        foreach ($default as $key => $value) {
            if (! isset($seoMeta[$key])) {
                $seoMeta[$key] = $default[$key];
            }
        }

        $string = "";
        foreach ($seoMeta as $key => $value) {
            if (self::$seoMetaTypes[$key]['type'] == 'string') {
                if ($seoMeta[$key] != '') {
                    $string .= sprintf(self::$seoMetaTypes[$key]['template'], $seoMeta[$key]) . "\n";
                }
            } else {
                $seoMeta[$key] = explode("\n", self::$seoMeta[$key]);
                foreach ($seoMeta[$key] as $row) {
                    if ($row != '') {
                        $string .= sprintf(self::$seoMetaTypes[$key]['template'], $row) . "\n";
                    }
                }
            }
        }
        echo $string;
    }

    public static function form($model)
    {
        $string = "";

        $seoMetasConfig = $model->seoMetasConfig();
        $seoMetas       = $model->seoMetas()->lists('value', 'key');

        foreach ($seoMetasConfig as $key => $seoMetaConfig) {
            $generator = (isset($seoMetaConfig['generator'])) ? $seoMetaConfig['generator'] : '';
            if (is_array($generator)) {
                $generator = implode("\n", $generator);
            }

            if (! isset($seoMetas[$key])) {
                $seoMetas[$key] = $generator;
            }
        }

        foreach ($seoMetasConfig as $key => $seoMetaConfig) {
            $type = self::$seoMetaTypes[$key]['type'];
            $edit = (isset($seoMetaConfig['edit'])) ? $seoMetaConfig['edit'] : true;

            $config          = [];
            $config['class'] = 'form-control';
            $config += (! $edit) ? ['disabled' => 'disabled'] : [];

            $string .= '<div class="form-group">';
            $string .= Form::label($key, $key, ['class' => 'control-label']);
            if ($type == 'string') {
                $string .= Form::text('seoMeta[' . $key . ']', $seoMetas[$key], $config);
            } else {
                $string .= Form::textarea('seoMeta[' . $key . ']', $seoMetas[$key], $config);
            }
            $string .= '</div>';
        }

        return $string;
    }
}
