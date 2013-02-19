couchdesigntool
===============

A tool for convenient management of CouchDB design documents.

Installation
============

The only thing you need is the latest .phar file, just download it and put it in your path, e.g. in /usr/bin/couchdesigntool

Purpose
=======

If you have managed a CouchDB design document, you know that the java script code for your views and filters etc. 
resides inside javascript strings. This makes the code amazingly ugly and hard to maintain.
For example, it might look like this:
<pre>
"views": {
    "get_score_data": {
        "map": "function(doc) {\n  if (doc.type==\"score\")\n    emit([doc._id,0], doc);\n\n 
            if (doc.type==\"track\")\n emit([doc.score_id,1], doc);\n\n  if (doc.type==\"recording\")\n
            emit([doc.score_id,2], doc);\n\n if (doc.type==\"trackasset\")\n 
            emit([doc.score_id,3], doc);\n\n if (doc.type==\"trackrecording\")\n 
            emit([doc.score_id,3], doc);  if (doc.type==\"trackeffect\")\n 
            emit([doc.score_id,4], doc);\n\n}"
    }
</pre>
Not pretty. To address this problem, this tool uses a template JSON file as well as one or more javascript files
containing the code for your functions. It then replaces the certain hooks in the JSON file with that code, and
it can optionally store the resulting document in a CouchDB database.

Example
=======

So, let's consider an example JSON template:
<pre>
{
    "id": "_design/mydesigndoc",
	"language": "javascript",

	"views": {
		"get_score_data": {
			"map": "@get_score_data_map"
		},

      "get_user_disk_usage": {
           "map": "@get_user_disk_usage_map",
           "reduce": "@get_user_disk_usage_reduce"
       }
	}
}
</pre>

And then its corresponding jacascript file:
<pre>
/* @get_score_data_map */
function(doc) {
  if (doc.type=="score")
    emit([doc._id,0], doc);

  if (doc.type=="track")
    emit([doc.score_id,1], doc);
}

/* @get_user_disk_usage_map */
function(doc) {
  if (doc.type=="asset" || doc.type=="recording")
    emit(parseInt(doc.user_id), doc.filesize);
}

/* @get_user_disk_usage_reduce */
function (key, values, rereduce) {
    return sum(values);
}
</pre>
