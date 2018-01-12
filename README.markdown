# YWHDoctrineEncryptionBundle

This bundle provides integration for
[DoctrineEncryptaionExtension](https://github.com/yeswehack/DoctrineEncryptionExtension) in
your Symfony2 Project.

License: [MIT](LICENSE)

# Installation

## Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
    $ composer require stof/doctrine-extensions-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

## 2: Enable the Bundle

> When using Flex, this step is handled automatically.

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php

    // app/AppKernel.php

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new YWH\DoctrineEncryptionBundle\YWHDoctrineEncryptionBundle(),
            );

            // ...
        }

        // ...
    }
```

## 3: Configure the bundle

```yaml
# app/config/config.yml
ywh_doctrine_encryption:
    orm: ['default']
    password: password
    key: ... # defuse_key
```

To generate the key, use the `generate-defuse-key` script located in vendor/bin/generate-defuse-key
```bash
$ vendor/bin/generate-defuse-key
```