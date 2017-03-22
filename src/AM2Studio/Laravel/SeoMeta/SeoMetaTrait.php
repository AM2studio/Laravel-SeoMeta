<?php

namespace AM2Studio\Laravel\SeoMeta;

trait SeoMetaTrait
{
    private $seoMetaFromForm = [];
    
    public function seoMetas()
    {
        return $this->hasMany(SeoMeta::class, 'model_id')->where(['model_type' => __CLASS__]);
    }

    public function seoMetaModelUpdated()
    {
        $this->saveSeoMeta();
    }

    public function seoMetaModelUpdating()
    {
        $this->seoMetaFromForm = $this->seoMeta;
        unset($this->seoMeta);
    }

    public function getSeoMeta($variant)
    {
        $showSeoMetas = [];
        $config       = self::$seoMeta;
        $configMetas     = $config['metas'];
        $seoMetas = $this->seoMetas->lists('value', 'key')->toArray();
        foreach ($configMetas as $meta) {
            $key = $variant . '.' . $meta;
            if (isset($seoMetas[$key])) {
                $showSeoMetas[$meta] = $seoMetas[$key];
            }
        }

        return $showSeoMetas;
    }

    private function saveSeoMeta()
    {
        $config          = self::$seoMeta;
        $configMetas     = $config['metas'];
        $configVariants  = $config['variants'];
        
        foreach ($configVariants as $variant) {
            foreach ($configMetas as $meta) {
                $key     = $variant . '.' . $meta;
                $seoMeta = $this->seoMetas()->where(['key' => $key])->first();
                $content = $this->seoMetaFromForm[$key];
                
                if($content == ''){
                    continue;
                }
                
                if ( ! $seoMeta) {
                    SeoMeta::create(['model_id' => $this->id, 'model_type' => __CLASS__, 'key' => $key, 'value' => $content]);
                } else {
                    $seoMeta->update(['value' => $content]);
                }
            }
        }

    }
}
