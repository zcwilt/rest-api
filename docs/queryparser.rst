Query Parser
============

The query parser allows for complex filtering, sorting, use of child relations and more.

Currently the filter parser supports

- where
- orWhere
- whereIn
- orWhereIn
- whereNotIn
- orWhereNotIn

Sorting allows for multiple sort targets for ascending, descending sorts.

Includes allows for loading child models.

Query results by default return all columns for the query, however can use the columns filter to restrict which columns are returned.



.. toctree::
    :maxdepth: 2
    :hidden:

    queryparserwhere
    queryparsersorting
    queryparsercolumns
    queryparserincludes


