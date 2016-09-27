<?php

namespace AM2Studio\Laravel\SeoMeta;

trait SeoMetaTrait
{
    private $seoMetaFromForm = [];

    public function seoMetaModelCreated()
    {
        $this->generateSeoMeta('create');
    }

    public function seoMetaModelUpdated()
    {
        $this->generateSeoMeta('update');
    }

    public function seoMetaModelUpdating()
    {
        $this->seoMetaFromForm = $this->seoMeta;
        unset($this->seoMeta);
    }

    public function getSeoMeta()
    {
        $showSeoMetas = [];
        $seoMetasConfig = $this->seoMetasConfig();
        $seoMetas = $this->seoMetas->lists('value', 'key')->toArray();
        foreach ($seoMetas as $k => $v) {
            if (isset($seoMetasConfig[$k])) {
                $showSeoMetas[$k] = $v;
            }
        }

        return $showSeoMetas;
    }

    private function generateSeoMeta($type = 'create')
    {
        $seoMetasConfig = $this->seoMetasConfig();
        $seoMetas = $this->seoMetas;

        foreach ($seoMetasConfig as $key => $seoMetaConfig) {
            $existsInDbId = false;
            foreach ($seoMetas as $seoMeta) {
                if ($key == $seoMeta->key) {
                    $existsInDbId = $seoMeta->id;
                }
            }
            $content = (isset($seoMetaConfig['generator'])) ? $seoMetaConfig['generator'] : '';
            if (is_array($content)) {
                $content = implode("\n", $content);
            }
            $edit = (isset($seoMetaConfig['edit'])) ? $seoMetaConfig['edit'] : true;
            if ($type == 'update' && $edit == true) {
                if (isset($this->seoMetaFromForm[$key])) {
                    $content = $this->seoMetaFromForm[$key];
                }
            }

            if ($existsInDbId == false) {
                SeoMeta::create(['model_id' => $this->id, 'model_type' => __CLASS__, 'key' => $key, 'value' => $content]);
            } else {
                SeoMeta::find($existsInDbId)->update(['value' => $content]);
            }
        }

        foreach ($seoMetas as $seoMeta) {
            $existsInConfig = false;
            foreach ($seoMetasConfig as $key => $seoMetaConfig) {
                if ($key == $seoMeta->key) {
                    $existsInConfig = true;
                }
            }
            if ($existsInConfig == false) {
                SeoMeta::destroy($seoMeta->id);
            }
        }
    }
}
