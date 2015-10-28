# Atolye15 SlackExceptionBundle
This bundle lets you push all exceptions to any Slack channel automatically in your application.

## Installation
First add the dependency to your `composer.json` file:

    "require": {
        ...
        "atolye15/slack-exception-bundle": "dev-master"
    },

Then run composer update command.

    php composer.phar update

Finally enable bundle in your kernel

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Atolye15\SlackExceptionBundle\Atolye15SlackExceptionBundle(),
        );
    }

## Configuration
Before you start to configuration, you must create a new web api token in your Slack dashboard. You can create token from https://api.slack.com/web url.

Full config for SlackExceptioBundle is below:

    atolye15_slack_exception:
        environment: prod # all or prod, default prod
        token: TOKEN # Your Slack token
        channel: general # Channel to publish
        username: APPException # Owner of message
        project: MyAwesomeProject # Identifier for your project
        throw_exception: false # Throw an error if Slack request fails. Default false
        request_timeout: 3000 # Timeout for Slack request. Set 0 for disable timeout. Default 3000
