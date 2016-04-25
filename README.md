#Gnome
Embed content from popular providers into the templates using the Noembed gateway.

##Currently supported sources
- BoingBoing
- Flickr
- Gist
- IMDB
- Instagram
- Mixcloud
- SoundCloud
- Twitter
- Vimeo
- Wikipedia
- Wired
- YouTube

##Installation

1. Download and unzip the package directory into /system/user/addons/
2. Install the extension in CP > Add-ons Manager
3. In the settings for the channel you'll be using, set the  "Render URLs and Email addresses as links?" preference to "No".

Note: A bug in EE 3.3.0 is ignoring user settings for not rendering URL and email addresses as links. Until that is fixed, you'll need to apply [a patch to disable auto rendering of URLs as links](https://support.ellislab.com/bugs/detail/21808/mailto-link-using-custom-text-field-that-is-an-email).


##Usage: Tag Pairs


###{exp:gnome:html}
Display provider embed html and standard properties such as title, url, provider name, etc.

####Parameters

| Parameter | Required? |	Description | Default | Options
| --- | --- | --- | --- | --- |
| url | Yes | URL of content to embed | |
| nowrap | No	| Suppress provider wrapper? | no | no,yes
| maxwidth | No	| Set preferred maximum width of returned embed |  | 
| maxheight | No	| Set preferred maximum height of returned embed |  | 


####Variables

|	Variable	| Description | Example |
|	---	|	---	|	---	|
|	{gnome_html}	|	HTML for embed	|	|
|	{gnome_title}	|	Content title	|	Havana . baby you can drive my car series	|
|	{gnome_url}	|	URL to original content	|	https://flickr.com/photos/zedzap/13342893375/ |
|	{gnome_provider_name}	|	Content provider name	|	Flickr |


####Extra Variables 

Availability may vary depending on provider.

|	Variable	| Description | Example |
|	---	|	---	|	---	|
|	{gnome_author_name}	|	Content creator name	|	Nick Kenrick	|
|	{gnome_author_url}	|	URL associated with content creator	| https://www.flickr.com/photos/zedzap/	|
|	{gnome_version}	|	Version number	|	1.0 |
|	{gnome_provider_url}	| URL of content provider	|	https://www.flickr.com/ |
|	{gnome_thumbnail_width}	|	Thumbnail pixel width	| 150	|
|	{gnome_thumbnail_url}	|	URL to provided thumbnail	|	https://farm4.staticflickr.com/3774/13342893375_fd1bde28ec_q.jpg |
|	{gnome_thumbnail_height}	|	Thumbnail pixel height	|	150 |
|	{gnome_type}	|	Content type	|	photo	|


#####Examples

Simple example outputting embedded HTML from a URL hardcoded in a template:

```
{exp:gnome:html url="https://www.flickr.com/photos/zedzap/13342893375"}
	{gnome_html}
{/exp:gnome_html}
```
Outputting multiple embeds from URLs saved to a custom field in grid rows:

```
{exp:channel:entries 
	channel="site" 
	dynamic="off" 
	url_title="gnome-test" 
	limit="1"}
<h1>{title}</h1>
{grid_custom_field}
<h2>{grid_custom_field:heading}</h2>
	{exp:gnome:html 
		url="{grid_custom_field:url}"
		nowrap="on"
		maxwidth="940"
		}
		<h3>{gnome_author_name} / {gnome_type}</h3>		
		<h4>{gnome_title}</h4>		
		{gnome_html}		
		<p><a target="_blank" href="{gnome_url}">View on {gnome_provider_name}</a></p>
	{/exp:gnome:html}
<hr>
{/grid_custom_field}
{/exp:channel:entries}
```

###{exp:gnome:bulk}
Render all supported URLs in a block of template text. 

| Parameter | Required? |	Description | Default | Options
| --- | --- | --- | --- | --- |
| sources	| Yes	| Pipe separated list of providers to render | |	See list of supported sources above 
| nowrap | No	| Suppress provider wrapper? | no | no,yes
| maxwidth | No	| Set preferred maximum width of returned embed |  | 
| maxheight | No	| Set preferred maximum height of returned embed |  | 

####Variables

|	Variable	| Description | Example |
|	---	|	---	|	---	|
|	{gnome_html}	|	HTML for embed	|	|
|	{gnome_title}	|	Content title	|	Havana . baby you can drive my car series	|
|	{gnome_url}	|	URL to original content	|	https://flickr.com/photos/zedzap/13342893375/ |
|	{gnome_provider_name}	|	Content provider name	|	Flickr |


####Extra Variables 

Availability may vary depending on provider.

|	Variable	| Description | Example |
|	---	|	---	|	---	|
|	{gnome_author_name}	|	Content creator name	|	Nick Kenrick	|
|	{gnome_author_url}	|	URL associated with content creator	| https://www.flickr.com/photos/zedzap/	|
|	{gnome_version}	|	Version number	|	1.0 |
|	{gnome_provider_url}	| URL of content provider	|	https://www.flickr.com/ |
|	{gnome_thumbnail_width}	|	Thumbnail pixel width	| 150	|
|	{gnome_thumbnail_url}	|	URL to provided thumbnail	|	https://farm4.staticflickr.com/3774/13342893375_fd1bde28ec_q.jpg |
|	{gnome_thumbnail_height}	|	Thumbnail pixel height	|	150 |
|	{gnome_type}	|	Content type	|	photo	|


#####Example

```
{exp:channel:entries 
	channel="site" 
	dynamic="off" 
	url_title="gnome-test" 
	limit="1"}
<h1>{title}</h1>
{grid_custom_field}
<h2>{grid_custom_field:heading}</h2>
	{exp:gnome:bulk 
		sources="flickr|soundcloud|youtube"
		nowrap="on"
		maxwidth="940"
		}
		<h3>{gnome_author_name} / {gnome_type}</h3>		
		<h4>{gnome_title}</h4>		
		{gnome_html}		
		<p><a target="_blank" href="{gnome_url}">View on {gnome_provider_name}</a></p>
	{/exp:gnome:bulk}
<hr>
{/grid_custom_field}
{/exp:channel:entries}
```


###Notes

This plugin makes use of the Noembed service, an effort to build consistency onto oEmbed APIs provided by many websites.
More information about Noembed at [https://noembed.com/](https://noembed.com/)
