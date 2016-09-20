drupal-module
=============

[![Build Status](https://travis-ci.org/AdTechMedia/drupal-module.svg?branch=master)](https://travis-ci.org/AdTechMedia/drupal-module)
[![Test Coverage](https://codeclimate.com/repos/57dff2b4f01b5b648b0042b0/badges/aed49615ace44e12bda8/coverage.svg)](https://codeclimate.com/repos/57dff2b4f01b5b648b0042b0/coverage)

Drupal Module for AdTechMedia.io

Ad Tech Media (aka ATM) is an Innovative B2B Solution to Monetize Content through Micropayments


## Getting Started

### Step 1. Pre-requisites

- [x] [Create an Amazon Web Services account](https://www.youtube.com/watch?v=WviHsoz8yHk)
- [x] [Configure AWS Command Line Interface](https://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-started.html)
- [x] [Get Started - Installing Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
- [x] [JDK 8 and JRE 8 Installation Start Here](https://docs.oracle.com/javase/8/docs/technotes/guides/install/install_overview.html)
- [x] [Install nvm](https://github.com/creationix/nvm#install-script) and [use node v4.3+](https://github.com/creationix/nvm#usage)
- [ ] Install DEEP CLI, also known as `deepify`:

```bash
npm install deepify -g
```

> If you want to use `deepify` on Windows, please follow the steps from
[Windows Configuration](https://github.com/MitocGroup/deep-framework/blob/master/docs/windows.md)
before running `npm install deepify -g` and make sure all `npm` and `deepify` commands are executed
inside Git Bash.

### Step 2. Install Microservice(s) Locally

```bash
deepify install github://MitocGroup/drupal-module ~/drupal-module
```

> Path parameter in all `deepify` commands is optional and if not specified, assumes current
working directory. Therefore you can skip `~/drupal-module` by executing
`mkdir ~/drupal-module && cd ~/drupal-module` before `deepify install`.

### Step 3. Run Microservice(s) in Development

```bash
deepify server ~/drupal-module -o
```

> When this step is finished, you can open in your browser the link *http://localhost:8000*
and enjoy the drupal-module running locally.

### Step 4. Deploy Microservice(s) to Production

```bash
deepify deploy ~/drupal-module
```

> Amazon CloudFront distribution takes up to 20 minutes to provision, therefore don’t worry
if it returns an HTTP error in the first couple of minutes.

### Step 5. Remove Microservice(s) from Production

```bash
deepify undeploy ~/drupal-module
```

> Amazon CloudFront distribution takes up to 20 minutes to unprovision. That's why `deepify`
command checks every 30 seconds if it's disabled and when successful, removes it from your account.


## Developer Resources

Having questions related to drupal-module?

- Ask questions: https://stackoverflow.com/questions/tagged/deep-framework
- Chat with us: https://mitocgroup.slack.com/messages/general
- Send an email: feedback@deep.mg

Interested in contributing to drupal-module?

- Contributing: https://github.com/MitocGroup/drupal-module/blob/master/CONTRIBUTING.md
- Issue tracker: https://github.com/MitocGroup/drupal-module/issues
- Releases: https://github.com/MitocGroup/drupal-module/releases
- Roadmap: https://github.com/MitocGroup/drupal-module/blob/master/ROADMAP.md

Looking for web applications that use (or are similar to) drupal-module?

- Hello World: https://hello.deep.mg | https://github.com/MitocGroup/deep-microservices-helloworld
- Todo App: https://todo.deep.mg | https://github.com/MitocGroup/deep-microservices-todomvc
- Enterprise Software Marketplace: https://www.deep.mg


## Sponsors

This repository is being sponsored by:
- [Mitoc Group](https://www.mitocgroup.com)
- [DEEP Marketplace](https://www.deep.mg)

This code can be used under MIT license:
> See [LICENSE](https://github.com/MitocGroup/drupal-module/blob/master/LICENSE) for more details.
