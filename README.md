# Tasc

A generic Tool for Assembling Source Code.

## About

Sometimes you just need a generic tool to fetch source code from various 
locations and assemble it in a way you specify. My goal for this project is to
create a simple reproducable method for assembling code so that your 
development workflow and your deployment workflow can use the same method.

## Usage

### Download

Clone this repository or download a release.

### Create a Manifest

The manifest describes your project. It contains references to all the source
code you want to fetch and where you want it put. It also contains any patches
you want to apply. The file contains an array of projects and an array of 
patches. It is a single canonical description of your project.

Here is an example:

```yml
# manifest.yml
---
projects:
  # Out of the box, git and zip providers are supported. You can create your
  # own providers by implementing the HcpssBanderson\Provider\ProviderInterface.
  - provider: git
    
    # Where the project is located. 
    source: "https://github.com/moodle/moodle.git"
    # 
    # You could also add your GitHub token like this for private repos:
    source: "https://MYGITHUBTOKEN:x-oauth-basic@github.com/MyCompany/private.git"
    # 
    # Or even better, you can use Symfony's Dependency injection to define the
    # token in your parameters.yml file (described later) and inject it here:
    source: "https://%github.access_token%:x-oauth-basic@github.com/MyCompany/private.git"
    
    # The git provider accepts a tag or a branch.
    tag: v2.9.3
    
    # This won't be used because a tag is specified. If neither is specified,
    # the default is "master".
    branch: MOODLE29_STABLE
    
    # Where do you want to put this relative to the project root? This is 
    # optional, the default is the current directory.
    destination: moodle
    
    # Clone this repo into directory called what? This is optional. In this 
    # example, Moodle would be cloned into PROJECT_ROOT/moodle/core.
    rename: core
    
  - provider: zip
    rename: essential
    source: "https://moodle.org/plugins/download.php/9675/theme_essential_moodle29_2015062412.zip"
    destination: moodle/core/theme
  
patches:
  # Simple source of the patch file and where they should be applied relative to 
  # the project root. Right now only patch files are supported. But you can
  # define your own patch type by implementing 
  # HcpssBanderson\Patch\PatcherInterface.
  - type: patch_file
    src:  mod_forum_lib.php.patch
    dest: "mod/forum/lib.php"
  - type: patch_file
    src:  calendar_lib.php.patch
    dest: "calendar/lib.php"
```

### Create a Parameters File

Create a file called parameters.yml in the Tasc project root. In the above 
example, I used a placeholer for my GitHub access token. This is where it will
be defined:

```
# parameters.yml
---
parameters:
  github.access_token: MyRealAccessToken
```

### Run it

```
$ php /path/to/tasc.php --manifest=/my/manifest.yml --destination=/var/www
```

## Dependencies

PHP 5.5.9 or higher

## License

Released Under the GNU General Public License v3 
http://www.gnu.org/copyleft/gpl.html
