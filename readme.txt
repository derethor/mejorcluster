=== El mejor Cluster ===
Contributors: derethor
Tags: cluster,seo,related posts
Tested up to: 5.3
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Create easy related posts blocks with a simple shortcode.
This plugin is light, very easy to use and designed for SEO.

== Description ==
This plugin with create a related post block with a simple shortcode.

You can configure the title, description and image of each post (by default will use the title, first  content words and featued image of the post)

You can setup few global options to change the visual aspect of the block.

### Default usage

    [mejorcluster]

### Basic usage

    [mejorcluster posts="20,2,9,51,55,59,63,67,71"]
    [mejorcluster parent="2,3,4"]
    [mejorcluster categories="3,4"]
    [mejorcluster categories="3" exclude="101"]
    [mejorcluster tags="4,5"]

### Rounded and shadows style

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" round="yes" shadow="yes" skip_title="no"]

### Skip Links

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" skip_title_link="yes"]
    [mejorcluster posts="20,2,9,51,55,59,63,67,71" skip_image_link="yes"]

### Title and Desc HTML Tag

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" title_tag="h3"]
    [mejorcluster posts="20,2,9,51,55,59,63,67,71" desc_tag="h5"]

### Image Size

    Use the same name than the wordpress size name (i.e. medium, large, etc)

    [mejorcluster imagesize="large"]

### Grid

    [mejorcluster posts="20,2,9,51,55,59,63,67,71" grid="3" maxitems="9"]
    [mejorcluster posts="20,2,9,51" grid="1" classname="clusterlist" maxitems="9" skip_image="yes" shadow="no" skip_title_link="no" skip_desc="yes"]

### Spanish video tutorial

[https://d.pr/v/u4XJZm](https://d.pr/v/u4XJZm)
