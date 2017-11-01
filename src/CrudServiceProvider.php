<?php

namespace Qla\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // LOAD THE VIEWS
        // - first the published views (in case they have any changes)
        $this->loadViewsFrom(resource_path('views/vendor/qla/crud'), 'crud');
        // - then the stock views that come with the package, in case a published view might be missing
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'crud');

        //$this->mergeConfigFrom(
        //    __DIR__.'/config/qla/base.php', 'qla.base'
        //);


        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/qla/crud'),
            __DIR__.'/public' => public_path('vendor/qla'),
        ], 'qla');



        \Form::component('requiredText', 'crud::components.requiredTextbox', [
            'name',
            'value',
            'unit',
            'attributes',
            'addon' => null,
        ]);

        \Form::component('hasValidatorText', 'crud::components.hasValidatorTextbox', [
            'name',
            'value',
            'unit',
            'attributes',
            'addon' => null,
        ]);

        \Validator::extend('ip', function ($attr, $value, $para, $validator) {
            return preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $value);
        }, '请输入正确的ip地址！');

        \Validator::extend('phone', function ($attr, $value, $para, $validator) {
            return preg_match('((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)', $value);
        }, '请输入正确的电话号码！');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // register its dependencies
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);

        // register their aliases
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('CRUD', \Qla\CRUD\CrudServiceProvider::class);
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('Html', \Collective\Html\HtmlFacade::class);



    }
}
