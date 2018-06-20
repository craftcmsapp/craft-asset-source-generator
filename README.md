# Craft Asset Source Generator plugin for Craft CMS 3.x

Creates configured asset sources when the plugin is first installed

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require servdhost/craft-asset-source-generator

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Craft Asset Source Generator.

## Craft Asset Source Generator Overview

A Craft CMS plugin to create some asset volumes upon installation. Used within Servd's infrastructure.

## Configuring Craft Asset Source Generator

Create a config file at `config/craft-asset-source-generator.php` with something like the following contents:

```
<?php

return [
    'sources' => [
        [
            'keyId' => 'your-AWS-key',
            'secret' => 'your-AWS-secret',
            'bucket' => 's3-bucket',
            'region' => 's3-region',
            'subfolder' => 'prepended-to-all-asset-keys',
            'expires' => '5 minutes',
            'cfDistributionId' => 'cloudfront-distro-id',
            'name' => 'volume-name',
            'handle' => 'volume-handle',
            'hasUrls' => true,
            'url' => 'https://url-to-your-volume.com'
        ]
    ]
];

```

You can add multiple volumes, they just need different handles.

## Using Craft Asset Source Generator

Ensure [craftcms/aws-s3](https://github.com/craftcms/aws-s3/) is installed prior to installing this plugin.

Then install this plugin.

Brought to you by [Servd](https://twitter.com/servdhosting)
