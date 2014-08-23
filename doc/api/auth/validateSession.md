Nuts n Bolts REST API
=====================
##Authentication Service

---
###Validate Session

You can call this service to keep track of a user's authentication status.<br>
<br>
This is particularly useful for dynamic pages which exclusively interact with the REST API via XHR.

**URL**

`/rest/auth/validateSession`

**Method:**

`POST`

**URL Params**

None

**Data Params**

None

**Success Response:**
	
* **Code:** 200 <br>
*  **Content:** 
```
	{
		success:	true,
		message:	'OK',
		data:		null
	}
```

**Error Response:**

* **Code:** 401 <br>
* **Content:** 
```
	{
		success:	false,
		message:	'Session No Longer Valid',
		data:		null
	}
```

**Sample Call:**

```
	$.post
	(
		'/rest/auth/validateSession'
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