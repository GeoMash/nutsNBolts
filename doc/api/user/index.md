Nuts n Bolts REST API
=====================
##User Service

---
###Get Users

Fetches all users or specific users based on a field value search.

**URL**

`/rest/user`

**Method:**

`GET`

**URL Params**

	**Optional:**
 
	`*=[string]` - Any User field.

**Data Params**

None

**Success Response:**
	
* **Code:** 200
* **Content:** 
```
	{
		success:	true,
		message:	'OK',
		data:		[{id:1 ...}, ...] //User Objects
	}
```

**Error Response:**

* **Code:** 401
*  **Content:** 
```
	{
		success:	false,
		message:	'Permission Denied',
		data:		null
	}
```

**Sample Call:**

```
	$.post
	(
		'/rest/user.json',
		{
			name_first:	'Foo',
			name_last:	'Bar'
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

**Notes:**

None