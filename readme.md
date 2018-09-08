# Api Query Builder #

## Introduction ##

Note 

Api Query Builder allows for complex filtering, sorting via an api endpoint.

It is intended to be part of a larger package that also provides full CRUD abilities for a Laravel API.

Full documentation is available [here](https://laravel-rest-api.readthedocs.io/en/latest/)

but see examples below for an idea of what the package provides.

## Installation ##

``composer require zcwilt/rest-api``


## Filtering ##

Examples 

    {api-uri}?where=id:eq:1
    
    {api-uri}?whereIn=id:(1,2,3)
    
    {api-uri}?whereBetween=age:18,45
    
    
   
## Sorting ##

    {api-uri}?sort=id,-name

would sort ascending on id, the sort descending on name

## Columns ##

By default queries will return all columns 

You can restrict columns using 

    {api-uri}?columns=id,name
    
## Lots More ##

Lots more filtering options are available.
see the [Documentation](https://laravel-rest-api.readthedocs.io/en/latest/)    