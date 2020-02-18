=== El mejor Cluster ===
Contributors: derethor
Tags: cluster,seo,related posts
Tested up to: 5.3
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Create easy related posts blocks with a simple shortcode.
This plugin is light, very easy to use and designed for SEO.

Visit the official wordpress plugin page: https://wordpress.org/plugins/mejorcluster/

== Description ==
This plugin with create a related post block with a simple shortcode.

You can configure the title, description and image of each post (by default will use the title, first  content words and featued image of the post)

You can setup few global options to change the visual aspect of the block.


== Change Log ==

1.1.9:

- added the meta="key=value" to find posts with a custom field

1.1.8:

- added the title_maxwords and desc_maxwords parameters (also global) to limit the number of words displayed
- now you can hide the meta box on post editor (go to settings and select 'hide custom box')

== How to use ==

### Default usage

    [mejorcluster]

### Basic usage

    [mejorcluster posts="20,2,9,51,55,59,63,67,71"]
    [mejorcluster parent="2,3,4"]
    [mejorcluster categories="3,4"]
    [mejorcluster categories="3" exclude="101"]
    [mejorcluster tags="4,5"]

### Sister pages (with same parent)

    [mejorcluster parent="post_parent"]
    [mejorcluster parent="post_parent" exclude="self"]

### Rounded and shadows style

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" round="yes" shadow="yes" skip_title="no"]

### Skip Links

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" skip_title_link="yes"]
    [mejorcluster posts="20,2,9,51,55,59,63,67,71" skip_image_link="yes"]

### Title and Desc HTML Tag

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" title_tag="h3"]
    [mejorcluster posts="20,2,9,51,55,59,63,67,71" desc_tag="h5"]

### Title and Desc max len

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" title_maxwords="2"]
    [mejorcluster posts="20,2,9,51,55,59,63,67,71" desc_maxwords="50"]

### Image Size

    Use the same name than the wordpress size name (i.e. medium, large, etc)

    [mejorcluster imagesize="large"]

### Grid

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" grid="3" maxitems="9"]
    [mejorcluster posts="20,2,9,51" grid="1" classname="clusterlist" maxitems="9" skip_image="yes" shadow="no" skip_title_link="no" skip_desc="yes"]

### Spanish video tutorial

[https://d.pr/v/u4XJZm](https://d.pr/v/u4XJZm)
