<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 API Project Template</h1>
    <br>
</p>

Yii 2 API Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing API Web applications with multiple tiers.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-advanced.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-advanced)

DIRECTORY STRUCTURE
-------------------

```
common
    config/                 contains shared configurations
    mail/                   contains view files for e-mails
    tests/                  contains tests for common classes    
console
    config/                 contains console configurations
    controllers/            contains console controllers (commands)
    migrations/             contains database migrations
    models/                 contains console-specific model classes
    runtime/                contains files generated during runtime
rest
    config/                 contains frontend configurations
    versions             
        v1                  contains API version 1
            controllers/    contains API version 1 controllers
    models/                 contains frontend-specific model classes
    runtime/                contains files generated during runtime
    tests/                  contains tests for frontend application
vendor/                     contains dependent 3rd-party packages
environments/               contains environment-based overrides
```
