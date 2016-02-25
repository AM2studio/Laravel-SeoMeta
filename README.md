# Laravel-Seo Meta

Package for handling seo meta tags in Laravel apps

## Install

Via Composer

``` bash
$ composer require am2studio/laravel-seo-meta
```

## Usage

First run migration for this package (src/migration/):

```php
Schema::create('seo_metas', function (Blueprint $table) {
	$table->increments('id');
	$table->string('model_type');
	$table->integer('model_id')->unsigned();
	$table->text('key');
	$table->text('value');

	$table->timestamps();
});
```

For each model which that use seo meta add trait "SeoMetaTrait" and implement interface "SeoMetaInterface"

```php
use AM2Studio\Laravel\SeoMeta\SeoMetaTrait;
use AM2Studio\Laravel\SeoMeta\SeoMetaInterface;

class User implements  SeoMetaInterface
{
    use SeoMetaTrait;
```


Interface "SeoMetaInterface" have 2 function that model need to implement "seoMetasConfig()" and "seoMetas()"

seoMetasConfig() is configuration for meta data for model

seoMetas() is "hasMany" relation to seoMetas of model

```php
public function seoMetasConfig()
{
	return [
		'title'         => ['generator' => 'example.com - '. $this->title],
		'description'   => ['generator' => 'green-rush.com - '. $this->title . ' - ' . $this->short_description,],
		'keywords'      => ['generator' => 'greenrush, dispensary, ' . $this->title . ', ' . $this->short_description,
		'edit'=> false],
		'og:image'      => ['generator' => ["http://i.stack.imgur.com/hEobN.jpg", "http://i.stack.imgur.com/hEobN2.jpg"]],
		'twitter:site'  => [],
	];
}

public function seoMetas()
{
	return $this->hasMany(SeoMeta::class, 'model_id')->where(['model_type' => __CLASS__]);
}
```

Each seo meta that you want model to use must be fefined here. List of possible seo meta tags:

```php
title						-> string
description					-> string
keywords           			-> string
canonical      				-> string
article:published_time		-> string
article:section				-> string
og:description				-> string
og:title					-> string
og:url               		-> string
og:type             		-> string
og:locale           		-> string
og:locale:alternate			-> array
og:site_name        		-> string
og:image         			-> array
og:image:url       			-> array
og:image:size       		-> string
twitter:card       			-> string
twitter:title      			-> string
twitter:site				-> string
```
For each seo meta in config you define generator(how seo meta will be generated) and edit (if seo meta can be edited or will be always generated on model save, default - true)

Finaly add "saeMeta" to Model -> fillable
```php
protected $fillable = [
	...
	'seoMeta'
];
```


Show form for meta seo deta on model:

```php
{!! \AM2Studio\Laravel\SeoMeta\SeoMetaHelper::form($dispensary) !!}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
