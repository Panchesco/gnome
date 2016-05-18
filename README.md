#Gnome
Embed content from popular providers into the templates using the Noembed gateway.

##Currently supported sources
- BoingBoing
- Codepen
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


##Usage: Single Tags
###{exp:gnome:youtube_player}
Display a YouTube embed.

####Parameters

| Parameter | Required? |	Description | Default | Options
| --- | --- | --- | --- | --- |
| url | Yes | URL of content to embed | |
| nowrap | No	| Suppress provider wrapper? | no | no,yes
| maxwidth | No	| Set preferred maximum width of returned embed |  | 
| maxheight | No	| Set preferred maximum height of returned embed |  | 
|	autoplay	| No |	Automatically play the video? |	no	| no, yes	|
|	rel	|	No | Show related videos at end of current video play?	| yes	| no, yes |
|	showinfo	| No | Show video information like the tile and uploader information.	|	yes | no, yes |
|	controls	| No | Display player controls? | yes | yes, no |
|	modestbranding | No | Prevent YouTube logo in control bar | no	| no, yes	|
|	color	| No | Change the color of the prograss bar | red, | red, white |
|	start	| No | How many seconds in should the video begin? | 0 | 
|	end	| No | How many seconds in should the video end? | 0 | 

###{exp:gnome:vimeo_player}
Display a Vimeo embed.

####Parameters

| Parameter | Required? |	Description | Default | Options
| --- | --- | --- | --- | --- |
| url | Yes | URL of content to embed | |
| nowrap | No	| Suppress provider wrapper? | no | no,yes
| maxwidth | No	| Set preferred maximum width of returned embed |  | 
| maxheight | No	| Set preferred maximum height of returned embed |  | 
|	autoplay	| No |	Automatically play the video? |	no	| no, yes	|
|	color	| No | Hex for colors to use on player | #00adef | Any hex value (do not include the #) |
|	loop	| No	| Play the video again when it reaches the end.	| no | no, yes	|
|	player_id	| No	| A unique id for the player that<br>will be passed back with all Javascript API responses.	|  | 	|
|	portrait| No	| Show the user’s portrait on the video.	|  yes | 	no, yes	|
|	title| No	|  	Show the title on the video.	|  yes | 	no, yes	|


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
|	{gnome_provider_slug}	| An alpha_dash version of the content provider name	| flickr	|


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
Wrap a custom field containing multiple URLs in the bulk tag to render all the URLs as embedded content. 

| Parameter | Required? |	Description | Default | Options
| --- | --- | --- | --- | --- |
| sources	| Yes	| Pipe separated list of providers to render | |	See list of supported sources above 
| nowrap | No	| Suppress provider wrapper? | no | no,yes
| maxwidth | No	| Set preferred maximum width of returned embed |  | 
| maxheight | No	| Set preferred maximum height of returned embed |  | 


#####Example
```
	{exp:gnome:bulk 
		sources="flickr|soundcloud|youtube"
		nowrap="on"
		maxwidth="940"
		}
		{my_custom_field_name}
	{/exp:gnome:bulk}
```

###Notes

If you're outputting YouTube content, you can further customize the player returned by `{gnome_html}` by adding YouTube specific parameters. See the `{exp:gnome:youtube_player}` single tag parameters for a list of what is available.

If you're outputting Vimeo content, you can further customize the player returned by `{gnome_html}` by adding Vimeo specific parameters. See the `{exp:gnome:vimeo_player}` single tag parameters for a list of what is available.

This plugin makes use of the Noembed service, an effort to build consistency onto oEmbed APIs provided by many websites.
More information about Noembed at [https://noembed.com/](https://noembed.com/)
