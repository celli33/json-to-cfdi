# phpcfdi/json-to-cfdi-bridge

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Scrutinizer][badge-quality]][quality]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

> Library to convert json given structure to cfdi.

:us: The documentation of this project is in spanish as this is the natural language for intended audience.

:mexico: La documentación del proyecto está en español porque ese es el lenguaje principal de los usuarios.

Esta librería ha sido creada para llevar un json con una estructura definida a un CFDI válido. 

## Instalación

Usa [composer](https://getcomposer.org/)

```shell
composer require phpcfdi/json-to-cfdi-bridge
```

## Ejemplo básico de uso

```php
<?php 

use PhpCfdi\Credentials\Credential;
use PhpCfdi\JsonToCfdiBridge\Factory;
use PhpCfdi\JsonToCfdiBridge\Values\CredentialCsd;
use PhpCfdi\JsonToCfdiBridge\Values\JsonContent;
declare(strict_types=1);

$jsonContent = new JsonContent($this->fileContents('invoice.json'));
        // si no se envían argumentos se usan los recursos en línea del SAT.
        $factory = Factory::create();
        $preCfdiAction = $factory->createBuildPreCfdiFromJsonAction();
        $password = trim($this->fileContents('Csd/CACX7605101P8-password.txt'));
        $credential = Credential::openFiles(
            $this->filePath('Csd/CACX7605101P8.cer'),
            $this->filePath('Csd/CACX7605101P8.key'),
            trim($password)
        );
        $csd = new CredentialCsd($credential);
        $result = $preCfdiAction->execute($jsonContent, $csd);
        print_r($result->getConvertedXml());
```

[contributing]: https://github.com/phpcfdi/credentials/blob/main/CONTRIBUTING.md
[changelog]: https://github.com/phpcfdi/credentials/blob/main/docs/CHANGELOG.md
[todo]: https://github.com/phpcfdi/credentials/blob/main/docs/TODO.md

[source]: https://github.com/phpcfdi/credentials
[release]: https://github.com/phpcfdi/credentials/releases
[license]: https://github.com/phpcfdi/credentials/blob/main/LICENSE
[build]: https://github.com/phpcfdi/credentials/actions/workflows/build.yml?query=branch:main
[quality]: https://scrutinizer-ci.com/g/phpcfdi/credentials/
[coverage]: https://scrutinizer-ci.com/g/phpcfdi/credentials/code-structure/main/code-coverage
[downloads]: https://packagist.org/packages/phpcfdi/credentials

[badge-source]: https://img.shields.io/badge/source-phpcfdi/credentials-blue?style=flat-square
[badge-release]: https://img.shields.io/github/release/phpcfdi/credentials?style=flat-square
[badge-license]: https://img.shields.io/github/license/phpcfdi/credentials?style=flat-square
[badge-build]: https://img.shields.io/github/workflow/status/phpcfdi/credentials/build/main?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/phpcfdi/credentials/main?style=flat-square
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/phpcfdi/credentials/main?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/credentials?style=flat-square