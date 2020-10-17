# zen-do.ru

Contemporary Zen practice in Russian (and a bit of English).

_(Static mirror)_

When the main site https://zen-do.ru/ does not work, come here:

# https://s.zen-do.ru/

# How to convert articles from Joomla to static HTML

_(Work in progress)_

How to convert articles from Joomla (or other DB-based engine) to Static HTML files, in folders according to categories structure:

(1) Export articles from the database.

For example, with `phpMyAdmin` export Joomla site table `<prefix>_content` to JSON file `articles.json`. Edit that file manually as needed (or modify its processing script). Raw exported file:

```[
{"type":"header","version":"4.8.3","comment":"Export to JSON plugin for PHPMyAdmin"},
{"type":"database","name":"joomla"},
{"type":"table","name":"my1_content","database":"joomla","data":
[
{"id":"1", ...},
...
]
}
]```

Edited:

```{"id":"1", ...}
{"id":"2", ...}
...```

(2) Run `extract.php` from this repository, it would read the JSON file line by line, writing the data in separate HTML files, with directory structure according to that web site categories. See `extract.php` for details and edit it to set the options (input file name, output directory, categories names).

(3) Run `wrap.sh` to wrap the files into static HTML template (header & footer, see `/tpl`).

  * Input files directory "/in",
  * output files directory "/docs".

(4) **ToDo**: Automatically list articles in categories, show them as blogs etc. I think just static HTML + AJAX should work fine. Also GitHub actions could help to minimize data processing, by updating info on `git pull` events (e.g. creating lists of files in categories; lists of articles belonging to particular authors).

## Content-Type problem

As pages URLs in Joomla usually do not contain file extensions (not `*.html`, but something like `/texts/123-good-article`), serving static files with such URLs would be with mime type `application/octet-stream`. So instead of seeing a page in browser, a user would get a prompt to save the file. To avoid that, we can tune our server configuration: `default_type "text/html; charset=utf-8";` or, if we can't configure the server, we might be able to use Cloudflare workers (when client-server requests go through Cloudflare):

```
// Worker to change the default Content-Type in response headers:
addEventListener('fetch', event => {
  // Call the function to set the Content-Type only for files without extensions:
  if (event.request.url.lastIndexOf('.') <= event.request.url.lastIndexOf('/'))
    event.respondWith(handleRequest(event.request))
})
 
/**
 * Respond to the request
 * @param {Request} request
 */
async function handleRequest(request) {
  let response = await fetch(request.url, request)
  // Act only if the Content-Type is 'application':
  if (response.headers.get('content-type') !== 'application/octet-stream')
    return response
 
  // Make the headers mutable by re-constructing the Response:
  response = new Response(response.body, response)
  response.headers.set("content-type", "text/html; charset=utf-8")
  return response
}
```
