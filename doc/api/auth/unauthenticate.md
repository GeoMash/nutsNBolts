Nuts n Bolts REST API
=====================
##Authentication Service

---
###Unauthenticate

Unauthenticates a user, destroying their session.

* **URL**

`/rest/auth/unauthenticate`

**Method:**
	
`POST`
	
**URL Params**

None

**Data Params**

None

**Success Response:**

* **Code:** 200
*  **Content:** 
```
	{
		success:	true,
		message:	'OK',
		data:		null
	}
```

**Error Response:**

None

**Sample Call:**

```
	$.post('/rest/auth/unauthenticate.json')
	.done
	(
		function(response)
		{
			window.location='/';
		}
	);
```

**Notes:**

None