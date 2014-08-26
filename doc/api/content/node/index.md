Nuts n Bolts REST API
=====================
##Content Node Service

---
###Get Nodes

Gets content nodes based on id or parameter matching. 

**URL**

`/rest/content/node/{int}`

**Method:**

`GET`

**URL Params**

**Optional:**
 
	`*=[string]` - Any User field.

**Data Params**

	None

***Success Response:**
	
* **Code:** 200
* **Content:** 
```
	{
		success:	true,
		message:	'OK',
		data:		null
	}
```

**Error Response:**

* **Code:** 400
* **Content:** 
```
	{
		success:	false,
		message:	'Invalid Request',
		data:		null
	}
```

* **Code:** 401 <br>
```
	{
		success:	false,
		message:	'Permission Denied',
		data:		null
	}
```

**Sample Call:**

Get node's where foo=bar and status=2 (published)

```
	$.getJSON
	(
		'/rest/content/node.json',
		{
			foo:	'bar',
			status: 2
		}
	).done
	(
		function(response)
		{
			if (!response.success)
			{
				//Deal with it!
			}
		}
	).fail
	(
		function()
		{
			//Deal with it!
		}
	);
```

Get 1 Node

```
	$.getJSON('/rest/content/node/101.json').done
	(
		function(response)
		{
			if (!response.success)
			{
				//Deal with it!
			}
		}
	).fail
	(
		function()
		{
			//Deal with it!
		}
	);
```

**Notes:**

None