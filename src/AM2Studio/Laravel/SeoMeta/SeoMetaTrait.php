<?php

namespace AM2Studio\Laravel\SeoMeta;

trait SeoMetaTrait
{
    private $seoMetaFromForm = [];
    
    public function seoMetas()
    {
        return $this->morphMany(SeoMeta::class, 'model')->where('model_id', '<>', '');
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

    public function getSeoMeta($variantCurrent)
    {
        $seoMetas = [];
        $seoMetasTmp = $this->seoMetas;
        foreach($seoMetasTmp as $seoMetaTmp){
            $key     = $seoMetaTmp['key'];
            $value   = $seoMetaTmp['value'];
            $variant = $seoMetaTmp['variant'];

            if($variantCurrent == $variant){
                $seoMetas[$key] = $value;
            }
        }

        return $seoMetas;
    }

    private function saveSeoMeta()
    {
        $config          = self::$seoMeta;
        $configMetas     = $config['metas'];
        $configVariants  = $config['variants'];
        
        foreach ($configVariants as $variant) {
            foreach ($configMetas as $key) {
                $seoMeta = $this->seoMetas()->where(['key' => $key, 'variant' => $variant])->first();
                $value   = $this->seoMetaFromForm[$variant][$key];
                
                if ( ! $seoMeta) {
                    if($value != ''){
                        SeoMeta::create(['key' => $key, 'value' => $value, 'variant' => $variant, 'model_id' => $this->id, 'model_type' => __CLASS__]);
                    }
                } else {
                    if($value == ''){
                        $seoMeta->delete();
                    }else{
                        $seoMeta->update(['value' => $value]);
                    }
                }
            }
        }
    }
}
