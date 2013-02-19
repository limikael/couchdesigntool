couchdesigntool
===============

A tool for convenient management of CouchDB design documents.

Installation
============

The only thing you need is the latest .phar file, just download it and put it in your path, e.g. in /usr/bin/couchdesigntool

Purpose and usage
=================

If you have managed a CouchDB design document, you know that the java script code for your views and filters etc. resides inside javascript strings. This makes the code amazingly ugly and hard to maintain. For example, it might look like this:
<pre>
"views": {
    "get_score_data": {
        "map": "function(doc) {\n  if (doc.type==\"score\")\n    emit([doc._id,0], doc);\n\n 
            if (doc.type==\"track\")\n emit([doc.score_id,1], doc);\n\n  if (doc.type==\"recording\")\n
            emit([doc.score_id,2], doc);\n\n if (doc.type==\"trackasset\")\n 
            emit([doc.score_id,3], doc);\n\n if (doc.type==\"trackrecording\")\n 
            emit([doc.score_id,3], doc);  if (doc.type==\"trackeffect\")\n emit([doc.score_id,4], doc);\n\n}"
    },
</pre>
