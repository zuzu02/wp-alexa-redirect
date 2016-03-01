# Better “Alexa Redirect” WordPress Plugin #

This is a WordPress plugin, which is designed to improve the Alexa Ranking of your blog by prefixing all your blog's links with the Alexa Redirect URL (`http://redirect.alexa.com/redirect?...`).

Here’s what you need to know about it.

## How it Works: ##

The particular plugin scans the entire set of links on any page of your blog while someone is watching it and it adds a JavaScript event to each of them, which upon clicking on the link (no matter is it a _Left Click_ or a _Right Click_) will prefix it with the **Alexa Redirect URL**: in this way the person visiting your blog will visit the desired URL by forwarding the URL to Alexa. Here is an example - you have a link on your blog like `http://www.yourblog.com/page/2`, but when the user clicks on it, it will transform into `http://redirect.alexa.com/redirect?http://www.yourblog.com/page/2`. In the same time, when a _Search-Engine_ bot like **Google** visits your page it will not trigger the JavaScript event, because they are not JavaScript-aware: and in this way you "Alexa-Rating boosting efforts" will not mess your **Google** rating and search results positions.

## Installation: ##

  1. Download the [Better “Alexa Redirect” WordPress Plugin](http://kaloyan.info/blog/better-alexa-redirect-plugin/)
  1. Upload [Better “Alexa Redirect” WordPress Plugin](http://kaloyan.info/blog/better-alexa-redirect-plugin/) to your plugins folder
  1. Activate the [Better “Alexa Redirect” WordPress Plugin](http://kaloyan.info/blog/better-alexa-redirect-plugin/)
  1. You are Done!

## Usage: ##

You are not required to do anything: once you have activated the [Better “Alexa Redirect” WordPress Plugin](http://kaloyan.info/blog/better-alexa-redirect-plugin/) there is really nothing more to do. All you can do is I would however recommend that you keep an eye on your Alexa rating and monitor if the [Better “Alexa Redirect” WordPress Plugin](http://kaloyan.info/blog/better-alexa-redirect-plugin/) is proving to be of any use at all :)

## Coming Soon: ##

Here’s what you can expect in the next release:

  * ~~Options page in the administration panel for fine-tunning the [Better “Alexa Redirect” WordPress Plugin](http://kaloyan.info/blog/better-alexa-redirect-plugin/)~~ (done as of v0.2)
  * ~~List of white-listed domains, for which to apply the Alexa Redirect technique (not just your blog, but probably your other sites too, and your friends’ sites too)~~ (done as of v0.2)
  * The ability to apply the Alexa Redirect technique to the images posted on your blog (?)
  * ~~Adding a link on the Options page to your Alexa Ranking page~~ (done as of v0.2)
  * ~~Detecting if redirect.alexa.com is a trusted site, and skip the “redirect-voodoo” if it is not (generally because of **Windows Vista + Internet Explorer 7**)~~ (as of v0.2, the plugin gets disabled itself if IE7+Vista is detected ... still looking how to detect if redirect.alexa.com is a trusted site)