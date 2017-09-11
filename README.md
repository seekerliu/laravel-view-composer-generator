# Laravel View Composer Generator
给 `Laravel` 增加一个了 `make:view-composer` 命令，用来自动生成 `view composer` 类文件。


## 安装

1. 安装包文件

  ```shell
  composer require seekerliu/laravel-view-composer-generator:dev-master --dev
  ```

## 配置

1. 注册 `ServiceProvider`:

  ```php
  // app/Providers/AppServiceProvider.php
  public function register()
  {
      if ($this->app->environment() == 'local') {
          $this->app->register('Seekerliu\ViewComposerGenerator\ServiceProvider');
      }
  }   
  ```

2. 创建配置文件：

  ```shell
  php artisan vendor:publish --provider="Seekerliu\ViewComposerGenerator\ServiceProvider"
  ```
  
## 用法

  ```shell
  php artisan make:view-composer TestComposer
  php artisan make:view-composer Test\\TestComposer
  ```