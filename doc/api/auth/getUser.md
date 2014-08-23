Nuts n Bolts REST API
=====================
##Authentication Service

---
###Validate Session

Get's the user object for the currently authenticated user.

**URL**

`/rest/auth/getUser`

**Method:**
	
`GET`
	
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
		data:		{id:1 ...} //User Object
	}
```

**Error Response:**

* **Code:** 401 <br>
* **Content:** 
```
	{
		success:	false,
		message:	'Not Authenticated',
		data:		null
	}
```

**Sample Call:**

```
	$.post('/rest/auth/getUser.json')
	.done
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