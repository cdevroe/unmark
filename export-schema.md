Unmark's Export JSON Schema
===============================

Unmark's Export JSON file should be thought of as more of a backup of someone's Unmark installation moreso than an export/import from other services. This document is simply made to help those that would like to understand the schema.

Here is an example...

```
{
	"export": {
		"export_version": 1,
		"export_date": "2016-09-15 07:45:30",
		"marks_count": 163,
		"marks": [
			{"mark_id":"1","mark_title":null,"notes":null,"active":"1","created_on":"2016-09-13 20:40:20","archived_on":null,"title":"Read Unmark's FAQ","url":"http:\/\/help.unmark.it\/faq","embed":null,"label_id":"7","label_name":"Do","tags":{},"nice_time":"1 day ago"},
			{"mark_id":"2","mark_title":null,"notes":null,"active":"1","created_on":"2016-09-13 20:40:20","archived_on":null,"title":"How To Use Unmark","url":"http:\/\/help.unmark.it","embed":null,"label_id":"7","label_name":"Do","tags":{},"nice_time":"1 day ago"}
    ]
	}
}
```

- **export_version** - used internally to denote the version of the export. Currently the only option is 1.
- **export_date** - unused by the importer, but created for reference for the user.
- **marks_count** - The total number of marks exported. Unused by importer.
- **marks** - All metadata related to the bookmarks exported.
  - **mark_id** - (optional) If provided, will set as the mark ID in the database.
  - **mark_title** - (optional) The user-specified title of a mark. Used only if the user customized the title of the bookmark in Unmark.
  - **notes** - (optional) The user's notes for the bookmark.
  - **active** - (boolean) 1 if active, 0 if not (deleted)
  - **created_on** - The date the mark was created
  - **archived_on** - The date the mark was archived (or marked completed) by the user. If present the mark is considered archived.
  - **title** - The mark's title as provided, typically, from the Unmark bookmarklet or Chrome extension. Usually the URL's <title>
  - **url** - The URL of the bookmark.
  - **embed** - Used within Unmark to "preview" a bookmark. Typically a YouTube or Soundcloud embed but could also be an hrecipe.
  - **label_id** - The ID of the label for the mark used by Unmark
  - **label_name** - The name of the label (Read, Watch, Listen, Eat & Drink, or Do -- also, custom labels coming soon)
  - **tags** (object/array) - The tags associated with this bookmark.
  - **nice_time** - The nice time relative to the created_on date. E.g. "1 day ago"
