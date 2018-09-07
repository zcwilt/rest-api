Query Parser Soft Deletes
=========================

For models that support soft deletes, the query parser also provides 2 further filters.


With Trashed
------------

To show all entries for the model, even when soft deleted use

::

    {api-uri}?withTrashed


Only Trashed
------------

To show only entries for the model that have been soft deleted use

::

    {api-uri}?onlyTrashed



.. warning:: There is currently no way to force delete soft deleted items. This is on the todo list.